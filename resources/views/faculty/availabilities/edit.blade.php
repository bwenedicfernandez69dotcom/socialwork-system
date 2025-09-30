@extends('layouts.app')

@section('content')
<div class="p-6 bg-white rounded shadow max-w-lg mx-auto">
    <h2 class="text-xl font-bold mb-4">Edit Availability</h2>

    {{-- Show Validation Errors --}}
    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('faculty.availabilities.update', $availability->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Day --}}
        <div class="mb-3">
            <label class="block font-medium">Day</label>
            <select name="day" class="w-full border rounded p-2">
                @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $day)
                    <option value="{{ $day }}" {{ old('day', $availability->day) == $day ? 'selected' : '' }}>
                        {{ $day }}
                    </option>
                @endforeach
            </select>
            @error('day') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Start Time --}}
        <div class="mb-3">
            <label class="block font-medium">Start Time</label>
            <input type="time" name="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($availability->start_time)->format('H:i')) }}" class="w-full border rounded p-2" required>
            @error('start_time') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- End Time --}}
        <div class="mb-3">
            <label class="block font-medium">End Time</label>
            <input type="time" name="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($availability->end_time)->format('H:i')) }}" class="w-full border rounded p-2" required>
            @error('end_time') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Semester --}}
        <div class="mb-3">
            <label class="block font-medium">Semester</label>
            <select name="semester" class="w-full border rounded p-2" required>
                <option value="1st" {{ old('semester', $availability->semester) == '1st' ? 'selected' : '' }}>1st</option>
                <option value="2nd" {{ old('semester', $availability->semester) == '2nd' ? 'selected' : '' }}>2nd</option>
            </select>
            @error('semester') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- School Year --}}
        <div class="mb-3">
            <label class="block font-medium">School Year</label>
            <input type="text" name="school_year" value="{{ old('school_year', $availability->school_year) }}" class="w-full border rounded p-2" required>
            @error('school_year') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Status --}}
        <div class="mb-3">
            <label class="block font-medium">Status</label>
            <select name="status" class="w-full border rounded p-2" required>
                <option value="available" {{ old('status', $availability->status) == 'available' ? 'selected' : '' }}>Available</option>
                <option value="unavailable" {{ old('status', $availability->status) == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
            </select>
            @error('status') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Buttons --}}
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
            <a href="{{ route('faculty.availabilities.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded">Cancel</a>
        </div>
    </form>
</div>
@endsection
