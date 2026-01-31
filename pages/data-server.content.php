<?php
// pages/data-server.content.php
?>

<style>
.table-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    flex-wrap: wrap;
    gap: 10px;
}

.btn-group-top {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.btn-toggle-view {
    padding: 8px 16px;
    background: #3b82f6;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
}

.btn-toggle-view:hover {
    background: #2563eb;
}

.btn-add-data {
    padding: 8px 16px;
    background: #10b981;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
}

.btn-add-data:hover {
    background: #059669;
}

.table-wrapper {
    overflow-x: auto;
    border-radius: 10px;
    border: 1px solid #e5e7eb;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}

.data-table thead th {
    background: #f8fafc;
    padding: 12px 14px;
    text-align: left;
    font-weight: 700;
    color: #475569;
    border-bottom: 2px solid #e5e7eb;
    white-space: nowrap;
}

.data-table tbody td {
    padding: 12px 14px;
    border-bottom: 1px solid #e5e7eb;
    vertical-align: top;
}

.data-table tbody tr:hover {
    background: #f8fafc;
}

/* Hide/Show columns based on view mode */
.data-table.compact .extended-col {
    display: none;
}

.data-table.full .extended-col {
    display: table-cell;
}

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    margin-top: 20px;
    flex-wrap: wrap;
}

.pagination a,
.pagination span {
    padding: 8px 14px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    text-decoration: none;
    color: #374151;
    font-size: 13px;
    font-weight: 600;
}

.pagination a:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
}

.pagination .active {
    background: #3b82f6;
    color: white;
    border-color: #3b82f6;
}

.pagination .disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>

<div class="card">
    <div class="card-header">
        <h2>🖥️ Daftar Server</h2>
        <p>Total: <strong><?= $total_count ?></strong> server | Halaman <?= $page ?> dari <?= max(1, $total_pages) ?></p>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success" style="margin: 15px 25px 0;">
            ✅ <?= h($success) ?>
        </div>
    <?php endif; ?>

    <div style="padding: 25px;">
        <div class="table-controls">
            <div>
                <button type="button" class="btn-toggle-view" onclick="toggleTableView()">
                    <span id="toggleText">📖 Luaskan Tabel</span>
                </button>
            </div>
            
            <div class="btn-group-top">
                <a href="<?= base_url('pages/data-server-input.php') ?>" class="btn-add-data">
                    ➕ Input Data
                </a>
            </div>
        </div>

        <?php if (empty($servers)): ?>
            <p style="text-align: center; padding: 60px 20px; color: #94a3b8; font-size: 15px;">
                📦 Belum ada data server.<br>
                <span style="font-size: 13px;">Klik tombol <strong>"Input Data"</strong> untuk menambahkan server baru.</span>
            </p>
        <?php else: ?>
            <div class="table-wrapper">
                <table class="data-table compact" id="serverTable">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>IND</th>
                            <th>Fungsi Server</th>
                            <th>IP Address</th>
                            <th>Detail</th>
                            
                            <!-- Extended columns (hidden by default) -->
                            <th class="extended-col">Merk</th>
                            <th class="extended-col">Type</th>
                            <th class="extended-col">OS</th>
                            <th class="extended-col">Processor</th>
                            <th class="extended-col">RAM</th>
                            <th class="extended-col">Storage</th>
                            <th class="extended-col">Server Fisik</th>
                            
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = $offset + 1; 
                        foreach ($servers as $server): 
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= h($server['ind']) ?></strong></td>
                                <td><?= h($server['fungsi_server']) ?></td>
                                <td><code style="background: #f3f4f6; padding: 2px 6px; border-radius: 4px; font-size: 12px;"><?= h($server['ip']) ?></code></td>
                                <td><?= h(substr($server['detail'], 0, 50)) ?><?= strlen($server['detail']) > 50 ? '...' : '' ?></td>
                                
                                <!-- Extended columns -->
                                <td class="extended-col"><?= h($server['merk'] ?: '-') ?></td>
                                <td class="extended-col"><?= h($server['type'] ?: '-') ?></td>
                                <td class="extended-col"><?= h($server['system_operasi'] ?: '-') ?></td>
                                <td class="extended-col">
                                    <?php if ($server['processor_merk']): ?>
                                        <?= h($server['processor_merk']) ?> <?= h($server['processor_type']) ?>
                                        <br><small><?= h($server['processor_kecepatan']) ?> GHz, <?= h($server['processor_core']) ?> cores</small>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td class="extended-col">
                                    <?php if ($server['ram_jenis']): ?>
                                        <?= h($server['ram_jenis']) ?> <?= h($server['ram_kapasitas']) ?>
                                        <br><small><?= h($server['ram_jumlah_keping']) ?> keping</small>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td class="extended-col">
                                    <?php if ($server['storage_jenis']): ?>
                                        <?= h($server['storage_jenis']) ?> <?= h($server['storage_kapasitas_total']) ?>
                                        <br><small><?= h($server['storage_jumlah']) ?> unit</small>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td class="extended-col"><?= h($server['server_fisik'] ?: '-') ?></td>
                                
                                <td>
                                    <div class="btn-group">
                                        <a 
                                            href="<?= base_url('pages/data-server-detail.php?id=' . $server['id']) ?>" 
                                            class="btn btn-sm" 
                                            style="background: #10b981; color: white; padding: 6px 10px; font-size: 12px; text-decoration: none; display: inline-block; border-radius: 6px;"
                                        >
                                            👁️ Detail
                                        </a>
                                        
                                        <?php if (is_admin()): ?>
                                            <a 
                                                href="<?= base_url('pages/data-server-edit.php?id=' . $server['id']) ?>" 
                                                class="btn btn-sm btn-edit"
                                            >
                                                ✏️ Edit
                                            </a>
                                            
                                            <form method="post" style="display: inline; margin: 0;" onsubmit="return confirm('Yakin hapus server ini?\n\nSemua history pemeliharaan juga akan terhapus.')">
                                                <input type="hidden" name="action" value="delete_server">
                                                <input type="hidden" name="server_id" value="<?= $server['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-danger">🗑️ Hapus</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=1">«« First</a>
                        <a href="?page=<?= $page - 1 ?>">‹ Prev</a>
                    <?php endif; ?>

                    <?php
                    $start = max(1, $page - 2);
                    $end = min($total_pages, $page + 2);
                    
                    for ($i = $start; $i <= $end; $i++):
                    ?>
                        <?php if ($i == $page): ?>
                            <span class="active"><?= $i ?></span>
                        <?php else: ?>
                            <a href="?page=<?= $i ?>"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?= $page + 1 ?>">Next ›</a>
                        <a href="?page=<?= $total_pages ?>">Last »»</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<script>
// Toggle Table View
let isExpanded = false;
function toggleTableView() {
    const table = document.getElementById('serverTable');
    const toggleText = document.getElementById('toggleText');
    
    isExpanded = !isExpanded;
    
    if (isExpanded) {
        table.classList.remove('compact');
        table.classList.add('full');
        toggleText.textContent = '📕 Ringkaskan Tabel';
    } else {
        table.classList.remove('full');
        table.classList.add('compact');
        toggleText.textContent = '📖 Luaskan Tabel';
    }
}
</script>