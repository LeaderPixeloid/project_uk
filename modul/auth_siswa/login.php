<?php
session_start();
require '../config/database.php';

$error = "";

if (isset($_POST['login'])) {
    $nis = mysqli_real_escape_string($conn, $_POST['nis']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM siswa WHERE nis='$nis'");
    $siswa = mysqli_fetch_assoc($query);

    if ($siswa && password_verify($password, $siswa['password'])) {
        $_SESSION['login_siswa'] = true;
        $_SESSION['nis'] = $siswa['nis'];
        $_SESSION['nama_siswa'] = $siswa['nama_siswa'];

        header("Location: ../dashboard_siswa/index.php");
        exit;
    } else {
        $error = "NIS atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Siswa | Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="bg-indigo-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 shadow-xl shadow-indigo-100">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                </svg>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Portal Siswa</h2>
            <p class="text-slate-500 text-sm mt-1">Cek riwayat dan poin pelanggaran Anda</p>
        </div>

        <div class="bg-white p-8 rounded-3xl shadow-2xl shadow-slate-200 border border-slate-100">
            <div class="mb-6">
                <h3 class="text-xl font-bold text-slate-800">Masuk Ke Sistem</h3>
                <p class="text-sm text-slate-400">Gunakan NIS dan Password yang terdaftar.</p>
            </div>

            <?php if ($error): ?>
                <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-700 p-4 mb-6 rounded-r-lg text-sm flex items-center gap-3 animate-pulse">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">NIS (Nomor Induk Siswa)</label>
                    <input type="number" name="nis" placeholder="Contoh: 21221001"
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all placeholder:text-slate-300"
                        required autofocus>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                    <input type="password" name="password" placeholder="••••••••"
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all placeholder:text-slate-300"
                        required>
                </div>

                <button name="login"
                    class="w-full bg-indigo-600 text-white py-3 rounded-2xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all active:scale-[0.98]">
                    Masuk Sekarang
                </button>
            </form>

            <div class="relative my-8 text-center">
                <span class="bg-white px-4 text-xs text-slate-400 uppercase tracking-widest relative z-10">Bukan Siswa?</span>
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-100"></div>
                </div>
            </div>

            <div class="text-center">
                <a href="../../login.php"
                    class="inline-flex items-center justify-center gap-2 w-full text-slate-600 font-semibold hover:text-indigo-600 transition-all text-sm group">
                    <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Login Guru/BK
                </a>
            </div>
        </div>

        <p class="text-center mt-8 text-slate-400 text-[10px] tracking-widest uppercase">
            &copy; 2026 SMK TI BALI GLOBAL DENPASAR
        </p>
    </div>

</body>

</html>