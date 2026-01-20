<?php
/**
 * Master Ruangan Management
 * Path: pages/master-ruangan.php
 */

session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();

$pdo = db();
$success = '';
$errors = [];

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // ADD ROOM
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

    // DELETE ROOM
    if ($action === 'delete_room') {
        $id = (int)($_POST['room_id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare("DELETE FROM meeting_rooms WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $success = 'Ruang Rapat berhasil dihapus.';
        }
    }

    // EDIT ROOM
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
$room_list = $pdo->query("SELECT * FROM meeting_rooms ORDER BY sort_order ASC, id ASC")->fetchAll();

$page_title = "Master Ruangan";
$active_menu = "master-ruangan";
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($page_title) ?> - PLN UID</title>
    <style>
        <?php include __DIR__ . '/../includes/admin-styles.css'; ?>
    </style>
</head>
<body>
    <?php 
    $content_start = true;
    include __DIR__ . '/../includes/layout.php'; 
    ?>

    <!-- Content Start -->
    <div class="card">
        <div class="card-header">
            <h2>🏢 Master Ruang Rapat</h2>
            <p>Kelola daftar ruang rapat yang tersedia</p>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= h($success) ?></div>
        <?php endif; ?>

        <?php if ($errors): ?>
            <div class="alert alert-error">
                <strong>Error:</strong>
                <ul><?php foreach ($errors as $e): ?><li><?= h($e) ?></li><?php endforeach; ?></ul>
            </div>
        <?php endif; ?>

        <div class="form-section">
            <h3>➕ Tambah Ruang Rapat Baru</h3>
            <form method="post" class="form-inline">
                <input type="hidden" name="action" value="add_room">
                <input type="text" name="room_name" placeholder="Nama Ruang Rapat" required style="flex: 1; min-width: 300px;">
                <button type="submit" class="btn btn-primary">Tambah Ruangan</button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 70px;">No</th>
                        <th>Nama Ruang Rapat</th>
                        <th style="width: 200px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$room_list): ?>
                        <tr>
                            <td colspan="3" class="text-center">Belum ada data ruang rapat</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($room_list as $room): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= h($room['name']) ?></strong></td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-edit" onclick="editRoom(<?= $room['id'] ?>, '<?= h($room['name']) ?>')">
                                            Edit
                                        </button>
                                        <form method="post" style="display: inline;" onsubmit="return confirm('Yakin hapus ruangan ini?')">
                                            <input type="hidden" name="action" value="delete_room">
                                            <input type="hidden" name="room_id" value="<?= $room['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
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

    <!-- Modal Edit Room -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>✏️ Edit Ruang Rapat</h3>
                <button class="close-modal" onclick="closeEditModal()">&times;</button>
            </div>
            <form method="post">
                <input type="hidden" name="action" value="edit_room">
                <input type="hidden" name="room_id" id="editRoomId">
                
                <div class="form-group">
                    <label>Nama Ruang Rapat</label>
                    <input type="text" name="room_name" id="editRoomName" required>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editRoom(id, name) {
            document.getElementById('editRoomId').value = id;
            document.getElementById('editRoomName').value = name;
            document.getElementById('editModal').classList.add('show');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.remove('show');
        }

        window.onclick = function(e) {
            if (e.target.classList.contains('modal')) {
                e.target.classList.remove('show');
            }
        }
    </script>
</body>
</html>