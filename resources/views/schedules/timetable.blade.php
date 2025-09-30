@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Timetable</h1>
        <div class="flex items-center gap-4">
            <a href="{{ route('schedules.timetable.export.pdf') }}"
                class="bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-4 py-2 rounded shadow">
                Export to PDF
            </a>
        </div>
    </div>

    {{-- Legend --}}
    @if(!empty($facultyColors) && !empty($facultyNames))
        <div class="mb-4">
            <h2 class="font-semibold mb-2">Faculty Legend</h2>
            <div class="flex flex-wrap gap-3">
                @foreach($facultyNames as $id => $name)
                    <div class="flex items-center gap-2 text-sm">
                        <span style="display:inline-block;width:16px;height:16px;background:{{ $facultyColors[$id] }};border-radius:3px;"></span>
                        <span>{{ $name }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Unified Timetable --}}
    <h2 class="text-xl font-semibold mb-2">Class Schedule</h2>
    <div class="overflow-auto border rounded mb-6">
        <table class="min-w-full border-collapse border" style="table-layout: fixed; width: 100%;">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border px-2 py-1 w-[80px]">Time</th>
                    @foreach($days as $day)
                        <th class="border px-2 py-1">{{ $day }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($times as $time)
                    <tr>
                        <td class="border px-2 py-1 text-xs font-mono">
                            {{ \Carbon\Carbon::createFromFormat('H:i:s', $time)->format('h:i A') }}
                        </td>

                        @foreach($days as $day)
                            @php $cell = $matrix[$day][$time] ?? null; @endphp

                            @if($cell === 'merged')
                                {{-- skip: cell is occupied by a rowspan from previous row --}}
                                @continue
                            @elseif(is_array($cell))
                                <td class="border px-2 py-1 text-white text-xs font-semibold align-middle"
                                    rowspan="{{ $cell['rowspan'] }}"
                                    style="background-color: {{ $cell['color'] }}; word-wrap: break-word; white-space: normal; line-height: 1.2;">
                                    <div>{{ $cell['schedule']->subject->title ?? 'N/A' }}</div>
                                    <div>{{ $cell['schedule']->faculty->user->name ?? 'N/A' }}</div>
                                    <div>{{ $cell['schedule']->room->room_no ?? 'N/A' }}</div>
                                    <div class="text-[10px] opacity-80">
                                        {{ \Carbon\Carbon::createFromFormat('H:i:s', $cell['schedule']->start_time)->format('h:i A') }}
                                        -
                                        {{ \Carbon\Carbon::createFromFormat('H:i:s', $cell['schedule']->end_time)->format('h:i A') }}
                                    </div>
                                </td>
                            @else
                                <td class="border px-2 py-1"></td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
