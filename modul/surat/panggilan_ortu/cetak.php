<?php
require $_SERVER['DOCUMENT_ROOT'] . '../config/database.php';

if (!isset($_POST['nis'])) {
    echo "Akses ditolak.";
    exit;
}

// Ambil data POST
$nis = $_POST['nis'];
$no_surat = $_POST['no_surat'];
$keperluan = $_POST['keperluan'];
$tanggal_raw = $_POST['tanggal']; // format: YYYY-MM-DDTHH:MM

// Olah Waktu Pertemuan
$dt = explode("T", $tanggal_raw);
$tgl_panggilan = $dt[0];
$jam_input = $dt[1];

$hari_indo = ["Monday"=>"Senin", "Tuesday"=>"Selasa", "Wednesday"=>"Rabu", "Thursday"=>"Kamis", "Friday"=>"Jumat", "Saturday"=>"Sabtu", "Sunday"=>"Minggu"];
$hari = $hari_indo[date("l", strtotime($tgl_panggilan))];

$bulan_indo = ["", "Januari", "Pebruari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
$tanggal_input_indo = date("d", strtotime($tgl_panggilan)) ." ". $bulan_indo[date("n", strtotime($tgl_panggilan))] . " " . date("Y", strtotime($tgl_panggilan));

// Info Surat (Sekarang)
$tgl_sekarang = date("d") ." ". $bulan_indo[date("n")] . " " . date("Y");
$bulan_romawi_arr = ["", "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII"];
$bulan_romawi = $bulan_romawi_arr[date("n")];

// Query Data Siswa Lengkap
$query_siswa = mysqli_query($conn, "SELECT siswa.*, tingkat.tingkat, program_keahlian.program_keahlian, kelas.rombel 
    FROM siswa 
    JOIN kelas ON siswa.id_kelas = kelas.id_kelas
    JOIN tingkat ON kelas.id_tingkat = tingkat.id_tingkat
    JOIN program_keahlian ON kelas.id_program_keahlian = program_keahlian.id_program_keahlian
    WHERE nis = '$nis'");
$row_siswa = mysqli_fetch_assoc($query_siswa);

// Query Guru (BK & Waka)
$tk = $row_siswa['tingkat'];
$query_bk = mysqli_query($conn, "SELECT nama_pengguna FROM guru WHERE jabatan = 'Guru BK $tk' AND aktif = 'Y'");
$row_bk = mysqli_fetch_assoc($query_bk);
$guru_bk = $row_bk['nama_pengguna'] ?? "(Nama Guru BK)";

$query_waka = mysqli_query($conn, "SELECT nama_pengguna FROM guru WHERE jabatan = 'Waka Kesiswaan' AND aktif = 'Y'");
$row_waka = mysqli_fetch_assoc($query_waka);
$waka_kesiswaan = $row_waka['nama_pengguna'] ?? "(Nama Waka Kesiswaan)";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Surat Panggilan - <?= $nis ?></title>
    <style>
        body { font-family: "Times New Roman", Times, serif; font-size: 12pt; line-height: 1.5; }
        .page { width: 210mm; min-height: 297mm; padding: 10mm 20mm; margin: 10mm auto; background: white; border: 1px solid #ddd; }
        .no-print { background: #f4f4f4; padding: 10px; text-align: center; margin-bottom: 20px; }
        @media print { 
            .no-print { display: none; } 
            .page { border: none; margin: 0; }
        }
        table { border-collapse: collapse; }
        .header-img { width: 100%; margin-bottom: 10px; }
        .ttd-table { width: 100%; margin-top: 50px; }
    </style>
</head>
<body>

<div class="no-print">
    <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">Cetak Surat</button>
    <button onclick="window.close()" style="padding: 10px 20px; cursor: pointer;">Tutup</button>
</div>

<div class="page">
    <img src="../../asset/gambar/kop.jpg" width="100%" alt="Kop Surat">

    <table style="width: 100%; margin-top: 20px;">
        <tr><td width="80">No.</td><td>: <?= $no_surat ?>/SMK TI/BG/<?= $bulan_romawi ?>/<?= date("Y") ?></td></tr>
        <tr><td>Lamp.</td><td>: -</td></tr>
        <tr><td>Perihal</td><td>: <b>Pemanggilan Orang Tua / Wali Siswa</b></td></tr>
    </table>

    <p style="margin-top: 30px;">Kepada<br>Yth. Bapak/ Ibu</p>
    <table style="margin-left: 30px;">
        <tr><td width="150">Orang Tua / Wali dari</td><td>: <?= $row_siswa['nama_siswa'] ?></td></tr>
        <tr><td>Kelas / NIS</td><td>: <?= $row_siswa['tingkat'].' '.$row_siswa['program_keahlian'].' '.$row_siswa['rombel'] ?> / <?= $row_siswa['nis'] ?></td></tr>
    </table>

    <p>Dengan hormat,</p>
    <p>Bersama surat ini, kami mengharapkan kehadiran Bapak / Ibu pada :</p>

    <table style="margin-left: 30px;">
        <tr><td width="150">Hari / Tanggal</td><td>: <?= $hari ?>, <?= $tanggal_input_indo ?></td></tr>
        <tr><td>Pukul</td><td>: <?= $jam_input ?> WITA</td></tr>
        <tr><td>Tempat</td><td>: SMK TI Bali Global Denpasar</td></tr>
        <tr><td>Keperluan</td><td>: <?= nl2br($keperluan) ?></td></tr>
    </table>

    <p style="text-indent: 40px; margin-top: 20px;">
        Demikian surat ini kami sampaikan, besar harapan kami pertemuan ini agar tidak diwakilkan. Atas perhatian dan kerjasamanya, kami ucapkan terimakasih.
    </p>

    <table class="ttd-table">
        <tr>
            <td width="60%">Mengetahui,</td>
            <td>Denpasar, <?= $tgl_sekarang ?></td>
        </tr>
        <tr>
            <td>Waka Kesiswaan</td>
            <td>Guru BK</td>
        </tr>
        <tr><td colspan="2" style="height: 80px;"></td></tr>
        <tr>
            <td><u><b><?= $waka_kesiswaan ?></b></u></td>
            <td><u><b><?= $guru_bk ?></b></u></td>
        </tr>
    </table>
</div>

<script>
    window.onload = function() { window.print(); }
</script>
</body>
</html>