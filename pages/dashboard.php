<?php
/**
 * Dashboard Page
 * Path: pages/dashboard.php
 */

// Load data dari database (DYNAMIC)
$pdo = db();
$PIC_IT_OPTIONS = $pdo->query("SELECT name FROM pic_it_support WHERE is_active = 1 ORDER BY sort_order ASC, id ASC")->fetchAll(PDO::FETCH_COLUMN);
$MEETING_ROOMS = $pdo->query("SELECT name FROM meeting_rooms WHERE is_active = 1 ORDER BY sort_order ASC, id ASC")->fetchAll(PDO::FETCH_COLUMN);

$errors = [];
$success = null;
$PELAKSANAAN_OPTIONS = ["ONLINE", "OFFLINE", "HYBRID"];
$STANDBY_OPTIONS = ["STANDBY", "ON CALL"];
$TINDAK_LANJUT_OPTIONS = ["SOLVED", "UNSOLVED"];

// Handle submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_date = trim($_POST['start_date'] ?? '');
    $end_date   = trim($_POST['end_date'] ?? '');
    $pic_acara  = trim($_POST['pic_acara'] ?? '');
    $nama_acara = trim($_POST['nama_acara'] ?? '');
    $pic_it_support = $_POST['pic_it_support'] ?? [];
    $meeting_room    = trim($_POST['meeting_room'] ?? '');
    $pelaksanaan     = trim($_POST['pelaksanaan'] ?? '');
    $standby_status  = trim($_POST['standby_status'] ?? '');
    $kebutuhan_detail = trim($_POST['kebutuhan_detail'] ?? '');
    $tindak_lanjut   = trim($_POST['tindak_lanjut'] ?? '');

    // Validasi
    if ($start_date === '') $errors[] = "Start wajib diisi.";
    if ($end_date === '')   $errors[] = "End wajib diisi.";
    if ($start_date !== '' && $end_date !== '' && $end_date < $start_date) $errors[] = "End tidak boleh lebih kecil dari Start.";
    if ($pic_acara === '')  $errors[] = "PIC Acara wajib diisi.";
    if ($nama_acara === '') $errors[] = "Nama Acara wajib diisi.";
    if ($meeting_room === '') $errors[] = "Ruang Rapat wajib dipilih.";
    if ($pelaksanaan === '') $errors[] = "Pelaksanaan wajib dipilih.";
    if ($standby_status === '') $errors[] = "Standby/On Call wajib dipilih.";
    if ($tindak_lanjut === '') $errors[] = "Tindak Lanjut wajib dipilih.";

    if ($meeting_room !== '' && !in_array($meeting_room, $MEETING_ROOMS, true)) $errors[] = "Ruang Rapat tidak valid.";
    if ($pelaksanaan !== '' && !in_array($pelaksanaan, $PELAKSANAAN_OPTIONS, true)) $errors[] = "Pelaksanaan tidak valid.";
    if ($standby_status !== '' && !in_array($standby_status, $STANDBY_OPTIONS, true)) $errors[] = "Standby/On Call tidak valid.";
    if ($tindak_lanjut !== '' && !in_array($tindak_lanjut, $TINDAK_LANJUT_OPTIONS, true)) $errors[] = "Tindak Lanjut tidak valid.";

    // whitelist pilihan dropdown
    $pic_it_support = array_values(array_intersect($pic_it_support, $PIC_IT_OPTIONS));
    $pic_it_support_json = json_encode($pic_it_support, JSON_UNESCAPED_UNICODE);

    if (!$errors) {
        $transaction_id = generate_transaction_id($pdo);
        $stmt = $pdo->prepare("
          INSERT INTO schedules (
            transaction_id,
            start_date, end_date,
            pic_acara, nama_acara,
            pic_it_support,
            meeting_room, pelaksanaan, standby_status,
            kebutuhan_detail, tindak_lanjut
          )
          VALUES (
            :transaction_id,
            :start_date, :end_date,
            :pic_acara, :nama_acara,
            :pic_it_support,
            :meeting_room, :pelaksanaan, :standby_status,
            :kebutuhan_detail, :tindak_lanjut
          )
        ");

        $stmt->execute([
          ':transaction_id' => $transaction_id,
          ':start_date' => $start_date,
          ':end_date' => $end_date,
          ':pic_acara' => $pic_acara,
          ':nama_acara' => $nama_acara,
          ':pic_it_support' => $pic_it_support_json,
          ':meeting_room' => $meeting_room,
          ':pelaksanaan' => $pelaksanaan,
          ':standby_status' => $standby_status,
          ':kebutuhan_detail' => $kebutuhan_detail,
          ':tindak_lanjut' => $tindak_lanjut,
        ]);

        $success = "Data berhasil disimpan.";
        $_POST = [];

        // Reset form
        $start_date = '';
        $end_date = '';
        $pic_acara = '';
        $nama_acara = '';
        $pic_it_support = [];
        $meeting_room = '';
        $pelaksanaan = '';
        $standby_status = '';
        $kebutuhan_detail = '';
        $tindak_lanjut = '';
    }
}

