<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with(['subject', 'faculty.user', 'section', 'room'])
            ->orderBy('start_time', 'asc')
            ->get();

        $facultyColors = [];
        $colors = [
            '#1f77b4', '#ff7f0e', '#2ca02c', '#d62728',
            '#9467bd', '#8c564b', '#e377c2', '#7f7f7f',
            '#bcbd22', '#17becf'
        ];
        $colorIndex = 0;

        $events = $schedules->map(function($schedule) use (&$facultyColors, $colors, &$colorIndex) {
            $facultyId = $schedule->faculty->id;

            if(!isset($facultyColors[$facultyId])) {
                $facultyColors[$facultyId] = $colors[$colorIndex % count($colors)];
                $colorIndex++;
            }

            return [
                'title' => $schedule->subject->name . ' (' . $schedule->section->name . ')',
                'start' => $schedule->start_time,
                'end' => $schedule->end_time,
                'description' => 'Room: ' . $schedule->room->name . ', Instructor: ' . $schedule->faculty->user->name,
                'color' => $facultyColors[$facultyId],
            ];
        });

        return view('calendar.index', compact('events'));
    }
}
