<?php
session_start();
require '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $name, $hashed_password);
    $stmt->fetch();

    if (password_verify($password, $hashed_password)) {
      $_SESSION['user_id'] = $id;
      $_SESSION['user_name'] = $name;

      if (!empty($_POST['remember'])) {
        setcookie("user_id", $id, time() + (86400 * 7), "/");
        setcookie("user_name", $name, time() + (86400 * 7), "/");
      }

      header("Location: dashboard.php");
      exit();
    } else {
      echo "Password salah.";
    }
  } else {
    echo "Email tidak ditemukan.";
  }
}
?>