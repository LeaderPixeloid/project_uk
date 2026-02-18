<?php
session_start();
require '../config/database.php';


/*
|--------------------------------------------------------------------------
| GUARD SISWA
|--------------------------------------------------------------------------
*/
if (!isset($_SESSION['login_siswa'])) {
    header("Location: ../auth_siswa/login.php");
    exit;
}
if (
    !isset($_SESSION['login_siswa']) ||
    !isset($_SESSION['nis'])
) {
    header("Location: ../auth/login_siswa.php");
    exit;
}

$nis = $_SESSION['nis'];

/*
|--------------------------------------------------------------------------
| AMBIL DATA SISWA
|--------------------------------------------------------------------------
*/
$siswa = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT 
        s.nis,
        s.nama_siswa,
        s.jenis_kelamin,
        s.alamat,
        s.status,
        t.tingkat,
        p.program_keahlian,
        k.rombel
    FROM siswa s
    JOIN kelas k ON s.id_kelas = k.id_kelas
    JOIN tingkat t ON k.id_tingkat = t.id_tingkat
    JOIN program_keahlian p ON k.id_program_keahlian = p.id_program_keahlian
    WHERE s.nis = '$nis'
"));


/*
|--------------------------------------------------------------------------
| REKAP PELANGGARAN
|--------------------------------------------------------------------------
*/
$rekap = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT
        COUNT(ps.id_pelanggaran_siswa) AS total_pelanggaran,
        COALESCE(SUM(jp.poin), 0) AS total_poin
    FROM pelanggaran_siswa ps
    JOIN jenis_pelanggaran jp
        ON ps.id_jenis_pelanggaran = jp.id_jenis_pelanggaran
    WHERE ps.nis = '$nis'
"));

/*
|--------------------------------------------------------------------------
| RIWAYAT PELANGGARAN
|--------------------------------------------------------------------------
*/
$riwayat = mysqli_query($conn, "
    SELECT
        ps.tanggal,
        jp.jenis,
        jp.poin,
        ps.keterangan
    FROM pelanggaran_siswa ps
    JOIN jenis_pelanggaran jp
        ON ps.id_jenis_pelanggaran = jp.id_jenis_pelanggaran
    WHERE ps.nis = '$nis'
    ORDER BY ps.tanggal DESC
");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">

    <div class="max-w-5xl mx-auto space-y-6">

        <!-- HEADER -->
        <div class="bg-white p-4 rounded shadow flex justify-between items-center">
            <h1 class="text-xl font-bold">Dashboard Siswa</h1>
            <a href="../auth_siswa/logout.php"
                class="text-red-500 hover:underline">
                Logout
            </a>
        </div>

        <!-- DATA SISWA -->
        <div class="bg-white p-6 rounded shadow">
            <h2 class="font-semibold text-lg mb-4">Data Diri</h2>

            <div class="grid grid-cols-2 gap-4 text-sm">
                <p><b>NIS:</b> <?= $siswa['nis'] ?></p>
                <p><b>Nama:</b> <?= $siswa['nama_siswa'] ?></p>
                <p><b>Jenis Kelamin:</b> <?= $siswa['jenis_kelamin'] ?></p>
                <p><b>Kelas:</b> <?= $siswa['tingkat'] . ' ' . $siswa['program_keahlian'] . ' ' . $siswa['rombel'] ?></p>
                <p class="col-span-2"><b>Alamat:</b> <?= $siswa['alamat'] ?></p>
                <p><b>Status:</b> <?= ucfirst($siswa['status']) ?></p>
            </div>
        </div>

        <!-- REKAP -->
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-white p-4 rounded shadow text-center">
                <p class="text-gray-500">Total Pelanggaran</p>
                <p class="text-3xl font-bold"><?= $rekap['total_pelanggaran'] ?></p>
            </div>

            <div class="bg-white p-4 rounded shadow text-center">
                <p class="text-gray-500">Total Poin</p>
                <p class="text-3xl font-bold"><?= $rekap['total_poin'] ?></p>
            </div>
        </div>

        <!-- RIWAYAT PELANGGARAN -->
        <div class="bg-white p-6 rounded shadow">
            <h2 class="font-semibold text-lg mb-4">Riwayat Pelanggaran</h2>

            <?php if (mysqli_num_rows($riwayat) > 0): ?>
                <div class="overflow-x-auto">
                    <table class="w-full border text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border p-2">Tanggal</th>
                                <th class="border p-2">Pelanggaran</th>
                                <th class="border p-2">Poin</th>
                                <th class="border p-2">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($r = mysqli_fetch_assoc($riwayat)): ?>
                                <tr>
                                    <td class="border p-2"><?= date('d-m-Y H:i', strtotime($r['tanggal'])) ?></td>
                                    <td class="border p-2"><?= $r['jenis'] ?></td>
                                    <td class="border p-2 text-center"><?= $r['poin'] ?></td>
                                    <td class="border p-2"><?= $r['keterangan'] ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-gray-500">Belum ada pelanggaran.</p>
            <?php endif; ?>
        </div>

    </div>

</body>

</html>