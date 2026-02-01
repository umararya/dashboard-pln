<?php
// pages/master-user-v2.content.php
?>

<style>
/* Existing styles from master-user.content.php */
.card { background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 0; margin-bottom: 25px; }
.card-header { padding: 20px 25px; border-bottom: 1px solid #e5e7eb; }
.card-header h2 { font-size: 22px; font-weight: 700; margin-bottom: 5px; color: #1e293b; }
.card-header p { color: #64748b; font-size: 14px; margin: 0; }
.alert { padding: 12px 20px; border-radius: 8px; margin: 20px 25px; font-size: 14px; }
.alert-success { background: #ecfdf5; border: 1px solid #10b981; color: #065f46; }
.alert-error { background: #fef2f2; border: 1px solid #ef4444; color: #991b1b; }
.form-section { padding: 20px 25px; background: #f8fafc; border-bottom: 1px solid #e5e7eb; }
.form-section h3 { font-size: 16px; font-weight: 700; margin-bottom: 15px; color: #1e293b; }
.form-inline { display: flex; gap: 10px; flex-wrap: wrap; }
.form-inline input, .form-inline select { padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; outline: none; }
.form-inline input[type="text"], .form-inline input[type="password"] { min-width: 180px; flex: 1; }
.table-responsive { padding: 0; overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; font-size: 14px; }
.data-table thead th { background: #f8fafc; padding: 14px 20px; text-align: left; font-weight: 700; color: #475569; border-bottom: 2px solid #e5e7eb; }
.data-table tbody td { padding: 14px 20px; border-bottom: 1px solid #e5e7eb; }
.data-table tbody tr:hover { background: #f8fafc; }
.text-center { text-align: center; color: #94a3b8; }
.badge { display: inline-block; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 700; text-transform: uppercase; }
.badge-primary { background: #dbeafe; color: #1e40af; }
.badge-danger { background: #fee2e2; color: #991b1b; }
.toggle-btn { padding: 6px 12px; border: none; border-radius: 20px; font-size: 12px; font-weight: 700; cursor: pointer; }
.toggle-btn.active { background: #10b981; color: white; }
.toggle-btn.inactive { background: #ef4444; color: white; }
.btn-group { display: flex; gap: 6px; }
.btn { padding: 8px 16px; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; }
.btn-sm { padding: 6px 12px; font-size: 13px; }
.btn-primary { background: #3b82f6; color: white; }
.btn-edit { background: #10b981; color: white; }
.btn-danger { background: #ef4444; color: white; }
.btn-secondary { background: #e5e7eb; color: #374151; }
.password-cell { font-family: 'Courier New', monospace; background: #f3f4f6; padding: 4px 8px; border-radius: 4px; font-weight: 600; color: #1e40af; }
.modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 2000; align-items: center; justify-content: center; }
.modal.show { display: flex; }
.modal-content { background: white; border-radius: 12px; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto; }
.modal-header { display: flex; justify-content: space-between; align-items: center; padding: 20px 25px; border-bottom: 1px solid #e5e7eb; }
.close-modal { background: none; border: none; font-size: 28px; cursor: pointer; color: #94a3b8; }
.form-group { padding: 0 25px; margin: 20px 0; }
.form-group label { display: block; font-weight: 600; margin-bottom: 8px; color: #374151; font-size: 14px; }
.form-group input, .form-group select { width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; }
.modal-footer { display: flex; gap: 10px; justify-content: flex-end; padding: 20px 25px; border-top: 1px solid #e5e7eb; }

/* NEW: Permission Checkboxes Styles */
.permission-section { padding: 0 25px; margin: 20px 0; }
.permission-section h4 { font-size: 15px; font-weight: 700; color: #1e293b; margin-bottom: 12px; }
.permission-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 12px; }
.permission-item { background: #f8fafc; border: 2px solid #e5e7eb; border-radius: 10px; padding: 14px; transition: all 0.2s; cursor: pointer; }
.permission-item:hover { background: #f1f5f9; border-color: #cbd5e1; }
.permission-item.selected { background: #dbeafe; border-color: #3b82f6; }
.permission-checkbox { display: flex; align-items: start; gap: 12px; }
.permission-checkbox input[type="checkbox"] { width: 20px; height: 20px; margin-top: 2px; cursor: pointer; flex-shrink: 0; }
.permission-info { flex: 1; }
.permission-title { font-weight: 700; color: #1e293b; font-size: 14px; margin-bottom: 4px; }
.permission-desc { font-size: 12px; color: #64748b; }
.permission-badge { display: inline-block; padding: 2px 6px; background: #dbeafe; color: #1e40af; border-radius: 4px; font-size: 11px; font-weight: 600; margin-left: 4px; }
</style>

<div class="card">
    <div class="card-header">
        <h2>👥 Master User Management</h2>
        <p>Kelola akses user dan permissions</p>
    </div>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success">✓ <?= h($success) ?></div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <strong>⚠ Error:</strong>
            <ul style="margin: 8px 0 0 20px;">
                <?php foreach ($errors as $e): ?>
                    <li><?= h($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="form-section">
        <h3>➕ Tambah User Baru</h3>
        <form method="post" id="addUserForm">
            <input type="hidden" name="action" value="add_user">
            
            <div class="form-inline">
                <input type="text" name="username" placeholder="Username" required>
                <input type="text" name="password" placeholder="Password" required>
                <select name="role" required id="addUserRole">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                <input type="text" name="bagian" placeholder="Bagian/Divisi">
            </div>
            
            <!-- Permission Checkboxes (show only for user role) -->
            <div id="addPermissionsSection" style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #e5e7eb; display: none;">
                <h4 style="font-size: 14px; font-weight: 700; margin-bottom: 10px; color: #1e293b;">🔐 Pilih Akses Page:</h4>
                <div class="permission-grid">
                    <?php foreach ($available_pages as $slug => $page): ?>
                        <label class="permission-item" onclick="togglePermissionItem(this)">
                            <div class="permission-checkbox">
                                <input type="checkbox" name="permissions[]" value="<?= h($slug) ?>" onclick="event.stopPropagation()">
                                <div class="permission-info">
                                    <div class="permission-title"><?= $page['icon'] ?> <?= h($page['name']) ?></div>
                                    <div class="permission-desc"><?= h($page['description']) ?></div>
                                </div>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div style="margin-top: 12px;">
                <button type="submit" class="btn btn-primary">Tambah User</button>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Role</th>
                    <th>Bagian</th>
                    <th>Permissions</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr><td colspan="8" class="text-center">Belum ada user</td></tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($users as $u): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><strong><?= h($u['username']) ?></strong></td>
                            <td><span class="password-cell"><?= h($u['plain_password'] ?? '******') ?></span></td>
                            <td><span class="badge badge-<?= ($u['role'] === 'admin') ? 'danger' : 'primary' ?>"><?= h(strtoupper($u['role'])) ?></span></td>
                            <td><?= h($u['bagian'] ?? '-') ?></td>
                            <td>
                                <?php if ($u['role'] === 'admin'): ?>
                                    <span style="color: #64748b; font-size: 13px;">Full Access</span>
                                <?php elseif (empty($u['permissions'])): ?>
                                    <span style="color: #94a3b8; font-size: 13px;">No Access</span>
                                <?php else: ?>
                                    <?php foreach ($u['permissions'] as $perm): ?>
                                        <?php $page = $available_pages[$perm] ?? null; ?>
                                        <?php if ($page): ?>
                                            <span class="permission-badge"><?= $page['icon'] ?> <?= h($page['name']) ?></span>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="action" value="toggle_active">
                                    <input type="hidden" name="user_id" value="<?= (int)$u['id'] ?>">
                                    <input type="hidden" name="current_status" value="<?= (int)$u['is_active'] ?>">
                                    <button type="submit" class="toggle-btn <?= ((int)$u['is_active'] === 1) ? 'active' : 'inactive' ?>">
                                        <?= ((int)$u['is_active'] === 1) ? '🟢 ON' : '🔴 OFF' ?>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-edit" onclick='editUser(<?= json_encode($u) ?>)'>Edit</button>

                                    <?php if ((int)$u['id'] !== (int)($_SESSION['user_id'] ?? 0)): ?>
                                        <form method="post" style="display: inline;" onsubmit="return confirm('Yakin hapus user ini?')">
                                            <input type="hidden" name="action" value="delete_user">
                                            <input type="hidden" name="user_id" value="<?= (int)$u['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Edit User -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>✏️ Edit User</h3>
            <button type="button" class="close-modal" onclick="closeEditModal()">&times;</button>
        </div>
        <form method="post" id="editUserForm">
            <input type="hidden" name="action" value="edit_user">
            <input type="hidden" name="user_id" id="editUserId">

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" id="editUsername" required>
            </div>

            <div class="form-group">
                <label>Password (kosongkan jika tidak diubah)</label>
                <input type="text" name="password" id="editPassword" placeholder="Isi jika ingin ubah password">
            </div>

            <div class="form-group">
                <label>Role</label>
                <select name="role" id="editRole" required onchange="toggleEditPermissions()">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="form-group">
                <label>Bagian/Divisi</label>
                <input type="text" name="bagian" id="editBagian">
            </div>

            <!-- Edit Permissions -->
            <div id="editPermissionsSection" class="permission-section">
                <h4>🔐 Pilih Akses Page:</h4>
                <div class="permission-grid">
                    <?php foreach ($available_pages as $slug => $page): ?>
                        <label class="permission-item" onclick="togglePermissionItem(this)">
                            <div class="permission-checkbox">
                                <input type="checkbox" name="permissions[]" value="<?= h($slug) ?>" class="edit-permission-checkbox" onclick="event.stopPropagation()">
                                <div class="permission-info">
                                    <div class="permission-title"><?= $page['icon'] ?> <?= h($page['name']) ?></div>
                                    <div class="permission-desc"><?= h($page['description']) ?></div>
                                </div>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
// Toggle add user permissions visibility based on role
document.getElementById('addUserRole').addEventListener('change', function() {
    const section = document.getElementById('addPermissionsSection');
    section.style.display = this.value === 'user' ? 'block' : 'none';
});

// Toggle permission item selection
function togglePermissionItem(label) {
    const checkbox = label.querySelector('input[type="checkbox"]');
    checkbox.checked = !checkbox.checked;
    
    if (checkbox.checked) {
        label.classList.add('selected');
    } else {
        label.classList.remove('selected');
    }
}

// Edit user function
function editUser(userData) {
    document.getElementById('editUserId').value = userData.id;
    document.getElementById('editUsername').value = userData.username;
    document.getElementById('editPassword').value = '';
    document.getElementById('editPassword').placeholder = 'Password saat ini: ' + (userData.plain_password || '******');
    document.getElementById('editRole').value = userData.role;
    document.getElementById('editBagian').value = userData.bagian || '';
    
    // Clear all checkboxes first
    document.querySelectorAll('.edit-permission-checkbox').forEach(cb => {
        cb.checked = false;
        cb.closest('.permission-item').classList.remove('selected');
    });
    
    // Check user's permissions
    if (userData.permissions && Array.isArray(userData.permissions)) {
        userData.permissions.forEach(perm => {
            const checkbox = document.querySelector('.edit-permission-checkbox[value="' + perm + '"]');
            if (checkbox) {
                checkbox.checked = true;
                checkbox.closest('.permission-item').classList.add('selected');
            }
        });
    }
    
    toggleEditPermissions();
    document.getElementById('editModal').classList.add('show');
}

// Toggle edit permissions visibility
function toggleEditPermissions() {
    const role = document.getElementById('editRole').value;
    const section = document.getElementById('editPermissionsSection');
    section.style.display = role === 'user' ? 'block' : 'none';
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