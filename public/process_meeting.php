<?php
// Aktifkan error untuk debugging (hapus di produksi)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Koneksi ke database
require '../config/db.php';

// Cek jika request POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ambil data dari form
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $date = trim($_POST['date'] ?? '');
    $time = trim($_POST['time'] ?? '');
    $location = trim($_POST['location'] ?? '');

    // Gabungkan tanggal dan waktu ke format DATETIME
    $date_time = $date . ' ' . $time;

    // Validasi wajib
    if (empty($title) || empty($date_time) || empty($location)) {
        echo "❌ Judul, tanggal/waktu, dan lokasi wajib diisi.";
        exit;
    }

    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO meetings (title, description, date_time, location) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        die("❌ Kesalahan prepare: " . $conn->error);
    }

    $stmt->bind_param("ssss", $title, $description, $date_time, $location);

    if ($stmt->execute()) {
        header("Location: dashboard.php?status=sukses");
        exit();
    } else {
        echo "❌ Gagal menyimpan jadwal: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "❌ Akses tidak sah.";
}
?>
