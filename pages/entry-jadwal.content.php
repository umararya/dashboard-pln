<?php
// pages/entry-jadwal.content.php
?>

<style>
.multiselect{position:relative;width:100%}
.selectBox{border:1px solid #d1d5db;border-radius:8px;padding:10px 14px;cursor:pointer;display:flex;justify-content:space-between;align-items:center;background:#fff}
.ms-panel{display:none;position:absolute;top:calc(100% + 8px);left:0;right:0;background:#fff;border:1px solid #d1d5db;border-radius:8px;padding:8px;max-height:220px;overflow-y:auto;z-index:9999;box-shadow:0 10px 24px rgba(0,0,0,.12);min-height:56px}
.cb-item{display:flex;align-items:center;gap:10px;padding:10px;border-radius:6px;margin:0}
.cb-item:hover{background:#f3f4f6}
.cb-item input.cb{width:auto!important;height:16px;margin:0!important;flex:0 0 auto}
.cb-text{flex:1}
</style>

<div class="card">
  <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
    <div>
      <h2>📝 Entry Jadwal Kegiatan</h2>
      <p>Tambah jadwal kegiatan baru</p>
    </div>
    <a class="btn btn-secondary btn-sm" href="<?= base_url('pages/dashboard.php') ?>">← Kembali</a>
  </div>

  <?php if (!empty($errors)): ?>
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
          <input type="date" name="start_date" value="<?= h($start_date) ?>" required>
        </div>
        <div class="form-group">
          <label>End (tanggal selesai)</label>
          <input type="date" name="end_date" value="<?= h($end_date) ?>" required>
        </div>
      </div>

      <div class="grid-2" style="margin-bottom: 20px;">
        <div class="form-group">
          <label>PIC Acara (nama pemesan ruangan)</label>
          <input type="text" name="pic_acara" value="<?= h($pic_acara) ?>" required>
        </div>
        <div class="form-group">
          <label>Nama Acara</label>
          <input type="text" name="nama_acara" value="<?= h($nama_acara) ?>" required>
        </div>
      </div>

      <div class="form-group" style="margin-bottom: 20px;">
        <label>PIC IT Support</label>
        <div class="multiselect" id="picItDropdown">
          <div class="selectBox" onclick="togglePicIt()">
            <span id="selectedText">Pilih PIC IT Support</span>
            <span class="arrow">▾</span>
          </div>
          <div class="ms-panel" id="picItPanel">
            <?php foreach ($PIC_IT_OPTIONS as $opt): ?>
              <label class="cb-item">
                <input class="cb" type="checkbox" name="pic_it_support[]" value="<?= h($opt) ?>" <?php if (in_array($opt, (array)$selected_pic_it, true)) echo 'checked'; ?>>
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
              <option value="<?= h($room) ?>" <?= ($meeting_room === $room) ? 'selected' : '' ?>><?= h($room) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Pelaksanaan Acara</label>
          <select name="pelaksanaan" required>
            <option value="">-- Pilih --</option>
            <?php foreach ($PELAKSANAAN_OPTIONS as $p): ?>
              <option value="<?= h($p) ?>" <?= ($pelaksanaan === $p) ? 'selected' : '' ?>><?= h($p) ?></option>
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
              <option value="<?= h($s) ?>" <?= ($standby_status === $s) ? 'selected' : '' ?>><?= h($s) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Tindak Lanjut</label>
          <select name="tindak_lanjut" required>
            <option value="">-- Pilih --</option>
            <?php foreach ($TINDAK_LANJUT_OPTIONS as $t): ?>
              <option value="<?= h($t) ?>" <?= ($tindak_lanjut === $t) ? 'selected' : '' ?>><?= h($t) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="form-group" style="margin-bottom: 20px;">
        <label>Kebutuhan Detail</label>
        <input type="text" name="kebutuhan_detail" value="<?= h($kebutuhan_detail) ?>" placeholder="Contoh: mic wireless, projector, zoom link, kabel HDMI, dll">
      </div>

      <button class="btn btn-primary" type="submit">💾 Simpan Jadwal</button>
    </form>
  </div>
</div>

<script>
let picItOpen = false;

function togglePicIt(){
  const panel = document.getElementById("picItPanel");
  picItOpen = !picItOpen;
  panel.style.display = picItOpen ? "block" : "none";
}

function updateSelectedText(){
  const checked = document.querySelectorAll('#picItPanel input.cb:checked');
  const values = Array.from(checked).map(cb => cb.value);
  document.getElementById("selectedText").innerText = values.length ? values.join(", ") : "Pilih PIC IT Support";
}

document.addEventListener("click", function(e){
  const wrap = document.getElementById("picItDropdown");
  const panel = document.getElementById("picItPanel");
  if (wrap && panel && !wrap.contains(e.target)){
    panel.style.display = "none";
    picItOpen = false;
  }
});

document.querySelectorAll('#picItPanel input.cb').forEach(cb => cb.addEventListener('change', updateSelectedText));
updateSelectedText();
</script>
