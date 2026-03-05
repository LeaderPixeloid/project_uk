<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/project_uk');
// Cara ini lebih aman karena mencari dari folder utama project
require_once $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/header.php';

include ROOTPATH . "/modul/config/database.php";

// if ($_SESSION['role'] != 'Guru') {
//     die("Akses ditolak");
// }


$result = mysqli_query($conn, "
    SELECT * FROM siswa 
    JOIN ortu_wali ON siswa.id_ortu_wali = ortu_wali.id_ortu_wali 
    JOIN kelas ON siswa.id_kelas = kelas.id_kelas 
    JOIN tingkat ON kelas.id_tingkat = tingkat.id_tingkat 
    JOIN program_keahlian ON kelas.id_program_keahlian = program_keahlian.id_program_keahlian 
    JOIN guru ON kelas.kode_guru = guru.kode_guru
");
$title = "Data Siswa";
?>


<!DOCTYPE html>
<html>

<head>
    <!-- <title>Data Siswa</title> -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold">Data Siswa</h2>
        <a href="../siswa/add.php"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Tambah Siswa
        </a>
        <a href="../../dashboard/index.php"
            class="text-gray-600 hover:underline">
            Kembali
        </a>
    </div>


    <div class="max-w-7xl mx-auto p-6">

        <!-- TABLE -->
        <div class="bg-white rounded shadow overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-200 text-gray-700">
                    <tr>
                        <th class="p-3 border">No</th>
                        <th class="p-3 border">NIS</th>
                        <th class="p-3 border">Nama</th>
                        <th class="p-3 border">JK</th>
                        <th class="p-3 border">Alamat</th>
                        <th class="p-3 border">Ortu</th>
                        <th class="p-3 border">Kelas</th>
                        <th class="p-3 border">Wali Kelas</th>
                        <th class="p-3 border text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($result)):
                    ?>
                        <tr class="hover:bg-gray-50">
                            <td class="p-2 border text-center"><?= $no++ ?></td>
                            <td class="p-2 border"><?= htmlspecialchars($row['nis']) ?></td>
                            <td class="p-2 border font-medium"><?= htmlspecialchars($row['nama_siswa']) ?></td>
                            <td class="p-2 border"><?= htmlspecialchars($row['jenis_kelamin']) ?></td>
                            <td class="p-2 border"><?= htmlspecialchars($row['alamat']) ?></td>

                            <!-- Nama ortu ringkas -->
                            <td class="p-2 border">
                                Ayah: <?= $row['ayah'] ?: '-' ?><br>
                                Ibu: <?= $row['ibu'] ?: '-' ?><br>
                                Wali: <?= $row['wali'] ?: '-' ?>
                            </td>

                            <td class="p-2 border">
                                <?= htmlspecialchars(
                                    $row['tingkat'] . ' ' . $row['program_keahlian'] . ' ' . $row['rombel']
                                ) ?>
                            </td>

                            <td class="p-2 border">
                                <?= htmlspecialchars($row['nama_pengguna']) ?>
                            </td>

                            <td class="p-2 border text-center space-x-2">
                                <a href="edit.php?id=<?= $row['nis'] ?>"
                                    class="text-blue-600 hover:underline">
                                    Edit
                                </a>

                                <a href="delete.php?nis=<?= $row['nis'] ?>"
                                    onclick="return confirm('PERINGATAN!\n\nMenghapus siswa akan menghapus:\n- Data pelanggaran\n- Data orang tua/wali\n\nYakin ingin melanjutkan?')"
                                    class="text-red-600">
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
<?php require $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/footer.php';