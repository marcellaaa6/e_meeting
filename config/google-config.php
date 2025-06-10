<?php
require_once __DIR__ . '/../vendor/autoload.php';

$client = new Google_Client();
$client->setClientId('1078489610505-k4l8ge3msph2jt1kbvvgnn21c123h9m7.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/e_meeting/google-callback.php');
$client->addScope(Google_Service_Calendar::CALENDAR);
$client->addScope(Google_Service_Oauth2::USERINFO_EMAIL);
$client->addScope(Google_Service_Oauth2::USERINFO_PROFILE);
$client->setAccessType('offline');
$client->setPrompt('consent');

// Tambahkan semua scope yang dibutuhkan
$client->addScope([
    "email",
    "profile",
    Google_Service_Calendar::CALENDAR  // <== Ini penting!
]);
