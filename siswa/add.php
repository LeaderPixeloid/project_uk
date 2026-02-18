<?php
require '../config/database.php';


if (isset($_POST['simpan'])) {

    // Ambil data ortu & wali
    $ayah = $_POST['ayah'];
    $ibu  = $_POST['ibu'];
    $wali = $_POST['wali'];

    $id_ortu = NULL;

    // 1️⃣ SIMPAN KE TABEL ORTU_WALI DULU
    if ($ayah != "" || $ibu != "" || $wali != "") {
        mysqli_query($conn, "
            INSERT INTO ortu_wali (ayah, ibu, wali)
            VALUES (
                '$ayah',
                '$ibu',
                '$wali'
            )
        ");

        $id_ortu = mysqli_insert_id($conn);
    }

    // 2️⃣ BARU SIMPAN KE SISWA
    mysqli_query($conn, "
        INSERT INTO siswa (nis, nama_siswa, id_kelas, id_ortu_wali)
        VALUES (
            '$_POST[nis]',
            '$_POST[nama_siswa]',
            '$_POST[id_kelas]',
            " . ($id_ortu ? $id_ortu : "NULL") . "
        )
    ");

    header("Location: index.php");
}
$title = "Add Data Siswa";
require '../layout/header.php';
?>

<!DOCTYPE html>
<html>

<head>
    <title>Tambah Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">

    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Tambah Siswa</h2>

        <form method="POST" class="space-y-4">

            <input name="nis" placeholder="NIS"
                class="w-full p-2 border rounded" required>

            <input name="nama_siswa" placeholder="Nama Siswa"
                class="w-full p-2 border rounded" required>

            <select name="jenis_kelamin"
                class="w-full p-2 border rounded" required>
                <option value="">-- Pilih Jenis Kelamin --</option>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select>

            <!-- ALAMAT -->
            <textarea name="alamat" rows="3"
                placeholder="Alamat Siswa"
                class="w-full p-2 border rounded" required></textarea>


            <select name="id_kelas" class="w-full p-2 border rounded" required>
                <option value="">-- Pilih Kelas --</option>
                <?php
                $nama_kelas = mysqli_query($conn, "SELECT * FROM kelas JOIN tingkat USING(id_tingkat) JOIN program_keahlian USING(id_program_keahlian)");

                while ($k = mysqli_fetch_assoc($nama_kelas)): ?>
                    <option value="<?= $k['id_kelas'] ?>">
                        <?php
                        echo $k['tingkat'] . " " . $k['program_keahlian'] . " " . $k['rombel'];
                        ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <!-- DATA ORANG TUA -->
            <div class="border rounded p-4">
                <h3 class="font-semibold mb-3">Data Orang Tua</h3>

                <input name="ayah" placeholder="Nama Ayah (opsional)"
                    class="w-full p-2 border rounded mb-2">

                <input name="ibu" placeholder="Nama Ibu (opsional)"
                    class="w-full p-2 border rounded">
            </div>

            <!-- DATA WALI -->
            <div class="border rounded p-4">
                <h3 class="font-semibold mb-3">Data Wali</h3>

                <input name="wali" placeholder="Nama Wali (opsional)"
                    class="w-full p-2 border rounded">
                <select name="jenis_kelamin"
                    class="w-full p-2 border rounded">
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>

            <button name="simpan"
                class="bg-blue-600 text-white px-4 py-2 rounded">
                Simpan
            </button>

            <a href="index.php" class="block text-gray-600 mt-2">Kembali</a>

        </form>
    </div>

</body>

</html>

<?php require '../layout/footer.php'; ?>