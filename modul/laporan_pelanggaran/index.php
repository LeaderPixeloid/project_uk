<?php
require '../config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/header.php';
?>



<div class="bg-white p-6 rounded-lg shadow mt-6">
    <h3 class="text-xl font-bold mb-4">Laporan Riwayat Pelanggaran</h3>
    <table class="w-full border-collapse">
        <thead class="bg-gray-100">
            <tr>
                <th class="border p-3 text-left">Tanggal</th>
                <th class="border p-3 text-left">Nama Siswa</th>
                <th class="border p-3 text-left">Pelanggaran</th>
                <th class="border p-3 text-center">Poin</th>
                <th class="border p-3 text-left">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT p.*, s.nama_siswa, j.jenis, j.poin 
                    FROM pelanggaran_siswa p
                    JOIN siswa s ON p.nis = s.nis
                    JOIN jenis_pelanggaran j ON p.id_jenis_pelanggaran = j.id_jenis_pelanggaran
                    ORDER BY p.tanggal DESC";
            $res = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_assoc($res)): ?>
            <tr class="hover:bg-gray-50">
                <td class="border p-3 text-sm text-gray-600">
                    <?= date('d/m/Y H:i', strtotime($row['tanggal'])) ?>
                </td>
                <td class="border p-3 font-medium"><?= $row['nama_siswa'] ?></td>
                <td class="border p-3"><?= $row['jenis'] ?></td>
                <td class="border p-3 text-center">
                    <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">
                        <?= $row['poin'] ?>
                    </span>
                </td>
                <td class="border p-3 text-sm text-gray-500 italic"><?= $row['keterangan'] ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require '../../asset/layout/footer.php'; ?>