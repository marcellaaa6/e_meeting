<?php
session_start();
require '../config/google-config.php';
require '../config/db.php';

if (!isset($_SESSION['user_id']) && !isset($_SESSION['access_token'])) {
    header("Location: login.php");
    exit();
}

$userName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Pengguna Google';

// Define date range for upcoming meetings (next 30 days)
$today = date('Y-m-d');
$nextMonth = date('Y-m-d', strtotime('+30 days'));

// Fetch upcoming meetings for schedule table
$query = "SELECT * FROM meetings WHERE DATE(date_time) BETWEEN ? AND ? ORDER BY date_time ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $today, $nextMonth);
$stmt->execute();
$meetingsResult = $stmt->get_result();

// Fetch all meetings for calendar events
$calendarResult = $conn->query("SELECT * FROM meetings ORDER BY date_time ASC");
$events = [];
while ($row = $calendarResult->fetch_assoc()) {
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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard - E-Meeting</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.5/main.min.css" rel="stylesheet" />
  <style>
    /* Reset and base */
    * {
      box-sizing: border-box;
    }
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      margin: 0;
      padding: 0;
      color: #333;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    header {
      width: 100%;
      background-color: #1a73e8;
      padding: 20px 40px;
      color: white;
      box-shadow: 0 2px 8px rgba(26, 115, 232, 0.4);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    header h1 {
      margin: 0;
      font-weight: 600;
      font-size: 1.5rem;
      letter-spacing: 1px;
    }
    .container {
      background: white;
      max-width: 900px;
      width: 90%;
      margin: 40px auto;
      padding: 40px;
      border-radius: 16px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    }
    .welcome {
      font-size: 1.8rem;
      font-weight: 600;
      margin-bottom: 10px;
      color: #222;
    }
    .subtitle {
      font-size: 1.1rem;
      color: #666;
      margin-bottom: 30px;
    }
    nav {
      display: flex;
      gap: 30px;
      margin-bottom: 40px;
    }
    nav a {
      text-decoration: none;
      color: #1a73e8;
      font-weight: 600;
      font-size: 1.1rem;
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 10px 20px;
      border-radius: 8px;
      transition: background-color 0.3s ease, color 0.3s ease;
      box-shadow: 0 2px 6px rgba(26, 115, 232, 0.2);
    }
    nav a:hover {
      background-color: #1a73e8;
      color: white;
      box-shadow: 0 4px 12px rgba(26, 115, 232, 0.4);
    }
    nav a svg {
      width: 20px;
      height: 20px;
      fill: currentColor;
    }
    .logout-btn {
      background-color: #d9534f;
      color: white;
      padding: 12px 28px;
      border-radius: 10px;
      text-align: center;
      display: inline-block;
      text-decoration: none;
      font-weight: 600;
      font-size: 1.1rem;
      box-shadow: 0 4px 12px rgba(217, 83, 79, 0.4);
      transition: background-color 0.3s ease;
    }
    .logout-btn:hover {
      background-color: #c9302c;
      box-shadow: 0 6px 16px rgba(201, 48, 44, 0.6);
    }
    /* Meeting schedule table */
    .schedule-section {
      margin-bottom: 40px;
    }
    .schedule-section h2 {
      font-weight: 600;
      margin-bottom: 20px;
      color: #1a73e8;
    }
    table.schedule-table {
      width: 100%;
      border-collapse: collapse;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      border-radius: 12px;
      overflow: hidden;
    }
    table.schedule-table th, table.schedule-table td {
      padding: 12px 15px;
      text-align: left;
    }
    table.schedule-table thead {
      background-color: #1a73e8;
      color: white;
      font-weight: 600;
    }
    table.schedule-table tbody tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    /* Calendar section */
    .calendar-section {
      margin-bottom: 40px;
    }
    #calendar {
      max-width: 100%;
      margin: 0 auto;
      border-radius: 12px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.1);
      background: white;
      padding: 20px;
    }
    @media (max-width: 600px) {
      .container {
        padding: 20px;
        width: 95%;
      }
      nav {
        flex-direction: column;
        gap: 15px;
      }
      nav a {
        justify-content: center;
      }
    }
  </style>
</head>
<body>
  <header>
    <h1>E-Meeting Dashboard</h1>
    <a href="logout.php" class="logout-btn">Logout</a>
  </header>
  <main class="container">
    <div class="welcome">Selamat datang, <?php echo htmlspecialchars($userName); ?>!</div>
    <div class="subtitle">Ini adalah dashboard pribadimu.</div>
    <nav>
      <a href="events.php" aria-label="Event">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19 4h-1V2h-2v2H8V2H6v2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zM5 20V9h14v11H5z"/></svg>
        Event
      </a>
      <a href="meetings.php" aria-label="Manajemen Meeting">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h18v2H3V6zm0 5h18v2H3v-2zm0 5h12v2H3v-2z"/></svg>
      Kelola Meeting
      </a>
      <a href="profile.php" aria-label="Lihat Profil">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
        Lihat Profil
      </a>
    </nav>

    <section class="schedule-section">
      <h2>Jadwal Meeting Mendatang</h2>
      <table class="schedule-table" aria-label="Jadwal Meeting Mendatang">
        <thead>
          <tr>
            <th>Judul</th>
            <th>Deskripsi</th>
            <th>Waktu</th>
            <th>Lokasi</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($meetingsResult->num_rows > 0): ?>
            <?php while ($row = $meetingsResult->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td><?= htmlspecialchars($row['date_time']) ?></td>
                <td><?= htmlspecialchars($row['location']) ?></td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="4" style="text-align:center;">Tidak ada jadwal meeting mendatang.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </section>

    <section class="calendar-section">
      <h2>Kalender Meeting</h2>
      <div id="calendar"></div>
    </section>
  </main>

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
              if (info.event.url) {
                  window.open(info.event.url, '_blank');
                  info.jsEvent.preventDefault();
              }
          }
      });

      calendar.render();
  });
  </script>
</body>
</html>
