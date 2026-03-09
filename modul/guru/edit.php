<?php
session_start();
require '../config/database.php';

if (!isset($_SESSION['login_admin'])) {
    header("Location: ../auth/login.php");
    exit;
}

$kode = $_GET['kode'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM guru WHERE kode_guru='$kode'"));

if (!$data) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
    $telp = mysqli_real_escape_string($conn, $_POST['telp']);
    $aktif = $_POST['aktif'];

    mysqli_query($conn, "UPDATE guru SET nama_pengguna='$nama', jabatan='$jabatan', telp='$telp', aktif='$aktif' WHERE kode_guru='$kode'");
    header("Location: index.php");
    exit;
}

$title = "Edit Data Guru";
require_once $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/header.php';
?>

<div class="max-w-2xl mx-auto px-4 py-12">
    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
        <div class="bg-slate-800 p-8 text-white">
            <h2 class="text-2xl font-bold">Edit Informasi Guru</h2>
            <p class="text-slate-400 text-sm mt-1">Perbarui data profil dan status kepegawaian.</p>
        </div>

        <form method="POST" class="p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">ID / Kode Guru</label>
                    <input value="<?= $data['kode_guru'] ?>" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-400 font-mono text-sm cursor-not-allowed" readonly>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Lengkap</label>
                    <input name="nama" value="<?= $data['nama_pengguna'] ?>" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Jabatan</label>
                    <input name="jabatan" value="<?= $data['jabatan'] ?>" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">No. Telepon</label>
                    <input name="telp" value="<?= $data['telp'] ?>" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Status Akun</label>
                    <select name="aktif" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none bg-white">
                        <option value="Y" <?= $data['aktif'] == 'Y' ? 'selected' : '' ?>>✅ Aktif (Bisa Login)</option>
                        <option value="N" <?= $data['aktif'] == 'N' ? 'selected' : '' ?>>🚫 Non-Aktif (Blokir Akses)</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-slate-50">
                <a href="index.php" class="text-sm font-semibold text-slate-400 hover:text-slate-600 transition">
                    ← Batalkan
                </a>
                <button name="update" class="bg-blue-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-100 transition-all active:scale-95">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/footer.php'; ?>