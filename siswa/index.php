<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/project_uk');

include ROOTPATH . "/config/database.php";

if ($_SESSION['role'] != 'admin') {
    die("Akses ditolak");
}


$result = mysqli_query($conn, "
    SELECT * FROM siswa 
    JOIN ortu_wali ON siswa.id_ortu_wali = ortu_wali.id_ortu_wali 
    JOIN kelas ON siswa.id_kelas = kelas.id_kelas 
    JOIN tingkat ON kelas.id_tingkat = tingkat.id_tingkat 
    JOIN program_keahlian ON kelas.id_program_keahlian = program_keahlian.id_program_keahlian 
    JOIN guru ON kelas.kode_guru = guru.kode_guru
");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Data Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">
    <div class="bg-blue-700 text-white p-4 flex justify-between ">
        <span class="font-semibold">Sistem Pelanggaran Siswa/📁 Data Siswa</span>
        <a href="add.php"
            class="bg-blue-600 text-white  rounded hover:bg-blue-700">
            + Tambah Data Siswa
        </a>

        <a href="../dashboard/index.php" class="block text-blue-600 mt-2">Kembali</a>
        <a href="../auth/switch_account.php"
            onclick="return confirm('Ganti akun sekarang?')"
            class="hover:underline">
            Switch Account
        </a>
        <a href="../auth/logout.php" class="hover:underline text-red-300">
            Logout
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

                                <form action="/Poin_Pelanggaran_Siswa/process/siswa_process.php"
                                    method="post"
                                    class="inline"
                                    onsubmit="return confirm('Hapus data <?= $row['nama_siswa'] ?>?')">
                                    <input type="hidden" name="id" value="<?= $row['nis'] ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button type="submit"
                                        class="text-red-600 hover:underline">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    </div>

</body>

</html>