<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Faculty;
use App\Models\Room;
use App\Models\Section;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Total counts
        $totalSchedules = Schedule::count();
        $totalFaculty = Faculty::count();
        $totalRooms = Room::count();
        $totalSections = Section::count();

        // Faculty workload
        $facultyWorkloads = Faculty::withCount('schedules')->get();

        // Room occupancy
        $roomOccupancy = Room::withCount('schedules')->get();

        // Delivery mode stats
        $deliveryModes = Schedule::select('delivery_mode')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('delivery_mode')
            ->get();

        // Conflict detection

        // Room conflicts: same room, same days, same time
        $roomConflicts = Schedule::select('room_id', 'days', 'start_time', 'end_time')
            ->groupBy('room_id', 'days', 'start_time', 'end_time')
            ->havingRaw('COUNT(*) > 1')
            ->with('room')
            ->get();

        // Faculty conflicts: same faculty, same days, same time
        $facultyConflicts = Schedule::select('faculty_id', 'days', 'start_time', 'end_time')
            ->groupBy('faculty_id', 'days', 'start_time', 'end_time')
            ->havingRaw('COUNT(*) > 1')
            ->with('faculty.user')
            ->get();

        return view('admin.dashboard', compact(
            'totalSchedules',
            'totalFaculty',
            'totalRooms',
            'totalSections',
            'facultyWorkloads',
            'roomOccupancy',
            'deliveryModes',
            'roomConflicts',
            'facultyConflicts'
        ));
    }
}
