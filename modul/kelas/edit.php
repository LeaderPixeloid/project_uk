<?php
session_start();
require '../config/database.php';


if (!isset($_SESSION['login_admin'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

// Ambil data kelas lengkap
$data = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT 
        kelas.*,
        tingkat.tingkat,
        program_keahlian.program_keahlian
    FROM kelas
    JOIN tingkat ON kelas.id_tingkat = tingkat.id_tingkat
    JOIN program_keahlian 
        ON kelas.id_program_keahlian = program_keahlian.id_program_keahlian
    WHERE kelas.id_kelas = $id
"));

// Ambil daftar guru untuk wali kelas
$guru = mysqli_query($conn, "
    SELECT kode_guru, nama_pengguna 
    FROM guru 
    WHERE aktif='Y'
    ORDER BY nama_pengguna ASC
");

if (isset($_POST['update'])) {
    $kode_guru = mysqli_real_escape_string($conn, $_POST['kode_guru']);
    mysqli_query($conn, "UPDATE kelas SET kode_guru = '$kode_guru' WHERE id_kelas = $id");
    header("Location: index.php");
    exit;
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/header.php';
$title = "Edit Wali Kelas";
?>

<div class="max-w-3xl mx-auto px-4 py-12">
    <div class="mb-8 text-center">
        <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight sm:text-3xl">
            Penugasan Wali Kelas
        </h1>
        <p class="mt-2 text-gray-500">Perbarui informasi guru yang bertanggung jawab atas kelas ini.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="p-8">
            <form method="POST" class="space-y-6">

                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-600 rounded-lg text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-blue-600 font-bold uppercase tracking-wider">Sedang Mengedit Kelas</p>
                            <p class="text-lg font-bold text-blue-900">
                                <?= $data['tingkat'] . ' ' . $data['program_keahlian'] . ' ' . $data['rombel']; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700 ml-1">
                        Pilih Wali Kelas Baru
                    </label>
                    <div class="relative">
                        <select name="kode_guru"
                            class="block w-full pl-4 pr-10 py-3 text-base border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 rounded-xl transition duration-200 appearance-none bg-gray-50"
                            required>
                            <option value="" disabled>-- Pilih Guru --</option>
                            <?php while ($g = mysqli_fetch_assoc($guru)): ?>
                                <option value="<?= $g['kode_guru'] ?>"
                                    <?= $g['kode_guru'] == $data['kode_guru'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($g['nama_pengguna']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-[11px] text-gray-400 italic ml-1">*Hanya guru dengan status aktif yang muncul di daftar.</p>
                </div>

                <hr class="border-gray-100 my-6">

                <div class="flex items-center justify-between gap-4 pt-2">
                    <a href="index.php"
                        class="inline-flex items-center px-6 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Batalkan
                    </a>

                    <button type="submit" name="update"
                        class="inline-flex items-center px-8 py-3 border border-transparent text-sm font-bold rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 shadow-lg shadow-blue-500/30 transition-all active:scale-95">
                        Simpan Perubahan
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </button>
                </div>

            </form>
        </div>
    </div>

    <p class="mt-8 text-center text-xs text-gray-400 tracking-wide uppercase">
        Sistem Informasi Akademik &copy; 2026
    </p>
</div>

<?php require '../../asset/layout/footer.php'; ?>