@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800"> Schedule Reports</h1>
        <a href="{{ route('admin.reports.export.pdf') }}"
           class="bg-red-600 hover:bg-red-700 text-white font-semibold px-5 py-2 rounded-lg shadow">
            Export PDF
        </a>
    </div>

    {{-- Schedule Table --}}
    <div class="overflow-x-auto bg-white rounded-xl shadow border">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
                <tr>
                    <th class="px-4 py-3 text-left">Faculty</th>
                    <th class="px-4 py-3 text-left">Subject</th>
                    <th class="px-4 py-3 text-left">Room</th>
                    <th class="px-4 py-3 text-left">Section</th>
                    <th class="px-4 py-3 text-left">Semester</th>
                    <th class="px-4 py-3 text-left">School Year</th>
                    <th class="px-4 py-3 text-left">Days</th>
                    <th class="px-4 py-3 text-left">Time</th>
                    <th class="px-4 py-3 text-left">Delivery Mode</th>
                    <th class="px-4 py-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-800">
                @foreach($schedules as $schedule)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium">{{ $schedule->faculty->user->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3">{{ $schedule->subject->title ?? 'N/A' }}</td>
                        <td class="px-4 py-3">{{ $schedule->room->room_no ?? 'N/A' }}</td>
                        <td class="px-4 py-3">{{ $schedule->section->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3">{{ $schedule->semester }}</td>
                        <td class="px-4 py-3">{{ $schedule->school_year }}</td>
                        <td class="px-4 py-3">{{ $schedule->days }}</td>
                        <td class="px-4 py-3">
                            {{ \Carbon\Carbon::createFromFormat('H:i:s', $schedule->start_time)->format('h:i A') }}
                            -
                            {{ \Carbon\Carbon::createFromFormat('H:i:s', $schedule->end_time)->format('h:i A') }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                @if($schedule->delivery_mode === 'Online') bg-blue-100 text-blue-700
                                @elseif($schedule->delivery_mode === 'Face-to-Face') bg-green-100 text-green-700
                                @elseif($schedule->delivery_mode === 'Modular') bg-yellow-100 text-yellow-700
                                @else bg-gray-100 text-gray-700
                                @endif">
                                {{ $schedule->delivery_mode }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                @if($schedule->status === 'approved') bg-green-100 text-green-700
                                @elseif($schedule->status === 'pending') bg-yellow-100 text-yellow-700
                                @elseif($schedule->status === 'declined') bg-red-100 text-red-700
                                @else bg-gray-100 text-gray-700
                                @endif">
                                {{ ucfirst($schedule->status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
