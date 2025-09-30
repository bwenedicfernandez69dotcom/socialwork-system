@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">My Schedules</h1>

    @if ($schedules && $schedules->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border shadow-sm">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border px-4 py-2">Subject</th>
                        <th class="border px-4 py-2">Section</th>
                        <th class="border px-4 py-2">Days</th>
                        <th class="border px-4 py-2">Time</th>
                        <th class="border px-4 py-2">Room</th>
                        <th class="border px-4 py-2">Delivery Mode</th>
                        <th class="border px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($schedules as $schedule)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-2">{{ $schedule->subject->title ?? 'N/A' }}</td>
                            <td class="border px-4 py-2">{{ $schedule->section->name ?? 'N/A' }}</td>
                            <td class="border px-4 py-2">{{ $schedule->days ?? 'N/A' }}</td>
                            <td class="border px-4 py-2">
                                {{ $schedule->start_time ? \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') : '-' }}
                                -
                                {{ $schedule->end_time ? \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') : '-' }}
                            </td>
                            <td class="border px-4 py-2">{{ $schedule->room->room_no ?? 'N/A' }}</td>
                            <td class="border px-4 py-2">{{ $schedule->delivery_mode ?? 'N/A' }}</td>
                            <td class="border px-4 py-2 capitalize">
                                @if($schedule->status === 'Assigned')
                                    <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">{{ $schedule->status }}</span>
                                @elseif($schedule->status === 'Pending')
                                    <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-700">{{ $schedule->status }}</span>
                                @elseif($schedule->status === 'Declined')
                                    <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-700">{{ $schedule->status }}</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-700">{{ $schedule->status ?? 'Pending' }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $schedules->links() }}
        </div>
    @else
        <p class="text-gray-600">No schedules assigned to you yet.</p>
    @endif
</div>
@endsection
