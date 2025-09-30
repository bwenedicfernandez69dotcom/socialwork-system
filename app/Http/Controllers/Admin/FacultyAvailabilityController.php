<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faculty;

class FacultyAvailabilityController extends Controller
{
    public function index(Faculty $faculty)
    {
        // Load faculty availabilities
        $availabilities = $faculty->availabilities()->orderBy('day')->get();

        return view('admin.faculties.availabilities', compact('faculty', 'availabilities'));
    }
}
