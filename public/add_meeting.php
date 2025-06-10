<?php
session_start();
require '../config/db.php';
require '../config/google-config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $participants = $_POST['participants']; // comma-separated emails
    $description = $_POST['description'];
    $location = $_POST['location'];

    // Gabungkan tanggal dan waktu jadi format DATETIME
    $date_time = $date . ' ' . $time . ':00';
    $created_at = date('Y-m-d H:i:s');

    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO meetings (title, date_time, description, location, created_at) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $title, $date_time, $description, $location, $created_at);
    $stmt->execute();

    // Simpan ke Google Calendar
    if (isset($_SESSION['access_token'])) {
        $client->setAccessToken($_SESSION['access_token']);
        $service = new Google_Service_Calendar($client);

        $startDateTime = date('c', strtotime($date_time));
        $endDateTime = date('c', strtotime($date_time) + 3600); // default durasi 1 jam

        $event = new Google_Service_Calendar_Event([
            'summary' => $title,
            'location' => $location,
            'description' => $description,
            'start' => ['dateTime' => $startDateTime, 'timeZone' => 'Asia/Jakarta'],
            'end' => ['dateTime' => $endDateTime, 'timeZone' => 'Asia/Jakarta'],
            'attendees' => array_map(function($email) {
                return ['email' => trim($email)];
            }, explode(',', $participants)),
        ]);

        $service->events->insert('primary', $event);
    }

    header("Location: public/dashboard.php");
    exit();
}
?>
<form action="../public/process_meeting.php" method="POST">
  <input type="text" name="title" placeholder="Judul Meeting" required>
  <textarea name="description" placeholder="Deskripsi"></textarea>
  <input type="date" name="date" required>
  <input type="time" name="time" required>
  <input type="text" name="location" placeholder="Lokasi" required>
  <button type="submit">Simpan</button>
</form>

