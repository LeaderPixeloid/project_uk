<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/project_uk');
require_once ROOTPATH . '/asset/layout/header.php';
include ROOTPATH . "/modul/config/database.php";

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM siswa WHERE nis='$id'"));

if (!$data) {
    header("Location: index.php");
    exit;
}

$kelas_query = mysqli_query($conn, "
    SELECT kelas.id_kelas, tingkat.tingkat, program_keahlian.program_keahlian, kelas.rombel
    FROM kelas
    JOIN tingkat ON kelas.id_tingkat = tingkat.id_tingkat
    JOIN program_keahlian ON kelas.id_program_keahlian = program_keahlian.id_program_keahlian
    ORDER BY tingkat.id_tingkat, rombel
");

if (isset($_POST['simpan'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $id_kelas = $_POST['id_kelas'];
    $jk = $_POST['jk'];

    mysqli_query($conn, "UPDATE siswa SET nama_siswa='$nama', id_kelas='$id_kelas', jenis_kelamin='$jk' WHERE nis='$id'");
    echo "<script>window.location='index.php';</script>";
    exit;
}
?>

<div class="max-w-xl mx-auto px-4 py-12">
    <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
        <div class="p-8 bg-slate-900 text-white flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold">Edit Profil Siswa</h2>
                <p class="text-slate-400 text-xs mt-1">Lakukan pembaruan data akademik siswa.</p>
            </div>
            <div class="text-right">
                <span class="text-[10px] uppercase font-bold text-slate-500 block">NIS</span>
                <span class="font-mono text-lg"><?= $data['nis'] ?></span>
            </div>
        </div>

        <form method="POST" class="p-8 space-y-5">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Lengkap Siswa</label>
                <input name="nama" value="<?= $data['nama_siswa'] ?>" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all font-medium" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Jenis Kelamin</label>
                    <select name="jk" class="w-full px-4 py-3 rounded-xl border border-slate-200 outline-none bg-white">
                        <option value="L" <?= $data['jenis_kelamin'] == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                        <option value="P" <?= $data['jenis_kelamin'] == 'P' ? 'selected' : '' ?>>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Penempatan Kelas</label>
                    <select name="id_kelas" class="w-full px-4 py-3 rounded-xl border border-slate-200 outline-none bg-white">
                        <?php while ($k = mysqli_fetch_assoc($kelas_query)): ?>
                            <option value="<?= $k['id_kelas'] ?>" <?= $k['id_kelas'] == $data['id_kelas'] ? 'selected' : '' ?>>
                                <?= $k['tingkat'] . ' ' . $k['program_keahlian'] . ' ' . $k['rombel'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div class="pt-6 flex items-center justify-between">
                <a href="index.php" class="text-sm font-bold text-slate-400 hover:text-slate-600 transition">← Kembali</a>
                <button name="simpan" class="bg-blue-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-100 transition-all active:scale-95">
                    Update Data
                </button>
            </div>
        </form>
    </div>
</div>

<?php require ROOTPATH . '/asset/layout/footer.php'; ?>