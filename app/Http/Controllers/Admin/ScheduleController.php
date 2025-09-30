<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;

class AdminScheduleController extends Controller
{
    public function index()
    {
        // Load all schedules
        $schedules = Schedule::with(['subject', 'faculty.user', 'section', 'room'])
            ->orderBy('start_time')
            ->get()
            ->groupBy('day');

        // Define weekday order
        $weekOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        // Sort the grouped schedules according to $weekOrder
        $sortedSchedules = collect();
        foreach ($weekOrder as $day) {
            if (isset($schedules[$day])) {
                $sortedSchedules[$day] = $schedules[$day];
            }
        }

        return view('admin.schedules.index', [
            'schedules' => $sortedSchedules
        ]);
    }

    public function show($id)
    {
        $schedule = Schedule::with(['subject', 'faculty.user', 'section', 'room'])->findOrFail($id);
        return view('admin.schedules.show', compact('schedule'));
    }
}
