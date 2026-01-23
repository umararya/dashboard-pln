<?php
// pages/master-it-support.content.php
?>

<div class="card">
    <div class="card-header">
        <h2>👨‍💻 Master PIC IT Support</h2>
        <p>Kelola daftar penanggung jawab IT Support</p>
    </div>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success">✓ <?= h($success) ?></div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <strong>⚠ Error:</strong>
            <ul style="margin:8px 0 0 18px;">
                <?php foreach ($errors as $e): ?>
                    <li><?= h($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="form-section">
        <h3>➕ Tambah PIC IT Support Baru</h3>
        <form method="post" class="form-inline">
            <input type="hidden" name="action" value="add_pic">
            <input type="text" name="pic_name" placeholder="Nama PIC IT Support" required style="flex:1;min-width:300px;">
            <button type="submit" class="btn btn-primary">Tambah PIC</button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:70px;">No</th>
                    <th>Nama PIC IT Support</th>
                    <th style="width:220px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($pic_list)): ?>
                    <tr><td colspan="3" class="text-center">Belum ada data PIC IT Support</td></tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($pic_list as $pic): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><strong><?= h($pic['name']) ?></strong></td>
                            <td>
                                <div class="btn-group">
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-edit"
                                        onclick='editPic(<?= (int)$pic["id"] ?>, <?= json_encode($pic["name"]) ?>)'
                                    >
                                        Edit
                                    </button>

                                    <form method="post" style="display:inline;" onsubmit="return confirm('Yakin hapus PIC ini?')">
                                        <input type="hidden" name="action" value="delete_pic">
                                        <input type="hidden" name="pic_id" value="<?= (int)$pic['id'] ?>">
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
            <button type="button" class="close-modal" onclick="closeEditModal()">&times;</button>
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
document.addEventListener('click', function(e) {
    if (e.target && e.target.classList && e.target.classList.contains('modal')) {
        e.target.classList.remove('show');
    }
});
</script>
