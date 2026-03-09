<?php
session_start();
require '../config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/header.php';

if (!isset($_SESSION['login_admin'])) {
    header("Location: ../auth/login.php");
    exit;
}

$error = "";
if (isset($_POST['simpan'])) {
    $jenis = mysqli_real_escape_string($conn, trim($_POST['jenis']));
    $poin  = (int) $_POST['poin'];

    if ($jenis == "" || $poin <= 0) {
        $error = "Data tidak valid. Silakan periksa kembali.";
    } else {
        mysqli_query($conn, "INSERT INTO jenis_pelanggaran (jenis, poin) VALUES ('$jenis', '$poin')");
        header("Location: index.php");
        exit;
    }
}
?>

<div class="max-w-md mx-auto px-4 py-20">
    <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden">
        <div class="p-8 bg-slate-900 text-white">
            <h2 class="text-xl font-bold">Kategori Baru</h2>
            <p class="text-slate-400 text-xs mt-1">Tambahkan jenis pelanggaran baru ke sistem.</p>
        </div>

        <?php if ($error): ?>
            <div class="mx-8 mt-6 p-4 bg-rose-50 border border-rose-100 text-rose-600 text-xs rounded-xl font-bold">
                ⚠️ <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="p-8 space-y-6">
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Nama Pelanggaran</label>
                <input type="text" name="jenis" placeholder="Contoh: Tidak Memakai Atribut"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-rose-500 outline-none transition-all font-medium" required autofocus>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Bobot Poin</label>
                <input type="number" name="poin" placeholder="10 - 100"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-rose-500 outline-none transition-all font-bold text-rose-600" min="1" required>
                <p class="text-[10px] text-slate-400 mt-2 italic">*Poin akan diakumulasikan pada kartu skor siswa.</p>
            </div>

            <div class="pt-4 flex flex-col gap-3">
                <button name="simpan" class="w-full bg-slate-900 text-white py-4 rounded-xl font-bold hover:bg-slate-800 shadow-xl transition-all active:scale-95">
                    Simpan Kategori
                </button>
                <a href="index.php" class="text-center text-sm font-bold text-slate-400 py-2 hover:text-slate-600 transition">
                    Batalkan
                </a>
            </div>
        </form>
    </div>
</div>

<?php require $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/footer.php'; ?>