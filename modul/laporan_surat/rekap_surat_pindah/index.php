<?php
// require_once '../../config/auth_check.php'; // Proteksi login & timeout
require_once '../../config/database.php';   // Koneksi database

// 1. Ambil data tingkat untuk filter
$list_tingkat = mysqli_query($conn, "SELECT * FROM tingkat ORDER BY tingkat ASC");
$filter_tingkat = $_GET['tingkat'] ?? '';

// 2. Query Utama untuk Rekap Surat Pindah
// Kita join: surat_pindah -> surat_keluar -> siswa -> kelas -> tingkat -> program_keahlian
$sql = "SELECT 
            sp.sekolah_tujuan, 
            sp.alasan_pindah,
            sk.no_surat,
            sk.tanggal_pembuatan_surat,
            s.nama_siswa, 
            s.nis,
            t.tingkat, 
            pk.program_keahlian, 
            k.rombel 
        FROM surat_pindah sp
        JOIN surat_keluar sk ON sp.id_surat_pindah = sk.id_surat_pindah
        JOIN siswa s ON sk.nis = s.nis
        JOIN kelas k ON s.id_kelas = k.id_kelas
        JOIN tingkat t ON k.id_tingkat = t.id_tingkat 
        JOIN program_keahlian pk ON k.id_program_keahlian = pk.id_program_keahlian";

// Logika Filter berdasarkan Tingkat
if ($filter_tingkat != '') {
    $sql .= " WHERE t.id_tingkat = '" . mysqli_real_escape_string($conn, $filter_tingkat) . "'";
}

$sql .= " ORDER BY sk.tanggal_pembuatan_surat DESC";
$res = mysqli_query($conn, $sql);

// 3. Hitung Statistik Ringkasan (Total Pindah)
$total_pindah = mysqli_num_rows($res);
// Kamu bisa menambah statistik lain jika ada kolom status di db, sementara kita tampilkan total data
$total_keseluruhan = mysqli_query($conn, "SELECT COUNT(*) as total FROM surat_pindah")->fetch_assoc()['total'];

require_once $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-red-500 text-white p-4 rounded-lg shadow">
            <h4 class="text-sm uppercase font-semibold">Total Siswa Pindah (Filter)</h4>
            <p class="text-3xl font-bold"><?= $total_pindah ?></p>
        </div>
        <div class="bg-gray-800 text-white p-4 rounded-lg shadow">
            <h4 class="text-sm uppercase font-semibold">Total Seluruh Riwayat Pindah</h4>
            <p class="text-3xl font-bold"><?= $total_keseluruhan ?></p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <h3 class="text-xl font-bold text-gray-800">Daftar Surat Pindah Sekolah</h3>

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
                        <th class="p-4 font-semibold text-gray-600">No. Surat / Tanggal</th>
                        <th class="p-4 font-semibold text-gray-600">Nama Siswa</th>
                        <th class="p-4 font-semibold text-gray-600 text-center">Kelas Asal</th>
                        <th class="p-4 font-semibold text-gray-600">Sekolah Tujuan</th>
                        <th class="p-4 font-semibold text-gray-600">Alasan</th>
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
                                <td class="p-4">
                                    <span class="block font-mono text-sm text-blue-600"><?= $row['no_surat'] ?></span>
                                    <span class="text-xs text-gray-400"><?= date('d/m/Y', strtotime($row['tanggal_pembuatan_surat'])) ?></span>
                                </td>
                                <td class="p-4">
                                    <span class="font-medium text-gray-800"><?= htmlspecialchars($row['nama_siswa']) ?></span>
                                    <span class="block text-xs text-gray-400">NIS: <?= $row['nis'] ?></span>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="text-xs font-medium px-2 py-1 bg-gray-100 rounded text-gray-600">
                                        <?= $row['tingkat'] . ' ' . $row['program_keahlian'] . ' ' . $row['rombel'] ?>
                                    </span>
                                </td>
                                <td class="p-4 text-sm text-gray-700 font-semibold">
                                    <?= htmlspecialchars($row['sekolah_tujuan']) ?>
                                </td>
                                <td class="p-4 text-sm text-gray-500 italic">
                                    "<?= htmlspecialchars($row['alasan_pindah']) ?>"
                                </td>
                            </tr>
                        <?php
                        endwhile;
                    else:
                        ?>
                        <tr>
                            <td colspan="6" class="p-10 text-center text-gray-400 italic">Data surat pindah tidak ditemukan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../../../asset/layout/footer.php'; ?>