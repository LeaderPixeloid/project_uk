<?php
session_start();
require '../config/database.php';

if (!isset($_SESSION['login_siswa'])) {
    header("Location: ../auth_siswa/login.php");
    exit;
}

$nis = $_SESSION['nis'];
$error = "";
$success = "";

// Ambil data siswa untuk foto profil saat ini
$siswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama_siswa, foto_profil FROM siswa WHERE nis = '$nis'"));

if (isset($_POST['update_profil'])) {
    $password_baru = $_POST['password_baru'];
    $konfirmasi_password = $_POST['konfirmasi_password'];

    // 1. Logika Update Password
    if (!empty($password_baru)) {
        if ($password_baru === $konfirmasi_password) {
            $hash_password = password_hash($password_baru, PASSWORD_DEFAULT);
            mysqli_query($conn, "UPDATE siswa SET password = '$hash_password' WHERE nis = '$nis'");
            $success = "Profil berhasil diperbarui!";
        } else {
            $error = "Konfirmasi password tidak cocok!";
        }
    }

    // 2. Logika Update Foto Profil
    if ($_FILES['foto']['error'] === 0) {
        $nama_file = $_FILES['foto']['name'];
        $tmp_name = $_FILES['foto']['tmp_name'];

        // Ambil ekstensi file
        $ekstensi = pathinfo($nama_file, PATHINFO_EXTENSION);
        $nama_file_baru = $nis . "_" . time() . "." . $ekstensi;

        // Pindahkan file ke folder (pastikan folder 'asset/img/siswa' sudah ada)
        if (move_uploaded_file($tmp_name, "../../asset/img/siswa/" . $nama_file_baru)) {
            // Hapus foto lama jika bukan foto default
            if ($siswa['foto_profil'] && $siswa['foto_profil'] != 'default.png') {
                unlink("../../asset/img/siswa/" . $siswa['foto_profil']);
            }
            mysqli_query($conn, "UPDATE siswa SET foto_profil = '$nama_file_baru' WHERE nis = '$nis'");
            $success = "Profil berhasil diperbarui!";
            // Refresh data siswa
            $siswa['foto_profil'] = $nama_file_baru;
        } else {
            $error = "Gagal mengunggah gambar!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <title>Edit Profil | SPPS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-50 min-h-screen flex flex-col">

    <nav class="bg-indigo-700 text-white p-4 shadow-md">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <span class="font-bold text-lg">Edit Profil</span>
            <a href="index.php" class="text-sm bg-indigo-800 px-4 py-2 rounded-lg hover:bg-indigo-900 transition">Kembali ke Dashboard</a>
        </div>
    </nav>

    <main class="max-w-xl mx-auto w-full px-6 py-12 flex-grow">
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8">
            <h2 class="text-2xl font-bold text-slate-800 mb-2 text-center">Update Profil</h2>
            <p class="text-slate-500 text-center mb-8 text-sm italic">Ubah password atau foto profil Anda secara berkala.</p>

            <?php if ($error): ?>
                <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm border-l-4 border-red-500 italic font-medium">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="bg-green-50 text-green-600 p-4 rounded-xl mb-6 text-sm border-l-4 border-green-500 italic font-medium">
                    <?= $success ?>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <div class="flex flex-col items-center gap-4">
                    <img src="../../asset/gambar/siswa/<?= $siswa['foto_profil'] ?: 'default.png' ?>"
                        class="w-24 h-24 rounded-full object-cover border-4 border-indigo-50 shadow-md">
                    <div class="w-full">
                        <label class="block text-sm font-semibold text-slate-700 mb-2 text-center">Ganti Foto Profil</label>
                        <input type="file" name="foto" class="text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 w-full">
                    </div>
                </div>

                <hr class="border-slate-100">

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Password Baru</label>
                    <input type="password" name="password_baru" placeholder="Kosongkan jika tidak ingin diubah"
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Konfirmasi Password Baru</label>
                    <input type="password" name="confirm_password" placeholder="Ulangi password baru"
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none text-sm">
                </div>

                <button name="update_profil" class="w-full bg-indigo-600 text-white py-3 rounded-2xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all active:scale-95">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </main>

    <?php require '../../asset/layout/footer.php'; ?>
</body>

</html>