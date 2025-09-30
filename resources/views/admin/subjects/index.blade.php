@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Subjects</h1>
        <a href="{{ route('admin.subjects.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded shadow-md text-sm font-semibold transition">
            + Add Subject
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4 shadow">
            {{ session('success') }}
        </div>
    @endif

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('admin.subjects.index') }}" class="grid grid-cols-2 gap-4 mb-6">
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

        {{-- Buttons --}}
        <div class="col-span-2 flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Filter Subjects
            </button>
            <a href="{{ route('admin.subjects.index') }}" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400 transition">
                Reset
            </a>
        </div>
    </form>

    {{-- Show table only when filters applied --}}
    @if(request()->hasAny(['year_level','semester']))
        <div class="overflow-x-auto bg-white rounded-lg shadow border">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-6 py-3 text-gray-700 uppercase text-sm">Name</th>
                        <th class="text-left px-6 py-3 text-gray-700 uppercase text-sm">Code</th>
                        <th class="text-left px-6 py-3 text-gray-700 uppercase text-sm">Units</th>
                        <th class="text-left px-6 py-3 text-gray-700 uppercase text-sm">Year Level</th>
                        <th class="text-left px-6 py-3 text-gray-700 uppercase text-sm">Semester</th>
                        <th class="px-6 py-3 text-gray-700 uppercase text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($subjects as $subject)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 font-medium text-gray-800">{{ $subject->title }}</td>
                            <td class="px-6 py-3 text-gray-700">{{ $subject->code }}</td>
                            <td class="px-6 py-3 text-gray-700">{{ $subject->units }}</td>
                            <td class="px-6 py-3 text-gray-700">{{ $subject->year_level }}</td>
                            <td class="px-6 py-3 text-gray-700">{{ $subject->semester }}</td>
                            <td class="px-6 py-3 flex space-x-2">
                                <a href="{{ route('admin.subjects.edit', $subject->id) }}"
                                   class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded shadow-sm text-sm font-medium transition">
                                    Edit
                                </a>
                                <form action="{{ route('admin.subjects.destroy', $subject->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this subject?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded shadow-sm text-sm font-medium transition">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No subjects found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
