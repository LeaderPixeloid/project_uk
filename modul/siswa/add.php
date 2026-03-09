<?php
session_start();
require '../config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/header.php';

if (isset($_POST['simpan'])) {
    // Sanitasi Input Dasar
    $nis = mysqli_real_escape_string($conn, $_POST['nis']);
    $nama_siswa = mysqli_real_escape_string($conn, $_POST['nama_siswa']);
    $jk = $_POST['jenis_kelamin'];
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $id_kelas = $_POST['id_kelas'];

    // Data Ortu & Wali
    $ayah = mysqli_real_escape_string($conn, $_POST['ayah']);
    $ibu  = mysqli_real_escape_string($conn, $_POST['ibu']);
    $wali = mysqli_real_escape_string($conn, $_POST['wali']);
    $no_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);

    $id_ortu = "NULL";

    // 1. SIMPAN KE TABEL ORTU_WALI DULU
    if ($ayah != "" || $ibu != "" || $wali != "") {
        $query_ortu = "INSERT INTO ortu_wali (ayah, ibu, wali, no_telp) 
                       VALUES ('$ayah', '$ibu', '$wali', '$no_telp')";
        if (mysqli_query($conn, $query_ortu)) {
            $id_ortu = mysqli_insert_id($conn);
        }
    }

    // 2. BARU SIMPAN KE SISWA
    $query_siswa = "INSERT INTO siswa (nis, nama_siswa, jenis_kelamin, alamat, id_kelas, id_ortu_wali)
                    VALUES ('$nis', '$nama_siswa', '$jk', '$alamat', '$id_kelas', $id_ortu)";

    if (mysqli_query($conn, $query_siswa)) {
        echo "<script>alert('Data siswa berhasil ditambahkan!'); window.location='index.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

$title = "Tambah Siswa Baru";
?>

<div class="max-w-4xl mx-auto px-4 py-10">
    <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">

        <div class="bg-gradient-to-r from-blue-700 to-indigo-800 p-8 text-white">
            <h2 class="text-2xl font-bold">Registrasi Siswa Baru</h2>
            <p class="text-blue-100 text-sm opacity-80">Lengkapi formulir di bawah untuk mendaftarkan siswa dan data wali.</p>
        </div>

        <form method="POST" class="p-8 space-y-8">

            <div class="space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                    <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">1</span>
                    <h3 class="font-bold text-slate-700 uppercase tracking-wider text-sm">Informasi Dasar Siswa</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1 ml-1">Nomor Induk Siswa (NIS)</label>
                        <input type="number"  name="nis" placeholder="Contoh: 212210001" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1 ml-1">Nama Lengkap</label>
                        <input name="nama_siswa" placeholder="Nama sesuai ijazah" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1 ml-1">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white outline-none" required>
                            <option value="">-- Pilih --</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1 ml-1">Kelas / Rombel</label>
                        <select name="id_kelas" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white outline-none" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php
                            $kelas = mysqli_query($conn, "SELECT * FROM kelas JOIN tingkat USING(id_tingkat) JOIN program_keahlian USING(id_program_keahlian)");
                            while ($k = mysqli_fetch_assoc($kelas)): ?>
                                <option value="<?= $k['id_kelas'] ?>">
                                    <?= $k['tingkat'] . " " . $k['program_keahlian'] . " " . $k['rombel'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 mb-1 ml-1">Alamat Tinggal</label>
                        <textarea name="alamat" rows="2" placeholder="Alamat lengkap..." class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all" required></textarea>
                    </div>
                </div>
            </div>

            <div class="space-y-4 pt-4">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                    <span class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center font-bold text-sm">2</span>
                    <h3 class="font-bold text-slate-700 uppercase tracking-wider text-sm">Data Orang Tua / Wali</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1 ml-1">Nama Ayah</label>
                        <input name="ayah" placeholder="Nama ayah kandung" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-green-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1 ml-1">Nama Ibu</label>
                        <input name="ibu" placeholder="Nama ibu kandung" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-green-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1 ml-1">Nama Wali (Opsional)</label>
                        <input name="wali" placeholder="Isi jika tinggal dengan wali" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-green-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1 ml-1">No. Telp / WA (Penting)</label>
                        <input name="no_telp" placeholder="Contoh: 08123456789" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-green-500 outline-none transition-all" required>
                    </div>
                </div>
            </div>

            <div class="pt-8 flex items-center justify-between border-t border-slate-50">
                <a href="index.php" class="text-sm font-bold text-slate-400 hover:text-slate-600 transition">← Batalkan</a>
                <button name="simpan" class="bg-blue-600 text-white px-10 py-4 rounded-2xl font-bold hover:bg-blue-700 shadow-xl shadow-blue-200 transition-all active:scale-95">
                    Simpan Data Siswa
                </button>
            </div>

        </form>
    </div>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/footer.php'; ?>