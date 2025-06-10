<?php
session_start();
require 'config/google-config.php';

// 1. Periksa apakah kode tersedia
if (!isset($_GET['code'])) {
    die('Kode otentikasi tidak ditemukan.');
}

// 2. Tukar kode otorisasi dengan token akses
$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

// 3. Periksa jika token gagal diambil
if (isset($token['error'])) {
    die('Gagal mengambil token: ' . htmlspecialchars($token['error']));
}

// 4. Set token ke client (WAJIB menggunakan array token, bukan hanya string)
$client->setAccessToken($token);

// 5. Simpan token ke session untuk digunakan nanti
$_SESSION['access_token'] = $token;

// 6. Cek apakah token valid
if ($client->isAccessTokenExpired()) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// 7. Ambil data user dari Google
$google_service = new Google_Service_Oauth2($client);
$user_info = $google_service->userinfo->get();

// 8. Simpan data user ke session
$_SESSION['user_id'] = $user_info->id;
$_SESSION['user_name'] = $user_info->name;

// 9. Arahkan ke dashboard
header("Location: public\dashboard.php");
exit();
