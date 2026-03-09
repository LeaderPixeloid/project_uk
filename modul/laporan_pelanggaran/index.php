<?php
require '../config/database.php';

// 1. Ambil data tingkat secara UNIK
$list_tingkat = mysqli_query($conn, "SELECT * FROM tingkat ORDER BY tingkat ASC");

// 2. Tangkap filter dari URL
$filter_tingkat = $_GET['tingkat'] ?? '';

// 3. Bangun Query SQL Utama
$sql = "SELECT 
            pelanggaran_siswa.*, 
            siswa.nama_siswa, 
            siswa.nis, 
            jenis_pelanggaran.jenis, 
            jenis_pelanggaran.poin, 
            tingkat.tingkat, 
            program_keahlian.program_keahlian, 
            kelas.rombel 
        FROM pelanggaran_siswa
        JOIN siswa ON pelanggaran_siswa.nis = siswa.nis
        JOIN jenis_pelanggaran ON pelanggaran_siswa.id_jenis_pelanggaran = jenis_pelanggaran.id_jenis_pelanggaran
        JOIN kelas ON siswa.id_kelas = kelas.id_kelas
        JOIN tingkat ON kelas.id_tingkat = tingkat.id_tingkat 
        JOIN program_keahlian ON kelas.id_program_keahlian = program_keahlian.id_program_keahlian";

if ($filter_tingkat != '') {
    $sql .= " WHERE tingkat.id_tingkat = '" . mysqli_real_escape_string($conn, $filter_tingkat) . "'";
}

$sql .= " ORDER BY pelanggaran_siswa.tanggal DESC"; // Diubah ke tanggal terbaru biar lebih masuk akal buat laporan
$res = mysqli_query($conn, $sql);

require_once $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/header.php';
?>

<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Laporan Riwayat Pelanggaran</h2>
        <p class="text-gray-600 text-sm">Rekapitulasi data pelanggaran siswa berdasarkan kriteria yang dipilih.</p>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
        <div class="p-4 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">

            <div class="flex gap-2">
                <a href="../laporan_surat/index.php" class="bg-gray-100 text-gray-700 px-4 py-2 rounded text-sm font-medium hover:bg-gray-200">
                    ← Kembali
                </a>
                <button onclick="window.print()" class="bg-green-600 text-white px-4 py-2 rounded text-sm font-medium hover:bg-green-700">
                    Cetak Laporan
                </button>
            </div>

            <form method="GET" class="flex items-center gap-2">
                <select name="tingkat" class="border border-gray-300 p-2 rounded text-sm focus:border-blue-500 outline-none">
                    <option value="">-- Semua Tingkat --</option>
                    <?php while ($t = mysqli_fetch_assoc($list_tingkat)): ?>
                        <option value="<?= $t['id_tingkat'] ?>" <?= $filter_tingkat == $t['id_tingkat'] ? 'selected' : '' ?>>
                            Kelas <?= $t['tingkat'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-medium hover:bg-blue-700">
                    Filter
                </button>

                <?php if ($filter_tingkat != ''): ?>
                    <a href="index.php" class="text-xs text-red-500 hover:underline italic">Hapus Filter</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="p-4 text-xs font-bold uppercase text-gray-500 text-center">No</th>
                        <th class="p-4 text-xs font-bold uppercase text-gray-500">Waktu</th>
                        <th class="p-4 text-xs font-bold uppercase text-gray-500">Siswa / NIS</th>
                        <th class="p-4 text-xs font-bold uppercase text-gray-500">Kelas</th>
                        <th class="p-4 text-xs font-bold uppercase text-gray-500">Pelanggaran</th>
                        <th class="p-4 text-xs font-bold uppercase text-gray-500 text-center">Poin</th>
                        <th class="p-4 text-xs font-bold uppercase text-gray-500">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    <?php
                    $no = 1;
                    if (mysqli_num_rows($res) > 0):
                        while ($row = mysqli_fetch_assoc($res)):
                    ?>
                            <tr class="hover:bg-gray-50">
                                <td class="p-4 text-center text-gray-400"><?= $no++ ?></td>
                                <td class="p-4">
                                    <div class="font-medium"><?= date('d/m/Y', strtotime($row['tanggal'])) ?></div>
                                    <div class="text-xs text-gray-400"><?= date('H:i', strtotime($row['tanggal'])) ?> WIB</div>
                                </td>
                                <td class="p-4">
                                    <div class="font-bold text-gray-800"><?= htmlspecialchars($row['nama_siswa']) ?></div>
                                    <div class="text-xs text-gray-500"><?= $row['nis'] ?></div>
                                </td>
                                <td class="p-4 font-medium text-gray-700">
                                    <?= $row['tingkat'] ?> <?= $row['program_keahlian'] ?> <?= $row['rombel'] ?>
                                </td>
                                <td class="p-4 text-gray-600">
                                    <?= $row['jenis'] ?>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="bg-red-50 text-red-600 px-2 py-1 rounded font-bold border border-red-100">
                                        <?= $row['poin'] ?>
                                    </span>
                                </td>
                                <td class="p-4 text-gray-500 italic">
                                    <?= $row['keterangan'] ?: '-' ?>
                                </td>
                            </tr>
                        <?php
                        endwhile;
                    else:
                        ?>
                        <tr>
                            <td colspan="7" class="p-10 text-center text-gray-400">
                                Belum ada data riwayat pelanggaran untuk ditampilkan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require '../../asset/layout/footer.php'; ?>