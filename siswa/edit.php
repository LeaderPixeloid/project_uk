<?php
require '../config/database.php';

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM siswa WHERE nis=$id"));

$kelas = mysqli_query($conn, "
    SELECT 
        kelas.id_kelas,
        tingkat.tingkat,
        program_keahlian.program_keahlian,
        kelas.rombel
    FROM kelas
    JOIN tingkat ON kelas.id_tingkat = tingkat.id_tingkat
    JOIN program_keahlian 
        ON kelas.id_program_keahlian = program_keahlian.id_program_keahlian
");


$ortu  = mysqli_query($conn, "SELECT * FROM ortu_wali");

if (isset($_POST['simpan'])) {
    mysqli_query($conn, "
        UPDATE siswa SET
        nis='$_POST[nis]',
        nama_siswa='$_POST[nama]',
        id_kelas='$_POST[id_kelas]',
        id_ortu='$_POST[id_ortu]'
        WHERE id_siswa=$id
    ");
    header("Location: index.php");
}
$title = "Edit Data Siswa";
require '../layout/header.php';
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">

    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Edit Siswa</h2>

        <form method="POST" class="space-y-4">

            <input name="nis" value="<?= $data['nis'] ?>"
                class="w-full p-2 border rounded" required>

            <input name="nama" value="<?= $data['nama_siswa'] ?>"
                class="w-full p-2 border rounded" required>

            <select name="id_kelas" class="w-full p-2 border rounded">

                <?php while ($k = mysqli_fetch_assoc($kelas)): ?>

                    <option value="<?= $k['id_kelas'] ?>"
                        <?= $k['id_kelas'] == $data['id_kelas'] ? 'selected' : '' ?>>

                        <?= $k['tingkat'] . ' ' .
                            $k['program_keahlian'] . ' ' .
                            $k['rombel']; ?>

                    </option>

                <?php endwhile; ?>

            </select>
            <button name="simpan"
                class="bg-green-600 text-white px-4 py-2 rounded">
                Update
            </button>

            <a href="index.php" class="block text-gray-600 mt-2">Kembali</a>

        </form>
    </div>

</body>

</html>

<?php require '../layout/footer.php'; ?>