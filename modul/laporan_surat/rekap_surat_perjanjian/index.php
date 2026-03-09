<?php
require_once '../../config/auth_check.php'; 
require_once '../../config/database.php';   

// 1. Ambil data tingkat untuk filter
$list_tingkat = mysqli_query($conn, "SELECT * FROM tingkat ORDER BY tingkat ASC");
$filter_tingkat = $_GET['tingkat'] ?? '';

// 2. Query Utama Tanpa Singkatan (Mengikuti Alur id_pelanggaran_siswa)
$sql = "SELECT 
            perjanjian_siswa.*, 
            siswa.nama_siswa, 
            siswa.nis,
            tingkat.tingkat, 
            program_keahlian.program_keahlian, 
            kelas.rombel 
        FROM perjanjian_siswa
        JOIN pelanggaran_siswa ON perjanjian_siswa.id_pelanggaran_siswa = pelanggaran_siswa.id_pelanggaran_siswa
        JOIN siswa ON pelanggaran_siswa.nis = siswa.nis
        JOIN kelas ON siswa.id_kelas = kelas.id_kelas
        JOIN tingkat ON kelas.id_tingkat = tingkat.id_tingkat 
        JOIN program_keahlian ON kelas.id_program_keahlian = program_keahlian.id_program_keahlian";

// Logika Filter
if ($filter_tingkat != '') {
    $sql .= " WHERE tingkat.id_tingkat = '" . mysqli_real_escape_string($conn, $filter_tingkat) . "'";
}

$sql .= " ORDER BY perjanjian_siswa.id_perjanjian_siswa DESC";
$res = mysqli_query($conn, $sql);

// 3. Statistik
$total_perjanjian = mysqli_num_rows($res);
$total_semua = mysqli_query($conn, "SELECT COUNT(*) as total FROM perjanjian_siswa")->fetch_assoc()['total'];

require_once $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-green-600 text-white p-4 rounded-lg shadow">
            <h4 class="text-sm uppercase font-semibold">Perjanjian (Filter Aktif)</h4>
            <p class="text-3xl font-bold"><?= $total_perjanjian ?></p>
        </div>
        <div class="bg-gray-800 text-white p-4 rounded-lg shadow">
            <h4 class="text-sm uppercase font-semibold">Total Seluruh Surat Perjanjian</h4>
            <p class="text-3xl font-bold"><?= $total_semua ?></p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <h3 class="text-xl font-bold text-gray-800">Daftar Surat Perjanjian Siswa</h3>

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

        <div class="overflow-x-auto border rounded-lg shadow-sm">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="p-4 font-semibold text-gray-600 w-12 text-center">No</th>
                        <th class="p-4 font-semibold text-gray-600">Nama Siswa / NIS</th>
                        <th class="p-4 font-semibold text-gray-600 text-center">Kelas</th>
                        <th class="p-4 font-semibold text-gray-600">ID Pelanggaran Ref</th>
                        <th class="p-4 font-semibold text-gray-600 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    <?php
                    $no = 1;
                    if (mysqli_num_rows($res) > 0):
                        while ($row = mysqli_fetch_assoc($res)):
                    ?>
                            <tr class="hover:bg-blue-50/50 transition duration-150">
                                <td class="p-4 text-center text-gray-500"><?= $no++ ?></td>
                                <td class="p-4">
                                    <span class="font-bold text-gray-900"><?= htmlspecialchars($row['nama_siswa']) ?></span>
                                    <span class="block text-xs text-gray-500 font-mono italic">NIS: <?= $row['nis'] ?></span>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="inline-block text-xs font-semibold px-2 py-1 bg-blue-100 text-blue-700 rounded-md">
                                        <?= $row['tingkat'] ?> <?= $row['program_keahlian'] ?> <?= $row['rombel'] ?>
                                    </span>
                                </td>
                                <td class="p-4 text-sm text-gray-600">
                                    <span class="px-2 py-1 bg-gray-100 rounded border text-xs">
                                        #ID-<?= $row['id_pelanggaran_siswa'] ?>
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <button onclick="window.print()" class="bg-green-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-green-700 shadow-sm transition">
                                        🖨️ Cetak
                                    </button>
                                </td>
                            </tr>
                        <?php
                        endwhile;
                    else:
                        ?>
                        <tr>
                            <td colspan="5" class="p-10 text-center text-gray-400 italic font-medium bg-gray-50">
                                Belum ada data surat perjanjian yang tercatat.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../../../asset/layout/footer.php'; ?>