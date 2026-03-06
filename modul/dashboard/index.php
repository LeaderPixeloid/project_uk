<?php
session_start();
$base_url = "http://localhost/project_uk/";
if (!isset($_SESSION['login_admin'])) {
    header("Location: " . $base_url . "login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

    <div class="bg-blue-700 text-white p-4 flex justify-between items-center">
        <span class="font-semibold">Sistem Pelanggaran Siswa</span>
        <div class="space-x-4">
            <!-- <a href="../auth/switch_account.php"
                onclick="return confirm('Ganti akun sekarang?')"
                class="hover:underline">
                Switch Account
            </a> -->
            <a href="../auth/logout.php" class="hover:underline text-red-300">
                Logout
            </a>
        </div>

    </div>


    <div class="p-6">
        <h1 class="text-2xl font-bold mb-2">
            Halo, <?= $_SESSION['username']; ?>
        </h1>
        <p class="mb-6 text-gray-600">
            Role: <?= $_SESSION['role']; ?>
        </p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <?php if ($_SESSION['role'] == 'admin'): ?>
                <a href="../auth/register.php"
                    class="bg-white p-4 rounded shadow hover:bg-gray-50">
                    ➕ Register User
                </a>
                <a href="../siswa/index.php" class="bg-white p-4 rounded shadow">📁 Data Siswa</a>
                <div class="bg-white p-4 rounded shadow">⚠️ Jenis Pelanggaran</div>

            <?php elseif ($_SESSION['role'] == 'Guru'): ?>
                <a href="../auth/register.php"
                    class="bg-white p-4 rounded shadow hover:bg-gray-50">
                    ➕ Register User
                </a>
                <div class="relative">

                    <!-- CARD INPUT DATA -->
                    <a href="javascript:void(0)"
                        onclick="toggleDropdown(event , 'inputDropdown')"
                        class="bg-white p-4 rounded shadow hover:bg-gray-50 block cursor-pointer">
                        ✏️ Input Data
                    </a>

                    <!-- DROPDOWN -->
                    <div id="inputDropdown"
                        class="dropdown-menu absolute mt-2 w-56 bg-white rounded shadow-lg border
     opacity-0 scale-95 pointer-events-none
     transition-all duration-200 ease-out origin-top">

                        <a href="../guru/index.php"
                            class="block px-4 py-3 hover:bg-gray-100 border-b">
                            Data Guru
                        </a>

                        <a href="../siswa/index.php"
                            class="block px-4 py-3 hover:bg-gray-100 border-b">
                            Data Siswa
                        </a>

                        <a href="../pelanggaran/index.php"
                            class="block px-4 py-3 hover:bg-gray-100">
                            Data Jenis Pelanggaran
                        </a>

                        <a href="../kelas/index.php"
                            class="block px-4 py-3 hover:bg-gray-100">
                            Data Kelas
                        </a>


                    </div>

                </div>


                <div class="relative">

                    <!-- CARD INPUT DATA -->
                    <a href="javascript:void(0)"
                        onclick="toggleDropdown(event, 'suratDropdown')"
                        class="bg-white p-4 rounded shadow hover:bg-gray-50 block cursor-pointer">
                        📄 Cetak Surat
                    </a>

                    <!-- DROPDOWN -->
                    <div id="suratDropdown"
                        class="dropdown-menu absolute mt-2 w-56 bg-white rounded shadow-lg border
     opacity-0 scale-95 pointer-events-none
     transition-all duration-200 ease-out origin-top">

                        <a href="../surat/pernyataan_siswa/index.php"
                            class="block px-4 py-3 hover:bg-gray-100 border-b">
                            pernyataan siswa
                        </a>

                        <a href="../surat/panggilan_ortu/index.php"
                            class="block px-4 py-3 hover:bg-gray-100 border-b">
                            panggilan orang tua
                        </a>

                        <a href="../surat/perjanjian_ortu/index.php"
                            class="block px-4 py-3 hover:bg-gray-100">
                            perjanjian ortu
                        </a>
                        <a href="../surat/pindah_sekolah/index.php"
                            class="block px-4 py-3 hover:bg-gray-100">
                            pindah sekolah
                        </a>
                    </div>

                </div>
                <a href="../laporan_surat/index.php" class="bg-white p-4 rounded shadow">📊 Laporan</a>
                <a href="../Entri_pelanggaran/index.php" class="bg-white p-4 rounded shadow">✏️ Entri Pelanggaran</a>


            <?php elseif ($_SESSION['role'] == 'manajemen'): ?>
                <a href="../auth/register.php"
                    class="bg-white p-4 rounded shadow hover:bg-gray-50">
                    ➕ Register User
                </a>

                <div class="bg-white p-4 rounded shadow">✅ Validasi Surat</div>
            <?php endif; ?>

        </div>
    </div>
    <script>
        function toggleDropdown(e, id) {
            e.stopPropagation();

            // Tutup semua dropdown dulu
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.add("opacity-0", "scale-95", "pointer-events-none");
                menu.classList.remove("opacity-100", "scale-100");
            });

            const menu = document.getElementById(id);

            if (menu.classList.contains("pointer-events-none")) {
                menu.classList.remove("opacity-0", "scale-95", "pointer-events-none");
                menu.classList.add("opacity-100", "scale-100");
            } else {
                menu.classList.add("opacity-0", "scale-95", "pointer-events-none");
                menu.classList.remove("opacity-100", "scale-100");
            }
        }

        // Klik luar → tutup semua
        document.addEventListener("click", function() {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.add("opacity-0", "scale-95", "pointer-events-none");
                menu.classList.remove("opacity-100", "scale-100");
            });
        });
    </script>
</body>

</html>