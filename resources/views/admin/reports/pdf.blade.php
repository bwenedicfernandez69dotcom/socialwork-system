<!DOCTYPE html>
<html>
<head>
    <title>Schedules Report</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 5px; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h2>Schedules Report</h2>
    <table>
        <thead>
            <tr>
                <th>Faculty</th>
                <th>Subject</th>
                <th>Room</th>
                <th>Section</th>
                <th>Semester</th>
                <th>School Year</th>
                <th>Days</th>
                <th>Time</th>
                <th>Delivery Mode</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($schedules as $schedule)
                <tr>
                    <td>{{ $schedule->faculty->user->name ?? 'N/A' }}</td>
                    <td>{{ $schedule->subject->title ?? 'N/A' }}</td>
                    <td>{{ $schedule->room->room_no ?? 'N/A' }}</td>
                    <td>{{ $schedule->section->name ?? 'N/A' }}</td>
                    <td>{{ $schedule->semester }}</td>
                    <td>{{ $schedule->school_year }}</td>
                    <td>{{ $schedule->days }}</td>
                    <td>{{ $schedule->start_time }} - {{ $schedule->end_time }}</td>
                    <td>{{ $schedule->delivery_mode }}</td>
                    <td>{{ $schedule->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
