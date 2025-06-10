<?php
require '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  // Cek apakah email sudah ada
  $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
  $check->bind_param("s", $email);
  $check->execute();
  $check->store_result();

  if ($check->num_rows > 0) {
    echo "Email sudah terdaftar. Silakan gunakan email lain.";
  } else {
    // Simpan data jika email belum ada
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);

    if ($stmt->execute()) {
      echo "Registrasi berhasil! Silakan <a href='login.php'>login</a>";
    } else {
      echo "Gagal mendaftar!";
    }
  }
}
?>