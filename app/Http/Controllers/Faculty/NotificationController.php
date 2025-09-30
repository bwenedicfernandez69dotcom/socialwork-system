<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;

class NotificationController extends Controller
{
    // Display all schedule notifications
    public function index()
    {
        $user = Auth::user();

        // Fetch all schedule notifications for this faculty
        $notifications = $user->notifications()
            ->where('type', 'App\Notifications\NewScheduleNotification')
            ->get();

        return view('faculty.notifications.index', compact('notifications'));
    }

    // Faculty responds to a schedule notification
    public function respond(Request $request, $notificationId)
    {
        $user = Auth::user();

        $notification = $user->notifications()
            ->where('id', $notificationId)
            ->first();

        if (!$notification) {
            return redirect()->back()->with('error', 'Notification not found.');
        }

        $response = $request->input('response');

        if (!in_array($response, ['accepted', 'declined'])) {
            return redirect()->back()->with('error', 'Invalid response.');
        }

        $scheduleId = $notification->data['schedule_id'] ?? null;

        if (!$scheduleId) {
            return redirect()->back()->with('error', 'Schedule not found in notification.');
        }

        $schedule = Schedule::find($scheduleId);

        if (!$schedule) {
            return redirect()->back()->with('error', 'Schedule not found.');
        }

        // Update schedule status based on response
        $schedule->update([
            'status' => $response === 'accepted' ? 'Assigned' : 'Pending',
            'faculty_response' => $response,
        ]);

        // Mark the notification as read
        $notification->markAsRead();

        return redirect()->back()->with('success', "Schedule {$response} successfully.");
    }
}
