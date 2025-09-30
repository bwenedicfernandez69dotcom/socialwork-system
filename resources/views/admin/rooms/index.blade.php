@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Rooms</h2>
        <a href="{{ route('admin.rooms.create') }}"
           class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded shadow-md text-sm font-semibold transition">
            + Add Room
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
                    <th class="text-left px-6 py-3 text-gray-700 uppercase text-sm">Capacity</th>
                    <th class="text-left px-6 py-3 text-gray-700 uppercase text-sm">Created At</th>
                    <th class="px-6 py-3 text-gray-700 uppercase text-sm">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($rooms as $room)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 font-medium text-gray-800">{{ $room->room_no }}</td>
                        <td class="px-6 py-3 text-gray-700">{{ $room->capacity }}</td>
                        <td class="px-6 py-3 text-gray-700">{{ $room->created_at->format('Y-m-d') }}</td>
                        <td class="px-6 py-3 flex space-x-2">
                            <a href="{{ route('admin.rooms.edit', $room->id) }}"
                               class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded shadow-sm text-sm font-medium transition">
                                Edit
                            </a>
                            <form action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this room?');">
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
                            No rooms available.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
