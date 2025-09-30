<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Faculty;
use App\Models\Subject;
use App\Models\Section;
use App\Models\Room;
use App\Notifications\NewScheduleNotification;
use Illuminate\Http\Request;

class AdminScheduleController extends Controller
{
    /**
     * Display all schedules grouped by day.
     */
    public function index()
    {
        $schedules = Schedule::with(['subject', 'faculty.user', 'section', 'room'])
            ->orderBy('start_time')
            ->get()
            ->flatMap(function($schedule) {
                // Split multiple days into separate entries
                $days = explode(',', $schedule->days ?? '');
                return collect($days)->map(fn($day) => [
                    'day' => trim($day),
                    'schedule' => $schedule
                ]);
            })
            ->groupBy('day'); // group by day

        return view('admin.schedules.index', compact('schedules'));
    }

    /**
     * Show a single schedule.
     */
    public function show($id)
    {
        $schedule = Schedule::with(['subject', 'faculty.user', 'section', 'room'])->findOrFail($id);
        return view('admin.schedules.show', compact('schedule'));
    }

    /**
     * Show the form to create a schedule.
     */
    public function create()
    {
        return view('admin.schedules.create', [
            'faculties' => Faculty::with('user')->get(),
            'subjects'  => Subject::all(),
            'sections'  => Section::all(),
            'rooms'     => Room::all(),
        ]);
    }

    /**
     * Store a new schedule and notify faculty.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'faculty_id' => 'nullable|exists:faculties,id',
            'section_id' => 'required|exists:sections,id',
            'room_id'    => 'nullable|exists:rooms,id',
            'days'       => 'required|array',
            'days.*'     => 'in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i|after:start_time',
            'delivery_mode' => 'required|in:Face-to-Face,Online,Modular',
        ]);

        $validated['days'] = implode(',', $validated['days']);
        $validated['status'] = $validated['faculty_id'] ? 'Pending' : 'Unassigned';

        $schedule = Schedule::create($validated);

        // Notify faculty if assigned
        if (!empty($validated['faculty_id'])) {
            $faculty = Faculty::find($validated['faculty_id']);
            if ($faculty && $faculty->user) {
                $faculty->user->notify(new NewScheduleNotification($schedule));
            }
        }

        return redirect()
            ->route('admin.schedules.index')
            ->with('success', 'Schedule created and faculty notified successfully.');
    }
}
