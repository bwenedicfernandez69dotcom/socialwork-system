<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvailabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $availabilities = Availability::where('faculty_id', Auth::user()->faculty->id)
            ->latest()
            ->paginate(10);

        return view('faculty.availabilities.index', compact('availabilities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('faculty.availabilities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'day'         => 'required|in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i|after:start_time',
            'semester'    => 'required|in:1st,2nd',
            'school_year' => ['required', 'regex:/^\d{4}-\d{4}$/'],
            'status'      => 'required|in:available,unavailable',
        ]);

        $validated['faculty_id'] = Auth::user()->faculty->id;

        Availability::create($validated);

        return redirect()
            ->route('faculty.availabilities.index')
            ->with('success', 'Availability added successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $availability = Availability::where('faculty_id', Auth::user()->faculty->id)
            ->findOrFail($id);

        return view('faculty.availabilities.edit', compact('availability'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $availability = Availability::where('faculty_id', Auth::user()->faculty->id)
            ->findOrFail($id);

        $validated = $request->validate([
            'day'         => 'required|in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i|after:start_time',
            'semester'    => 'required|in:1st,2nd',
            'school_year' => ['required', 'regex:/^\d{4}-\d{4}$/'],
            'status'      => 'required|in:available,unavailable',
        ]);

        $availability->update($validated);

        return redirect()
            ->route('faculty.availabilities.index')
            ->with('success', 'Availability updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $availability = Availability::where('faculty_id', Auth::user()->faculty->id)
            ->findOrFail($id);

        $availability->delete();

        return redirect()
            ->route('faculty.availabilities.index')
            ->with('success', 'Availability deleted successfully.');
    }
}
