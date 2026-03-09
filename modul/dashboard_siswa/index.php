<?php
session_start();
require '../config/database.php';

/* | GUARD SISWA | */
if (!isset($_SESSION['login_siswa']) || !isset($_SESSION['nis'])) {
    header("Location: ../auth_siswa/login.php");
    exit;
}

$nis = $_SESSION['nis'];

/* | AMBIL DATA SISWA | */
$siswa = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT 
        s.nis, s.nama_siswa, s.jenis_kelamin, s.alamat, s.status,
        t.tingkat, p.program_keahlian, k.rombel
    FROM siswa s
    JOIN kelas k ON s.id_kelas = k.id_kelas
    JOIN tingkat t ON k.id_tingkat = t.id_tingkat
    JOIN program_keahlian p ON k.id_program_keahlian = p.id_program_keahlian
    WHERE s.nis = '$nis'
"));

/* | REKAP PELANGGARAN | */
$rekap = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT
        COUNT(ps.id_pelanggaran_siswa) AS total_pelanggaran,
        COALESCE(SUM(jp.poin), 0) AS total_poin
    FROM pelanggaran_siswa ps
    JOIN jenis_pelanggaran jp ON ps.id_jenis_pelanggaran = jp.id_jenis_pelanggaran
    WHERE ps.nis = '$nis'
"));

/* | RIWAYAT PELANGGARAN | */
$riwayat = mysqli_query($conn, "
    SELECT ps.tanggal, jp.jenis, jp.poin, ps.keterangan
    FROM pelanggaran_siswa ps
    JOIN jenis_pelanggaran jp ON ps.id_jenis_pelanggaran = jp.id_jenis_pelanggaran
    WHERE ps.nis = '$nis'
    ORDER BY ps.tanggal DESC
");
?>

<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Siswa | SPPS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen flex flex-col text-slate-800">

    <nav class="bg-indigo-700 text-white shadow-md">
        <div class="max-w-5xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="bg-white/20 p-2 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <span class="font-bold tracking-tight text-lg">Area Siswa</span>
            </div>

            <a href="../auth_siswa/logout.php" class="bg-indigo-800 hover:bg-red-600 px-4 py-2 rounded-lg text-sm font-semibold transition-all">
                Keluar
            </a>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto w-full px-6 py-8 flex-grow">

        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-900">Halo, <?= htmlspecialchars($siswa['nama_siswa']) ?>!</h1>
            <p class="text-slate-500">Pantau kedisiplinan dan total poin Anda di sini.</p>
        </div>

        <div class="mt-4 pt-4 border-t border-slate-100">
            <a href="edit.php" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                ⚙️ Edit Profil & Password
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="md:col-span-1 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Profil Saya</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 uppercase">NIS</label>
                            <p class="font-semibold text-slate-700"><?= $siswa['nis'] ?></p>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 uppercase">Kelas</label>
                            <p class="font-semibold text-slate-700"><?= $siswa['tingkat'] . ' ' . $siswa['program_keahlian'] . ' ' . $siswa['rombel'] ?></p>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 uppercase">Status</label>
                            <span class="inline-block px-2 py-1 bg-green-100 text-green-700 text-xs font-bold rounded mt-1">
                                <?= strtoupper($siswa['status']) ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="bg-indigo-600 rounded-2xl p-6 text-white shadow-lg shadow-indigo-100">
                    <p class="text-indigo-100 text-sm font-medium">Akumulasi Poin</p>
                    <h3 class="text-4xl font-bold mt-1"><?= $rekap['total_poin'] ?></h3>
                    <div class="mt-4 pt-4 border-t border-indigo-500 flex justify-between items-center text-xs">
                        <span>Total Pelanggaran:</span>
                        <span class="font-bold bg-white/20 px-2 py-0.5 rounded"><?= $rekap['total_pelanggaran'] ?></span>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-6 border-b border-slate-100">
                        <h2 class="font-bold text-slate-800">Riwayat Pelanggaran</h2>
                    </div>

                    <?php if (mysqli_num_rows($riwayat) > 0): ?>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 text-[11px] uppercase tracking-wider text-slate-500 font-bold">
                                        <th class="px-6 py-4">Waktu</th>
                                        <th class="px-6 py-4">Jenis</th>
                                        <th class="px-6 py-4 text-center">Poin</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <?php while ($r = mysqli_fetch_assoc($riwayat)): ?>
                                        <tr class="hover:bg-slate-50/80 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-semibold text-slate-700"><?= date('d M Y', strtotime($r['tanggal'])) ?></div>
                                                <div class="text-[11px] text-slate-400"><?= date('H:i', strtotime($r['tanggal'])) ?> WIB</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-slate-600 font-medium"><?= $r['jenis'] ?></div>
                                                <div class="text-xs text-slate-400 italic"><?= $r['keterangan'] ?: '-' ?></div>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="inline-block w-8 py-1 bg-rose-50 text-rose-600 text-xs font-bold rounded-lg border border-rose-100">
                                                    <?= $r['poin'] ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="p-10 text-center">
                            <div class="bg-green-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-green-500">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <p class="text-slate-500 font-medium">Luar biasa! Tidak ada riwayat pelanggaran.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </main>

    <?php require '../../asset/layout/footer.php'; ?>

</body>

</html>