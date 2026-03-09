<?php
session_start();
require '../config/database.php';

/* =========================
   CEK AKSES
   ========================= */
if (!isset($_POST['nis'])) {
    die("Akses tidak valid");
}

$nis = mysqli_real_escape_string($conn, $_POST['nis']);

/* =========================
   AMBIL DATA SISWA + ORTU + KELAS
   ========================= */
$query_siswa = mysqli_query($conn, "
    SELECT siswa.*, 
           ortu_wali.*,
           kelas.*,
           tingkat.tingkat,
           program_keahlian.program_keahlian,
           guru.nama_pengguna AS wali_kelas
    FROM siswa
    LEFT JOIN ortu_wali USING(id_ortu_wali)
    JOIN kelas USING(id_kelas)
    JOIN tingkat USING(id_tingkat)
    JOIN program_keahlian USING(id_program_keahlian)
    LEFT JOIN guru ON kelas.kode_guru = guru.kode_guru
    WHERE nis = '$nis'
");

$row_siswa = mysqli_fetch_assoc($query_siswa);

if (!$row_siswa) {
    die("Data siswa tidak ditemukan");
}

/* =========================
   AMBIL GURU BK SESUAI TINGKAT
   ========================= */
$tingkat = $row_siswa['tingkat'];

$query_bk = mysqli_query($conn, "
    SELECT nama_pengguna 
    FROM guru 
    WHERE jabatan = 'Guru BK $tingkat'
    AND aktif = 'Y'
");

$row_bk = mysqli_fetch_assoc($query_bk);
$guru_bk = $row_bk['nama_pengguna'] ?? '-';

/* =========================
   AMBIL WAKA KESISWAAN
   ========================= */
$query_waka = mysqli_query($conn, "
    SELECT nama_pengguna 
    FROM guru 
    WHERE jabatan = 'Waka Kesiswaan'
    AND aktif = 'Y'
");

$row_waka = mysqli_fetch_assoc($query_waka);
$waka_kesiswaan = $row_waka['nama_pengguna'] ?? '-';
$title = "cetak Surat";
require '../../layout/header.php';
?>

<!DOCTYPE html>
<html>

<head>
    <title>Surat Pernyataan Siswa</title>

    <style>
        body {
            font-family: "Times New Roman", serif;
            margin: 40px;
        }

        /* Hilangkan tombol saat print */
        @media print {
            .no-print {
                display: none;
            }

            body {
                margin: 30px;
            }
        }

        .title {
            text-align: center;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 20px;
        }

        .form-row {
            display: flex;
            margin-bottom: 8px;
        }

        .label {
            width: 180px;
        }

        .separator {
            width: 20px;
        }

        .field {
            flex: 1;
            border-bottom: 1px dotted black;
        }

        .signature {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }

        .center-sign {
            text-align: center;
            margin-top: 60px;
        }
    </style>

    <script>
        // Auto print saat halaman dibuka
        window.onload = function() {
            window.print();
        }
    </script>
</head>

<body>

    <!-- KOP SURAT -->
    <img src="../../asset/gambar/kop.jpg" width="100%">

    <div class="title">
        SURAT PERNYATAAN SISWA
    </div>

    <p>Yang bertandatangan di bawah ini :</p>

    <!-- DATA SISWA -->
    <div class="form-row">
        <div class="label">Nama</div>
        <div class="separator">:</div>
        <div class="field"><?= htmlspecialchars($row_siswa['nama_siswa']) ?></div>
    </div>

    <div class="form-row">
        <div class="label">NIS</div>
        <div class="separator">:</div>
        <div class="field"><?= htmlspecialchars($row_siswa['nis']) ?></div>
    </div>

    <div class="form-row">
        <div class="label">Kelas</div>
        <div class="separator">:</div>
        <div class="field">
            <?= htmlspecialchars(
                $row_siswa['tingkat'] . ' ' .
                    $row_siswa['program_keahlian'] . ' ' .
                    $row_siswa['rombel']
            ) ?>
        </div>
    </div>

    <!-- DATA ORANG TUA -->
    <div class="form-row">
        <div class="label">Nama Orang Tua</div>
        <div class="separator">:</div>
        <div class="field"><?= htmlspecialchars($row_siswa['ayah'] ?? '-') ?></div>
    </div>

    <div class="form-row">
        <div class="label">Pekerjaan</div>
        <div class="separator">:</div>
        <div class="field"><?= htmlspecialchars($row_siswa['pekerjaan_ayah'] ?? '-') ?></div>
    </div>

    <div class="form-row">
        <div class="label">Alamat Rumah</div>
        <div class="separator">:</div>
        <div class="field"><?= htmlspecialchars($row_siswa['alamat'] ?? '-') ?></div>
    </div>

    <div class="form-row">
        <div class="label">No. Hp./Telp.</div>
        <div class="separator">:</div>
        <div class="field"><?= htmlspecialchars($row_siswa['no_telp'] ?? '-') ?></div>
    </div>

    <p style="margin-top:30px">
        Menyatakan dan berjanji akan bersungguh-sungguh berubah dan bersedia
        mentaati aturan dan tata tertib sekolah.
    </p>

    <!-- TANDA TANGAN ATAS -->
    <div class="signature">
        <div>
            Mengetahui,<br>
            Orang Tua/Wali<br><br><br><br>
            <b><?= htmlspecialchars($row_siswa['ayah'] ?? '-') ?></b>
        </div>

        <div>
            Denpasar, <?= date("d-m-Y") ?><br>
            Siswa yang bersangkutan<br><br><br><br>
            <b><?= htmlspecialchars($row_siswa['nama_siswa']) ?></b>
        </div>
    </div>

    <!-- TANDA TANGAN GURU -->
    <div class="signature">
        <div>
            Guru Bimbingan Konseling<br><br><br><br>
            <b><?= htmlspecialchars($guru_bk) ?></b>
        </div>

        <div>
            Guru Wali Kelas<br><br><br><br>
            <b><?= htmlspecialchars($row_siswa['wali_kelas'] ?? '-') ?></b>
        </div>
    </div>

    <!-- WAKA KESISWAAN -->
    <div class="center-sign">
        Mengetahui<br>
        Wakasek Kesiswaan<br><br><br><br>
        <b><?= htmlspecialchars($waka_kesiswaan) ?></b>
    </div>

</body>

</html>

<?php require '../../layout/footer.php'; ?>
