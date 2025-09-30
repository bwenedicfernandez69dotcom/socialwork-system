<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;

class SectionController extends Controller
{
    public function index()
    {
        $sections = Section::all();
        return view('admin.sections.index', compact('sections'));
    }

    public function create()
    {
        return view('admin.sections.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'year_level' => 'required|string|max:50',
            'program' => 'required|string|max:50',
        ]);

        Section::create($request->all());

        return redirect()->route('admin.sections.index')
                         ->with('success', 'Section created successfully.');
    }

    public function edit(string $id)
    {
        $section = Section::findOrFail($id);
        return view('admin.sections.edit', compact('section'));
    }

    public function update(Request $request, string $id)
    {
        $section = Section::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'year_level' => 'required|string|max:50',
            'program' => 'required|string|max:50',
        ]);

        $section->update($request->only('name', 'year_level', 'program'));

        return redirect()->route('admin.sections.index')
                         ->with('success', 'Section updated successfully.');
    }

    public function destroy(string $id)
    {
        $section = Section::findOrFail($id);
        $section->delete();

        return redirect()->route('admin.sections.index')
                         ->with('success', 'Section deleted successfully.');
    }
}
