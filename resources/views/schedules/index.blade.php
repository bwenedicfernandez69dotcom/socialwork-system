@extends('layouts.app')

@section('content')
<div class="max-w-5x1 mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-6">Class Schedules</h2>

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('schedules.index') }}" class="grid grid-cols-2 gap-4 mb-6">
        {{-- Year Level --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Year Level</label>
            <select name="year_level" class="border rounded px-2 py-1 w-full">
                <option value="">All Years</option>
                @foreach([1,2,3,4] as $year)
                    <option value="{{ $year }}" {{ request('year_level') == $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Semester --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Semester</label>
            <select name="semester" class="border rounded px-2 py-1 w-full">
                <option value="">All Semesters</option>
                <option value="1st" {{ request('semester') == '1st' ? 'selected' : '' }}>1st</option>
                <option value="2nd" {{ request('semester') == '2nd' ? 'selected' : '' }}>2nd</option>
            </select>
        </div>

        {{-- Faculty --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Faculty</label>
            <select name="faculty_id" class="border rounded px-2 py-1 w-full">
                <option value="">All Faculties</option>
                @foreach($faculties as $faculty)
                    <option value="{{ $faculty->id }}" {{ request('faculty_id') == $faculty->id ? 'selected' : '' }}>
                        {{ $faculty->user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Section --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Section</label>
            <select name="section_id" class="border rounded px-2 py-1 w-full">
                <option value="">All Sections</option>
                @foreach($sections as $section)
                    <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                        {{ $section->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Buttons --}}
        <div class="col-span-2 flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Display Schedules
            </button>
            <a href="{{ route('schedules.index') }}" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400 transition">
                Reset
            </a>
            <a href="{{ route('schedules.create') }}" class="ml-auto bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                + Create Schedule
            </a>
        </div>
    </form>

    {{-- Table & Pagination --}}
    @if(request()->hasAny(['year_level','semester','faculty_id','section_id']))
        <div class="overflow-x-auto bg-white rounded-lg shadow border">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-gray-700 text-sm uppercase">Year Level</th>
                        <th class="px-4 py-2 text-left text-gray-700 text-sm uppercase">Faculty</th>
                        <th class="px-4 py-2 text-left text-gray-700 text-sm uppercase">Section</th>
                        <th class="px-4 py-2 text-left text-gray-700 text-sm uppercase">Room</th>
                        <th class="px-4 py-2 text-left text-gray-700 text-sm uppercase">Semester</th>
                        <th class="px-4 py-2 text-left text-gray-700 text-sm uppercase">School Year</th>
                        <th class="px-4 py-2 text-left text-gray-700 text-sm uppercase">Days</th>
                        <th class="px-4 py-2 text-left text-gray-700 text-sm uppercase">Time</th>
                        <th class="px-4 py-2 text-left text-gray-700 text-sm uppercase">Delivery Mode</th>
                        <th class="px-4 py-2 text-left text-gray-700 text-sm uppercase">Status</th>
                        <th class="px-4 py-2 text-center text-gray-700 text-sm uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($schedules as $schedule)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 text-gray-700">{{ $schedule->year_level }}</td>
                            <td class="px-4 py-2 text-gray-700">{{ $schedule->faculty->user->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2 text-gray-700">{{ $schedule->section->name }}</td>
                            <td class="px-4 py-2 text-gray-700">{{ $schedule->room->room_no ?? 'N/A' }}</td>
                            <td class="px-4 py-2 text-gray-700">{{ $schedule->semester }}</td>
                            <td class="px-4 py-2 text-gray-700">{{ $schedule->school_year }}</td>
                            <td class="px-4 py-2 text-gray-700">{{ $schedule->days }}</td>
                            <td class="px-4 py-2 text-gray-700">{{ $schedule->start_time }} - {{ $schedule->end_time }}</td>
                            <td class="px-4 py-2 text-gray-700">{{ $schedule->delivery_mode }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded text-white text-sm font-medium {{ $schedule->status === 'Assigned' ? 'bg-green-600' : 'bg-yellow-500' }}">
                                    {{ $schedule->status }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-center flex justify-center space-x-2">
                                <a href="{{ route('schedules.edit', $schedule->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded shadow-sm text-sm font-medium transition">
                                    Edit
                                </a>
                                <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded shadow-sm text-sm font-medium transition">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-4 py-4 text-center text-gray-500">No schedules found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $schedules->links() }}
        </div>
    @else
    @endif
</div>
@endsection
