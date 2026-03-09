<?php
session_start();
require '../config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/header.php';

if (!isset($_SESSION['login_admin'])) {
    header("Location: ../auth/login.php");
    exit;
}

$data = mysqli_query($conn, "SELECT * FROM jenis_pelanggaran ORDER BY poin DESC");
$title = "Katalog Pelanggaran";
?>

<div class="max-w-5xl mx-auto px-4 py-10">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Jenis Pelanggaran</h2>
            <p class="text-slate-500 text-sm">Daftar poin kategori pelanggaran kedisiplinan siswa.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="../dashboard/index.php" class="px-4 py-2 text-slate-500 hover:text-slate-800 transition font-medium text-sm">
                Kembali
            </a>
            <a href="add.php" class="bg-rose-600 text-white px-5 py-2.5 rounded-xl hover:bg-rose-700 transition shadow-lg shadow-rose-100 font-bold text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Kategori
            </a>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="px-8 py-5 text-[11px] font-bold uppercase tracking-wider text-slate-400 text-center w-20">No</th>
                    <th class="px-8 py-5 text-[11px] font-bold uppercase tracking-wider text-slate-400">Deskripsi Pelanggaran</th>
                    <th class="px-8 py-5 text-[11px] font-bold uppercase tracking-wider text-slate-400 text-center w-32">Bobot Poin</th>
                    <th class="px-8 py-5 text-[11px] font-bold uppercase tracking-wider text-slate-400 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php $no = 1;
                while ($row = mysqli_fetch_assoc($data)): ?>
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-8 py-5 text-sm text-slate-400 text-center font-mono"><?= $no++ ?></td>
                        <td class="px-8 py-5">
                            <span class="text-sm font-semibold text-slate-700 uppercase"><?= htmlspecialchars($row['jenis']) ?></span>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <?php
                            $color = $row['poin'] >= 50 ? 'bg-rose-100 text-rose-600' : ($row['poin'] >= 25 ? 'bg-orange-100 text-orange-600' : 'bg-amber-100 text-amber-600');
                            ?>
                            <span class="<?= $color ?> px-3 py-1 rounded-full text-xs font-bold tracking-tight">
                                <?= $row['poin'] ?> Poin
                            </span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="edit.php?id=<?= $row['id_jenis_pelanggaran'] ?>" class="text-blue-500 hover:text-blue-700 p-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <a href="delete.php?id=<?= $row['id_jenis_pelanggaran'] ?>" onclick="return confirm('Yakin ingin menghapus?')" class="text-rose-400 hover:text-rose-600 p-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/footer.php'; ?>