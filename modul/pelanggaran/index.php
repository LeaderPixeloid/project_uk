<?php
session_start();
require '../config/database.php';
// Cara ini lebih aman karena mencari dari folder utama project
require_once $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/header.php';


if (!isset($_SESSION['login_admin'])) {
    header("Location: ../auth/login.php");
    exit;
}

$data = mysqli_query($conn, "SELECT * FROM jenis_pelanggaran ORDER BY id_jenis_pelanggaran ASC");
$title = "Data Pelanggaran";
?>

<!DOCTYPE html>
<html>

<head>
    <!-- <title>Data Jenis Pelanggaran</title> -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">

    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">

        <div class="flex justify-between mb-3">
            <h2 class="text-xl font-bold">Jenis Pelanggaran</h2>
            <a href="../dashboard/index.php" class="bg-yellow-600 text-white px-4 py-2 rounded">
                Kembali
            </a>
            <a href="../pelanggaran/add.php" class="bg-blue-600 text-white px-4 py-2 rounded">
                + Tambah
            </a>
        </div>

        <table class="w-full border text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-2">No</th>
                    <th class="border p-2">Jenis</th>
                    <th class="border p-2">Poin</th>
                    <th class="border p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                while ($row = mysqli_fetch_assoc($data)): ?>
                    <tr>
                        <td class="border p-2"><?= $no++ ?></td>
                        <td class="border p-2"><?= $row['jenis'] ?></td>
                        <td class="border p-2 text-center"><?= $row['poin'] ?></td>
                        <td class="border p-2 text-center space-x-2">
                            <a href="edit.php?id=<?= $row['id_jenis_pelanggaran'] ?>"
                                class="text-blue-600">Edit</a>
                            <a href="delete.php?id=<?= $row['id_jenis_pelanggaran'] ?>"
                                onclick="return confirm('PERINGATAN!\n\nJika jenis ini sudah dipakai oleh siswa, data tidak bisa dihapus.\n\nYakin ingin menghapus?')"
                                class="text-red-600">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    </div>
</body>

</html>
<?php require '../../asset/layout/footer.php'; ?>