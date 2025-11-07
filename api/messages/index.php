<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Tangani preflight request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require '../../config/database.php';
require '../response.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    // GET - Ambil semua messages
    $query = "SELECT * FROM messages ORDER BY created_at DESC";
    $result = $conn->query($query);

    if (!$result) {
        die(ApiResponse::error('Query error: ' . $conn->error, 500));
    }

    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }

    echo ApiResponse::success($messages, 'Messages retrieved successfully');

} elseif ($method == 'POST') {
    // POST - Tambah message baru
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['nama']) || !isset($data['pesan'])) {
        die(ApiResponse::error('Nama dan pesan harus diisi', 400));
    }

    $nama = $conn->real_escape_string($data['nama']);
    $pesan = $conn->real_escape_string($data['pesan']);

    $query = "INSERT INTO messages (nama, pesan) VALUES ('$nama', '$pesan')";

    if ($conn->query($query)) {
        $lastId = $conn->insert_id;
        echo ApiResponse::success(
            ['id' => $lastId],
            'Message created successfully',
            201
        );
    } else {
        echo ApiResponse::error('Insert failed: ' . $conn->error, 500);
    }

} else {
    echo ApiResponse::error('Method not allowed', 405);
}
?>