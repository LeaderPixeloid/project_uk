<?php
session_start();
require '../config/database.php';
// Cara ini lebih aman karena mencari dari folder utama project
require_once $_SERVER['DOCUMENT_ROOT'] . '/project_uk/layout/header.php';

if (!isset($_SESSION['login_admin'])) {
    header("Location: ../auth/login.php");
    exit;
}

$data = mysqli_query($conn, "
    SELECT 
        kelas.id_kelas,
        tingkat.tingkat,
        program_keahlian.program_keahlian,
        kelas.rombel,
        wali.nama_pengguna AS wali_kelas,
        bk.nama_pengguna AS guru_bk

    FROM kelas

    JOIN tingkat 
        ON kelas.id_tingkat = tingkat.id_tingkat

    JOIN program_keahlian 
        ON kelas.id_program_keahlian = program_keahlian.id_program_keahlian

    LEFT JOIN guru AS wali 
        ON kelas.kode_guru = wali.kode_guru

    LEFT JOIN guru AS bk
        ON bk.jabatan = CONCAT('Guru BK ', tingkat.tingkat)

    ORDER BY tingkat.id_tingkat DESC,
             program_keahlian.program_keahlian ASC,
             kelas.rombel ASC
");

$title = "Data Kelas";
require '../layout/header.php';
?>

<!DOCTYPE html>
<html>

<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">

    <div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">

        <h2 class="text-2xl font-bold text-center mb-4">
            Data Kelas
        </h2>

        <div class="text-center mb-6">
            <a href="add.php"
                class="text-blue-600 hover:underline">
                + Tambah Data
            </a>
            <a href="../dashboard/index.php"
                class="text-yellow-600 hover:underline">
                Kembali
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border border-gray-300 text-sm">

                <thead class="bg-gray-200">
                    <tr>
                        <th class="border p-2">No</th>
                        <th class="border p-2">Kelas</th>
                        <th class="border p-2">Wali Kelas</th>
                        <th class="border p-2">Guru BK</th>
                        <th class="border p-2">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    <?php $no = 1;
                    while ($row = mysqli_fetch_assoc($data)): ?>

                        <tr class="text-center hover:bg-gray-50">
                            <td class="border p-2"><?= $no++ ?></td>

                            <td class="border p-2 font-semibold">
                                <?= $row['tingkat'] . " " .
                                    $row['program_keahlian'] . " " .
                                    $row['rombel']; ?>
                            </td>

                            <td class="border p-2">
                                <?= htmlspecialchars($row['wali_kelas'] ?? '-') ?>
                            </td>

                            <td class="border p-2">
                                <?= htmlspecialchars($row['guru_bk'] ?? '-') ?>
                            </td>

                            <td class="border p-2 space-x-2">
                                <a href="edit.php?id=<?= $row['id_kelas'] ?>"
                                    class="text-blue-600 hover:underline">
                                    Edit
                                </a>

                                <a href="delete.php?id=<?= $row['id_kelas'] ?>"
                                    onclick="return confirm('Yakin hapus kelas ini?')"
                                    class="text-red-600 hover:underline">
                                    Delete
                                </a>
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