<?php
session_start();
// require '../config/auth_check.php';
if (!isset($_SESSION['login_admin'])) {
    header("Location: ../auth/login.php");
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
                <div class="bg-white p-4 rounded shadow">✏️ Input Pelanggaran</div>
                <a href="../surat/pindah.php"
                    class="bg-white p-4 rounded shadow hover:bg-gray-50 block">
                    📄 Cetak Surat
                </a>


            <?php elseif ($_SESSION['role'] == 'manajemen'): ?>
                <a href="../auth/register.php"
                    class="bg-white p-4 rounded shadow hover:bg-gray-50">
                    ➕ Register User
                </a>
                <div class="bg-white p-4 rounded shadow">📊 Laporan</div>
                <div class="bg-white p-4 rounded shadow">✅ Validasi Surat</div>
            <?php endif; ?>

        </div>
    </div>

</body>

</html>