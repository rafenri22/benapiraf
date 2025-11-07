<?php
require '../config/database.php';

$messages = [];
$query = "SELECT * FROM messages ORDER BY created_at DESC";
$result = $conn->query($query);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BELAJAR RESTFUL API</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --color-white: #ffffff;
            --color-black: #000000;
            --color-gray-50: #fafafa;
            --color-gray-100: #f3f3f3;
            --color-gray-200: #e8e8e8;
            --color-gray-300: #d4d4d4;
            --color-gray-400: #a1a1a1;
            --color-gray-500: #707070;
            --color-gray-600: #404040;
            --color-gray-700: #262626;
            --color-gray-900: #121212;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: var(--color-gray-100);
            min-height: 100vh;
            padding: 12px;
            color: var(--color-gray-900);
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: var(--color-white);
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        /* <CHANGE> Header dengan warna netral hitam dan putih */
        header {
            background: var(--color-black);
            color: var(--color-white);
            padding: 20px 16px;
            text-align: center;
            border-bottom: 1px solid var(--color-gray-300);
        }

        header h1 {
            font-size: 22px;
            margin-bottom: 4px;
            font-weight: 600;
        }

        header p {
            opacity: 0.9;
            font-size: 13px;
            color: var(--color-gray-300);
        }

        .content {
            padding: 16px;
        }

        /* <CHANGE> Form section dengan warna abu-abu netral */
        .form-section {
            background: var(--color-gray-50);
            padding: 16px;
            border-radius: 4px;
            margin-bottom: 24px;
            border: 1px solid var(--color-gray-200);
        }

        .form-section h2 {
            font-size: 16px;
            margin-bottom: 16px;
            color: var(--color-gray-900);
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 12px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: var(--color-gray-700);
            font-weight: 500;
            font-size: 13px;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--color-gray-300);
            border-radius: 3px;
            font-family: inherit;
            font-size: 13px;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: var(--color-white);
            color: var(--color-gray-900);
        }

        input[type="text"]:focus,
        textarea:focus {
            outline: none;
            border-color: var(--color-gray-500);
            box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.08);
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .button-group {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
            flex-wrap: wrap;
        }

        button {
            padding: 10px 18px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s;
        }

        /* <CHANGE> Tombol dengan warna netral */
        .btn-submit {
            background: var(--color-gray-900);
            color: var(--color-white);
            border: 1px solid var(--color-gray-900);
        }

        .btn-submit:hover {
            background: var(--color-black);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        }

        .btn-reset {
            background: var(--color-gray-200);
            color: var(--color-gray-900);
            border: 1px solid var(--color-gray-300);
        }

        .btn-reset:hover {
            background: var(--color-gray-300);
        }

        .messages-section h2 {
            font-size: 16px;
            margin-bottom: 16px;
            color: var(--color-gray-900);
            font-weight: 600;
        }

        /* <CHANGE> Styling pesan dengan warna netral */
        .message-item {
            background: var(--color-gray-50);
            padding: 16px;
            border-radius: 3px;
            margin-bottom: 12px;
            border: 1px solid var(--color-gray-200);
            border-left: 3px solid var(--color-gray-600);
        }

        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 8px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .message-nama {
            font-weight: 600;
            color: var(--color-gray-900);
            font-size: 14px;
        }

        .message-time {
            font-size: 12px;
            color: var(--color-gray-500);
            white-space: nowrap;
        }

        .message-pesan {
            color: var(--color-gray-700);
            line-height: 1.5;
            margin-bottom: 12px;
            font-size: 13px;
            word-break: break-word;
        }

        .message-actions {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
            flex-wrap: wrap;
        }

        .btn-small {
            padding: 7px 14px;
            font-size: 12px;
            border-radius: 3px;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            font-weight: 500;
        }

        /* <CHANGE> Tombol edit dan delete dengan warna abu-abu */
        .btn-edit {
            background: var(--color-gray-400);
            color: var(--color-white);
            border: 1px solid var(--color-gray-400);
        }

        .btn-edit:hover {
            background: var(--color-gray-500);
            border-color: var(--color-gray-500);
        }

        .btn-delete {
            background: var(--color-gray-700);
            color: var(--color-white);
            border: 1px solid var(--color-gray-700);
        }

        .btn-delete:hover {
            background: var(--color-black);
            border-color: var(--color-black);
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--color-gray-500);
            font-size: 14px;
        }

        .alert {
            padding: 12px 14px;
            border-radius: 3px;
            margin-bottom: 16px;
            display: none;
            font-size: 13px;
            border: 1px solid;
        }

        /* <CHANGE> Alert dengan warna netral */
        .alert-success {
            background: var(--color-gray-50);
            color: var(--color-gray-900);
            border-color: var(--color-gray-300);
            display: block;
        }

        .alert-error {
            background: var(--color-gray-100);
            color: var(--color-gray-900);
            border-color: var(--color-gray-400);
            display: block;
        }

        /* <CHANGE> Media queries untuk mobile dan tablet responsif */
        @media (max-width: 640px) {
            body {
                padding: 8px;
            }

            header {
                padding: 16px 12px;
            }

            header h1 {
                font-size: 18px;
            }

            header p {
                font-size: 12px;
            }

            .content {
                padding: 12px;
            }

            .form-section {
                padding: 12px;
                margin-bottom: 20px;
            }

            .form-section h2 {
                font-size: 14px;
                margin-bottom: 12px;
            }

            .message-item {
                padding: 12px;
                margin-bottom: 10px;
            }

            .messages-section h2 {
                font-size: 14px;
                margin-bottom: 12px;
            }

            .button-group {
                gap: 6px;
            }

            button {
                padding: 9px 16px;
                font-size: 12px;
            }

            .btn-small {
                padding: 6px 12px;
                font-size: 11px;
            }

            .message-actions {
                gap: 6px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 6px;
            }

            header {
                padding: 12px 8px;
            }

            header h1 {
                font-size: 16px;
                margin-bottom: 2px;
            }

            header p {
                font-size: 11px;
            }

            .content {
                padding: 8px;
            }

            .form-section {
                padding: 10px;
                margin-bottom: 16px;
            }

            .form-section h2 {
                font-size: 13px;
                margin-bottom: 10px;
            }

            input[type="text"],
            textarea {
                padding: 8px;
                font-size: 12px;
            }

            textarea {
                min-height: 80px;
            }

            .button-group {
                gap: 6px;
            }

            button {
                padding: 8px 14px;
                font-size: 11px;
            }

            .message-item {
                padding: 10px;
                margin-bottom: 8px;
            }

            .message-nama {
                font-size: 13px;
            }

            .message-time {
                font-size: 11px;
            }

            .message-pesan {
                font-size: 12px;
                margin-bottom: 10px;
            }

            .btn-small {
                padding: 6px 10px;
                font-size: 10px;
            }

            .empty-state {
                padding: 30px 12px;
                font-size: 12px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <h1>BELAJAR RESTFUL API</h1>
            <p>By Rafky Ferdian Algiffari</p>
        </header>

        <div class="content">
            <div id="alertBox" class="alert"></div>

            <!-- Form Input -->
            <div class="form-section">
                <h2>Tambah Pesan Baru</h2>
                <form id="messageForm">
                    <div class="form-group">
                        <label for="nama">Nama <span style="color: var(--color-gray-600);">*</span></label>
                        <input type="text" id="nama" name="nama" placeholder="Masukkan nama..." required>
                    </div>

                    <div class="form-group">
                        <label for="pesan">Pesan <span style="color: var(--color-gray-600);">*</span></label>
                        <textarea id="pesan" name="pesan" placeholder="Masukkan pesan..." required></textarea>
                    </div>

                    <div class="button-group">
                        <button type="reset" class="btn-reset">Reset</button>
                        <button type="submit" class="btn-submit">Kirim Pesan</button>
                    </div>
                </form>
            </div>

            <!-- Daftar Pesan -->
            <div class="messages-section">
                <h2>Daftar Pesan</h2>
                <div id="messagesList">
                    <?php if (empty($messages)): ?>
                        <div class="empty-state">
                            <p>Belum ada pesan. Mulai dengan menambahkan pesan baru!</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($messages as $msg): ?>
                            <div class="message-item" data-id="<?= $msg['id'] ?>">
                                <div class="message-header">
                                    <span class="message-nama"><?= htmlspecialchars($msg['nama']) ?></span>
                                    <span class="message-time"><?= date('d M Y H:i', strtotime($msg['created_at'])) ?></span>
                                </div>
                                <p class="message-pesan"><?= nl2br(htmlspecialchars($msg['pesan'])) ?></p>
                                <div class="message-actions">
                                    <button class="btn-small btn-edit" onclick="editMessage(<?= $msg['id'] ?>)">Edit</button>
                                    <button class="btn-small btn-delete"
                                        onclick="deleteMessage(<?= $msg['id'] ?>)">Hapus</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Gunakan URL dinamis atau URL hosting yang benar
        const API_BASE = window.location.origin + '/api';
        // Atau hardcode: const API_BASE = 'https://rafkycreative.site/api';

        // Submit form
        document.getElementById('messageForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const nama = document.getElementById('nama').value.trim();
            const pesan = document.getElementById('pesan').value.trim();

            if (!nama || !pesan) {
                showAlert('Nama dan pesan harus diisi!', 'error');
                return;
            }

            try {
                const response = await fetch(`${API_BASE}/messages`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ nama, pesan })
                });

                const result = await response.json();

                if (result.status) {
                    showAlert('Pesan berhasil ditambahkan!', 'success');
                    document.getElementById('messageForm').reset();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert(result.message || 'Gagal menambahkan pesan', 'error');
                }
            } catch (error) {
                showAlert('Terjadi kesalahan koneksi ke server!', 'error');
                console.error('Error:', error);
            }
        });

        // Edit message
        async function editMessage(id) {
            const newNama = prompt('Masukkan nama baru:');
            if (!newNama || !newNama.trim()) return;

            const newPesan = prompt('Masukkan pesan baru:');
            if (!newPesan || !newPesan.trim()) return;

            try {
                const response = await fetch(`${API_BASE}/messages/${id}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ nama: newNama.trim(), pesan: newPesan.trim() })
                });

                const result = await response.json();

                if (result.status) {
                    showAlert('Pesan berhasil diupdate!', 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert(result.message || 'Gagal mengupdate pesan', 'error');
                }
            } catch (error) {
                showAlert('Terjadi kesalahan koneksi ke server!', 'error');
                console.error('Error:', error);
            }
        }

        // Delete message
        async function deleteMessage(id) {
            if (!confirm('Yakin ingin menghapus pesan ini?')) return;

            try {
                const response = await fetch(`${API_BASE}/messages/${id}`, {
                    method: 'DELETE'
                });

                const result = await response.json();

                if (result.status) {
                    showAlert('Pesan berhasil dihapus!', 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert(result.message || 'Gagal menghapus pesan', 'error');
                }
            } catch (error) {
                showAlert('Terjadi kesalahan koneksi ke server!', 'error');
                console.error('Error:', error);
            }
        }

        // Show alert
        function showAlert(message, type) {
            const alertBox = document.getElementById('alertBox');
            alertBox.textContent = message;
            alertBox.className = `alert alert-${type}`;
            setTimeout(() => alertBox.className = 'alert', 5000);
        }
    </script>
</body>

</html>