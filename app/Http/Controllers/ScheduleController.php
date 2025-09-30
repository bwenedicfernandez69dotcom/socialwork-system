<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Faculty;
use App\Models\Section;
use App\Models\Room;
use App\Models\Availability;
use Illuminate\Http\Request;
use App\Notifications\NewScheduleNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\ScheduleCreatedMail;

class ScheduleController extends Controller
{
    // ---------------- CRUD ---------------- //

    public function index(Request $request)
    {
        $query = Schedule::with(['subject', 'faculty.user', 'section', 'room'])->latest();

        if ($request->filled('faculty_id')) {
            $query->where('faculty_id', $request->faculty_id);
        }
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }
        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }
        if ($request->filled('year_level')) {
            $query->where('year_level', $request->year_level);
        }

        $schedules = $query->paginate(10)->withQueryString();

        $faculties = Faculty::with('user')->get();
        $subjects  = Subject::all();
        $sections  = Section::all();

        return view('schedules.index', compact('schedules', 'faculties', 'subjects', 'sections'));
    }

    public function create()
    {
        $subjects  = Subject::all();
        $sections  = Section::all();
        $faculties = $this->getFacultiesWithLoad();

        $semester   = request()->old('semester') ?? '1st';
        $schoolYear = request()->old('school_year') ?? '2025-2026';

        $rooms = Room::withCount(['schedules as booking_count' => function ($q) use ($semester, $schoolYear) {
            $q->where('semester', $semester)
              ->where('school_year', $schoolYear);
        }])->get();

        return view('schedules.create', compact('subjects', 'faculties', 'sections', 'rooms'));
    }

    public function edit(Schedule $schedule)
    {
        $subjects  = Subject::all();
        $sections  = Section::all();
        $faculties = $this->getFacultiesWithLoad();

        $semester   = old('semester', $schedule->semester);
        $schoolYear = old('school_year', $schedule->school_year);

        $rooms = Room::withCount(['schedules as booking_count' => function ($q) use ($semester, $schoolYear) {
            $q->where('semester', $semester)
              ->where('school_year', $schoolYear);
        }])->get();

        return view('schedules.edit', compact('schedule', 'subjects', 'faculties', 'sections', 'rooms'));
    }

     public function store(Request $request)
    {
        $validated = $this->validateSchedule($request);

        if ($validated['delivery_mode'] === 'Online') {
            $validated['room_id'] = null;
        }

        $validated['days'] = implode(',', $validated['days']);

        $conflicts = $this->checkConflicts($validated);
        if (!empty($conflicts)) return back()->withErrors($conflicts)->withInput();

        $workloadError = $this->checkWorkload($validated);
        if ($workloadError) return back()->withErrors($workloadError)->withInput();

        $validated['status'] = 'Assigned';
        $validated['faculty_response'] = 'accepted';

        $schedule = Schedule::create($validated);

        

        // notify via email (only if may faculty at may email)
        if ($schedule->faculty && $schedule->faculty->user && $schedule->faculty->user->email) {
            Mail::to($schedule->faculty->user->email)->send(new ScheduleCreatedMail($schedule));
        }

        return redirect()->route('schedules.index')
                         ->with('success', 'Schedule created, faculty notified, and email sent!');
    }

    public function update(Request $request, Schedule $schedule)
{
    $validated = $this->validateSchedule($request);

    if ($validated['delivery_mode'] === 'Online') {
        $validated['room_id'] = null;
    }

    $validated['days'] = implode(',', $validated['days']);

    $conflicts = $this->checkConflicts($validated, $schedule->id);
    if (!empty($conflicts)) return back()->withErrors($conflicts)->withInput();

    $workloadError = $this->checkWorkload($validated, $schedule->id);
    if ($workloadError) return back()->withErrors($workloadError)->withInput();

    $validated['status'] = 'Assigned';
    $validated['faculty_response'] = 'accepted';

    $oldFacultyId = $schedule->faculty_id;
    $schedule->update($validated);

    // kung iba na ang faculty, send notification at email
    if ($validated['faculty_id'] && $validated['faculty_id'] != $oldFacultyId) {
        

        if ($schedule->faculty && $schedule->faculty->user && $schedule->faculty->user->email) {
            Mail::to($schedule->faculty->user->email)->send(new ScheduleCreatedMail($schedule));
        }
    }

    return redirect()->route('schedules.index')->with('success', 'Schedule updated successfully.');
}


    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('schedules.index')->with('success', 'Schedule deleted successfully.');
    }

    // ---------------- FullCalendar ---------------- //

    public function calendar()
    {
        $schedules = Schedule::with(['subject', 'faculty.user', 'section', 'room'])->get();

        $dayMap = ['Sun'=>0,'Mon'=>1,'Tue'=>2,'Wed'=>3,'Thu'=>4,'Fri'=>5,'Sat'=>6];

        $events = $schedules->flatMap(function ($schedule) use ($dayMap) {
            return collect(explode(',', $schedule->days))->map(function ($day) use ($schedule, $dayMap) {
                return [
                    'title' => "{$schedule->subject->title} ({$schedule->faculty->user->name}) [{$schedule->section->name}]",
                    'daysOfWeek' => [$dayMap[$day]],
                    'startTime' => $schedule->start_time,
                    'endTime' => $schedule->end_time,
                    'backgroundColor' => '#7f3fbf',
                    'borderColor' => '#5a2e8c',
                    'textColor' => '#fff',
                ];
            });
        });

        return view('schedules.calendar', compact('events'));
    }

    // ---------------- Timetable (Single Table) ---------------- //

    public function timetable()
    {
        [$days, $matrix, $times, $facultyColors, $facultyNames] = $this->buildTimetableData();

        return view('schedules.timetable', compact('days','matrix','times','facultyColors','facultyNames'));
    }

    // ---------------- Export Timetable to PDF ---------------- //

    public function exportTimetablePDF()
    {
        [$days, $matrix, $times, $facultyColors, $facultyNames] = $this->buildTimetableData();

        $semester   = Schedule::distinct()->pluck('semester')->first() ?? '1st';
        $schoolYear = Schedule::distinct()->pluck('school_year')->first() ?? '2025-2026';

        $pdf = Pdf::loadView(
            'schedules.timetable-pdf',
            compact('days', 'matrix', 'times', 'semester', 'schoolYear', 'facultyColors', 'facultyNames')
        )->setPaper('a4', 'landscape');

        return $pdf->download("timetable_{$semester}_{$schoolYear}.pdf");
    }

    // ---------------- Helper Methods ---------------- //

    protected function buildTimetableData()
    {
        $days = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
        // start 07:30, end 20:00, 30-minute increments
        $times = $this->generateTimes('07:30:00', '20:00:00');
        $matrix = [];

        foreach ($days as $d) {
            foreach ($times as $t) {
                $matrix[$d][$t] = null;
            }
        }

        $facultyColors = [];
        $facultyNames = [];
        $baseColors = [
            '#1f77b4','#ff7f0e','#2ca02c','#d62728','#9467bd',
            '#8c564b','#e377c2','#7f7f7f','#bcbd22','#17becf',
            '#e41a1c','#377eb8','#4daf4a','#984ea3','#ff7f00',
            '#ffff33','#a65628','#f781bf','#999999','#66c2a5',
            '#fc8d62','#8da0cb','#e78ac3','#a6d854','#ffd92f',
        ];
        $colorIndex = 0;

        $schedules = Schedule::with(['subject','faculty.user','section','room'])->get();

        foreach ($schedules as $schedule) {
            $scheduleDays = explode(',', $schedule->days);
            $start = date('H:i:s', strtotime($schedule->start_time));
            $end = date('H:i:s', strtotime($schedule->end_time));

            foreach ($scheduleDays as $day) {
                $day = trim($day);
                if ($day === '' || !in_array($day, $days)) continue;

                $facultyId = $schedule->faculty_id;
                $facultyName = $schedule->faculty->user->name ?? 'Unknown';

                if ($facultyId && !isset($facultyColors[$facultyId])) {
                    $facultyColors[$facultyId] = $baseColors[$colorIndex % count($baseColors)];
                    $facultyNames[$facultyId] = $facultyName;
                    $colorIndex++;
                } elseif ($facultyId && !isset($facultyNames[$facultyId])) {
                    $facultyNames[$facultyId] = $facultyName;
                }

                $color = $facultyId ? ($facultyColors[$facultyId] ?? '#999') : '#999';
                $rowspan = $this->calculateRowspan($start, $end);
                if ($rowspan <= 0) continue;

                $firstSlot = $this->normalizeSlotToGeneratedTimes($start, $times);
                if ($firstSlot === null) continue;

                // place the main cell
                $matrix[$day][$firstSlot] = [
                    'schedule' => $schedule,
                    'rowspan' => $rowspan,
                    'color' => $color,
                ];

                // mark subsequent cells as merged (so view will skip them)
                $current = $firstSlot;
                for ($i = 1; $i < $rowspan; $i++) {
                    $current = $this->incrementTime($current);
                    if (array_key_exists($current, $matrix[$day])) {
                        $matrix[$day][$current] = 'merged';
                    }
                }
            }
        }

        return [$days, $matrix, $times, $facultyColors, $facultyNames];
    }

    protected function generateTimes(string $start = '07:30:00', string $end = '20:00:00'): array
    {
        $times = [];
        $current = strtotime($start);
        $endTs = strtotime($end);

        while ($current <= $endTs) {
            $times[] = date('H:i:s', $current);
            $current = strtotime('+30 minutes', $current);
        }

        return $times;
    }

    protected function incrementTime(string $time): string
    {
        return date('H:i:s', strtotime('+30 minutes', strtotime($time)));
    }

    protected function calculateRowspan(string $start, string $end): int
    {
        $diffMinutes = (int)((strtotime($end) - strtotime($start)) / 60);
        return $diffMinutes > 0 ? (int) ceil($diffMinutes / 30) : 0;
    }

    protected function normalizeSlotToGeneratedTimes(string $start, array $times): ?string
    {
        foreach ($times as $t) {
            if ($t === $start) {
                return $t;
            }
        }
        return null;
    }

    private function getFacultiesWithLoad()
    {
        return Faculty::with('user')
            ->withCount(['schedules as current_load'])
            ->get()
            ->map(function ($faculty) {
                $faculty->max_load = $faculty->max_load ?? 5;
                return $faculty;
            });
    }

    private function validateSchedule(Request $request): array
    {
        return $request->validate([
            'subject_id'   => 'required|exists:subjects,id',
            'section_id'   => 'required|exists:sections,id',
            'year_level'   => 'required|in:1,2,3,4',
            'faculty_id'   => 'nullable|exists:faculties,id',
            'room_id'      => 'nullable|exists:rooms,id',
            'semester'     => 'required|in:1st,2nd',
            'school_year'  => ['required','regex:/^\d{4}-\d{4}$/'],
            'days'         => 'required|array',
            'days.*'       => 'in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
            'start_time'   => 'required|date_format:H:i',
            'end_time'     => 'required|date_format:H:i|after:start_time',
            'delivery_mode'=> 'required|in:Face-to-Face,Online',
        ]);
    }

    private function checkConflicts(array $validated, $ignoreId = null): array
    {
        $conflicts = [];
        $days = explode(',', $validated['days']);

        foreach ($days as $day) {
            $day = trim($day);

            if (!empty($validated['faculty_id']) && $this->hasOverlap('faculty_id', $validated['faculty_id'], $day, $validated, $ignoreId)) {
                $conflicts['faculty_conflict'] = "Faculty already has a class on $day at this time.";
            }

            if (!empty($validated['room_id']) && $this->hasOverlap('room_id', $validated['room_id'], $day, $validated, $ignoreId)) {
                $conflicts['room_conflict'] = "Room is already booked on $day at this time.";
            }

            if (!empty($validated['section_id']) && $this->hasOverlap('section_id', $validated['section_id'], $day, $validated, $ignoreId)) {
                $conflicts['section_conflict'] = "Section already has a class on $day at this time.";
            }

            if (!empty($validated['faculty_id'])) {
                $available = Availability::where('faculty_id', $validated['faculty_id'])
                    ->where('day', $day)
                    ->where('status', 'available')
                    ->whereTime('start_time', '<=', $validated['start_time'])
                    ->whereTime('end_time', '>=', $validated['end_time'])
                    ->exists();

                if (!$available) {
                    $conflicts['faculty_conflict'] = "Faculty is not available on $day at this time.";
                }
            }
        }

        return $conflicts;
    }

    private function hasOverlap(string $column, $value, string $day, array $validated, $ignoreId = null): bool
    {
        return Schedule::where($column, $value)
            ->where('days', 'LIKE', "%$day%")
            ->where('semester', $validated['semester'])
            ->where('school_year', $validated['school_year'])
            ->where(function ($q) use ($validated) {
                $q->where('start_time', '<', $validated['end_time'])
                  ->where('end_time', '>', $validated['start_time']);
            })
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists();
    }

    private function checkWorkload(array $validated, $ignoreId = null): ?array
    {
        if (!empty($validated['faculty_id'])) {
            $faculty = Faculty::withCount(['schedules as current_load' => function ($q) use ($validated, $ignoreId) {
                $q->where('semester', $validated['semester'])
                  ->where('school_year', $validated['school_year']);
                if ($ignoreId) $q->where('id', '!=', $ignoreId);
            }])->find($validated['faculty_id']);

            if ($faculty && $faculty->current_load >= ($faculty->max_load ?? 5)) {
                return ['workload_conflict' => "Faculty {$faculty->user->name} has reached the maximum load limit."];
            }
        }

        return null;
    }
}
