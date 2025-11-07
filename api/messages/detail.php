<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require '../../config/database.php';
require '../response.php';

// Ambil ID dari URL parameter
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;

if (!$id) {
    die(ApiResponse::error('ID is required', 400));
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    // GET - Ambil detail message berdasarkan ID
    $query = "SELECT * FROM messages WHERE id = $id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $message = $result->fetch_assoc();
        echo ApiResponse::success($message, 'Message retrieved successfully');
    } else {
        echo ApiResponse::error('Message not found', 404);
    }

} elseif ($method == 'PUT') {
    // PUT - Update message
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['nama']) || !isset($data['pesan'])) {
        die(ApiResponse::error('Nama dan pesan harus diisi', 400));
    }

    $nama = $conn->real_escape_string($data['nama']);
    $pesan = $conn->real_escape_string($data['pesan']);

    $query = "UPDATE messages SET nama = '$nama', pesan = '$pesan' WHERE id = $id";

    if ($conn->query($query)) {
        if ($conn->affected_rows > 0) {
            echo ApiResponse::success(null, 'Message updated successfully');
        } else {
            echo ApiResponse::error('Message not found', 404);
        }
    } else {
        echo ApiResponse::error('Update failed: ' . $conn->error, 500);
    }

} elseif ($method == 'DELETE') {
    // DELETE - Hapus message
    $query = "DELETE FROM messages WHERE id = $id";

    if ($conn->query($query)) {
        if ($conn->affected_rows > 0) {
            echo ApiResponse::success(null, 'Message deleted successfully');
        } else {
            echo ApiResponse::error('Message not found', 404);
        }
    } else {
        echo ApiResponse::error('Delete failed: ' . $conn->error, 500);
    }

} else {
    echo ApiResponse::error('Method not allowed', 405);
}
?>