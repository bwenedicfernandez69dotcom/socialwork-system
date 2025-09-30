@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Schedule Notifications</h1>

    @if(session('success'))
        <div class="mb-4 p-2 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-2 bg-red-100 text-red-700 rounded">{{ session('error') }}</div>
    @endif

    @if($notifications->count() > 0)
        <table class="w-full border-collapse border shadow-sm">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border px-4 py-2">Subject</th>
                    <th class="border px-4 py-2">Section</th>
                    <th class="border px-4 py-2">Time</th>
                    <th class="border px-4 py-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($notifications as $notification)
                    @php
                        $schedule = App\Models\Schedule::find($notification->data['schedule_id']);
                    @endphp
                    @if($schedule)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-2">{{ $schedule->subject->title ?? 'N/A' }}</td>
                            <td class="border px-4 py-2">{{ $schedule->section->name ?? 'N/A' }}</td>
                            <td class="border px-4 py-2">
                                {{ $schedule->start_time ? \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') : '-' }}
                                -
                                {{ $schedule->end_time ? \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') : '-' }}
                            </td>
                            <td class="border px-4 py-2 capitalize">
                                <span class="px-2 py-1 text-xs rounded
                                    @if($schedule->status === 'Assigned') bg-green-100 text-green-700
                                    @elseif($schedule->status === 'Pending') bg-yellow-100 text-yellow-700
                                    @elseif($schedule->status === 'Declined') bg-red-100 text-red-700
                                    @else bg-gray-100 text-gray-700 @endif">
                                    {{ $schedule->status }}
                                </span>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-gray-600">No notifications at the moment.</p>
    @endif
</div>
@endsection
