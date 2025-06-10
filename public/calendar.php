<?php
require '../config/db.php';

// Ambil data meeting dari database
$result = $conn->query("SELECT * FROM meetings ORDER BY date_time ASC");

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = [
        'title' => $row['title'],
        'start' => $row['date_time'],
        'url'   => 'meeting_detail.php?id=' . $row['id']
    ];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kalender Meeting</title>
    <!-- FullCalendar CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.5/main.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        #calendar {
            max-width: 900px;
            margin: 0 auto;
        }
    </style>
</head>
<body>

<h2>Jadwal Meeting</h2>
<div id="calendar"></div>

<!-- FullCalendar JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.5/main.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: <?= json_encode($events, JSON_UNESCAPED_UNICODE) ?>,
        eventClick: function(info) {
            // Buka link detail meeting
            if (info.event.url) {
                window.open(info.event.url, '_blank');
                info.jsEvent.preventDefault(); // agar tidak follow link default
            }
        }
    });

    calendar.render();
});
</script>

</body>
</html>