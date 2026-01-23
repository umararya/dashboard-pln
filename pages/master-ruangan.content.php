<?php
// pages/master-ruangan.content.php
?>

<style>
/* Kalau CSS global sudah cover card/table/modal, kamu bisa hapus block style ini.
   Tapi biar pasti langsung rapi, saya kasih minimal styling yang konsisten. */

.card{
  background:#fff;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,.08);
  margin-bottom:24px;overflow:hidden
}
.card-header{padding:20px 24px;border-bottom:1px solid #e5e7eb}
.card-header h2{margin:0 0 6px 0;font-size:22px;font-weight:800;color:#0f172a}
.card-header p{margin:0;color:#64748b;font-size:14px}

.alert{padding:12px 18px;border-radius:10px;margin:16px 24px;font-size:14px}
.alert-success{background:#ecfdf5;border:1px solid #10b981;color:#065f46}
.alert-error{background:#fef2f2;border:1px solid #ef4444;color:#991b1b}

.form-section{padding:18px 24px;background:#f8fafc;border-bottom:1px solid #e5e7eb}
.form-section h3{margin:0 0 12px 0;font-size:16px;font-weight:800;color:#0f172a}
.form-inline{display:flex;gap:10px;flex-wrap:wrap;align-items:center}
.form-inline input{
  padding:10px 12px;border:1px solid #d1d5db;border-radius:10px;font-size:14px;min-width:260px;flex:1
}

.table-responsive{overflow-x:auto}
.data-table{width:100%;border-collapse:collapse;font-size:14px}
.data-table thead th{
  background:#f8fafc;padding:14px 18px;text-align:left;font-weight:800;color:#475569;border-bottom:2px solid #e5e7eb
}
.data-table tbody td{padding:12px 18px;border-bottom:1px solid #e5e7eb}
.data-table tbody tr:hover{background:#f8fafc}

.text-center{text-align:center;color:#94a3b8}

.btn{border:none;border-radius:10px;padding:10px 14px;font-weight:700;cursor:pointer}
.btn-primary{background:#3b82f6;color:#fff}
.btn-secondary{background:#e5e7eb;color:#111827}
.btn-danger{background:#ef4444;color:#fff}
.btn-edit{background:#10b981;color:#fff}
.btn-sm{padding:7px 10px;font-size:13px}

.btn-group{display:flex;gap:8px;align-items:center}

.modal{display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:2000;align-items:center;justify-content:center}
.modal.show{display:flex}
.modal-content{background:#fff;border-radius:14px;width:92%;max-width:520px;overflow:hidden}
.modal-header{padding:18px 22px;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center}
.close-modal{background:none;border:none;font-size:28px;cursor:pointer;color:#94a3b8}
.form-group{padding:0 22px;margin:16px 0}
.form-group label{display:block;font-weight:700;margin-bottom:8px;color:#374151;font-size:14px}
.form-group input{width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:10px;font-size:14px}
.modal-footer{padding:16px 22px;border-top:1px solid #e5e7eb;display:flex;justify-content:flex-end;gap:10px}
</style>

<div class="card">
  <div class="card-header">
    <h2>🏢 Master Ruang Rapat</h2>
    <p>Kelola daftar ruang rapat yang tersedia</p>
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
    <h3>➕ Tambah Ruang Rapat Baru</h3>
    <form method="post" class="form-inline">
      <input type="hidden" name="action" value="add_room">
      <input type="text" name="room_name" placeholder="Nama Ruang Rapat" required>
      <button type="submit" class="btn btn-primary">Tambah Ruangan</button>
    </form>
  </div>

  <div class="table-responsive">
    <table class="data-table">
      <thead>
        <tr>
          <th style="width:70px;">No</th>
          <th>Nama Ruang Rapat</th>
          <th style="width:220px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($room_list)): ?>
          <tr><td colspan="3" class="text-center">Belum ada data ruang rapat</td></tr>
        <?php else: ?>
          <?php $no = 1; foreach ($room_list as $room): ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><strong><?= h($room['name']) ?></strong></td>
              <td>
                <div class="btn-group">
                  <button
                    type="button"
                    class="btn btn-sm btn-edit"
                    onclick='editRoom(<?= (int)$room["id"] ?>, <?= json_encode($room["name"]) ?>)'
                  >
                    Edit
                  </button>

                  <form method="post" style="display:inline;" onsubmit="return confirm('Yakin hapus ruangan ini?')">
                    <input type="hidden" name="action" value="delete_room">
                    <input type="hidden" name="room_id" value="<?= (int)$room['id'] ?>">
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

<!-- Modal Edit -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h3 style="margin:0;font-size:18px;font-weight:800;">✏️ Edit Ruang Rapat</h3>
      <button type="button" class="close-modal" onclick="closeEditModal()">&times;</button>
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

document.addEventListener('click', function(e) {
  if (e.target && e.target.classList && e.target.classList.contains('modal')) {
    e.target.classList.remove('show');
  }
});
</script>