// Load data
$rows = $pdo->query("SELECT * FROM schedules ORDER BY created_at DESC, id DESC")->fetchAll();

$page_title = "Dashboard";
$active_menu = "dashboard";
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($page_title) ?> - PLN UID</title>
    <style>
        <?php include __DIR__ . '/../includes/admin-styles.css'; ?>
        
        /* Additional styles for dashboard */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        
        .stat-card h3 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 10px;
            opacity: 0.9;
        }
        
        .stat-card .number {
            font-size: 32px;
            font-weight: 700;
        }
        
        .multiselect { 
            position: relative; 
            width: 100%; 
        }

        .selectBox {
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 10px 14px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
        }

        .ms-panel {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 8px;
            max-height: 220px;
            overflow-y: auto;
            z-index: 9999;
            box-shadow: 0 10px 24px rgba(0,0,0,0.12);
            min-height: 56px;
        }

        .cb-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border-radius: 6px;
            margin: 0;
        }

        .cb-item:hover { 
            background: #f3f4f6; 
        }

        .cb-item input.cb {
            width: auto !important;
            height: 16px;
            margin: 0 !important;
            flex: 0 0 auto;
        }

        .cb-text { 
            flex: 1; 
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../includes/layout.php'; ?>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>📊 Total Jadwal</h3>
            <div class="number"><?= count($rows) ?></div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <h3>📅 Bulan Ini</h3>
            <div class="number">
                <?php
                $thisMonth = date('Y-m');
                $monthCount = 0;
                foreach ($rows as $r) {
                    if (substr($r['created_at'], 0, 7) === $thisMonth) {
                        $monthCount++;
                    }
                }
                echo $monthCount;
                ?>
            </div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <h3>✅ Solved</h3>
            <div class="number">
                <?php
                $solved = 0;
                foreach ($rows as $r) {
                    if ($r['tindak_lanjut'] === 'SOLVED') {
                        $solved++;
                    }
                }
                echo $solved;
                ?>
            </div>
        </div>
    </div>

    <!-- Entry Form -->
    <div class="card">
        <div class="card-header">
            <h2>📝 Entry Jadwal Kegiatan</h2>
            <p>Tambah jadwal kegiatan baru</p>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= h($success) ?></div>
        <?php endif; ?>

        <?php if ($errors): ?>
            <div class="alert alert-error">
                <strong>Perbaiki input berikut:</strong>
                <ul><?php foreach ($errors as $e): ?><li><?= h($e) ?></li><?php endforeach; ?></ul>
            </div>
        <?php endif; ?>

        <div style="padding: 25px;">
            <form method="post">
                <div class="grid-2" style="margin-bottom: 20px;">
                    <div class="form-group">
                        <label>Start (tanggal mulai)</label>
                        <input type="date" name="start_date" value="<?= h($_POST['start_date'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label>End (tanggal selesai)</label>
                        <input type="date" name="end_date" value="<?= h($_POST['end_date'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="grid-2" style="margin-bottom: 20px;">
                    <div class="form-group">
                        <label>PIC Acara (nama pemesan ruangan)</label>
                        <input type="text" name="pic_acara" value="<?= h($_POST['pic_acara'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Acara</label>
                        <input type="text" name="nama_acara" value="<?= h($_POST['nama_acara'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label>PIC IT Support</label>
                    <?php $selected = $_POST['pic_it_support'] ?? []; ?>
                    <div class="multiselect" id="picItDropdown">
                        <div class="selectBox" onclick="togglePicIt()">
                            <span id="selectedText">Pilih PIC IT Support</span>
                            <span class="arrow">▾</span>
                        </div>
                        <div class="ms-panel" id="picItPanel">
                            <?php foreach ($PIC_IT_OPTIONS as $opt): ?>
                                <label class="cb-item">
                                    <input class="cb" type="checkbox" name="pic_it_support[]" value="<?= h($opt) ?>" <?php if (in_array($opt, $selected, true)) echo 'checked'; ?>>
                                    <span class="cb-text"><?= h($opt) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="grid-2" style="margin-bottom: 20px;">
                    <div class="form-group">
                        <label>Ruang Rapat</label>
                        <select name="meeting_room" required>
                            <option value="">-- Pilih Ruang Rapat --</option>
                            <?php foreach ($MEETING_ROOMS as $room): ?>
                                <option value="<?= h($room) ?>" <?= (($meeting_room ?? '') === $room) ? 'selected' : '' ?>><?= h($room) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Pelaksanaan Acara</label>
                        <select name="pelaksanaan" required>
                            <option value="">-- Pilih --</option>
                            <?php foreach ($PELAKSANAAN_OPTIONS as $p): ?>
                                <option value="<?= h($p) ?>" <?= (($pelaksanaan ?? '') === $p) ? 'selected' : '' ?>><?= h($p) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="grid-2" style="margin-bottom: 20px;">
                    <div class="form-group">
                        <label>STANDBY / ON CALL</label>
                        <select name="standby_status" required>
                            <option value="">-- Pilih --</option>
                            <?php foreach ($STANDBY_OPTIONS as $s): ?>
                                <option value="<?= h($s) ?>" <?= (($standby_status ?? '') === $s) ? 'selected' : '' ?>><?= h($s) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tindak Lanjut</label>
                        <select name="tindak_lanjut" required>
                            <option value="">-- Pilih --</option>
                            <?php foreach ($TINDAK_LANJUT_OPTIONS as $t): ?>
                                <option value="<?= h($t) ?>" <?= (($tindak_lanjut ?? '') === $t) ? 'selected' : '' ?>><?= h($t) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label>Kebutuhan Detail</label>
                    <input type="text" name="kebutuhan_detail" value="<?= h($kebutuhan_detail ?? '') ?>" placeholder="Contoh: mic wireless, projector, zoom link, kabel HDMI, dll">
                </div>

                <div style="display: flex; gap: 10px;">
                    <button class="btn btn-primary" type="submit">💾 Simpan Jadwal</button>
                    <a class="btn btn-secondary" href="<?= base_url('pages/export.php') ?>">📥 Export CSV</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card">
        <div class="card-header">
            <h2>📋 Data Jadwal</h2>
            <p>Total: <strong><?= count($rows) ?></strong> jadwal</p>
        </div>

        <?php if (!$rows): ?>
            <p style="text-align: center; padding: 40px; color: #94a3b8;">Belum ada data jadwal</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>ID Transaksi</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>PIC Acara</th>
                            <th>Nama Acara</th>
                            <th>PIC IT Support</th>
                            <th>Ruang</th>
                            <th>Pelaksanaan</th>
                            <th>Standby</th>
                            <th>Kebutuhan</th>
                            <th>Tindak Lanjut</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($rows as $r): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= h($r['transaction_id']) ?></strong></td>
                                <td><?= h($r['start_date']) ?></td>
                                <td><?= h($r['end_date']) ?></td>
                                <td><?= h($r['pic_acara']) ?></td>
                                <td><?= h($r['nama_acara']) ?></td>
                                <td><?= h(it_support_to_text($r['pic_it_support'])) ?></td>
                                <td><?= h($r['meeting_room']) ?></td>
                                <td><?= h($r['pelaksanaan']) ?></td>
                                <td><?= h($r['standby_status']) ?></td>
                                <td><?= h($r['kebutuhan_detail']) ?></td>
                                <td>
                                    <span class="badge badge-<?= $r['tindak_lanjut'] === 'SOLVED' ? 'success' : 'warning' ?>">
                                        <?= h($r['tindak_lanjut']) ?>
                                    </span>
                                </td>
                                <td><?= h($r['created_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <script>
        let picItOpen = false;

        function togglePicIt() {
            const panel = document.getElementById("picItPanel");
            picItOpen = !picItOpen;
            panel.style.display = picItOpen ? "block" : "none";
        }

        function updateSelectedText() {
            const checked = document.querySelectorAll('#picItPanel input.cb:checked');
            const values = Array.from(checked).map(cb => cb.value);
            document.getElementById("selectedText").innerText = values.length ? values.join(", ") : "Pilih PIC IT Support";
        }

        document.addEventListener("click", function(e) {
            const wrap = document.getElementById("picItDropdown");
            const panel = document.getElementById("picItPanel");
            if (wrap && panel && !wrap.contains(e.target)) {
                panel.style.display = "none";
                picItOpen = false;
            }
        });

        document.querySelectorAll('#picItPanel input.cb').forEach(cb => cb.addEventListener('change', updateSelectedText));
        updateSelectedText();
    </script>
</body>
</html>