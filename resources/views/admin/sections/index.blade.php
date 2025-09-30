@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Sections</h1>
        <a href="{{ route('admin.sections.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded shadow-md text-sm font-semibold transition">
            + Add Section
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4 shadow">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto bg-white rounded-lg shadow border">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-6 py-3 text-gray-700 uppercase text-sm">Name</th>
                    <th class="text-left px-6 py-3 text-gray-700 uppercase text-sm">Year Level</th>
                    <th class="text-left px-6 py-3 text-gray-700 uppercase text-sm">Program</th>
                    <th class="px-6 py-3 text-gray-700 uppercase text-sm">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($sections as $section)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 font-medium text-gray-800">{{ $section->name }}</td>
                        <td class="px-6 py-3 text-gray-700">{{ $section->year_level }}</td>
                        <td class="px-6 py-3 text-gray-700">{{ $section->program }}</td>
                        <td class="px-6 py-3 flex space-x-2">
                            <a href="{{ route('admin.sections.edit', $section->id) }}"
                               class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded shadow-sm text-sm font-medium transition">
                                Edit
                            </a>
                            <form action="{{ route('admin.sections.destroy', $section->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this section?');">
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
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            No sections found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
