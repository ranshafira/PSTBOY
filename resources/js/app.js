import "./bootstrap";
import Alpine from "alpinejs";

import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";

window.Alpine = Alpine;
Alpine.start();

document.addEventListener("DOMContentLoaded", () => {
    const calendarEl = document.getElementById("calendar");

    // Pastikan elemen #calendar ada di halaman
    if (calendarEl) {
        const calendar = new Calendar(calendarEl, {
            plugins: [dayGridPlugin, interactionPlugin],
            initialView: "dayGridMonth",
            events: "/admin/jadwal/events", // endpoint untuk ambil data event
        });

        calendar.render();
    }
});
