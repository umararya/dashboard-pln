<?php
/**
 * Export Perangkat Aplikasi ke XLSX (valid OpenXML via ZipArchive)
 * Path: pages/export-perangkat-aplikasi.php
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/functions-permissions.php';

require_login();
require_permission('perangkat-aplikasi');

$pdo = db();

$rows = $pdo->query("
    SELECT pa.*, u.username AS created_by_name
    FROM perangkat_aplikasi pa
    LEFT JOIN users u ON pa.created_by = u.id
    ORDER BY pa.created_at DESC
")->fetchAll();

// ── Patch label mapping ──────────────────────────────────────────
$patch_labels = [
    '✅' => 'Up-to-date',
    '❌' => 'Belum Up-to-date',
    '–'  => 'Tidak relevan',
    '⌛' => 'Belum Konfirmasi',
];

// ── Helper: escape XML ───────────────────────────────────────────
function xe(string $val): string {
    $val = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $val);
    return htmlspecialchars($val, ENT_QUOTES | ENT_XML1, 'UTF-8');
}

// ── Headers & column config ──────────────────────────────────────
$headers = [
    'No', 'Nama Perangkat', 'URL', 'IP Address', 'Brand', 'Type',
    'Server', 'OS', 'Lokasi', 'Bidang', 'MSB / Sub Bidang',
    'Firmware Patch', 'Network Device Patch', 'Pemilik Aset',
    'Dibuat Oleh', 'Dibuat Pada',
];
$col_widths = [5, 22, 28, 15, 14, 16, 18, 20, 22, 14, 28, 16, 20, 20, 14, 22];
$cols       = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P'];
$lastCol    = $cols[count($headers) - 1];
$totalCols  = count($headers);

// ── Style index getter ───────────────────────────────────────────
function patchStyle(string $val, bool $isEven): int {
    $base = match($val) {
        '✅' => 4,
        '❌' => 6,
        '–'  => 8,
        default => 10,
    };
    return $isEven ? $base + 1 : $base;
}

// ── Cell builders ─────────────────────────────────────────────────
function strCell(string $ref, string $val, int $s): string {
    return '<c r="' . $ref . '" s="' . $s . '" t="inlineStr"><is><t>' . xe($val) . '</t></is></c>';
}
function numCell(string $ref, int $val, int $s): string {
    return '<c r="' . $ref . '" s="' . $s . '"><v>' . $val . '</v></c>';
}

// ============================================================
// 1. [Content_Types].xml
// ============================================================
$contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml" ContentType="application/xml"/>
  <Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>
  <Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>
  <Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>
</Types>';

// ============================================================
// 2. _rels/.rels
// ============================================================
$topRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>
</Relationships>';

// ============================================================
// 3. xl/_rels/workbook.xml.rels
// ============================================================
$workbookRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>
  <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>
</Relationships>';

// ============================================================
// 4. xl/workbook.xml
// ============================================================
$workbook = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
  <fileVersion appName="xl"/>
  <workbookPr/>
  <sheets>
    <sheet name="Perangkat Aplikasi" sheetId="1" r:id="rId1"/>
  </sheets>
</workbook>';

// ============================================================
// 5. xl/styles.xml
// ============================================================
$styles = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
  <fonts count="5">
    <font><sz val="10"/><name val="Arial"/></font>
    <font><b/><sz val="10"/><color rgb="FFFFFFFF"/><name val="Arial"/></font>
    <font><b/><sz val="13"/><color rgb="FF1E293B"/><name val="Arial"/></font>
    <font><sz val="9"/><color rgb="FF64748B"/><name val="Arial"/></font>
    <font><b/><sz val="10"/><name val="Arial"/></font>
  </fonts>
  <fills count="10">
    <fill><patternFill patternType="none"/></fill>
    <fill><patternFill patternType="gray125"/></fill>
    <fill><patternFill patternType="solid"><fgColor rgb="FF1E40AF"/><bgColor indexed="64"/></patternFill></fill>
    <fill><patternFill patternType="solid"><fgColor rgb="FFF1F5F9"/><bgColor indexed="64"/></patternFill></fill>
    <fill><patternFill patternType="solid"><fgColor rgb="FFD1FAE5"/><bgColor indexed="64"/></patternFill></fill>
    <fill><patternFill patternType="solid"><fgColor rgb="FFFEE2E2"/><bgColor indexed="64"/></patternFill></fill>
    <fill><patternFill patternType="solid"><fgColor rgb="FFF1F5F9"/><bgColor indexed="64"/></patternFill></fill>
    <fill><patternFill patternType="solid"><fgColor rgb="FFFEF3C7"/><bgColor indexed="64"/></patternFill></fill>
    <fill><patternFill patternType="solid"><fgColor rgb="FFEBFDF4"/><bgColor indexed="64"/></patternFill></fill>
    <fill><patternFill patternType="solid"><fgColor rgb="FFFFF7ED"/><bgColor indexed="64"/></patternFill></fill>
  </fills>
  <borders count="2">
    <border><left/><right/><top/><bottom/><diagonal/></border>
    <border>
      <left style="thin"><color rgb="FFE2E8F0"/></left>
      <right style="thin"><color rgb="FFE2E8F0"/></right>
      <top style="thin"><color rgb="FFE2E8F0"/></top>
      <bottom style="thin"><color rgb="FFE2E8F0"/></bottom>
      <diagonal/>
    </border>
  </borders>
  <cellStyleXfs count="1">
    <xf numFmtId="0" fontId="0" fillId="0" borderId="0"/>
  </cellStyleXfs>
  <cellXfs>
    <xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0"><alignment vertical="center" wrapText="1"/></xf>
    <xf numFmtId="0" fontId="1" fillId="2" borderId="0" xfId="0"><alignment horizontal="center" vertical="center" wrapText="1"/></xf>
    <xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0"><alignment vertical="center" wrapText="1"/></xf>
    <xf numFmtId="0" fontId="0" fillId="3" borderId="1" xfId="0"><alignment vertical="center" wrapText="1"/></xf>
    <xf numFmtId="0" fontId="4" fillId="4" borderId="1" xfId="0"><alignment horizontal="center" vertical="center"/></xf>
    <xf numFmtId="0" fontId="4" fillId="4" borderId="1" xfId="0"><alignment horizontal="center" vertical="center"/></xf>
    <xf numFmtId="0" fontId="4" fillId="5" borderId="1" xfId="0"><alignment horizontal="center" vertical="center"/></xf>
    <xf numFmtId="0" fontId="4" fillId="5" borderId="1" xfId="0"><alignment horizontal="center" vertical="center"/></xf>
    <xf numFmtId="0" fontId="4" fillId="6" borderId="1" xfId="0"><alignment horizontal="center" vertical="center"/></xf>
    <xf numFmtId="0" fontId="4" fillId="6" borderId="1" xfId="0"><alignment horizontal="center" vertical="center"/></xf>
    <xf numFmtId="0" fontId="4" fillId="7" borderId="1" xfId="0"><alignment horizontal="center" vertical="center"/></xf>
    <xf numFmtId="0" fontId="4" fillId="7" borderId="1" xfId="0"><alignment horizontal="center" vertical="center"/></xf>
    <xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0"><alignment horizontal="center" vertical="center"/></xf>
    <xf numFmtId="0" fontId="0" fillId="3" borderId="1" xfId="0"><alignment horizontal="center" vertical="center"/></xf>
    <xf numFmtId="0" fontId="2" fillId="0" borderId="0" xfId="0"><alignment vertical="center"/></xf>
    <xf numFmtId="0" fontId="3" fillId="0" borderId="0" xfId="0"><alignment vertical="center"/></xf>
  </cellXfs>
</styleSheet>';

// ============================================================
// 6. xl/worksheets/sheet1.xml
// ============================================================
$sheetXml  = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
$sheetXml .= '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"';
$sheetXml .= ' xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">';

// Freeze pane below header (row 4)
$sheetXml .= '<sheetViews><sheetView workbookViewId="0">';
$sheetXml .= '<pane ySplit="4" topLeftCell="A5" activePane="bottomLeft" state="frozen"/>';
$sheetXml .= '</sheetView></sheetViews>';

// Column widths
$sheetXml .= '<cols>';
foreach ($col_widths as $i => $w) {
    $ci = $i + 1;
    $sheetXml .= '<col min="' . $ci . '" max="' . $ci . '" width="' . $w . '" customWidth="1"/>';
}
$sheetXml .= '</cols>';

$sheetXml .= '<sheetData>';

// Row 1: Title
$titleText = 'Data Perangkat Aplikasi - PLN UID JATENG DIY';
$sheetXml .= '<row r="1" ht="28" customHeight="1">';
$sheetXml .= strCell('A1', $titleText, 14);
$sheetXml .= '</row>';

// Row 2: Subtitle
$subtitle  = 'Diekspor: ' . date('d/m/Y H:i') . ' WIB  |  Total: ' . count($rows) . ' data';
$sheetXml .= '<row r="2" ht="16" customHeight="1">';
$sheetXml .= strCell('A2', $subtitle, 15);
$sheetXml .= '</row>';

// Row 3: spacer
$sheetXml .= '<row r="3" ht="4" customHeight="1"></row>';

// Row 4: Headers
$sheetXml .= '<row r="4" ht="30" customHeight="1">';
foreach ($headers as $i => $h) {
    $sheetXml .= strCell($cols[$i] . '4', $h, 1);
}
$sheetXml .= '</row>';

// Data rows
$no     = 1;
$rowNum = 5;
foreach ($rows as $r) {
    $isEven   = ($no % 2 === 0);
    $rowStyle = $isEven ? 3 : 2;
    $noStyle  = $isEven ? 13 : 12;

    $fp      = (string)($r['firmware_patch'] ?? '⌛');
    $np      = (string)($r['network_device_patch'] ?? '⌛');
    $fpLabel = $patch_labels[$fp] ?? 'Belum Konfirmasi';
    $npLabel = $patch_labels[$np] ?? 'Belum Konfirmasi';
    $fpStyle = patchStyle($fp, $isEven);
    $npStyle = patchStyle($np, $isEven);

    $sheetXml .= '<row r="' . $rowNum . '" ht="18" customHeight="1">';
    $sheetXml .= numCell('A' . $rowNum, $no, $noStyle);
    $sheetXml .= strCell('B' . $rowNum, (string)($r['nama_perangkat'] ?? ''), $rowStyle);
    $sheetXml .= strCell('C' . $rowNum, (string)($r['url'] ?? ''), $rowStyle);
    $sheetXml .= strCell('D' . $rowNum, (string)($r['ip'] ?? ''), $rowStyle);
    $sheetXml .= strCell('E' . $rowNum, (string)($r['brand'] ?? ''), $rowStyle);
    $sheetXml .= strCell('F' . $rowNum, (string)($r['type'] ?? ''), $rowStyle);
    $sheetXml .= strCell('G' . $rowNum, (string)($r['server'] ?? ''), $rowStyle);
    $sheetXml .= strCell('H' . $rowNum, (string)($r['os'] ?? ''), $rowStyle);
    $sheetXml .= strCell('I' . $rowNum, (string)($r['lokasi'] ?? ''), $rowStyle);
    $sheetXml .= strCell('J' . $rowNum, (string)($r['bidang'] ?? ''), $rowStyle);
    $sheetXml .= strCell('K' . $rowNum, (string)($r['msb_sub_bidang'] ?? ''), $rowStyle);
    $sheetXml .= strCell('L' . $rowNum, $fpLabel, $fpStyle);
    $sheetXml .= strCell('M' . $rowNum, $npLabel, $npStyle);
    $sheetXml .= strCell('N' . $rowNum, (string)($r['pemilik_aset'] ?? ''), $rowStyle);
    $sheetXml .= strCell('O' . $rowNum, (string)($r['created_by_name'] ?? ''), $rowStyle);
    $sheetXml .= strCell('P' . $rowNum, (string)($r['created_at'] ?? ''), $rowStyle);
    $sheetXml .= '</row>';

    $no++;
    $rowNum++;
}

$sheetXml .= '</sheetData>';

// Merge title rows across all columns
$sheetXml .= '<mergeCells count="2">';
$sheetXml .= '<mergeCell ref="A1:' . $lastCol . '1"/>';
$sheetXml .= '<mergeCell ref="A2:' . $lastCol . '2"/>';
$sheetXml .= '</mergeCells>';

$sheetXml .= '</worksheet>';

// ============================================================
// Pack into ZIP → XLSX
// ============================================================
$tmpFile = sys_get_temp_dir() . '/pa_export_' . uniqid() . '.xlsx';

$zip = new ZipArchive();
if ($zip->open($tmpFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    http_response_code(500);
    die('Gagal membuat file export. Pastikan ekstensi ZipArchive aktif di server.');
}

$zip->addFromString('[Content_Types].xml',        $contentTypes);
$zip->addFromString('_rels/.rels',                $topRels);
$zip->addFromString('xl/workbook.xml',            $workbook);
$zip->addFromString('xl/_rels/workbook.xml.rels', $workbookRels);
$zip->addFromString('xl/styles.xml',              $styles);
$zip->addFromString('xl/worksheets/sheet1.xml',   $sheetXml);
$zip->close();

// ============================================================
// Stream to browser
// ============================================================
$filename = 'perangkat_aplikasi_' . date('Ymd_His') . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . filesize($tmpFile));
header('Cache-Control: max-age=0, no-store');
header('Pragma: public');

readfile($tmpFile);
unlink($tmpFile);
exit;