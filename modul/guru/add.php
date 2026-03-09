<?php
session_start();
require '../config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/header.php';

if (!isset($_SESSION['login_admin'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Auto generate kode guru
$last = mysqli_fetch_assoc(mysqli_query($conn, "SELECT kode_guru FROM guru ORDER BY kode_guru DESC LIMIT 1"));
if ($last) {
    $lastNumber = (int) substr($last['kode_guru'], -3);
    $newNumber = $lastNumber + 1;
} else {
    $newNumber = 1;
}
$kode_guru = "0021." . str_pad($newNumber, 3, "0", STR_PAD_LEFT);

// Ambil role & jabatan unik dari DB untuk tambahan opsi jika ada
$role_query = mysqli_query($conn, "SELECT DISTINCT role FROM guru WHERE role NOT IN ('Admin', 'Guru BK') AND role != ''");
$jabatan_query = mysqli_query($conn, "SELECT DISTINCT jabatan FROM guru WHERE jabatan NOT IN ('Guru BK') AND jabatan != ''");

if (isset($_POST['simpan'])) {
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);

    // Password tidak muncul di form, tapi diambil dari input hidden
    $password = password_hash($_POST['password_hidden'], PASSWORD_DEFAULT);

    $role     = $_POST['role'];
    $jabatan  = $_POST['jabatan'];
    $telp     = mysqli_real_escape_string($conn, $_POST['telp']);

    mysqli_query($conn, "INSERT INTO guru (kode_guru, nama_pengguna, role, username, password, aktif, jabatan, telp) 
                         VALUES ('$kode_guru', '$nama', '$role', '$username', '$password', 'Y', '$jabatan', '$telp')");

    header("Location: index.php");
    exit;
}
?>

<div class="max-w-3xl mx-auto px-4 py-12">
    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">

        <div class="bg-indigo-900 p-8 text-white relative overflow-hidden">
            <div class="relative z-10">
                <h2 class="text-2xl font-bold">Tambah Personel Sekolah</h2>
                <p class="text-indigo-200 text-sm mt-1">Sistem akan otomatis mengatur password default: <span class="font-mono text-white underline">Guru12345*!</span></p>
            </div>
            <div class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 bg-white/5 rounded-full"></div>
        </div>

        <form method="POST" class="p-8 space-y-6">
            <input type="hidden" name="password_hidden" value="Guru12345*!">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">ID Pegawai</label>
                    <input value="<?= $kode_guru ?>" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-400 font-mono text-sm cursor-not-allowed" readonly>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">Nama Lengkap</label>
                    <input name="nama" placeholder="Contoh: Budi Santoso, S.Pd" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none transition-all" required>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">Username Login</label>
                    <input name="username" placeholder="Gunakan NIP atau nama tanpa spasi" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none transition-all" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">Hak Akses (Role)</label>
                    <select name="role" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none bg-white" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="Admin">Admin Utama</option>
                        <option value="Guru BK">Guru BK (Bimbingan Konseling)</option>
                        <?php while ($r = mysqli_fetch_assoc($role_query)): ?>
                            <option value="<?= $r['role'] ?>"><?= $r['role'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">Jabatan</label>
                    <select name="jabatan" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none bg-white" required>
                        <option value="">-- Pilih Jabatan --</option>
                        <option value="Guru BK">Guru BK</option>
                        <option value="Kepala Sekolah">Kepala Sekolah</option>
                        <option value="Wali Kelas">Wali Kelas</option>
                        <?php while ($j = mysqli_fetch_assoc($jabatan_query)): ?>
                            <option value="<?= $j['jabatan'] ?>"><?= $j['jabatan'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">Nomor WhatsApp Aktif</label>
                    <input name="telp" placeholder="08xxxxxxxxxx" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                </div>
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-slate-50">
                <a href="index.php" class="text-sm font-bold text-slate-400 hover:text-slate-600 transition">
                    ← Kembali ke List
                </a>
                <button name="simpan" class="bg-indigo-600 text-white px-10 py-3 rounded-xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all active:scale-95">
                    Simpan Data Guru
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/footer.php'; ?>