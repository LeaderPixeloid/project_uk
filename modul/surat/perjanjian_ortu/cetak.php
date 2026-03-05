<?php
require $_SERVER['DOCUMENT_ROOT'] . '../config/database.php';

if (!isset($_POST['nis'])) {
    exit("Akses Ditolak");
}

$nis = $_POST['nis'];
$nama_ortu = $_POST['nama_ortu'];
$tempat_lahir = $_POST['tempat_lahir'];
$pekerjaan = $_POST['pekerjaan'];
$alamat = $_POST['alamat'];
$no_telp = $_POST['no_telp'];
$tgl_lahir_raw = $_POST['tanggal_lahir'];

// Pengaturan Bahasa Indonesia
$bulan_indo = ["", "Januari", "Pebruari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

// Format Tanggal Lahir & Hari Ini
$tanggal_lahir = date("d", strtotime($tgl_lahir_raw)) . " " . $bulan_indo[date("n", strtotime($tgl_lahir_raw))] . " " . date("Y", strtotime($tgl_lahir_raw));
$tanggal_sekarang = date("d") . " " . $bulan_indo[date("n")] . " " . date("Y");

// Prediksi 3 Bulan Kedepan (NB)
$tgl_target = strtotime("+3 months");
$bulan_target = $bulan_indo[date("n", $tgl_target)] . " " . date("Y", $tgl_target);

// Ambil Data Siswa
$q = mysqli_query($conn, "SELECT siswa.*, tingkat.tingkat, program_keahlian.program_keahlian, kelas.rombel 
    FROM siswa 
    JOIN kelas ON siswa.id_kelas = kelas.id_kelas
    JOIN tingkat ON kelas.id_tingkat = tingkat.id_tingkat
    JOIN program_keahlian ON kelas.id_program_keahlian = program_keahlian.id_program_keahlian
    WHERE nis = '$nis'");
$siswa = mysqli_fetch_assoc($q);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Perjanjian - <?= $siswa['nama_siswa'] ?></title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            background-color: #525659;
            padding: 20px;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 10mm 20mm;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .header-img {
            width: 100%;
            border-bottom: 3px solid black;
            padding-bottom: 5px;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            margin: 20px 0;
            text-decoration: underline;
        }

        .form-table {
            width: 100%;
            margin: 15px 0;
            border-collapse: collapse;
        }

        .form-table td {
            vertical-align: top;
            padding: 2px 0;
        }

        .label {
            width: 180px;
        }

        .sep {
            width: 20px;
            text-align: center;
        }

        .statement {
            text-align: justify;
            margin: 20px 0;
        }

        .statement b {
            text-decoration: underline;
        }

        .signature-section {
            margin-top: 40px;
            float: right;
            width: 300px;
            text-align: center;
        }

        .sig-space {
            height: 80px;
        }

        .footer-note {
            margin-top: 50px;
            font-size: 10pt;
            clear: both;
        }

        @media print {
            body {
                background: none;
                padding: 0;
            }

            .page {
                box-shadow: none;
                margin: 0;
                border: none;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="no-print">
    <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">Cetak Surat</button>
    <button onclick="window.close()" style="padding: 10px 20px; cursor: pointer;">Tutup</button>
</div>

    <div class="page">
        <img src="../../asset/gamba/kop.jpg" class="header-img">

        <div class="title">SURAT PERNYATAAN ORANG TUA / WALI</div>

        <p>Yang bertandatangan di bawah ini orang tua/wali siswa SMK TI Bali Global Denpasar :</p>

        <table class="form-table">
            <tr>
                <td class="label">Nama</td>
                <td class="sep">:</td>
                <td><?= $nama_ortu ?></td>
            </tr>
            <tr>
                <td class="label">Tempat/Tanggal Lahir</td>
                <td class="sep">:</td>
                <td><?= $tempat_lahir ?> / <?= $tanggal_lahir ?></td>
            </tr>
            <tr>
                <td class="label">Pekerjaan</td>
                <td class="sep">:</td>
                <td><?= $pekerjaan ?></td>
            </tr>
            <tr>
                <td class="label">Alamat Rumah</td>
                <td class="sep">:</td>
                <td><?= $alamat ?></td>
            </tr>
            <tr>
                <td class="label">No. HP / Telp.</td>
                <td class="sep">:</td>
                <td><?= $no_telp ?></td>
            </tr>
        </table>

        <p class="statement">
            Menyatakan memang benar sanggup membina anak kami yang bernama <b><?= $siswa['nama_siswa'] ?></b>,
            Kelas : <b><?= $siswa['tingkat'] . ' ' . $siswa['program_keahlian'] . ' ' . $siswa['rombel'] ?></b>
            untuk lebih disiplin mengikuti proses pembelajaran dan mengikuti Tata Tertib Sekolah.
            <br><br>
            Demikian pernyataan kami dan jika tidak sesuai dengan pernyataan diatas, anak kami dapat dikeluarkan dari sekolah ini dengan rekomendasi pindah ke SMK lain yang serumpun.
        </p>

        <div class="signature-section">
            <div>Denpasar, <?= $tanggal_sekarang ?></div>
            <div>Yang membuat pernyataan,<br>Orang Tua/Wali Siswa</div>
            <div class="sig-space"></div>
            <div><b>( <?= $nama_ortu ?> )</b></div>
        </div>

        <div class="footer-note">
            <u>NB :</u><br>
            <i>Jika siswa tidak bisa mengikuti proses pembelajaran sampai bulan <b><?= $bulan_target ?></b> maka siswa dinyatakan mengundurkan diri.</i>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>