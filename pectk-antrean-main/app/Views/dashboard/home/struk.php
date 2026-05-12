<?php 
/** 
 * @var string $title 
 * @var array $antrean 
 */

$antrean = $antrean ?? [
    'kode_antrean' => '',
    'nomor_antrean' => '',
    'nama_jaminan' => '',
    'tanggal_antrean' => date('Y-m-d H:i:s')
]; 
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title><?= $title; ?></title> <!-- Harus menggunakan variabel $title -->
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 9pt;
            width: 44mm;
            margin: 0 auto;
            padding: 5px;
        }
        .header { text-align: center; border-bottom: 1px dashed #000; padding-bottom: 5px; margin-bottom: 10px; }
        .bps-title { font-weight: bold; font-size: 10pt; display: block; }
        .nomor-antrean { font-size: 30pt; font-weight: bold; display: block; margin: 10px 0; text-align: center; }
        .footer { text-align: center; border-top: 1px dashed #000; padding-top: 5px; font-size: 7pt; }
    </style>
</head>
<body>
    <div class="header">
        <span class="bps-title">BPS KOTA PEKALONGAN</span>
        <small>Pelayanan Statistik Terpadu</small>
    </div>
    <div style="text-align:center;">
        <span>Nomor Antrean:</span>
        <span class="nomor-antrean"><?= $antrean['nomor_antrean'] ?></span>
    </div>
    <div class="footer">
        Terima kasih atas kunjungan Anda.
    </div>
</body>
</html>