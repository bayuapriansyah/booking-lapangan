import { Calendar } from '@fullcalendar/core';
import resourceTimeGridPlugin from '@fullcalendar/resource-timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import timeGridPlugin from '@fullcalendar/timegrid';
import dayGridPlugin from '@fullcalendar/daygrid';

import './bootstrap';

import Alpine from 'alpinejs';

window.FullCalendar = {
    Calendar,
    resourceTimeGridPlugin,
    interactionPlugin,
    timeGridPlugin,
    dayGridPlugin
};
window.Alpine = Alpine;


document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');

    const calendar = new Calendar(calendarEl, {
        plugins: [resourceTimeGridPlugin, interactionPlugin, timeGridPlugin, dayGridPlugin],
        initialView: 'resourceTimeGridDay',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'resourceTimeGridDay,resourceTimeGridWeek,dayGridMonth'
        },
        resources: [
            { id: '1', title: 'Large Field 1\n40x20m - Vinyl Floor' },
            { id: '2', title: 'Large Field 2\n40x20m - Vinyl Floor' },
        ],
        events: [
            { id: '1', resourceId: '1', start: '2025-10-15T08:00:00', end: '2025-10-15T10:00:00', title: 'Booked', color: '#cbd5e1' },
            { id: '2', resourceId: '2', start: '2025-10-15T11:00:00', end: '2025-10-15T13:00:00', title: 'Your Reservation', color: '#10b981' },
        ],
        slotMinTime: '06:00:00',
        slotMaxTime: '18:00:00',
        height: 'auto',
    });

    calendar.render();
});

Alpine.start();
