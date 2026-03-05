<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/project_uk/modul/config/database.php';
require $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/header.php';

$siswa = null;
$error = "";

// Proses Cek NIS
if (isset($_POST['cek'])) {
    $nis = mysqli_real_escape_string($conn, $_POST['nis']);
    header("Location: index.php?nis=" . $nis);
    exit;
}

// Ambil data siswa jika NIS ada di URL
if (isset($_GET['nis'])) {
    $nis = mysqli_real_escape_string($conn, $_GET['nis']);
    $query = mysqli_query($conn, "
        SELECT siswa.*, ortu_wali.*
        FROM siswa
        JOIN ortu_wali USING(id_ortu_wali)
        WHERE siswa.nis='$nis'
    ");
    $siswa = mysqli_fetch_assoc($query);
    if (!$siswa) {
        $error = "NIS tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrasi Pindah Sekolah</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow-lg">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800 border-b-2 border-blue-500 pb-2">Administrasi Pindah Sekolah</h2>

        <form method="POST" class="flex flex-wrap gap-2 mb-8 max-w-2xl mx-auto justify-center">
            <input name="nis" placeholder="Masukkan NIS Siswa" class="flex-1 min-w-[200px] p-3 border rounded focus:ring-2 focus:ring-blue-500 outline-none" required value="<?= $_GET['nis'] ?? '' ?>">

            <button name="cek" class="bg-blue-600 text-white px-6 py-2 rounded font-bold hover:bg-blue-700 transition">Cek NIS</button>

            <a href="index.php" class="bg-yellow-500 text-white px-6 py-2 rounded font-bold hover:bg-yellow-600 transition">Reset</a>

            <a href="../../dashboard/index.php" class="bg-gray-600 text-white px-6 py-2 rounded font-bold hover:bg-gray-700 transition">Kembali</a>
        </form>

        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 text-center">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <?php if ($siswa): ?>
            <form action="cetak.php" method="POST" target="_blank">
                <input type="hidden" name="nis" value="<?= $siswa['nis'] ?>">

                <div class="bg-blue-50 p-6 rounded-lg mb-6 border border-blue-100">
                    <h3 class="font-bold text-blue-800 mb-4 border-b border-blue-200 pb-2 flex items-center">
                        <span class="mr-2">📄</span> Informasi Perpindahan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Nomor Urut Surat</label>
                            <input type="number" name="no_surat" class="w-full p-2 border rounded focus:border-blue-500 outline-none" placeholder="Contoh: 230" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Sekolah Tujuan (Input Manual)</label>
                            <input type="text" name="pindah_ke" class="w-full p-2 border rounded focus:border-blue-500 outline-none" placeholder="Contoh: SMK Negeri 1 Denpasar" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Alasan Pindah</label>
                            <textarea name="alasan_pindah" class="w-full p-2 border rounded focus:border-blue-500 outline-none" rows="2" placeholder="Contoh: Mengikuti domisili orang tua..." required></textarea>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php if (!empty($siswa['ayah'])): ?>
                        <div class="border-2 border-transparent hover:border-blue-300 rounded-lg p-4 bg-gray-50 transition">
                            <h4 class="font-bold text-gray-700 mb-3 flex items-center">👨 Data Ayah</h4>
                            <div class="flex items-center mb-3">
                                <input type="radio" name="pilih_ortu" value="ayah" id="f_ayah" class="w-4 h-4 text-blue-600" checked>
                                <label for="f_ayah" class="ml-2 cursor-pointer font-medium">Pilih Ayah</label>
                            </div>
                            <div class="p-3 bg-white rounded border text-sm text-gray-600">
                                <p>Nama: <span class="font-bold text-gray-800"><?= $siswa['ayah'] ?></span></p>
                                <p class="mt-1 italic"><?= $siswa['alamat_ayah'] ?></p>
                            </div>
                            <input type="hidden" name="nama_ayah" value="<?= $siswa['ayah'] ?>">
                            <input type="hidden" name="alamat_ayah" value="<?= $siswa['alamat_ayah'] ?>">
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($siswa['ibu'])): ?>
                        <div class="border-2 border-transparent hover:border-pink-300 rounded-lg p-4 bg-gray-50 transition">
                            <h4 class="font-bold text-gray-700 mb-3 flex items-center">👩 Data Ibu</h4>
                            <div class="flex items-center mb-3">
                                <input type="radio" name="pilih_ortu" value="ibu" id="f_ibu" class="w-4 h-4 text-pink-600">
                                <label for="f_ibu" class="ml-2 cursor-pointer font-medium">Pilih Ibu</label>
                            </div>
                            <div class="p-3 bg-white rounded border text-sm text-gray-600">
                                <p>Nama: <span class="font-bold text-gray-800"><?= $siswa['ibu'] ?></span></p>
                                <p class="mt-1 italic"><?= $siswa['alamat_ibu'] ?></p>
                            </div>
                            <input type="hidden" name="nama_ibu" value="<?= $siswa['ibu'] ?>">
                            <input type="hidden" name="alamat_ibu" value="<?= $siswa['alamat_ibu'] ?>">
                        </div>
                    <?php endif; ?>
                </div>

                <button type="submit" class="w-full mt-8 bg-green-600 text-white py-4 rounded-lg font-bold text-lg hover:bg-green-700 shadow-md transform hover:-translate-y-1 transition duration-200">
                    🖨️ Cetak Keterangan Pindah Sekolah
                </button>
            </form>
        <?php endif; ?>
    </div>
</body>

</html>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/footer.php'; ?>