<?php
// Pastikan path ke auth_check benar
require_once '../config/auth_check.php'; 
require_once $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/header.php';

$title = "Jenis Laporan"
?>

<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">
    <div class="max-w-2xl mx-auto">
        
        <a href="../dashboard/index.php" class="text-blue-600 hover:underline mb-6 inline-block">← Kembali ke Dashboard</a>
        
        <h1 class="text-2xl font-bold text-center mb-8 text-gray-800">Pilih Jenis Laporan</h1>

        <div class="space-y-4">
            <a href="../laporan_pelanggaran/index.php" class="flex items-center p-4 bg-white rounded-lg shadow hover:bg-blue-50 transition border-l-4 border-blue-500">
                <span class="text-2xl mr-4">📝</span>
                <span class="text-lg font-medium text-gray-700">Laporan Detail Pelanggaran Per Siswa</span>
            </a>

            <a href="../laporan_surat/rekap_surat_panggilan/index.php" class="flex items-center p-4 bg-white rounded-lg shadow hover:bg-blue-50 transition border-l-4 border-orange-500">
                <span class="text-2xl mr-4">📨</span>
                <span class="text-lg font-medium text-gray-700">Laporan Surat Panggilan yang Dikeluarkan</span>
            </a>

            <a href="../laporan_surat/rekap_surat_perjanjian/index.php" class="flex items-center p-4 bg-white rounded-lg shadow hover:bg-blue-50 transition border-l-4 border-green-500">
                <span class="text-2xl mr-4">📜</span>
                <span class="text-lg font-medium text-gray-700">Laporan Surat Perjanjian yang Dibuat</span>
            </a>

            <a href="../laporan_surat/rekap_surat_pindah/index.php" class="flex items-center p-4 bg-white rounded-lg shadow hover:bg-blue-50 transition border-l-4 border-red-500">
                <span class="text-2xl mr-4">🏠</span>
                <span class="text-lg font-medium text-gray-700">Laporan Surat Pindah Siswa</span>
            </a>
        </div>
    </div>
</body>
</html>

<?php require '../../asset/layout/footer.php'; ?>