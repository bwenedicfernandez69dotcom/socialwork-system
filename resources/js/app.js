// resources/js/app.js

import './bootstrap';

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// ---------------- FullCalendar ---------------- //
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import bootstrapPlugin from '@fullcalendar/bootstrap';

// ✅ No CSS imports here — FullCalendar v6+ ships styles in the plugins automatically
// You can override styling with your own CSS in app.css if needed

// Example: initialize a calendar dynamically if needed
document.addEventListener('DOMContentLoaded', () => {
    const calendarEl = document.getElementById('calendar');

    if (calendarEl) {
        const calendar = new Calendar(calendarEl, {
            plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin, bootstrapPlugin],
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
            events: [], // optional: can dynamically pass events via Blade template
        });

        calendar.render();
    }
});
