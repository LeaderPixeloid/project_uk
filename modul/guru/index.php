<?php
session_start();
require '../config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/header.php';

if (!isset($_SESSION['login_admin'])) {
    header("Location: ../../auth/login.php");
    exit;
}

$aktif = mysqli_query($conn, "SELECT * FROM guru WHERE aktif='Y' ORDER BY kode_guru ASC");
$nonaktif = mysqli_query($conn, "SELECT * FROM guru WHERE aktif='N' ORDER BY kode_guru ASC");
?>

<div class="max-w-7xl mx-auto px-4 py-8 space-y-8">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Manajemen Data Guru</h2>
            <p class="text-slate-500 text-sm">Kelola data petugas, pengajar, dan admin sistem.</p>
        </div>
        <div class="flex gap-3">
            <a href="../dashboard/index.php" class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-xl transition font-medium text-sm flex items-center">
                ← Kembali
            </a>
            <a href="add.php" class="bg-blue-600 text-white px-5 py-2 rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-100 font-bold text-sm flex items-center gap-2">
                <span>+</span> Tambah Guru Baru
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2">
            <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
            <h3 class="font-bold text-slate-700">Guru & Staff Aktif</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[11px] uppercase tracking-wider text-slate-400 font-bold border-b border-slate-100">
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Informasi Guru</th>
                        <th class="px-6 py-4">Username</th>
                        <th class="px-6 py-4">Jabatan</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php $no = 1;
                    while ($row = mysqli_fetch_assoc($aktif)): ?>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-sm text-slate-500"><?= $no++ ?></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs">
                                        <?= substr($row['nama_pengguna'], 0, 1) ?>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-700"><?= $row['nama_pengguna'] ?></div>
                                        <div class="text-[10px] text-slate-400 font-mono"><?= $row['kode_guru'] ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-medium"><?= $row['username'] ?></td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-slate-100 text-slate-600 text-[11px] font-bold rounded-full uppercase">
                                    <?= $row['jabatan'] ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <a href="edit.php?kode=<?= $row['kode_guru'] ?>" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                        ⚙️
                                    </a>
                                    <a href="delete.php?kode=<?= $row['kode_guru'] ?>" onclick="return confirm('Hapus data guru ini?')" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus">
                                        🗑️
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-slate-200/50 rounded-2xl border border-dashed border-slate-300 p-1">
        <details class="group">
            <summary class="list-none p-4 cursor-pointer flex justify-between items-center text-slate-500 hover:text-slate-700 transition">
                <span class="text-sm font-bold uppercase tracking-widest flex items-center gap-2">
                    📁 Lihat Guru Non-Aktif (<?= mysqli_num_rows($nonaktif) ?>)
                </span>
                <span class="group-open:rotate-180 transition-transform">▼</span>
            </summary>
            <div class="bg-white rounded-xl mt-2 overflow-hidden shadow-inner">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-400">
                        <tr>
                            <th class="px-6 py-3">Nama</th>
                            <th class="px-6 py-3">Username</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php while ($row = mysqli_fetch_assoc($nonaktif)): ?>
                            <tr>
                                <td class="px-6 py-3 text-slate-500 font-medium"><?= $row['nama_pengguna'] ?></td>
                                <td class="px-6 py-3 text-slate-400"><?= $row['username'] ?></td>
                                <td class="px-6 py-3 text-center">
                                    <a href="edit.php?kode=<?= $row['kode_guru'] ?>" class="text-blue-500 hover:underline">Aktifkan Kembali</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </details>
    </div>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/footer.php'; ?>