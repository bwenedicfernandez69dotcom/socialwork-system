<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // Correct import for PDF

class ReportController extends Controller
{
    // Show schedule reports
    public function index()
    {
        $schedules = Schedule::with(['faculty.user', 'subject', 'room', 'section'])->get();

        return view('admin.reports.index', compact('schedules'));
    }

    // Export schedules as PDF
    public function exportPDF()
{
    $schedules = Schedule::with(['faculty.user', 'subject', 'room', 'section'])->get();

    // Add 'landscape' option
    $pdf = Pdf::loadView('admin.reports.pdf', compact('schedules'))
              ->setPaper('a4', 'landscape');

    return $pdf->download('schedules_report.pdf');
}
}
