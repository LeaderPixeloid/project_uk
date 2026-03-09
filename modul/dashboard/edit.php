<?php
session_start();
require '../config/database.php';

// Guard Admin/Guru
if (!isset($_SESSION['login_admin'])) {
    header("Location: ../../login.php");
    exit;
}

$kode_guru = $_SESSION['kode_guru'];
$error = "";
$success = "";

// Ambil data guru untuk foto profil saat ini
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama_pengguna, foto_profil FROM guru WHERE kode_guru = '$kode_guru'"));

if (isset($_POST['update_profil'])) {
    $password_baru = $_POST['password_baru'];
    $konfirmasi_password = $_POST['confirm_password'];

    // 1. Logika Update Password
    if (!empty($password_baru)) {
        if ($password_baru === $konfirmasi_password) {
            $hash_password = password_hash($password_baru, PASSWORD_DEFAULT);
            mysqli_query($conn, "UPDATE guru SET password = '$hash_password' WHERE kode_guru = '$kode_guru'");
            $success = "Password berhasil diperbarui!";
        } else {
            $error = "Konfirmasi password tidak cocok!";
        }
    }

    // 2. Logika Update Foto Profil
    if ($_FILES['foto']['error'] === 0) {
        $nama_file = $_FILES['foto']['name'];
        $tmp_name = $_FILES['foto']['tmp_name'];

        $ekstensi = pathinfo($nama_file, PATHINFO_EXTENSION);
        $nama_file_baru = "guru_" . $kode_guru . "_" . time() . "." . $ekstensi;

        // Pastikan folder 'asset/img/guru' sudah ada
        if (move_uploaded_file($tmp_name, "../../asset/gambar/guru/" . $nama_file_baru)) {
            // Hapus foto lama jika ada dan bukan default
            if ($user['foto_profil'] && $user['foto_profil'] != 'default.png') {
                @unlink("../../asset/gambar/guru/" . $user['foto_profil']);
            }
            mysqli_query($conn, "UPDATE guru SET foto_profil = '$nama_file_baru' WHERE kode_guru = '$kode_guru'");
            $success = "Profil berhasil diperbarui!";
            $user['foto_profil'] = $nama_file_baru; // Update tampilan instan
        } else {
            $error = "Gagal mengunggah foto!";
        }
    }
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/header.php';
?>

<div class="max-w-4xl mx-auto px-4 py-10">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Pengaturan Profil</h2>
                <p class="text-sm text-slate-500">Kelola informasi akun dan keamanan Anda.</p>
            </div>
            <a href="index.php" class="text-sm text-slate-600 hover:text-blue-600 font-medium">← Kembali</a>
        </div>

        <div class="p-8">
            <?php if ($error): ?>
                <div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6 text-sm border-l-4 border-red-500">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="bg-green-50 text-green-700 p-4 rounded-lg mb-6 text-sm border-l-4 border-green-500">
                    <?= $success ?>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center space-y-4">
                    <div class="relative inline-block">
                        <img src="../../asset/gambar/guru/<?= $user['foto_profil'] ?: 'default.png' ?>"
                            class="w-32 h-32 rounded-2xl object-cover border-4 border-slate-50 shadow-sm mx-auto">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Foto Profil</label>
                        <input type="file" name="foto" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    </div>
                </div>

                <div class="md:col-span-2 space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Pengguna</label>
                        <input type="text" value="<?= $user['nama_pengguna'] ?>" disabled
                            class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-500 text-sm cursor-not-allowed">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Password Baru</label>
                            <input type="password" name="password_baru" placeholder="••••••••"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Ulangi Password</label>
                            <input type="password" name="confirm_password" placeholder="••••••••"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                        </div>
                    </div>

                    <div class="pt-4">
                        <button name="update_profil" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-100 transition-all active:scale-[0.98]">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/footer.php'; ?>