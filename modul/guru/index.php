<?php
session_start();
require '../config/database.php';
// Cara ini lebih aman karena mencari dari folder utama project
require_once $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/header.php';


if (!isset($_SESSION['login_admin'])) {
    header("Location: ../../auth/login.php");
    exit;
}

$aktif = mysqli_query($conn, "
    SELECT * FROM guru 
    WHERE aktif='Y'
    ORDER BY kode_guru ASC
");

$nonaktif = mysqli_query($conn, "
    SELECT * FROM guru 
    WHERE aktif='N'
    ORDER BY kode_guru ASC
");

$title = "Data Guru";
// require '../layout/header.php';
?>

<!DOCTYPE html>
<html>

<head>
    <!-- <title>Data Guru</title> -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">

    <div class="max-w-6xl mx-auto space-y-10">

        <!-- HEADER -->
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold">Data Guru</h2>

            <a href="../guru/add.php"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Tambah Guru
            </a>
            <a href="../dashboard/index.php"
                    class="text-gray-600 hover:underline">
                    Kembali
                </a>
        </div>

        <!-- GURU AKTIF -->
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-xl font-semibold mb-4">List Guru Aktif</h3>

            <table class="w-full border text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border p-2">No</th>
                        <th class="border p-2">Kode</th>
                        <th class="border p-2">Nama</th>
                        <th class="border p-2">Username</th>
                        <th class="border p-2">Jabatan</th>
                        <th class="border p-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    while ($row = mysqli_fetch_assoc($aktif)): ?>
                        <tr>
                            <td class="border p-2"><?= $no++ ?></td>
                            <td class="border p-2"><?= $row['kode_guru'] ?></td>
                            <td class="border p-2"><?= $row['nama_pengguna'] ?></td>
                            <td class="border p-2"><?= $row['username'] ?></td>
                            <td class="border p-2"><?= $row['jabatan'] ?></td>
                            <td class="border p-2 text-center space-x-2">

                                <a href="edit.php?kode=<?= $row['kode_guru'] ?>"
                                    class="text-blue-600">Edit</a>

                                <a href="delete.php?kode=<?= $row['kode_guru'] ?>"
                                    onclick="return confirm('PERINGATAN!\n\nGuru akan dihapus permanen.\n\nYakin?')"
                                    class="text-red-600">Delete</a>

                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- GURU NON AKTIF -->
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-xl font-semibold mb-4">List Guru Non Aktif</h3>

            <table class="w-full border text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border p-2">No</th>
                        <th class="border p-2">Kode</th>
                        <th class="border p-2">Nama</th>
                        <th class="border p-2">Username</th>
                        <th class="border p-2">Jabatan</th>
                        <th class="border p-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    while ($row = mysqli_fetch_assoc($nonaktif)): ?>
                        <tr>
                            <td class="border p-2"><?= $no++ ?></td>
                            <td class="border p-2"><?= $row['kode_guru'] ?></td>
                            <td class="border p-2"><?= $row['nama_pengguna'] ?></td>
                            <td class="border p-2"><?= $row['username'] ?></td>
                            <td class="border p-2"><?= $row['jabatan'] ?></td>
                            <td class="border p-2 text-center space-x-2">

                                <a href="edit.php?kode=<?= $row['kode_guru'] ?>"
                                    class="text-blue-600">Edit</a>

                                <a href="delete.php?kode=<?= $row['kode_guru'] ?>"
                                    onclick="return confirm('PERINGATAN!\n\nGuru akan dihapus permanen.\n\nYakin?')"
                                    class="text-red-600">Delete</a>

                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    </div>

</body>

</html>
<?php require '../../asset/layout/footer.php'; ?>