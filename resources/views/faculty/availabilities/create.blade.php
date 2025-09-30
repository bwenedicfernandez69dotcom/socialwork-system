@extends('layouts.app')

@section('content')
<div class="p-6 bg-white rounded shadow">
    <h2 class="text-xl font-bold mb-4">Add Availability</h2>

    <form action="{{ route('faculty.availabilities.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="block font-medium">Day</label>
            <select name="day" class="w-full border rounded p-2">
                @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $day)
                    <option value="{{ $day }}">{{ $day }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="block font-medium">Start Time</label>
            <input type="time" name="start_time" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-3">
            <label class="block font-medium">End Time</label>
            <input type="time" name="end_time" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-3">
            <label class="block font-medium">Semester</label>
            <select name="semester" class="w-full border rounded p-2" required>
                <option value="1st">1st</option>
                <option value="2nd">2nd</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="block font-medium">School Year</label>
            <input type="text" name="school_year" placeholder="2025-2026" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-3">
            <label class="block font-medium">Status</label>
            <select name="status" class="w-full border rounded p-2" required>
                <option value="available">Available</option>
                <option value="unavailable">Unavailable</option>
            </select>
        </div>

        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Save</button>
        <a href="{{ route('faculty.availabilities.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded">Cancel</a>
    </form>
</div>
@endsection
