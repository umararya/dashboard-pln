<?php
/**
 * Dashboard Page
 * Path: pages/dashboard.php
 */

if (!defined('INCLUDED_FROM_INDEX')) {
    session_start();
    require_once __DIR__ . '/../config/db.php';
    require_once __DIR__ . '/../includes/functions.php';
    require_login();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
$rows = $pdo->query("SELECT * FROM schedules ORDER BY created_at ASC, id ASC")->fetchAll();

$page_title = "Jadwal Kegiatan PLN UID JATENG DIY";
require __DIR__ . '/../includes/header.php';
?>

<div class="card">
    <h2>Entry Jadwal</h2>

    <?php if ($success): ?>
      <div class="msg msg-ok"><?= h($success) ?></div>
    <?php endif; ?>

    <?php if ($errors): ?>
      <div class="msg msg-err">
        <div><strong>Perbaiki input berikut:</strong></div>
        <ul>
          <?php foreach ($errors as $e): ?>
            <li><?= h($e) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="post">
      <div class="row">
        <div class="col">
          <label>Start (tanggal mulai)</label>
          <input type="date" name="start_date" value="<?= h($_POST['start_date'] ?? '') ?>">
        </div>
        <div class="col">
          <label>End (tanggal selesai)</label>
          <input type="date" name="end_date" value="<?= h($_POST['end_date'] ?? '') ?>">
        </div>
      </div>

      <div class="row">
        <div class="col">
          <label>PIC Acara (nama pemesan ruangan)</label>
          <input type="text" name="pic_acara" value="<?= h($_POST['pic_acara'] ?? '') ?>">
        </div>
        <div class="col">
          <label>Nama Acara</label>
          <input type="text" name="nama_acara" value="<?= h($_POST['nama_acara'] ?? '') ?>">
        </div>
      </div>

      <div class="row">
        <div class="col">
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
                  <input
                    class="cb"
                    type="checkbox"
                    name="pic_it_support[]"
                    value="<?= h($opt) ?>"
                    <?php if (in_array($opt, $selected, true)) echo 'checked'; ?>
                  >
                  <span class="cb-text"><?= h($opt) ?></span>
                </label>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col">
          <label>Ruang Rapat</label>
          <select name="meeting_room">
            <option value="">-- Pilih Ruang Rapat --</option>
            <?php foreach ($MEETING_ROOMS as $room): ?>
              <option value="<?= h($room) ?>" <?= (($meeting_room ?? '') === $room) ? 'selected' : '' ?>>
                <?= h($room) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col">
          <label>Pelaksanaan Acara</label>
          <select name="pelaksanaan">
            <option value="">-- Pilih --</option>
            <?php foreach ($PELAKSANAAN_OPTIONS as $p): ?>
              <option value="<?= h($p) ?>" <?= (($pelaksanaan ?? '') === $p) ? 'selected' : '' ?>>
                <?= h($p) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="row">
        <div class="col">
          <label>STANDBY / ON CALL</label>
          <select name="standby_status">
            <option value="">-- Pilih --</option>
            <?php foreach ($STANDBY_OPTIONS as $s): ?>
              <option value="<?= h($s) ?>" <?= (($standby_status ?? '') === $s) ? 'selected' : '' ?>>
                <?= h($s) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col">
          <label>Tindak Lanjut</label>
          <select name="tindak_lanjut">
            <option value="">-- Pilih --</option>
            <?php foreach ($TINDAK_LANJUT_OPTIONS as $t): ?>
              <option value="<?= h($t) ?>" <?= (($tindak_lanjut ?? '') === $t) ? 'selected' : '' ?>>
                <?= h($t) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="row">
        <div class="col">
          <label>Kebutuhan Detail</label>
          <input type="text" name="kebutuhan_detail" value="<?= h($kebutuhan_detail ?? '') ?>" placeholder="Contoh: mic wireless, projector, zoom link, kabel HDMI, dll">
        </div>
      </div>

      <div style="margin-top:14px;">
        <button class="btn btn-primary" type="submit">Simpan</button>
        <a class="btn btn-secondary" href="<?= base_url('pages/export.php') ?>">Export CSV</a>
      </div>
    </form>
</div>

<div class="card">
    <div class="actions">
      <h2>Data Jadwal</h2>
      <div>Total: <strong><?= count($rows) ?></strong></div>
    </div>

    <?php if (!$rows): ?>
      <p>Belum ada data.</p>
    <?php else: ?>
      <div class="table-wrap">
        <table class="jadwal-table">
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
            <?php $no = 1; ?>  
            <?php foreach ($rows as $r): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= h($r['transaction_id']) ?></td>
                <td><?= h($r['start_date']) ?></td>
                <td><?= h($r['end_date']) ?></td>
                <td><?= h($r['pic_acara']) ?></td>
                <td><?= h($r['nama_acara']) ?></td>
                <td><?= h(it_support_to_text($r['pic_it_support'])) ?></td>
                <td><?= h($r['meeting_room']) ?></td>
                <td><?= h($r['pelaksanaan']) ?></td>
                <td><?= h($r['standby_status']) ?></td>
                <td><?= h($r['kebutuhan_detail']) ?></td>
                <td><?= h($r['tindak_lanjut']) ?></td>
                <td><?= h($r['created_at']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>