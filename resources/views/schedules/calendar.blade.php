@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Weekly Schedule Calendar</h2>
    <div id="calendar"></div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');

    if (calendarEl) {
        const calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: [
                FullCalendar.dayGridPlugin,
                FullCalendar.timeGridPlugin,
                FullCalendar.interactionPlugin,
                FullCalendar.bootstrapPlugin
            ],
            themeSystem: 'bootstrap',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            initialView: 'timeGridWeek',
            slotMinTime: "07:00:00",
            slotMaxTime: "20:00:00",
            allDaySlot: false,
            events: @json($events) // Pass your events from the controller
        });

        calendar.render();
    }
});
</script>
@endsection
