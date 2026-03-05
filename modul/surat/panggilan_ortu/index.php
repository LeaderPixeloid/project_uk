<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/project_uk/modul/config/database.php';
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
        SELECT siswa.*, tingkat.tingkat, program_keahlian.program_keahlian, kelas.rombel 
        FROM siswa
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
$title = "surat panggilan ortu"
?>

<!DOCTYPE html>
<html>

<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4 text-center">Pemanggilan Orang Tua</h2>

        <form method="POST" class="space-y-4 mb-6">
            <div class="flex gap-2">
                <input name="nis" placeholder="Masukkan NIS Siswa" class="flex-1 p-2 border rounded" required value="<?= $_GET['nis'] ?? '' ?>">
                <button name="cek" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Cek</button>
                <a href="index.php" class="bg-gray-500 text-white px-4 py-2 rounded">Reset</a>
                <a href="../../dashboard/index.php" class="bg-gray-500 text-white px-4 py-2 rounded">Kembali</a>
            </div>
        </form>

        <?php if ($error): ?>
            <p class="text-red-600 mb-4"><?= $error ?></p>
        <?php endif; ?>

        <?php if ($siswa): ?>
            <div class="bg-blue-50 p-4 rounded mb-6 text-sm">
                <p><b>Nama Siswa:</b> <?= htmlspecialchars($siswa['nama_siswa']) ?></p>
                <p><b>Kelas:</b> <?= $siswa['tingkat'] . ' ' . $siswa['program_keahlian'] . ' ' . $siswa['rombel'] ?></p>
            </div>

            <form method="POST" action="cetak.php" target="_blank" class="space-y-4">
                <input type="hidden" name="nis" value="<?= $siswa['nis'] ?>">

                <div>
                    <label class="block text-sm font-medium">Nomor Surat (Urut)</label>
                    <input type="number" name="no_surat" placeholder="Contoh: 230" class="w-full p-2 border rounded" required>
                </div>

                <div>
                    <label class="block text-sm font-medium">Waktu Pertemuan (Tanggal & Jam)</label>
                    <input type="datetime-local" name="tanggal" class="w-full p-2 border rounded" required>
                </div>

                <div>
                    <label class="block text-sm font-medium">Keperluan</label>
                    <textarea name="keperluan" rows="3" placeholder="Alasan pemanggilan..." class="w-full p-2 border rounded" required></textarea>
                </div>

                <div class="flex gap-2 pt-4">
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 flex-1">
                        Generate & Cetak Surat
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</body>

</html>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/footer.php'; ?>