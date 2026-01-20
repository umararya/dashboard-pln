<?php
/**
 * Master IT Support Management
 * Path: pages/master-it-support.php
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

    // ADD PIC
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

    // DELETE PIC
    if ($action === 'delete_pic') {
        $id = (int)($_POST['pic_id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare("DELETE FROM pic_it_support WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $success = 'PIC IT Support berhasil dihapus.';
        }
    }

    // EDIT PIC
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
}

// Load data
$pic_list = $pdo->query("SELECT * FROM pic_it_support ORDER BY sort_order ASC, id ASC")->fetchAll();

$page_title = "Master IT Support";
$active_menu = "master-it-support";
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
            <h2>👨‍💻 Master PIC IT Support</h2>
            <p>Kelola daftar penanggung jawab IT Support</p>
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
            <h3>➕ Tambah PIC IT Support Baru</h3>
            <form method="post" class="form-inline">
                <input type="hidden" name="action" value="add_pic">
                <input type="text" name="pic_name" placeholder="Nama PIC IT Support" required style="flex: 1; min-width: 300px;">
                <button type="submit" class="btn btn-primary">Tambah PIC</button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 70px;">No</th>
                        <th>Nama PIC IT Support</th>
                        <th style="width: 200px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$pic_list): ?>
                        <tr>
                            <td colspan="3" class="text-center">Belum ada data PIC IT Support</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($pic_list as $pic): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= h($pic['name']) ?></strong></td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-edit" onclick="editPic(<?= $pic['id'] ?>, '<?= h($pic['name']) ?>')">
                                            Edit
                                        </button>
                                        <form method="post" style="display: inline;" onsubmit="return confirm('Yakin hapus PIC ini?')">
                                            <input type="hidden" name="action" value="delete_pic">
                                            <input type="hidden" name="pic_id" value="<?= $pic['id'] ?>">
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

    <!-- Modal Edit PIC -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>✏️ Edit PIC IT Support</h3>
                <button class="close-modal" onclick="closeEditModal()">&times;</button>
            </div>
            <form method="post">
                <input type="hidden" name="action" value="edit_pic">
                <input type="hidden" name="pic_id" id="editPicId">
                
                <div class="form-group">
                    <label>Nama PIC IT Support</label>
                    <input type="text" name="pic_name" id="editPicName" required>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editPic(id, name) {
            document.getElementById('editPicId').value = id;
            document.getElementById('editPicName').value = name;
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