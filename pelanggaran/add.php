<?php
session_start();
require '../config/database.php';

if (!isset($_SESSION['login_admin'])) {
    header("Location: ../auth/login.php");
    exit;
}

$error = "";

if (isset($_POST['simpan'])) {

    $jenis = trim($_POST['jenis']);
    $poin  = (int) $_POST['poin'];

    if ($jenis == "" || $poin <= 0) {
        $error = "Jenis dan poin wajib diisi dengan benar!";
    } else {

        mysqli_query($conn, "
            INSERT INTO jenis_pelanggaran (jenis, poin)
            VALUES ('$jenis', '$poin')
        ");

        header("Location: index.php");
        exit;
    }
}
$title = "Add Data Pelanggaran";
require '../layout/header.php';
?>

<!DOCTYPE html>
<html>

<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">

    <div class="max-w-md mx-auto bg-white p-6 rounded shadow">

        <h2 class="text-xl font-bold mb-4">Tambah Jenis Pelanggaran</h2>

        <?php if ($error): ?>
            <div class="bg-red-100 text-red-600 p-2 mb-4 rounded">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">

            <div>
                <label class="block text-sm font-medium mb-1">Nama Pelanggaran</label>
                <input type="text" name="jenis"
                    class="w-full p-2 border rounded"
                    required>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Poin</label>
                <input type="number" name="poin"
                    class="w-full p-2 border rounded"
                    min="1"
                    required>
            </div>

            <div class="flex justify-between">
                <a href="index.php" class="text-gray-600">Kembali</a>

                <button name="simpan"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Simpan
                </button>
            </div>

        </form>
    </div>

</body>

</html>

<?php require '../layout/footer.php'; ?>