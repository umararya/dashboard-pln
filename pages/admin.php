<?php
/**
 * Admin Panel
 * Path: pages/admin.php
 */

session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Hanya admin yang bisa akses
require_admin();

$pdo = db();
$success = '';
$errors = [];

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // ============ PIC IT SUPPORT ============
    if ($action === 'add_pic') {
        $name = trim($_POST['pic_name'] ?? '');
        if ($name === '') {
            $errors[] = 'Nama PIC IT Support wajib diisi.';
        } else {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM pic_it_support WHERE name = :name");
            $stmt->execute([':name' => $name]);
            if ($stmt->fetchColumn() > 0) {
                $errors[] = 'Nama PIC IT Support sudah ada.';
            } else {
                $max_order = $pdo->query("SELECT COALESCE(MAX(sort_order), 0) FROM pic_it_support")->fetchColumn();
                $stmt = $pdo->prepare("INSERT INTO pic_it_support (name, sort_order) VALUES (:name, :sort)");
                $stmt->execute([':name' => $name, ':sort' => $max_order + 1]);
                $success = "PIC IT Support '$name' berhasil ditambahkan.";
            }
        }
    }

    if ($action === 'delete_pic') {
        $id = (int)($_POST['pic_id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare("DELETE FROM pic_it_support WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $success = 'PIC IT Support berhasil dihapus.';
        }
    }

    if ($action === 'edit_pic') {
        $id = (int)($_POST['pic_id'] ?? 0);
        $name = trim($_POST['pic_name'] ?? '');
        if ($id > 0 && $name !== '') {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM pic_it_support WHERE name = :name AND id != :id");
            $stmt->execute([':name' => $name, ':id' => $id]);
            if ($stmt->fetchColumn() > 0) {
                $errors[] = 'Nama PIC IT Support sudah ada.';
            } else {
                $stmt = $pdo->prepare("UPDATE pic_it_support SET name = :name WHERE id = :id");
                $stmt->execute([':name' => $name, ':id' => $id]);
                $success = 'PIC IT Support berhasil diupdate.';
            }
        }
    }

    // ============ MEETING ROOM ============
    if ($action === 'add_room') {
        $name = trim($_POST['room_name'] ?? '');
        if ($name === '') {
            $errors[] = 'Nama Ruang Rapat wajib diisi.';
        } else {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM meeting_rooms WHERE name = :name");
            $stmt->execute([':name' => $name]);
            if ($stmt->fetchColumn() > 0) {
                $errors[] = 'Nama Ruang Rapat sudah ada.';
            } else {
                $max_order = $pdo->query("SELECT COALESCE(MAX(sort_order), 0) FROM meeting_rooms")->fetchColumn();
                $stmt = $pdo->prepare("INSERT INTO meeting_rooms (name, sort_order) VALUES (:name, :sort)");
                $stmt->execute([':name' => $name, ':sort' => $max_order + 1]);
                $success = "Ruang Rapat '$name' berhasil ditambahkan.";
            }
        }
    }

    if ($action === 'delete_room') {
        $id = (int)($_POST['room_id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare("DELETE FROM meeting_rooms WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $success = 'Ruang Rapat berhasil dihapus.';
        }
    }

    if ($action === 'edit_room') {
        $id = (int)($_POST['room_id'] ?? 0);
        $name = trim($_POST['room_name'] ?? '');
        if ($id > 0 && $name !== '') {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM meeting_rooms WHERE name = :name AND id != :id");
            $stmt->execute([':name' => $name, ':id' => $id]);
            if ($stmt->fetchColumn() > 0) {
                $errors[] = 'Nama Ruang Rapat sudah ada.';
            } else {
                $stmt = $pdo->prepare("UPDATE meeting_rooms SET name = :name WHERE id = :id");
                $stmt->execute([':name' => $name, ':id' => $id]);
                $success = 'Ruang Rapat berhasil diupdate.';
            }
        }
    }
}

// Load data
$pic_list = $pdo->query("SELECT * FROM pic_it_support ORDER BY sort_order ASC, id ASC")->fetchAll();
$room_list = $pdo->query("SELECT * FROM meeting_rooms ORDER BY sort_order ASC, id ASC")->fetchAll();
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel - Kelola Master Data</title>
    <style>
        * { box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 24px;
            background: #f6f7fb;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 8px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding: 16px;
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .user-info {
            color: #6b7280;
            font-size: 14px;
        }

        .nav-links {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
        }

        .nav-links a {
            padding: 10px 20px;
            background: #fff;
            color: #111827;
            text-decoration: none;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            transition: all 0.2s;
        }

        .nav-links a:hover {
            background: #f3f4f6;
            border-color: #111827;
        }

        .nav-links a.active {
            background: #111827;
            color: #fff;
        }

        .card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .card h2 {
            margin: 0 0 20px 0;
            font-size: 20px;
            color: #111827;
        }

        .msg {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .msg-ok {
            background: #ecfdf5;
            border: 1px solid #10b981;
            color: #065f46;
        }

        .msg-err {
            background: #fef2f2;
            border: 1px solid #ef4444;
            color: #991b1b;
        }

        .form-inline {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .form-inline input {
            flex: 1;
            min-width: 250px;
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            outline: none;
        }

        .form-inline input:focus {
            border-color: #111827;
            box-shadow: 0 0 0 3px rgba(17, 24, 39, 0.1);
        }

        .btn {
            padding: 10px 18px;
            border: 0;
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #111827;
            color: #fff;
        }

        .btn-primary:hover {
            background: #1f2937;
        }

        .btn-danger {
            background: #ef4444;
            color: #fff;
            font-size: 13px;
            padding: 6px 12px;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .btn-edit {
            background: #3b82f6;
            color: #fff;
            font-size: 13px;
            padding: 6px 12px;
        }

        .btn-edit:hover {
            background: #2563eb;
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #111827;
        }

        .btn-secondary:hover {
            background: #d1d5db;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }

        th, td {
            border: 1px solid #e5e7eb;
            padding: 12px;
            text-align: left;
        }

        th {
            background: #f9fafb;
            font-weight: 600;
            color: #374151;
        }

        tr:hover {
            background: #f9fafb;
        }

        .actions {
            display: flex;
            gap: 8px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        @media (max-width: 768px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            max-width: 500px;
            width: 90%;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-header h3 {
            margin: 0;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #6b7280;
        }

        .close-modal:hover {
            color: #111827;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
        }

        .modal-footer {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔧 Admin Panel - Master Data</h1>
            <div class="user-info">
                <strong>👤 <?= h(current_user()['username']) ?></strong> (Admin)
            </div>
        </div>

        <div class="nav-links">
            <a href="<?= base_url('index.php') ?>">← Kembali ke Dashboard</a>
            <a href="<?= base_url('pages/admin.php') ?>" class="active">Kelola Data Master</a>
            <a href="<?= base_url('auth/logout.php') ?>">Logout</a>
        </div>

        <?php if ($success): ?>
            <div class="msg msg-ok">✓ <?= h($success) ?></div>
        <?php endif; ?>

        <?php if ($errors): ?>
            <div class="msg msg-err">
                <strong>⚠ Error:</strong>
                <ul style="margin: 8px 0 0 20px; padding: 0;">
                    <?php foreach ($errors as $e): ?>
                        <li><?= h($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="grid-2">
            <!-- PIC IT SUPPORT -->
            <div class="card">
                <h2>👨‍💻 PIC IT Support</h2>

                <form method="post" class="form-inline">
                    <input type="hidden" name="action" value="add_pic">
                    <input type="text" name="pic_name" placeholder="Nama PIC IT Support baru" required>
                    <button type="submit" class="btn btn-primary">+ Tambah</button>
                </form>

                <table>
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Nama</th>
                            <th style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$pic_list): ?>
                            <tr>
                                <td colspan="3" style="text-align: center; color: #6b7280;">Belum ada data</td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1; foreach ($pic_list as $pic): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><strong><?= h($pic['name']) ?></strong></td>
                                    <td>
                                        <div class="actions">
                                            <button class="btn btn-edit" onclick="editPic(<?= $pic['id'] ?>, '<?= h($pic['name']) ?>')">Edit</button>
                                            <form method="post" style="margin: 0;" onsubmit="return confirm('Yakin hapus?')">
                                                <input type="hidden" name="action" value="delete_pic">
                                                <input type="hidden" name="pic_id" value="<?= $pic['id'] ?>">
                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- MEETING ROOMS -->
            <div class="card">
                <h2>🏢 Ruang Rapat</h2>

                <form method="post" class="form-inline">
                    <input type="hidden" name="action" value="add_room">
                    <input type="text" name="room_name" placeholder="Nama Ruang Rapat baru" required>
                    <button type="submit" class="btn btn-primary">+ Tambah</button>
                </form>

                <table>
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Nama Ruang</th>
                            <th style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$room_list): ?>
                            <tr>
                                <td colspan="3" style="text-align: center; color: #6b7280;">Belum ada data</td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1; foreach ($room_list as $room): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><strong><?= h($room['name']) ?></strong></td>
                                    <td>
                                        <div class="actions">
                                            <button class="btn btn-edit" onclick="editRoom(<?= $room['id'] ?>, '<?= h($room['name']) ?>')">Edit</button>
                                            <form method="post" style="margin: 0;" onsubmit="return confirm('Yakin hapus?')">
                                                <input type="hidden" name="action" value="delete_room">
                                                <input type="hidden" name="room_id" value="<?= $room['id'] ?>">
                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Edit PIC -->
    <div id="modalEditPic" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit PIC IT Support</h3>
                <button class="close-modal" onclick="closeModal('modalEditPic')">&times;</button>
            </div>
            <form method="post">
                <input type="hidden" name="action" value="edit_pic">
                <input type="hidden" name="pic_id" id="editPicId">
                <div class="form-group">
                    <label>Nama PIC IT Support</label>
                    <input type="text" name="pic_name" id="editPicName" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditPic')">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Room -->
    <div id="modalEditRoom" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Ruang Rapat</h3>
                <button class="close-modal" onclick="closeModal('modalEditRoom')">&times;</button>
            </div>
            <form method="post">
                <input type="hidden" name="action" value="edit_room">
                <input type="hidden" name="room_id" id="editRoomId">
                <div class="form-group">
                    <label>Nama Ruang Rapat</label>
                    <input type="text" name="room_name" id="editRoomName" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditRoom')">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editPic(id, name) {
            document.getElementById('editPicId').value = id;
            document.getElementById('editPicName').value = name;
            document.getElementById('modalEditPic').classList.add('show');
        }

        function editRoom(id, name) {
            document.getElementById('editRoomId').value = id;
            document.getElementById('editRoomName').value = name;
            document.getElementById('modalEditRoom').classList.add('show');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('show');
        }

        // Close modal ketika klik di luar modal
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('show');
            }
        }
    </script>
</body>
</html>