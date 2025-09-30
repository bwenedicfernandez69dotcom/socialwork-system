<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;

class SubjectController extends Controller
{
    /**
     * Display a listing of the subjects with optional filters.
     */
    public function index(Request $request)
    {
        $query = Subject::query();

        // Filter by year_level kung meron
        if ($request->filled('year_level')) {
            $query->where('year_level', $request->year_level);
        }

        // Filter by semester kung meron
        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        // Paginate results para hindi mabigat sa page
        $subjects = $query->orderBy('year_level')
                          ->orderByRaw("FIELD(semester,'1st','2nd')")
                          ->orderBy('title')
                          ->paginate(10);

        return view('admin.subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new subject.
     */
    public function create()
    {
        return view('admin.subjects.create');
    }

    /**
     * Store a newly created subject in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'code'       => 'required|string|max:50|unique:subjects,code',
            'units'      => 'required|numeric|min:1',
            'year_level' => 'required|integer|in:1,2,3,4',
            'semester'   => 'required|in:1st,2nd',
        ]);

        Subject::create($validated);

        return redirect()->route('admin.subjects.index')
                         ->with('success', 'Subject created successfully.');
    }

    /**
     * Show the form for editing the specified subject.
     */
    public function edit($id)
    {
        $subject = Subject::findOrFail($id);
        return view('admin.subjects.edit', compact('subject'));
    }

    /**
     * Update the specified subject in storage.
     */
    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);

        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'code'       => 'required|string|max:50|unique:subjects,code,' . $subject->id,
            'units'      => 'required|numeric|min:1',
            'year_level' => 'required|integer|in:1,2,3,4',
            'semester'   => 'required|in:1st,2nd',
        ]);

        $subject->update($validated);

        return redirect()->route('admin.subjects.index')
                         ->with('success', 'Subject updated successfully.');
    }

    /**
     * Filter subjects by year level and semester (AJAX).
     */
    public function filter(Request $request)
{
    $subjects = Subject::where('year_level', $request->year_level)
        ->where('semester', $request->semester)
        ->get(['id', 'title']);

    return response()->json($subjects);
}



    /**
     * Remove the specified subject from storage.
     */
    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();

        return redirect()->route('admin.subjects.index')
                         ->with('success', 'Subject deleted successfully.');
    }
}
