<?php


session_start();
require '../config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/header.php';

if (!isset($_SESSION['login_admin'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id = $_GET['id'];

// Ambil data kelas lengkap
$data = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT 
        kelas.*,
        tingkat.tingkat,
        program_keahlian.program_keahlian
    FROM kelas
    JOIN tingkat ON kelas.id_tingkat = tingkat.id_tingkat
    JOIN program_keahlian 
        ON kelas.id_program_keahlian = program_keahlian.id_program_keahlian
    WHERE kelas.id_kelas = $id
"));

// Ambil daftar guru untuk wali kelas
$guru = mysqli_query($conn, "
    SELECT kode_guru, nama_pengguna 
    FROM guru 
    WHERE aktif='Y'
    ORDER BY nama_pengguna ASC
");

if (isset($_POST['update'])) {

    $kode_guru = $_POST['kode_guru'];

    mysqli_query($conn, "
        UPDATE kelas 
        SET kode_guru = '$kode_guru'
        WHERE id_kelas = $id
    ");

    header("Location: index.php");
    exit;
}
$title = "Edit Data Kelas";

?>

<!DOCTYPE html>
<html>

<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">

    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">

        <h2 class="text-xl font-bold mb-6">Edit Wali Kelas</h2>

        <form method="POST" class="space-y-4">

            <!-- TAMPILAN KELAS (READONLY) -->
            <div>
                <label class="block text-sm font-medium mb-1">Kelas</label>
                <input
                    value="<?= $data['tingkat'] . ' ' . $data['program_keahlian'] . ' ' . $data['rombel']; ?>"
                    class="w-full p-2 border rounded bg-gray-100"
                    readonly>
            </div>

            <!-- PILIH WALI KELAS -->
            <div>
                <label class="block text-sm font-medium mb-1">Wali Kelas</label>
                <select name="kode_guru" class="w-full p-2 border rounded" required>
                    <?php while ($g = mysqli_fetch_assoc($guru)): ?>
                        <option value="<?= $g['kode_guru'] ?>"
                            <?= $g['kode_guru'] == $data['kode_guru'] ? 'selected' : '' ?>>
                            <?= $g['nama_pengguna'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="flex justify-between pt-4">
                <a href="index.php" class="text-gray-600 hover:underline">
                    Kembali
                </a>

                <button name="update"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Update
                </button>
            </div>

        </form>

    </div>

</body>

</html>
<?php require '../../asset/layout/footer.php'; ?>