<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class FacultyController extends Controller
{
    public function index()
    {
        $faculties = Faculty::with('user')->latest()->paginate(10);
        return view('admin.faculties.index', compact('faculties'));
    }

    public function create()
    {
        return view('admin.faculties.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'employee_id' => 'required|string|unique:faculties,employee_id',
            'department'  => 'required|string|max:255',
            'max_load'    => 'nullable|integer|min:1',
        ]);

        // Create linked user with role 'faculty'
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make('password123'), // Default password, admin can reset
            'role'     => 'faculty',
        ]);

        // Create faculty profile
        Faculty::create([
            'user_id'     => $user->id,
            'employee_id' => $validated['employee_id'],
            'department'  => $validated['department'],
            'max_load'    => $validated['max_load'] ?? 5,
        ]);

        return redirect()->route('admin.faculties.index')->with('success', 'Faculty created successfully.');
    }

    public function edit(Faculty $faculty)
    {
        return view('admin.faculties.edit', compact('faculty'));
    }

    public function update(Request $request, Faculty $faculty)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,'.$faculty->user_id,
            'employee_id' => 'required|string|unique:faculties,employee_id,'.$faculty->id,
            'department'  => 'required|string|max:255',
            'max_load'    => 'nullable|integer|min:1',
        ]);

        // Update linked user
        $faculty->user()->update([
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update faculty profile
        $faculty->update([
            'employee_id' => $validated['employee_id'],
            'department'  => $validated['department'],
            'max_load'    => $validated['max_load'] ?? $faculty->max_load,
        ]);

        return redirect()->route('admin.faculties.index')->with('success', 'Faculty updated successfully.');
    }

    public function destroy(Faculty $faculty)
    {
        // Delete linked user
        $faculty->user()->delete();

        // Delete faculty profile
        $faculty->delete();

        return redirect()->route('admin.faculties.index')->with('success', 'Faculty deleted successfully.');
    }
}
