<?php
$host = 'localhost';
$user = 'root';
$password = ''; // Default password XAMPP biasanya kosong
$database = 'e_meeting';

// Buat koneksi
$conn = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}
?>
