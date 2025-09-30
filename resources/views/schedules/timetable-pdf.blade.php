<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Class Timetable</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10.5px; }
        h2, h3 { text-align: center; margin-bottom: 5px; }
        h3 { margin-top: 0; font-weight: normal; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; table-layout: fixed; }
        th, td {
            border: 1px solid #444;
            padding: 4px;
            text-align: center;
            font-size: 9px;
            vertical-align: middle;
            word-wrap: break-word;
        }
        th { background: #f0f0f0; font-size: 10px; }
        .subject { font-weight: bold; font-size: 10px; }
        .small { font-size: 8px; }
        .legend { margin-top: 8px; page-break-inside: avoid; }
        .legend-item { display:inline-block; margin-right:10px; margin-bottom:5px; font-size:9px; }
        .color-box { display:inline-block; width:12px; height:12px; margin-right:6px; vertical-align:middle; border: 1px solid #333; }
    </style>
</head>
<body>
    <h2>Class Timetable</h2>
    <h3>{{ $semester ?? 'Semester' }} — SY {{ $schoolYear ?? 'School Year' }}</h3>

    {{-- Unified Timetable --}}
    <table>
        <thead>
            <tr>
                <th style="width:9%;">Time</th>
                @foreach($days as $day)
                    <th>{{ $day }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($times as $time)
                <tr>
                    <td>{{ date('h:i A', strtotime($time)) }}</td>
                    @foreach($days as $day)
                        @php $slot = $matrix[$day][$time] ?? null; @endphp

                        @if($slot === 'merged')
                            {{-- skipped, occupied by rowspan above --}}
                            @continue
                        @elseif(is_array($slot))
                            <td rowspan="{{ $slot['rowspan'] }}"
                                style="background-color: {{ $slot['color'] }}; color:#fff;">
                                <div class="subject">{{ $slot['schedule']->subject->title ?? '' }}</div>
                                <div class="small">{{ $slot['schedule']->section->name ?? '' }}</div>
                                <div class="small">{{ $slot['schedule']->faculty->user->name ?? '' }}</div>
                                <div class="small">{{ $slot['schedule']->room->room_no ?? 'TBA' }}</div>
                            </td>
                        @else
                            <td></td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Legend --}}
    <div class="legend">
        <h3 style="text-align:left;">Faculty Legend</h3>
        <div style="text-align:left;">
            @if(!empty($facultyNames) && !empty($facultyColors))
                @foreach($facultyNames as $id => $name)
                    <div class="legend-item">
                        <span class="color-box" style="background: {{ $facultyColors[$id] ?? '#999' }};"></span>
                        {{ $name }}
                    </div>
                @endforeach
            @else
                <div class="small">No faculty legend available.</div>
            @endif
        </div>
    </div>
</body>
</html>
