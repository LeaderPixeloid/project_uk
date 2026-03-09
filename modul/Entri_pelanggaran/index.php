<?php
session_start();
require_once '../config/database.php';

// Proteksi Admin
if (!isset($_SESSION['login_admin'])) {
    header("Location: ../../login.php");
    exit;
}

// Ambil data siswa
$siswa = mysqli_query($conn, "SELECT nis, nama_siswa FROM siswa ORDER BY nama_siswa ASC");

// Ambil data jenis pelanggaran
$jenis_p = mysqli_query($conn, "SELECT id_jenis_pelanggaran, jenis, poin FROM jenis_pelanggaran ORDER BY jenis ASC");

$title = "Entri Pelanggaran";
require_once '../../asset/layout/header.php';
?>

<div class="max-w-2xl mx-auto px-4 py-12">
    <a href="../dashboard/index.php" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-blue-600 mb-6 transition">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Kembali ke Dashboard
    </a>

    <div class="bg-white rounded-3xl shadow-2xl shadow-slate-200 border border-slate-100 overflow-hidden">
        <div class="bg-slate-900 p-8 text-white relative overflow-hidden">
            <div class="relative z-10">
                <h2 class="text-2xl font-bold flex items-center gap-3">
                    <span class="p-2 bg-rose-500 rounded-lg text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </span>
                    Catat Pelanggaran Baru
                </h2>
                <p class="text-slate-400 text-sm mt-2 ml-11">Pastikan data yang dimasukkan sudah sesuai dengan kejadian di lapangan.</p>
            </div>
            <div class="absolute -right-10 -bottom-10 opacity-10 uppercase font-black text-8xl italic">BK</div>
        </div>

        <form action="proses.php" method="POST" class="p-8 space-y-6">

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 ml-1">Identitas Siswa</label>
                <div class="relative group">
                    <select name="nis" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none transition-all appearance-none font-medium text-slate-700 cursor-pointer">
                        <option value="">-- Pilih Nama atau NIS --</option>
                        <?php while ($s = mysqli_fetch_assoc($siswa)): ?>
                            <option value="<?= $s['nis'] ?>"><?= $s['nis'] ?> - <?= htmlspecialchars($s['nama_siswa']) ?></option>
                        <?php endwhile; ?>
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 group-hover:text-blue-500 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 ml-1">Kategori Pelanggaran</label>
                <div class="relative group">
                    <select name="id_jenis_pelanggaran" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-rose-500 focus:bg-white outline-none transition-all appearance-none font-medium text-slate-700 cursor-pointer">
                        <option value="">-- Pilih Jenis Pelanggaran --</option>
                        <?php while ($jp = mysqli_fetch_assoc($jenis_p)): ?>
                            <option value="<?= $jp['id_jenis_pelanggaran'] ?>">
                                <?= htmlspecialchars($jp['jenis']) ?> [<?= $jp['poin'] ?> Poin]
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 group-hover:text-rose-500 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 ml-1">Kronologi Kejadian</label>
                <textarea name="keterangan" rows="4" placeholder="Jelaskan detail waktu, lokasi, dan saksi..."
                    class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none transition-all font-medium text-slate-700"></textarea>
            </div>

            <div class="pt-4">
                <button type="submit" name="submit" onclick="return confirm('Konfirmasi: Catat pelanggaran ini ke dalam kartu skor siswa?')" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-5 rounded-2xl shadow-xl shadow-rose-200 transition-all active:scale-[0.98] flex items-center justify-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    PROSES & SIMPAN DATA
                </button>
                <p class="text-center text-[10px] text-slate-400 mt-4 uppercase tracking-[0.2em]">Data akan langsung masuk ke rekapitulasi poin</p>
            </div>

        </form>
    </div>
</div>

<?php require '../../asset/layout/footer.php'; ?>