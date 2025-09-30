<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use App\Models\Schedule;

class FacultyScheduleController extends Controller
{
    /**
     * Display paginated schedules assigned to the logged-in faculty.
     */
    public function index()
    {
        $facultyId = auth()->user()->faculty?->id;

        if (!$facultyId) {
            return redirect()->back()->with('error', 'Faculty profile not found.');
        }

        // Get all relevant schedule notifications for this faculty
        $notifications = auth()->user()
            ->notifications()
            ->where('type', 'App\Notifications\NewScheduleNotification')
            ->get()
            ->keyBy(fn($notification) => $notification->data['schedule_id'] ?? null);

        // Retrieve schedules assigned to the faculty with pagination
        $schedules = Schedule::with(['subject', 'section', 'room', 'faculty.user'])
            ->where('faculty_id', $facultyId)
            ->latest()
            ->paginate(10);

        // Attach notification_id to each schedule for Blade forms
        $schedules->getCollection()->transform(function ($schedule) use ($notifications) {
            $schedule->notification_id = $notifications[$schedule->id]->id ?? null;
            return $schedule;
        });

        return view('faculty.schedules.index', compact('schedules'));
    }

    /**
     * Respond to a schedule notification (accept or decline)
     */
    public function respond(Request $request, DatabaseNotification $notification)
    {
        $request->validate([
            'response' => 'required|in:accepted,declined',
        ]);

        $response = $request->input('response');
        $data = $notification->data;

        $scheduleId = $data['schedule_id'] ?? null;
        if (!$scheduleId) {
            return back()->with('error', 'Invalid notification data.');
        }

        $schedule = Schedule::find($scheduleId);
        if (!$schedule) {
            return back()->with('error', 'Schedule not found.');
        }

        // Ensure the notification belongs to the logged-in user
        if ($notification->notifiable_id !== auth()->id()) {
            return back()->with('error', 'Unauthorized action.');
        }

        // Update notification data with faculty response
        $notification->update([
            'data' => array_merge($data, ['faculty_response' => $response])
        ]);

        // Update schedule status based on response
        if ($response === 'accepted') {
            $schedule->update(['status' => 'Assigned']);
        } else { // declined
            $schedule->update([
                'status' => 'Declined',
                'faculty_id' => null, // unassign faculty if declined
            ]);
        }

        // Mark notification as read
        $notification->markAsRead();

        return redirect()->back()->with('success', "You have {$response} the schedule.");
    }

    /**
     * Show a single schedule details
     */
    public function show(Schedule $schedule)
    {
        $facultyId = auth()->user()->faculty?->id;

        if ($schedule->faculty_id !== $facultyId) {
            abort(403, 'Unauthorized access to this schedule.');
        }

        return view('faculty.schedules.show', compact('schedule'));
    }
}
