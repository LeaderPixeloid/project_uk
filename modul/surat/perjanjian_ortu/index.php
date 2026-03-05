<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '../config/database.php';
require $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/header.php';

$siswa = null;
$error = "";

if (isset($_POST['cek'])) {
    $nis = mysqli_real_escape_string($conn, $_POST['nis']);
    header("Location: index.php?nis=" . $nis);
    exit;
}

if (isset($_GET['nis'])) {
    $nis = mysqli_real_escape_string($conn, $_GET['nis']);
    $query = mysqli_query($conn, "
        SELECT siswa.*, tingkat.tingkat, program_keahlian.program_keahlian, kelas.rombel, 
               ortu_wali.ayah, ortu_wali.pekerjaan_ayah, ortu_wali.alamat_ayah, ortu_wali.no_telp_ayah,
               ortu_wali.ibu, ortu_wali.pekerjaan_ibu, ortu_wali.alamat_ibu, ortu_wali.no_telp_ibu,
               ortu_wali.wali, ortu_wali.pekerjaan_wali, ortu_wali.alamat_wali, ortu_wali.no_telp_wali
        FROM siswa
        JOIN ortu_wali USING(id_ortu_wali)
        JOIN kelas ON siswa.id_kelas = kelas.id_kelas
        JOIN tingkat ON kelas.id_tingkat = tingkat.id_tingkat
        JOIN program_keahlian ON kelas.id_program_keahlian = program_keahlian.id_program_keahlian
        WHERE siswa.nis='$nis'
    ");
    $siswa = mysqli_fetch_assoc($query);
    if (!$siswa) {
        $error = "NIS tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-6 text-center">Surat Perjanjian Orang Tua</h2>

        <form method="POST" class="flex gap-2 mb-8 max-w-md mx-auto">
            <input name="nis" placeholder="Masukkan NIS Siswa" class="flex-1 p-2 border rounded focus:ring-2 focus:ring-orange-500 outline-none" required value="<?= $_GET['nis'] ?? '' ?>">
            <button name="cek" class="bg-orange-500 text-white px-6 py-2 rounded font-bold hover:bg-orange-600">Cek</button>
            <a href="index.php" class="bg-gray-500 text-white px-4 py-2 rounded">Reset</a>
            <a href="../../dashboard/index.php" class="bg-gray-500 text-white px-4 py-2 rounded">Kembali</a>
        </form>

        <?php if ($error): ?>
            <p class="text-red-600 text-center mb-4"><?= $error ?></p>
        <?php endif; ?>

        <?php if ($siswa): ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <?php if (!empty($siswa['ayah'])): ?>
                    <div class="border rounded-lg p-4 bg-gray-50 flex flex-col">
                        <h3 class="font-bold text-lg mb-3 border-b pb-2">Data Ayah</h3>
                        <form action="cetak.php" method="post" target="_blank" class="space-y-3 flex-1">
                            <input type="hidden" name="nis" value="<?= $siswa['nis'] ?>">
                            <input type="hidden" name="nama_ortu" value="<?= $siswa['ayah'] ?>">

                            <div class="text-sm">
                                <label class="block font-semibold">Nama</label>
                                <input type="text" name="Nama" value="<?= $siswa['ayah'] ?>" class="w-full p-1 border rounded" required>
                            </div>

                            <div class="text-sm">
                                <label class="block font-semibold">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" class="w-full p-1 border rounded" required>
                            </div>
                            <div class="text-sm">
                                <label class="block font-semibold">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" class="w-full p-1 border rounded" required>
                            </div>
                            <div class="text-sm">
                                <label class="block font-semibold">Pekerjaan</label>
                                <input type="text" name="pekerjaan" value="<?= $siswa['pekerjaan_ayah'] ?>" class="w-full p-1 border rounded" required>
                            </div>
                            <div class="text-sm">
                                <label class="block font-semibold">Alamat</label>
                                <textarea name="alamat" class="w-full p-1 border rounded text-xs" rows="2"><?= $siswa['alamat_ayah'] ?></textarea>
                            </div>
                            <div class="text-sm">
                                <label class="block font-semibold">No. HP</label>
                                <input type="Number" name="no_telp" value="<?= $siswa['no_telp_ayah'] ?>" class="w-full p-1 border rounded" required>
                            </div>
                            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded mt-2 hover:bg-blue-700">Cetak Surat (Ayah)</button>
                        </form>
                    </div>
                <?php endif; ?>

                <?php if (!empty($siswa['ibu'])): ?>
                    <div class="border rounded-lg p-4 bg-gray-50 flex flex-col">
                        <h3 class="font-bold text-lg mb-3 border-b pb-2">Data Ibu</h3>
                        <form action="cetak.php" method="post" target="_blank" class="space-y-3 flex-1">
                            <input type="hidden" name="nis" value="<?= $siswa['nis'] ?>">
                            <input type="hidden" name="nama_ortu" value="<?= $siswa['ibu'] ?>">

                            <div class="text-sm">
                                <label class="block font-semibold">Nama</label>
                                <input type="text" name="Nama" value="<?= $siswa['ibu'] ?>" class="w-full p-1 border rounded" required>
                            </div>

                            <div class="text-sm">
                                <label class="block font-semibold">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" class="w-full p-1 border rounded" required>
                            </div>
                            <div class="text-sm">
                                <label class="block font-semibold">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" class="w-full p-1 border rounded" required>
                            </div>
                            <div class="text-sm">
                                <label class="block font-semibold">Pekerjaan</label>
                                <input type="text" name="pekerjaan" value="<?= $siswa['pekerjaan_ibu'] ?>" class="w-full p-1 border rounded" required>
                            </div>
                            <div class="text-sm">
                                <label class="block font-semibold">Alamat</label>
                                <textarea name="alamat" class="w-full p-1 border rounded text-xs" rows="2"><?= $siswa['alamat_ibu'] ?></textarea>
                            </div>
                            <div class="text-sm">
                                <label class="block font-semibold">No. HP</label>
                                <input type="number" name="no_telp" value="<?= $siswa['no_telp_ibu'] ?>" class="w-full p-1 border rounded" required>
                            </div>
                            <button type="submit" class="w-full bg-pink-600 text-white py-2 rounded mt-2 hover:bg-pink-700">Cetak Surat (Ibu)</button>
                        </form>
                    </div>
                <?php endif; ?>

                <?php if (!empty($siswa['wali'])): ?>
                    <div class="border rounded-lg p-4 bg-gray-50 flex flex-col">
                        <h3 class="font-bold text-lg mb-3 border-b pb-2">Data Wali</h3>
                        <form action="cetak.php" method="post" target="_blank" class="space-y-3 flex-1">
                            <input type="hidden" name="nis" value="<?= $siswa['nis'] ?>">
                            <input type="hidden" name="nama_ortu" value="<?= $siswa['wali'] ?>">

                            <div class="text-sm">
                                <label class="block font-semibold">Nama</label>
                                <input type="text" name="Nama" value="<?= $siswa['wali'] ?>" class="w-full p-1 border rounded" required>
                            </div>

                            <div class="text-sm">
                                <label class="block font-semibold">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" class="w-full p-1 border rounded" required>
                            </div>
                            <div class="text-sm">
                                <label class="block font-semibold">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" class="w-full p-1 border rounded" required>
                            </div>
                            <div class="text-sm">
                                <label class="block font-semibold">Pekerjaan</label>
                                <input type="text" name="pekerjaan" value="<?= $siswa['pekerjaan_wali'] ?>" class="w-full p-1 border rounded" required>
                            </div>
                            <div class="text-sm">
                                <label class="block font-semibold">Alamat</label>
                                <textarea name="alamat" class="w-full p-1 border rounded text-xs" rows="2"><?= $siswa['alamat_wali'] ?></textarea>
                            </div>
                            <div class="text-sm">
                                <label class="block font-semibold">No. HP</label>
                                <input type="number" name="no_telp" value="<?= $siswa['no_telp_wali'] ?>" class="w-full p-1 border rounded" required>
                            </div>
                            <button type="submit" class="w-full bg-green-600 text-white py-2 rounded mt-2 hover:bg-green-700">Cetak Surat (Wali)</button>
                        </form>
                    </div>
                <?php endif; ?>

            </div>
        <?php endif; ?>
    </div>
</body>

</html>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/footer.php'; ?>