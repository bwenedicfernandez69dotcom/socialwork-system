@extends('layouts.app')

@section('content')
<div class="p-6 bg-white rounded shadow-md">
    <h2 class="text-2xl font-bold mb-4">Schedule Details</h2>

    <div class="space-y-2">
        <p><strong>Subject:</strong> {{ $schedule->subject->title ?? 'N/A' }}</p>
        <p><strong>Faculty:</strong> {{ $schedule->users->name ?? 'N/A' }}</p>
        <p><strong>Room:</strong> {{ $schedule->room->room_no ?? 'N/A' }}</p>
        <p><strong>Section:</strong> {{ $schedule->section->name ?? 'N/A' }}</p>
    </div>

    <div class="mt-4">
        <a href="{{ route('admin.schedules.index') }}" 
           class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
           Back to Schedules
        </a>
    </div>
</div>
@endsection
