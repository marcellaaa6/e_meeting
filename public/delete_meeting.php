<?php
require '../config/db.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM meetings WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect kembali ke daftar meeting
        header("Location: meetings.php");
        exit();
    } else {
        echo "❌ Gagal menghapus jadwal: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "❗ ID tidak valid.";
}
