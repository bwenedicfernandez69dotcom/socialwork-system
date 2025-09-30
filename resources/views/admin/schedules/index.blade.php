@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Schedules (Grouped by Day)</h1>

    @if($schedules->isEmpty())
        <div class="text-center text-gray-500 py-10 bg-white rounded-lg shadow">
            No schedules found.
        </div>
    @else
        @foreach($schedules as $day => $daySchedules)
            <div class="mb-10">
                {{-- Day Header --}}
                <h2 class="text-xl font-semibold text-gray-700 mb-3 flex items-center">
                    <span class="mr-2"></span> {{ $day }}
                </h2>

                {{-- Table --}}
                <div class="overflow-x-auto bg-white rounded-lg shadow border">
                    <table class="min-w-full border-collapse">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
                            <tr>
                                <th class="px-4 py-3 text-left">Subject</th>
                                <th class="px-4 py-3 text-left">Faculty</th>
                                <th class="px-4 py-3 text-left">Section</th>
                                <th class="px-4 py-3 text-left">Room</th>
                                <th class="px-4 py-3 text-left">Time</th>
                                <th class="px-4 py-3 text-left">Delivery Mode</th>
                                <th class="px-4 py-3 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-800">
                            @foreach($daySchedules as $entry)
                                @php $schedule = $entry['schedule']; @endphp
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3 font-medium">
                                        {{ $schedule->subject->title ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ $schedule->faculty?->user->name ?? 'Unassigned' }}
                                    </td>
                                    <td class="px-4 py-3">{{ $schedule->section->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-3">{{ $schedule->room->room_no ?? 'N/A' }}</td>
                                    <td class="px-4 py-3">
                                        {{ $schedule->start_time ? date('h:i A', strtotime($schedule->start_time)) : '-' }}
                                        –
                                        {{ $schedule->end_time ? date('h:i A', strtotime($schedule->end_time)) : '-' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @switch($schedule->delivery_mode)
                                            @case('Face-to-Face')
                                                <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">Face-to-Face</span>
                                                @break
                                            @case('Online')
                                                <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-700">Online</span>
                                                @break
                                            @case('Modular')
                                                <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-700">Modular</span>
                                                @break
                                            @default
                                                <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-700">
                                                    {{ $schedule->delivery_mode ?? 'N/A' }}
                                                </span>
                                        @endswitch
                                    </td>
                                    <td class="px-4 py-3 capitalize">
                                        <span class="px-2 py-1 text-xs rounded font-semibold
                                            @if($schedule->status === 'approved') bg-green-100 text-green-700
                                            @elseif($schedule->status === 'pending') bg-yellow-100 text-yellow-700
                                            @elseif($schedule->status === 'declined') bg-red-100 text-red-700
                                            @else bg-gray-100 text-gray-700
                                            @endif">
                                            {{ $schedule->status ?? 'Pending' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
