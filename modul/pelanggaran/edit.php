<?php
session_start();
require '../config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/header.php';

$id = $_GET['id'];
$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM jenis_pelanggaran WHERE id_jenis_pelanggaran = '$id'"));

if (isset($_POST['update'])) {
    $jenis = mysqli_real_escape_string($conn, $_POST['jenis']);
    $poin  = (int) $_POST['poin'];

    mysqli_query($conn, "UPDATE jenis_pelanggaran SET jenis='$jenis', poin='$poin' WHERE id_jenis_pelanggaran='$id'");
    header("Location: index.php");
    exit;
}
?>

<div class="max-w-md mx-auto px-4 py-20">
    <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden">
        <div class="p-8 bg-blue-600 text-white">
            <h2 class="text-xl font-bold">Edit Kategori</h2>
            <p class="text-blue-100 text-xs mt-1">Perbarui deskripsi atau bobot poin pelanggaran.</p>
        </div>

        <form method="POST" class="p-8 space-y-6">
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Nama Pelanggaran</label>
                <input type="text" name="jenis" value="<?= htmlspecialchars($row['jenis']) ?>"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all font-medium" required>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Bobot Poin</label>
                <input type="number" name="poin" value="<?= $row['poin'] ?>"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold text-blue-600" required>
            </div>

            <div class="pt-4 flex flex-col gap-3">
                <button name="update" class="w-full bg-blue-600 text-white py-4 rounded-xl font-bold hover:bg-blue-700 shadow-xl transition-all active:scale-95">
                    Perbarui Data
                </button>
                <a href="index.php" class="text-center text-sm font-bold text-slate-400 py-2 hover:text-slate-600 transition">
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<?php require $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/footer.php'; ?>