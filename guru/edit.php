<?php
session_start();
require '../config/database.php';

if (!isset($_SESSION['login_admin'])) {
    header("Location: ../auth/login.php");
    exit;
}

$kode = $_GET['kode'];

$data = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT * FROM guru WHERE kode_guru='$kode'
"));

if (!$data) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['update'])) {

    $nama    = $_POST['nama'];
    $jabatan = $_POST['jabatan'];
    $telp    = $_POST['telp'];
    $aktif   = $_POST['aktif'];

    mysqli_query($conn, "
        UPDATE guru SET
            nama_pengguna='$nama',
            jabatan='$jabatan',
            telp='$telp',
            aktif='$aktif'
        WHERE kode_guru='$kode'
    ");

    header("Location: index.php");
    exit;
}
$title = "Edit Data Guru";
require '../layout/header.php';
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Guru</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">

    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">

        <h2 class="text-xl font-bold mb-6">Edit Guru</h2>

        <form method="POST" class="space-y-4">

            <!-- KODE -->
            <div>
                <label class="block text-sm font-medium mb-1">Kode Guru</label>
                <input value="<?= $data['kode_guru'] ?>"
                    class="w-full p-2 border rounded bg-gray-100"
                    readonly>
            </div>

            <!-- NAMA -->
            <div>
                <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
                <input name="nama"
                    value="<?= $data['nama_pengguna'] ?>"
                    class="w-full p-2 border rounded"
                    required>
            </div>

            <!-- JABATAN -->
            <div>
                <label class="block text-sm font-medium mb-1">Jabatan</label>
                <input name="jabatan"
                    value="<?= $data['jabatan'] ?>"
                    class="w-full p-2 border rounded">
            </div>

            <!-- TELP -->
            <div>
                <label class="block text-sm font-medium mb-1">No Telepon</label>
                <input name="telp"
                    value="<?= $data['telp'] ?>"
                    class="w-full p-2 border rounded">
            </div>

            <!-- STATUS -->
            <div>
                <label class="block text-sm font-medium mb-1">Status</label>
                <select name="aktif"
                    class="w-full p-2 border rounded">
                    <option value="Y" <?= $data['aktif'] == 'Y' ? 'selected' : '' ?>>
                        Aktif
                    </option>
                    <option value="N" <?= $data['aktif'] == 'N' ? 'selected' : '' ?>>
                        Non Aktif
                    </option>
                </select>
            </div>

            <!-- BUTTON -->
            <div class="flex justify-between pt-4">
                <a href="index.php"
                    class="text-gray-600 hover:underline">
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

<?php require '../layout/footer.php'; ?>