<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use App\Models\Schedule;

class FacultyScheduleController extends Controller
{
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

        // Update the notification data with faculty response
        $notification->update([
            'data' => array_merge($data, ['faculty_response' => $response])
        ]);

        // Optionally update schedule status if accepted/declined
        if ($response === 'accepted') {
            $schedule->update(['status' => 'Assigned']);
        } else {
            $schedule->update(['status' => 'Pending']);
        }

        // Mark notification as read
        $notification->markAsRead();

        return back()->with('success', "You have {$response} the schedule.");
    }
}
