<?php
require_once '../../config/auth_check.php'; // Proteksi login & timeout
require_once '../../config/database.php';   // Koneksi database

// 1. Ambil data tingkat untuk filter (Sama seperti sebelumnya)
$list_tingkat = mysqli_query($conn, "SELECT * FROM tingkat ORDER BY tingkat ASC");
$filter_tingkat = $_GET['tingkat'] ?? '';

// 2. Query Utama untuk Rekap Surat Panggilan
// Kita join ke siswa dan kelas agar tahu surat itu milik siapa dan kelas berapa
$sql = "SELECT 
            surat_keluar.*, 
            siswa.nama_siswa, 
            tingkat.tingkat, 
            program_keahlian.program_keahlian, 
            kelas.rombel 
        FROM surat_keluar
        JOIN siswa ON surat_keluar.nis = siswa.nis
        JOIN kelas ON siswa.id_kelas = kelas.id_kelas
        JOIN tingkat ON kelas.id_tingkat = tingkat.id_tingkat 
        JOIN program_keahlian ON kelas.id_program_keahlian = program_keahlian.id_program_keahlian";

// Logika Filter berdasarkan Tingkat
if ($filter_tingkat != '') {
    $sql .= " WHERE tingkat.id_tingkat = '" . mysqli_real_escape_string($conn, $filter_tingkat) . "'";
}

$sql .= " ORDER BY surat_keluar.tanggal_pembuatan_surat DESC";
$res = mysqli_query($conn, $sql);

// 3. Hitung Total Surat per Jenis (Untuk Ringkasan di Atas)
$total_sp1 = mysqli_query($conn, "SELECT COUNT(*) as total FROM surat_keluar WHERE jenis_surat LIKE '%SP 1%'")->fetch_assoc()['total'];
$total_sp2 = mysqli_query($conn, "SELECT COUNT(*) as total FROM surat_keluar WHERE jenis_surat LIKE '%SP 2%'")->fetch_assoc()['total'];
$total_sp3 = mysqli_query($conn, "SELECT COUNT(*) as total FROM surat_keluar WHERE jenis_surat LIKE '%SP 3%'")->fetch_assoc()['total'];

require_once $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-blue-500 text-white p-4 rounded-lg shadow">
            <h4 class="text-sm uppercase font-semibold">Total SP 1</h4>
            <p class="text-3xl font-bold"><?= $total_sp1 ?></p>
        </div>
        <div class="bg-orange-500 text-white p-4 rounded-lg shadow">
            <h4 class="text-sm uppercase font-semibold">Total SP 2</h4>
            <p class="text-3xl font-bold"><?= $total_sp2 ?></p>
        </div>
        <div class="bg-red-600 text-white p-4 rounded-lg shadow">
            <h4 class="text-sm uppercase font-semibold">Total SP 3</h4>
            <p class="text-3xl font-bold"><?= $total_sp3 ?></p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <h3 class="text-xl font-bold text-gray-800">Daftar Surat Panggilan Keluar</h3>

            <form method="GET" class="flex flex-wrap items-center gap-2">
                <a href="../index.php"
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
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="p-4 font-semibold text-gray-600 w-12 text-center">No</th>
                        <th class="p-4 font-semibold text-gray-600">No. Surat</th>
                        <th class="p-4 font-semibold text-gray-600">Tanggal</th>
                        <th class="p-4 font-semibold text-gray-600">Nama Siswa</th>
                        <th class="p-4 font-semibold text-gray-600 text-center">Kelas</th>
                        <th class="p-4 font-semibold text-gray-600">Jenis Surat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php
                    $no = 1;
                    if (mysqli_num_rows($res) > 0):
                        while ($row = mysqli_fetch_assoc($res)):
                    ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-4 text-center text-gray-500"><?= $no++ ?></td>
                                <td class="p-4 font-mono text-sm text-blue-600"><?= $row['no_surat'] ?></td>
                                <td class="p-4 text-sm text-gray-600"><?= date('d/m/Y', strtotime($row['tanggal_pembuatan_surat'])) ?></td>
                                <td class="p-4 font-medium text-gray-800"><?= htmlspecialchars($row['nama_siswa']) ?></td>
                                <td class="p-4 text-center">
                                    <span class="text-xs font-medium px-2 py-1 bg-gray-100 rounded text-gray-600">
                                        <?= $row['tingkat'] . ' ' . $row['program_keahlian'] . ' ' . $row['rombel'] ?>
                                    </span>
                                </td>
                                <td class="p-4 text-sm">
                                    <span class="px-3 py-1 rounded-full font-bold text-xs 
                                    <?= strpos($row['jenis_surat'], 'SP 3') !== false ? 'bg-red-100 text-red-700' : (strpos($row['jenis_surat'], 'SP 2') !== false ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700') ?>">
                                        <?= $row['jenis_surat'] ?>
                                    </span>
                                </td>
                            </tr>
                        <?php
                        endwhile;
                    else:
                        ?>
                        <tr>
                            <td colspan="6" class="p-10 text-center text-gray-400 italic">Belum ada surat panggilan yang dikeluarkan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../../../asset/layout/footer.php'; ?>