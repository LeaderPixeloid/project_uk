<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$base_url = "http://localhost/project_uk/";

// Ambil data profil untuk di Navbar (jika sudah login admin)
if (isset($_SESSION['login_admin'])) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/project_uk/modul/config/database.php';
    $kd_guru = $_SESSION['kode_guru'];
    $data_nav = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama_pengguna, foto_profil FROM guru WHERE kode_guru = '$kd_guru'"));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? "SPPSISWA - Sistem Pelanggaran"; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }

        .dropdown-menu {
            display: none;
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.2s ease-out;
        }

        .dropdown-active {
            display: block !important;
            opacity: 1 !important;
            transform: translateY(0) !important;
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen flex flex-col">

    <nav class="bg-blue-800 text-white shadow-lg no-print sticky top-0 z-[100]">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16 items-center">

                <a href="<?= $base_url ?>modul/dashboard/index.php" class="flex items-center gap-3">
                    <div class="bg-white p-1.5 rounded-lg">
                        <svg class="w-6 h-6 text-blue-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        </svg>
                    </div>
                    <span class="font-bold text-lg tracking-tight uppercase">Spp Siswa</span>
                </a>

                <div class="hidden md:flex space-x-1 items-center">
                    <?php if (isset($_SESSION['login_admin'])): ?>

                        <div class="relative group-container">
                            <button onclick="handleDropdown(this)" class="px-3 py-2 hover:bg-blue-700 rounded-lg flex items-center gap-1 transition text-sm font-medium">
                                Data Master ▾
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 w-48 bg-white text-slate-800 rounded-xl shadow-xl border border-slate-100 z-50 overflow-hidden">
                                <a href="<?= $base_url ?>modul/guru/index.php" class="block px-4 py-2.5 hover:bg-slate-50 text-sm border-b border-slate-50">👨‍🏫 Data Guru</a>
                                <a href="<?= $base_url ?>modul/siswa/index.php" class="block px-4 py-2.5 hover:bg-slate-50 text-sm border-b border-slate-50">👨‍🎓 Data Siswa</a>
                                <a href="<?= $base_url ?>modul/kelas/index.php" class="block px-4 py-2.5 hover:bg-slate-50 text-sm border-b border-slate-50">🏫 Data Kelas</a>
                                <a href="<?= $base_url ?>modul/pelanggaran/index.php" class="block px-4 py-2.5 hover:bg-red-50 text-red-600 text-sm font-semibold">⚠️ Jenis Pelanggaran</a>
                            </div>
                        </div>
                        <div class="relative group-container">
                            <button onclick="handleDropdown(this)" class="px-3 py-2 hover:bg-blue-700 rounded-lg flex items-center gap-1 transition text-sm font-medium">
                                Administrasi ▾
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 w-56 bg-white text-slate-800 rounded-xl shadow-xl border border-slate-100 z-50 overflow-hidden">
                                <a href="<?= $base_url ?>modul/surat/panggilan_ortu/index.php" class="block px-4 py-2.5 hover:bg-slate-50 text-sm border-b border-slate-50">📩 Surat Panggilan</a>
                                <a href="<?= $base_url ?>modul/surat/perjanjian_ortu/index.php" class="block px-4 py-2.5 hover:bg-slate-50 text-sm border-b border-slate-50">📝 Surat Perjanjian</a>
                                <a href="<?= $base_url ?>modul/surat/pernyataan_siswa/index.php" class="block px-4 py-2.5 hover:bg-slate-50 text-sm">🧑‍🎓 Surat Pernyataan Siswa</a>
                                <a href="<?= $base_url ?>modul/surat/pindah_sekolah/index.php" class="block px-4 py-2.5 hover:bg-slate-50 text-sm">🏠 Surat Pindah</a>
                            </div>
                        </div>

                        <div class="relative group-container">
                            <button onclick="handleDropdown(this)" class="px-3 py-2 hover:bg-blue-700 rounded-lg flex items-center gap-1 transition text-sm font-medium">
                                Laporan & Input ▾
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 w-64 bg-white text-slate-800 rounded-xl shadow-xl border border-slate-100 z-50 overflow-hidden">
                                <a href="<?= $base_url ?>modul/Entri_pelanggaran/index.php" class="block px-4 py-3 bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-sm border-b border-blue-100">
                                    ➕ Input Pelanggaran Baru
                                </a>

                                <div class="px-4 py-2 bg-slate-50 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                    Main Reports
                                </div>

                                <a href="<?= $base_url ?>modul/laporan_surat/index.php" class="block px-4 py-2.5 hover:bg-slate-50 text-sm border-b border-slate-50 font-semibold text-slate-600">📊 Dashboard Laporan</a>
                                <a href="<?= $base_url ?>modul/laporan_pelanggaran/index.php" class="block px-4 py-2.5 hover:bg-slate-50 text-sm border-b border-slate-50 font-medium">📋 Detail Per Siswa</a>

                                <div class="px-4 py-2 bg-slate-50 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                    Rekapitulasi Surat
                                </div>
                                <a href="<?= $base_url ?>modul/laporan_surat/rekap_surat_panggilan/index.php" class="block px-4 py-2.5 hover:bg-slate-50 text-sm border-b border-slate-50 uppercase text-[11px] font-bold text-orange-600">📂 Rekap Panggilan</a>
                                <a href="<?= $base_url ?>modul/laporan_surat/rekap_surat_perjanjian/index.php" class="block px-4 py-2.5 hover:bg-slate-50 text-sm border-b border-slate-50 uppercase text-[11px] font-bold text-green-600">📂 Rekap Perjanjian</a>
                                <a href="<?= $base_url ?>modul/laporan_surat/rekap_surat_pindah/index.php" class="block px-4 py-2.5 hover:bg-slate-50 text-sm uppercase text-[11px] font-bold text-red-600">📂 Rekap Pindah</a>
                            </div>
                        </div>

                        <div class="h-6 w-px bg-blue-600/50 mx-2"></div>

                        <div class="relative group-container">
                            <button onclick="handleDropdown(this)" class="flex items-center gap-2 pl-2 pr-1 py-1 bg-blue-900/50 hover:bg-blue-900 rounded-full transition border border-blue-700">
                                <span class="text-xs font-semibold ml-2"><?= explode(' ', $data_nav['nama_pengguna'])[0] ?></span>
                                <img src="<?= $base_url ?>asset/gambar/guru/<?= $data_nav['foto_profil'] ?: 'default.png' ?>" class="w-8 h-8 rounded-full object-cover border border-white/20">
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 w-48 bg-white text-slate-800 rounded-xl shadow-xl border border-slate-100 z-50 overflow-hidden">
                                <div class="px-4 py-3 bg-slate-50 border-b border-slate-100">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Akun Petugas</p>
                                    <p class="text-sm font-bold truncate"><?= $data_nav['nama_pengguna'] ?></p>
                                </div>
                                <a href="<?= $base_url ?>modul/dashboard/edit.php" class="block px-4 py-2.5 hover:bg-slate-50 text-sm">⚙️ Edit Profil</a>
                                <a href="<?= $base_url ?>modul/auth/logout.php" class="block px-4 py-2.5 hover:bg-red-50 text-red-600 text-sm font-bold border-t border-slate-100">🚪 Keluar</a>
                            </div>
                        </div>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <script>
        function handleDropdown(btn) {
            const menu = btn.nextElementSibling;
            document.querySelectorAll('.dropdown-menu').forEach(m => {
                if (m !== menu) m.classList.remove('dropdown-active');
            });
            if (menu) menu.classList.toggle('dropdown-active');
        }

        window.addEventListener('click', function(e) {
            if (!e.target.closest('.group-container')) {
                document.querySelectorAll('.dropdown-menu').forEach(m => {
                    m.classList.remove('dropdown-active');
                });
            }
        });
    </script>

    <div class="flex-grow">