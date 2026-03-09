<?php
session_start();
$base_url = "http://localhost/project_uk/";
if (!isset($_SESSION['login_admin'])) {
    header("Location: " . $base_url . "login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Sistem Pelanggaran Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen">

    <nav class="bg-blue-800 text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2">
                    <div class="bg-white p-1.5 rounded-lg text-blue-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <span class="font-bold text-lg tracking-tight">SPPS Siswa</span>
                </div>

                <div class="flex items-center gap-4">
                    <a href="../dashboard/edit.php" class="text-sm hover:text-blue-200 transition">
                        ⚙️ Edit Profil
                    </a>
                    <div class="text-right hidden sm:block">
                        <p class="text-xs text-blue-200 leading-none mb-1">Log masuk sebagai:</p>
                        <p class="text-sm font-semibold italic"><?= $_SESSION['username']; ?> (<?= $_SESSION['role']; ?>)</p>
                    </div>
                    <div class="h-8 w-px bg-blue-700"></div>
                    <a href="../auth/logout.php"
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-bold transition-all shadow-md">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="mb-10">
            <h1 class="text-3xl font-extrabold text-gray-900">Selamat Datang, <?= $_SESSION['username']; ?>! 👋</h1>
            <p class="text-gray-500 mt-1">Hari ini adalah hari yang baik untuk menjaga kedisiplinan sekolah.</p>
        </div>

        <!-- <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 group hover:border-blue-500 transition-all">
                <p class="text-sm font-medium text-gray-500">Total Pelanggaran</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">128</h3>
                <span class="text-xs text-red-500 font-semibold">+12% Bulan ini</span>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 group hover:border-blue-500 transition-all">
                <p class="text-sm font-medium text-gray-500">Siswa Bermasalah</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">12</h3>
                <span class="text-xs text-blue-500 font-semibold">Butuh Penanganan</span>
            </div>
        </div> -->

        <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
            <span class="w-8 h-1 bg-blue-600 rounded-full"></span>
            Menu Navigasi
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">

            <?php if ($_SESSION['role'] == 'admin'): ?>
                <a href="../auth/register.php" class="bg-white group p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all">
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600 mb-4 group-hover:bg-indigo-600 group-hover:text-white transition-colors text-2xl">👤</div>
                    <h3 class="font-bold text-gray-800">Register User</h3>
                    <p class="text-xs text-gray-500 mt-1">Tambahkan akun admin atau guru baru.</p>
                </a>

                <a href="../siswa/index.php" class="bg-white group p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all">
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-colors text-2xl">📂</div>
                    <h3 class="font-bold text-gray-800">Data Siswa</h3>
                    <p class="text-xs text-gray-500 mt-1">Kelola biodata dan informasi kelas siswa.</p>
                </a>

                <a href="#" class="bg-white group p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all">
                    <div class="w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center text-rose-600 mb-4 group-hover:bg-rose-600 group-hover:text-white transition-colors text-2xl">⚠️</div>
                    <h3 class="font-bold text-gray-800">Jenis Pelanggaran</h3>
                    <p class="text-xs text-gray-500 mt-1">Atur kategori dan bobot poin sanksi.</p>
                </a>

            <?php elseif ($_SESSION['role'] == 'Guru'): ?>
                <a href="../auth/register.php" class="bg-white group p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center text-purple-600 mb-4 group-hover:bg-purple-600 group-hover:text-white transition-colors text-2xl">➕</div>
                    <h3 class="font-bold text-gray-800">Register User</h3>
                </a>

                <div class="relative group">
                    <div onclick="toggleDropdown(event , 'inputDropdown')" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all cursor-pointer">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors text-2xl">📝</div>
                        <h3 class="font-bold text-gray-800 flex items-center justify-between">
                            Master Data
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </h3>
                    </div>

                    <div id="inputDropdown" class="dropdown-menu absolute left-0 mt-3 w-64 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden opacity-0 scale-95 pointer-events-none transition-all duration-200 ease-out origin-top z-[60]">
                        <a href="../guru/index.php" class="block px-6 py-4 hover:bg-blue-50 text-sm font-medium border-b border-gray-50">👨‍🏫 Data Guru</a>
                        <a href="../siswa/index.php" class="block px-6 py-4 hover:bg-blue-50 text-sm font-medium border-b border-gray-50">👨‍🎓 Data Siswa</a>
                        <a href="../pelanggaran/index.php" class="block px-6 py-4 hover:bg-blue-50 text-sm font-medium border-b border-gray-50">📑 Jenis Pelanggaran</a>
                        <a href="../kelas/index.php" class="block px-6 py-4 hover:bg-blue-50 text-sm font-medium">🏫 Data Kelas</a>
                    </div>
                </div>

                <div class="relative">
                    <div onclick="toggleDropdown(event, 'suratDropdown')" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all cursor-pointer">
                        <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600 mb-4 group-hover:bg-amber-600 group-hover:text-white transition-colors text-2xl">📄</div>
                        <h3 class="font-bold text-gray-800 flex items-center justify-between">
                            Cetak Surat
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </h3>
                    </div>

                    <div id="suratDropdown" class="dropdown-menu absolute left-0 mt-3 w-64 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden opacity-0 scale-95 pointer-events-none transition-all duration-200 ease-out origin-top z-[60]">
                        <a href="../surat/pernyataan_siswa/index.php" class="block px-6 py-4 hover:bg-amber-50 text-sm font-medium border-b border-gray-50">Pernyataan Siswa</a>
                        <a href="../surat/panggilan_ortu/index.php" class="block px-6 py-4 hover:bg-amber-50 text-sm font-medium border-b border-gray-50">Panggilan Orang Tua</a>
                        <a href="../surat/perjanjian_ortu/index.php" class="block px-6 py-4 hover:bg-amber-50 text-sm font-medium border-b border-gray-50">Perjanjian Ortu</a>
                        <a href="../surat/pindah_sekolah/index.php" class="block px-6 py-4 hover:bg-amber-50 text-sm font-medium">Pindah Sekolah</a>
                    </div>
                </div>

                <a href="../laporan_surat/index.php" class="bg-white group p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors text-2xl">📊</div>
                    <h3 class="font-bold text-gray-800">Laporan</h3>
                </a>

                <a href="../Entri_pelanggaran/index.php" class="bg-white group p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all">
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center text-orange-600 mb-4 group-hover:bg-orange-600 group-hover:text-white transition-colors text-2xl">📌</div>
                    <h3 class="font-bold text-gray-800">Entri Pelanggaran</h3>
                </a>

            <?php elseif ($_SESSION['role'] == 'manajemen'): ?>
                <a href="../auth/register.php" class="bg-white group p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all">
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600 mb-4 group-hover:bg-indigo-600 group-hover:text-white transition-colors text-2xl">➕</div>
                    <h3 class="font-bold text-gray-800">Register User</h3>
                </a>
                <div class="bg-white group p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all cursor-pointer">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-green-600 mb-4 group-hover:bg-green-600 group-hover:text-white transition-colors text-2xl">✅</div>
                    <h3 class="font-bold text-gray-800">Validasi Surat</h3>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <script>
        function toggleDropdown(e, id) {
            e.stopPropagation();
            const allMenus = document.querySelectorAll('.dropdown-menu');
            const targetMenu = document.getElementById(id);

            allMenus.forEach(menu => {
                if (menu !== targetMenu) {
                    menu.classList.add("opacity-0", "scale-95", "pointer-events-none");
                    menu.classList.remove("opacity-100", "scale-100");
                }
            });

            if (targetMenu.classList.contains("pointer-events-none")) {
                targetMenu.classList.remove("opacity-0", "scale-95", "pointer-events-none");
                targetMenu.classList.add("opacity-100", "scale-100");
            } else {
                targetMenu.classList.add("opacity-0", "scale-95", "pointer-events-none");
                targetMenu.classList.remove("opacity-100", "scale-100");
            }
        }

        document.addEventListener("click", function() {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.add("opacity-0", "scale-95", "pointer-events-none");
                menu.classList.remove("opacity-100", "scale-100");
            });
        });
    </script>
</body>

</html>