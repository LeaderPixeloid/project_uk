<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$base_url = "http://localhost/project_uk/";
?>

<!DOCTYPE html>
<html>

<head>
    <title><?= $title ?? "Sistem Pelanggaran Siswa"; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">

    <!-- NAVBAR -->
    <div class="bg-blue-700 text-white p-4 flex justify-between items-center no-print">

        <a href="<?= $base_url ?>modul/dashboard/index.php" class="font-semibold">
            Sistem Pelanggaran Siswa
        </a>

        <div class="space-x-4 flex items-center">

            <?php if (isset($_SESSION['login_admin'])): ?>

                <!-- Dropdown Data -->
                <div class="relative">
                    <a href="javascript:void(0)"
                        onclick="toggleDropdown(event)"
                        class="px-4 py-2 hover:bg-blue-600 rounded inline-block">
                        Data ▾
                    </a>

                    <div id="dropdownMenu"
                        class="absolute right-0 mt-2 w-52 bg-blue-600 text-white rounded shadow-lg 
                            opacity-0 scale-95 pointer-events-none 
                            transition-all duration-200 ease-out origin-top-right">

                        <a href="<?= $base_url ?>modul/guru/index.php"
                            class="block px-4 py-2 hover:bg-blue-700 border-b border-blue-500">
                            Data Guru
                        </a>

                        <a href="<?= $base_url ?>modul/siswa/index.php"
                            class="block px-4 py-2 hover:bg-blue-700 border-b border-blue-500">
                            Data Siswa
                        </a>

                        <a href="<?= $base_url ?>modul/pelanggaran/index.php"
                            class="block px-4 py-2 hover:bg-blue-700">
                            Data Jenis Pelanggaran
                        </a>

                        <a href="<?= $base_url ?>modul/kelas/index.php"
                            class="block px-4 py-2 hover:bg-blue-700">
                            Data Kelas
                        </a>
                    </div>
                </div>

                <a href="<?= $base_url ?>modul/auth/logout.php"
                    class="hover:underline text-red-300">
                    Logout
                </a>

            <?php endif; ?>

        </div>

    </div>

    <!-- CONTENT WRAPPER -->
    <div class="flex-grow p-6">