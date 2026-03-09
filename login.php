<?php
session_start();
require_once __DIR__ . '/modul/config/database.php';

$error = "";

if (isset($_POST['login'])) {
    // Menggunakan real_escape_string untuk keamanan tambahan (standar tugas sekolah)
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM guru WHERE username='$username'");
    $user = mysqli_fetch_assoc($query);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['login_admin'] = true;
        $_SESSION['kode_guru'] = $user['kode_guru'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        header("Location: modul/dashboard/index.php");
        exit;
    } else {
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sistem Pelanggaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-100 flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="bg-blue-600 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-blue-200">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-800 uppercase tracking-tight">SPPS Siswa</h2>
            <p class="text-slate-500 text-sm mt-1">Sistem Poin Pelanggaran Siswa</p>
        </div>

        <div class="bg-white p-8 rounded-2xl shadow-xl shadow-slate-200 border border-slate-200">
            <div class="mb-6">
                <h3 class="text-xl font-bold text-slate-800">Login</h3>
                <p class="text-sm text-slate-400">Silakan masukkan akun Admin atau Guru.</p>
            </div>

            <?php if ($error): ?>
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-lg text-sm flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Username</label>
                    <input type="text" name="username" placeholder="Masukkan username"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all placeholder:text-slate-300"
                        required autofocus>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                    <input type="password" name="password" placeholder="••••••••"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all placeholder:text-slate-300"
                        required>
                </div>

                <button name="login"
                    class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-100 transition-all active:scale-[0.98]">
                    Masuk Sekarang
                </button>
            </form>

            <div class="relative my-8">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-100"></div>
                </div>
                <div class="relative flex justify-center text-sm uppercase">
                    <span class="bg-white px-2 text-slate-400">Atau</span>
                </div>
            </div>

            <div class="text-center">
                <p class="text-sm text-slate-500 mb-4">Ingin cek poin pelanggaran Anda?</p>
                <a href="modul/auth_siswa/login.php"
                    class="inline-block w-full border-2 border-slate-100 text-slate-600 py-3 rounded-xl font-semibold hover:bg-slate-50 transition-all text-sm">
                    Masuk Sebagai Siswa
                </a>
            </div>
        </div>

        <p class="text-center mt-8 text-slate-400 text-xs tracking-widest uppercase">
            &copy; 2026 SMK TI BALI GLOBAL DENPASAR Project
        </p>
    </div>

</body>

</html>