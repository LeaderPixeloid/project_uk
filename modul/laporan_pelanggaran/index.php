<?php
require '../config/database.php';

// 1. Ambil data tingkat secara UNIK (Hanya X, XI, XII) untuk pilihan filter
$list_tingkat = mysqli_query($conn, "SELECT * FROM tingkat ORDER BY tingkat ASC");

// 2. Tangkap filter dari URL (Gunakan nama 'tingkat' agar sesuai dengan logika pilihanmu)
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

// Tambahkan filter jika user memilih tingkat tertentu (X/XI/XII)
if ($filter_tingkat != '') {
    $sql .= " WHERE tingkat.id_tingkat = '" . mysqli_real_escape_string($conn, $filter_tingkat) . "'";
}

// Urutan berdasarkan rombel seperti permintaanmu
$sql .= " ORDER BY kelas.rombel ASC";
$res = mysqli_query($conn, $sql);

if (!$res) {
    die("Query Error: " . mysqli_error($conn));
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white p-6 rounded-lg shadow-md mt-6">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <h3 class="text-2xl font-bold text-gray-800 tracking-tight">Laporan Riwayat Pelanggaran</h3>

            <form method="GET" class="flex flex-wrap items-center gap-2">
                <a href="../laporan_surat/index.php"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>

                <select name="tingkat" class="p-2 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Semua Tingkat --</option>
                    <?php while ($t = mysqli_fetch_assoc($list_tingkat)): ?>
                        <option value="<?= $t['id_tingkat'] ?>" <?= $filter_tingkat == $t['id_tingkat'] ? 'selected' : '' ?>>
                            Kelas <?= htmlspecialchars($t['tingkat']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                    Filter
                </button>

                <?php if ($filter_tingkat != ''): ?>
                    <a href="index.php" class="text-red-500 text-sm hover:underline ml-2">Reset</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="overflow-x-auto border rounded-lg">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="p-4 font-bold text-gray-700 text-center w-12">No</th>
                        <th class="p-4 font-bold text-gray-700">Tanggal</th>
                        <th class="p-4 font-bold text-gray-700">Nama Siswa</th>
                        <th class="p-4 font-bold text-gray-700 text-center">Kelas</th>
                        <th class="p-4 font-bold text-gray-700">Pelanggaran</th>
                        <th class="p-4 font-bold text-gray-700 text-center">Poin</th>
                        <th class="p-4 font-bold text-gray-700">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php
                    $no = 1;
                    if (mysqli_num_rows($res) > 0):
                        while ($row = mysqli_fetch_assoc($res)):
                    ?>
                            <tr class="hover:bg-blue-50 transition-colors">
                                <td class="p-4 text-center text-gray-500"><?= $no++ ?></td>
                                <td class="p-4 text-sm text-gray-600 whitespace-nowrap">
                                    <span class="block font-medium"><?= date('d/m/Y', strtotime($row['tanggal'])) ?></span>
                                    <span class="text-xs text-gray-400"><?= date('H:i', strtotime($row['tanggal'])) ?> WIB</span>
                                </td>
                                <td class="p-4">
                                    <span class="font-semibold text-gray-800"><?= htmlspecialchars($row['nama_siswa']) ?></span>
                                    <span class="block text-xs text-gray-400">NIS: <?= $row['nis'] ?></span>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="bg-blue-50 text-blue-700 border border-blue-200 px-2 py-1 rounded text-xs font-medium">
                                        <?= htmlspecialchars($row['tingkat'] . ' ' . $row['program_keahlian'] . ' ' . $row['rombel']) ?>
                                    </span>
                                </td>
                                <td class="p-4 text-gray-700 italic"><?= $row['jenis'] ?></td>
                                <td class="p-4 text-center">
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-extrabold shadow-sm">
                                        <?= $row['poin'] ?>
                                    </span>
                                </td>
                                <td class="p-4 text-sm text-gray-500 max-w-xs truncate" title="<?= $row['keterangan'] ?>">
                                    <?= $row['keterangan'] ?: '-' ?>
                                </td>
                            </tr>
                        <?php
                        endwhile;
                    else:
                        ?>
                        <tr>
                            <td colspan="7" class="p-10 text-center text-gray-400 italic">Data tidak ditemukan untuk kriteria ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require '../../asset/layout/footer.php'; ?>