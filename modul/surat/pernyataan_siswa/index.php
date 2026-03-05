<?php
session_start();

require $_SERVER['DOCUMENT_ROOT'] . '../config/database.php';
require $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/header.php';


$siswa = null;
$ortu  = null;
$error = "";

/*
|--------------------------------------------------------------------------
| STEP 1: Jika tombol CEK ditekan (POST)
|--------------------------------------------------------------------------
| Kita redirect ke halaman ini dengan metode GET
| supaya kalau refresh tidak kirim ulang POST
*/
if (isset($_POST['cek'])) {

    $nis = mysqli_real_escape_string($conn, $_POST['nis']);

    // Redirect ke halaman ini dengan parameter nis
    header("Location: index.php?nis=" . $nis);
    exit;
}

/*
|--------------------------------------------------------------------------
| STEP 2: Jika ada parameter nis di URL (GET)
|--------------------------------------------------------------------------
| Ambil data siswa berdasarkan nis
*/
if (isset($_GET['nis'])) {

    $nis = mysqli_real_escape_string($conn, $_GET['nis']);

    $query = mysqli_query($conn, "
        SELECT siswa.*, 
               tingkat.tingkat,
               program_keahlian.program_keahlian,
               kelas.rombel
        FROM siswa
        JOIN kelas ON siswa.id_kelas = kelas.id_kelas
        JOIN tingkat ON kelas.id_tingkat = tingkat.id_tingkat
        JOIN program_keahlian 
            ON kelas.id_program_keahlian = program_keahlian.id_program_keahlian
        WHERE siswa.nis='$nis'
    ");

    $siswa = mysqli_fetch_assoc($query);

    if ($siswa) {

        // Jika punya relasi orang tua
        if (!empty($siswa['id_ortu_wali'])) {
            $ortu = mysqli_fetch_assoc(mysqli_query($conn, "
                SELECT * FROM ortu_wali 
                WHERE id_ortu_wali = '{$siswa['id_ortu_wali']}'
            "));
        }
    } else {
        $error = "NIS tidak ditemukan!";
    }
}

$title = "Cetak Surat";
?>

<!DOCTYPE html>
<html>

<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">

    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">

        <h2 class="text-xl font-bold mb-4 text-center">
            Cetak Surat
        </h2>

        <!-- ========================= -->
        <!-- FORM CEK NIS -->
        <!-- ========================= -->
        <form method="POST" class="space-y-4">

            <input name="nis"
                placeholder="Masukkan NIS"
                class="w-full p-2 border rounded"
                required>

            <div class="flex gap-2">
                <button name="cek"
                    class="bg-blue-600 text-white px-4 py-2 rounded">
                    Cek
                </button>

                <!-- Tombol reset -->
                <a href="index.php"
                    class="bg-gray-500 text-white px-4 py-2 rounded">
                    Reset
                </a>

                <a href="../../dashboard/index.php"
                    class="bg-red-600 text-white px-4 py-2 rounded">
                    Kembali
                </a>
            </div>
        </form>

        <!-- ========================= -->
        <!-- ERROR -->
        <!-- ========================= -->
        <?php if ($error): ?>
            <p class="text-red-600 mt-4"><?= $error ?></p>
        <?php endif; ?>

        <!-- ========================= -->
        <!-- JIKA SISWA DITEMUKAN -->
        <!-- ========================= -->
        <?php if ($siswa): ?>

            <hr class="my-6">

            <div class="bg-gray-50 p-3 rounded mb-4 text-sm">
                <p><b>Nama:</b> <?= htmlspecialchars($siswa['nama_siswa']) ?></p>
                <p><b>Kelas:</b>
                    <?= htmlspecialchars(
                        $siswa['tingkat'] . ' ' .
                            $siswa['program_keahlian'] . ' ' .
                            $siswa['rombel']
                    ) ?>
                </p>
            </div>

            <form method="POST" action="cetak.php" target="_blank">

                <!-- Hidden untuk dikirim ke cetak.php -->
                <input type="hidden" name="nis"
                    value="<?= htmlspecialchars($siswa['nis']) ?>">

                <input type="hidden" name="nama"
                    value="<?= htmlspecialchars($siswa['nama_siswa']) ?>">

                <input type="hidden" name="kelas"
                    value="<?= htmlspecialchars(
                                $siswa['tingkat'] . ' ' .
                                    $siswa['program_keahlian'] . ' ' .
                                    $siswa['rombel']
                            ) ?>">

                <?php if (!$ortu): ?>

                    <h3 class="font-semibold mb-2">Data Orang Tua</h3>

                    <input name="ayah"
                        placeholder="Nama Ayah"
                        class="w-full p-2 border rounded mb-2">

                    <input name="ibu"
                        placeholder="Nama Ibu"
                        class="w-full p-2 border rounded mb-4">

                <?php else: ?>

                    <input type="hidden" name="ayah"
                        value="<?= htmlspecialchars($ortu['ayah'] ?? '') ?>">

                    <input type="hidden" name="ibu"
                        value="<?= htmlspecialchars($ortu['ibu'] ?? '') ?>">

                    <p class="text-green-600 mb-3">
                        Data orang tua ditemukan ✓
                    </p>

                <?php endif; ?>

                <button name="cetak"
                    class="bg-green-600 text-white px-4 py-2 rounded">
                    Cetak Surat
                </button>

            </form>

        <?php endif; ?>

    </div>
</body>

</html>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/footer.php'; ?>