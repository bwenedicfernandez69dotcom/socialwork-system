@extends('layouts.app')

@section('content')
<div class="p-6 bg-white rounded shadow-md">
    <h2 class="text-xl font-bold mb-4">Edit Room</h2>

    @if ($errors->any())
        <div class="bg-red-100 text-red-600 p-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.rooms.update', $room->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block font-medium">Room Name</label>
            <input type="text" name="room_no" class="w-full border rounded p-2" value="{{ $room->room_no }}" required>
        </div>

        <div>
            <label class="block font-medium">Capacity</label>
            <input type="number" name="capacity" class="w-full border rounded p-2" value="{{ $room->capacity }}" min="1" required>
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
            Update Room
        </button>
    </form>
</div>
@endsection
