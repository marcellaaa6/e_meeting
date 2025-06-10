<?php
require 'config/db.php';

// Ambil semua data meeting dari database
$result = $conn->query("SELECT * FROM meetings ORDER BY date_time ASC");

if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='8' cellspacing='0'>";
    echo "<tr>
            <th>Judul</th>
            <th>Waktu</th>
            <th>Lokasi</th>
            <th>Aksi</th>
          </tr>";

    while ($row = $result->fetch_assoc()) {
        $id = htmlspecialchars($row['id']);
        $title = htmlspecialchars($row['title']);
        $date_time = htmlspecialchars($row['date_time']);
        $location = htmlspecialchars($row['location']);

        echo "<tr>";
        echo "<td>{$title}</td>";
        echo "<td>{$date_time}</td>";
        echo "<td>{$location}</td>";
        echo "<td>
                <a href='edit_meeting.php?id={$id}'>Edit</a> | 
                <a href='delete_meeting.php?id={$id}' onclick=\"return confirm('Yakin ingin menghapus?');\">Hapus</a>
              </td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "Belum ada jadwal meeting.";
}

$conn->close();
?>
