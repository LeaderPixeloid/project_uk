<?php
require $_SERVER['DOCUMENT_ROOT'] . '/project_uk/modul/config/database.php';

if (!isset($_POST['nis'])) {
    exit("Akses Ditolak");
}

$nis = $_POST['nis'];
$no_surat = $_POST['no_surat'];
$pindah_ke = $_POST['pindah_ke'];
$alasan_pindah = $_POST['alasan_pindah'];

// Logika pemilihan nama & alamat orang tua dari radio button
if ($_POST['pilih_ortu'] == 'ayah') {
    $nama_ortu = $_POST['nama_ayah'];
    $alamat_ortu = $_POST['alamat_ayah'];
} else {
    $nama_ortu = $_POST['nama_ibu'];
    $alamat_ortu = $_POST['alamat_ibu'];
}

// Data Pendukung (Kepala Sekolah & Tanggal)
$bulan_romawi = ["", "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII"];
$romawi = $bulan_romawi[date("n")];
$bulan_indo = ["", "Januari", "Pebruari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
$tanggal_sekarang = date("d") . " " . $bulan_indo[date("n")] . " " . date("Y");

// Ambil Nama Kepsek
$q_kepsek = mysqli_query($conn, "SELECT nama_pengguna FROM guru WHERE jabatan = 'Kepala Sekolah' AND aktif = 'Y' LIMIT 1");
$row_kepsek = mysqli_fetch_assoc($q_kepsek);
$nama_kepsek = $row_kepsek['nama_pengguna'] ?? "(Nama Kepala Sekolah Belum Diatur)";

// Ambil Data Siswa Lengkap
$q_siswa = mysqli_query($conn, "SELECT siswa.*, tingkat.tingkat, program_keahlian.program_keahlian, kelas.rombel 
    FROM siswa 
    JOIN kelas ON siswa.id_kelas = kelas.id_kelas
    JOIN tingkat ON kelas.id_tingkat = tingkat.id_tingkat
    JOIN program_keahlian ON kelas.id_program_keahlian = program_keahlian.id_program_keahlian
    WHERE nis = '$nis'");
$row = mysqli_fetch_assoc($q_siswa);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Pindah - <?= $row['nama_siswa'] ?></title>
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
        }

        .header-img {
            width: 100%;
            border-bottom: 3px double black;
            margin-bottom: 20px;
        }

        .title-block {
            text-align: center;
            margin-bottom: 30px;
        }

        .title-text {
            font-weight: bold;
            font-size: 14pt;
            text-decoration: underline;
            margin-bottom: 0;
        }

        .nomor-surat {
            font-size: 11pt;
            margin-top: 5px;
        }

        .content {
            text-align: justify;
        }

        .data-table {
            width: 100%;
            margin: 15px 0 15px 30px;
        }

        .data-table td {
            padding: 2px 0;
            vertical-align: top;
        }

        .label {
            width: 150px;
        }

        .sep {
            width: 20px;
        }

        .footer-section {
            margin-top: 50px;
            float: right;
            width: 300px;
            text-align: center;
        }

        .sig-space {
            height: 100px;
        }

        @media print {
            body {
                background: none;
                padding: 0;
            }

            .page {
                margin: 0;
                box-shadow: none;
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
        <img src="../../asset/gamba/kop.jpg" class=" header-img">

        <div class="title-block">
            <p class="title-text">KETERANGAN PINDAH SEKOLAH</p>
            <p class="nomor-surat">Nomor: <?= $no_surat ?>/SMK TI/BG/<?= $romawi ?>/<?= date("Y") ?></p>
        </div>

        <div class="content">
            <p>Yang bertandatangan di bawah ini Kepala SMK TI BALI GLOBAL Denpasar, Kecamatan Denpasar Selatan, Kota Denpasar, Provinsi Bali, menerangkan bahwa :</p>

            <table class="data-table">
                <tr>
                    <td class="label">Nama Siswa</td>
                    <td class="sep">:</td>
                    <td><b><?= $row['nama_siswa'] ?></b></td>
                </tr>
                <tr>
                    <td class="label">Kelas/Program</td>
                    <td class="sep">:</td>
                    <td><?= $row['tingkat'] ?> <?= $row['program_keahlian'] ?> <?= $row['rombel'] ?></td>
                </tr>
                <tr>
                    <td class="label">NIS</td>
                    <td class="sep">:</td>
                    <td><?= $row['nis'] ?></td>
                </tr>
                <tr>
                    <td class="label">Jenis Kelamin</td>
                    <td class="sep">:</td>
                    <td><?= $row['jenis_kelamin'] ?></td>
                </tr>
                <tr>
                    <td class="label">Alamat</td>
                    <td class="sep">:</td>
                    <td><?= $row['alamat'] ?></td>
                </tr>
            </table>

            <p>Sesuai dengan surat permohonan pindah sekolah dari Orang Tua / Wali siswa :</p>

            <table class="data-table">
                <tr>
                    <td class="label">Nama</td>
                    <td class="sep">:</td>
                    <td><?= $nama_ortu ?></td>
                </tr>
                <tr>
                    <td class="label">Alamat</td>
                    <td class="sep">:</td>
                    <td><?= $alamat_ortu ?></td>
                </tr>
            </table>

            <p>
                Telah mengajukan surat permohonan pindah ke <b><?= $pindah_ke ?></b>, dengan alasan <b><?= $alasan_pindah ?></b> dan untuk kelengkapan administrasi sudah diselesaikan.
                <br><br>
                Demikian surat keterangan pindah ini dibuat untuk dipergunakan sebagaimana mestinya.
            </p>

            <div class="footer-section">
                <div>Denpasar, <?= $tanggal_sekarang ?></div>
                <div>Kepala SMK TI Bali Global Denpasar</div>
                <div class="sig-space"></div>
                <div><b><u><?= $nama_kepsek ?></u></b></div>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>