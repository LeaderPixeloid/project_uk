<?php
session_start();
require '../config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/header.php';

if (!isset($_SESSION['login_admin'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Cek apakah ada parameter sortir, default adalah ASC (X ke XII)
$sort_order = isset($_GET['sort']) && $_GET['sort'] == 'desc' ? 'DESC' : 'ASC';

$query = "SELECT 
            kelas.id_kelas,
            tingkat.tingkat,
            program_keahlian.program_keahlian,
            kelas.rombel,
            wali.nama_pengguna AS wali_kelas,
            bk.nama_pengguna AS guru_bk
        FROM kelas
        JOIN tingkat ON kelas.id_tingkat = tingkat.id_tingkat
        JOIN program_keahlian ON kelas.id_program_keahlian = program_keahlian.id_program_keahlian
        LEFT JOIN guru AS wali ON kelas.kode_guru = wali.kode_guru
        LEFT JOIN guru AS bk ON bk.jabatan = CONCAT('Guru BK ', tingkat.tingkat)
        ORDER BY tingkat.id_tingkat $sort_order, program_keahlian.program_keahlian ASC, kelas.rombel ASC";

$data = mysqli_query($conn, $query);
$title = "Manajemen Data Kelas";
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10">
        <div>
            <nav class="flex mb-2" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-xs text-gray-400">
                    <li>Dashboard</li>
                    <li>
                    <li><span class="mx-2">/</span></li>
                    </li>
                    <li class="text-blue-600 font-medium">Data Kelas</li>
                </ol>
            </nav>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Data Master Kelas</h1>
            <p class="text-gray-500 mt-1">Kelola rombongan belajar, wali kelas, dan koordinasi bimbingan konseling.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="../dashboard/index.php"
                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition">
                Kembali
            </a>
            
            <a href="add.php"
                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Kelas
            </a>

            <a href="?sort=asc" 
               class="px-4 py-2 text-sm font-medium <?= $sort_order == 'ASC' ? 'bg-blue-50 text-blue-600' : 'bg-white text-gray-700 hover:bg-gray-50' ?> border-r border-gray-300 transition">
               X → XII
            </a>
            <a href="?sort=desc" 
               class="px-4 py-2 text-sm font-medium <?= $sort_order == 'DESC' ? 'bg-blue-50 text-blue-600' : 'bg-white text-gray-700 hover:bg-gray-50' ?> transition">
               XII → X
            </a>
        </div>
            
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-200">
                        <th class="px-6 py-4 font-semibold text-gray-600 text-xs uppercase tracking-wider text-center w-20">No</th>
                        <th class="px-6 py-4 font-semibold text-gray-600 text-xs uppercase tracking-wider">Informasi Kelas</th>
                        <th class="px-6 py-4 font-semibold text-gray-600 text-xs uppercase tracking-wider">Wali Kelas</th>
                        <th class="px-6 py-4 font-semibold text-gray-600 text-xs uppercase tracking-wider">Pembimbing BK</th>
                        <th class="px-6 py-4 font-semibold text-gray-600 text-xs uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php
                    $no = 1;
                    if (mysqli_num_rows($data) > 0):
                        while ($row = mysqli_fetch_assoc($data)):
                            // Logika Warna Badge
                            $badgeStyles = "bg-gray-100 text-gray-600";
                            if ($row['tingkat'] == 'X') $badgeStyles = "bg-indigo-100 text-indigo-700 border border-indigo-200";
                            if ($row['tingkat'] == 'XI') $badgeStyles = "bg-blue-100 text-blue-700 border border-blue-200";
                            if ($row['tingkat'] == 'XII') $badgeStyles = "bg-emerald-100 text-emerald-700 border border-emerald-200";
                    ?>
                            <tr class="hover:bg-gray-50/80 transition-colors group">
                                <td class="px-6 py-5 text-center text-sm text-gray-400 font-mono">
                                    <?= str_pad($no++, 2, '0', STR_PAD_LEFT) ?>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider <?= $badgeStyles ?>">
                                            <?= $row['tingkat'] ?>
                                        </span>
                                        <div>
                                            <div class="font-bold text-gray-900"><?= $row['program_keahlian'] ?></div>
                                            <div class="text-xs text-gray-500">Rombel <?= $row['rombel'] ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-semibold text-gray-700">
                                            <?= htmlspecialchars($row['wali_kelas'] ?? 'Belum Ditentukan') ?>
                                        </span>
                                        <span class="text-[11px] text-gray-400 uppercase tracking-tight">NIP / Kode Guru</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                        <svg class="w-3 h-3 mr-1.5 text-blue-400" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        <?= htmlspecialchars($row['guru_bk'] ?? 'Sistem Mencari...') ?>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex justify-center items-center gap-4">
                                        <a href="edit.php?id=<?= $row['id_kelas'] ?>"
                                            class="text-gray-400 hover:text-blue-600 transition-colors tooltip" title="Edit Data">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <a href="delete.php?id=<?= $row['id_kelas'] ?>"
                                            onclick="return confirm('Seluruh data siswa di kelas ini mungkin terdampak. Yakin hapus?')"
                                            class="text-gray-400 hover:text-red-600 transition-colors shadow-sm" title="Hapus Data">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php
                        endwhile;
                    else:
                        ?>
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="bg-gray-50 p-4 rounded-full mb-4">
                                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                    </div>
                                    <h3 class="text-sm font-medium text-gray-900">Belum ada data</h3>
                                    <p class="text-xs text-gray-500 mt-1">Silahkan tambahkan data kelas baru melalui tombol di atas.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="bg-gray-50 px-6 py-3 border-t border-gray-100">
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold text-center">Data Terintegrasi Database Sekolah</p>
        </div>
    </div>
</div>

<?php require '../../asset/layout/footer.php'; ?>