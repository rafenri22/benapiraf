<?php
// Konfigurasi Database
define('DB_HOST', 'localhost');
define('DB_USER', 'gpuwulny_rafky');
define('DB_PASS', 'Rafky@123');
define('DB_NAME', 'gpuwulny_restapi');

// Koneksi Database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Cek koneksi
if ($conn->connect_error) {
    die(json_encode(['status' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]));
}

// Set charset
$conn->set_charset('utf8');
?>