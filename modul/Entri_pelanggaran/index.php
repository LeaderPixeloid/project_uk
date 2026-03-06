<?php
session_start();
require_once '../config/database.php';

// Proteksi Admin
if (!isset($_SESSION['login_admin'])) {
    header("Location: ../../login.php");
    exit;
}

// Ambil data siswa untuk dropdown NIS
$siswa = mysqli_query($conn, "SELECT nis, nama_siswa FROM siswa ORDER BY nama_siswa ASC");

// Ambil data jenis pelanggaran untuk dropdown
$jenis_p = mysqli_query($conn, "SELECT id_jenis_pelanggaran, jenis, poin FROM jenis_pelanggaran ORDER BY jenis ASC");

$title = "Entri Pelanggaran";
require_once '../../asset/layout/header.php';
?>

<div class="max-w-2xl mx-auto mt-10">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
        <div class="bg-blue-700 p-4">
            <h2 class="text-white font-bold text-lg flex items-center">
                <span class="mr-2">📝</span> Entri Pelanggaran Siswa
            </h2>
        </div>

        <form action="proses.php" method="POST" class="p-8 space-y-6">

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Siswa (NIS)</label>
                <select name="nis" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">-- Cari Nama atau NIS --</option>
                    <?php while ($s = mysqli_fetch_assoc($siswa)): ?>
                        <option value="<?= $s['nis'] ?>"><?= $s['nis'] ?> - <?= $s['nama_siswa'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Pelanggaran</label>
                <select name="id_jenis_pelanggaran" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">-- Pilih Jenis Pelanggaran --</option>
                    <?php while ($jp = mysqli_fetch_assoc($jenis_p)): ?>
                        <option value="<?= $jp['id_jenis_pelanggaran'] ?>">
                            <?= $jp['jenis'] ?> (<?= $jp['poin'] ?> Poin)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan Kejadian</label>
                <textarea name="keterangan" rows="4" placeholder="Contoh: Siswa melompati pagar sekolah pada jam istirahat..."
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
            </div>

            <div class="pt-4">
                <button type="submit" name="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-lg shadow-md transition-all transform hover:scale-[1.01]">
                    SIMPAN PELANGGARAN
                </button>
            </div>

        </form>
    </div>
</div>
<?php require '../../asset/layout/footer.php'; ?>