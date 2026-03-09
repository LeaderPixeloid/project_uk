<?php
// Pastikan path ke auth_check benar
// require_once '../config/auth_check.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/header.php';

$title = "Pusat Laporan Kedisiplinan";
?>

<div class="max-w-5xl mx-auto px-4 py-12">
    <div class="text-center mb-12">
        <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Pusat Laporan & Rekapitulasi</h1>
        <p class="text-slate-500 mt-2">Pilih kategori laporan untuk melihat, mencetak, atau mengekspor data kedisiplinan siswa.</p>
        <div class="w-20 h-1.5 bg-indigo-600 mx-auto mt-4 rounded-full"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <a href="../laporan_pelanggaran/index.php" class="group relative bg-white p-6 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-blue-100 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                <span class="text-6xl text-blue-600">📝</span>
            </div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-4 text-2xl font-bold">01</div>
                <h3 class="text-xl font-bold text-slate-800">Detail Pelanggaran</h3>
                <p class="text-slate-500 text-sm mt-2 leading-relaxed text-pretty">Rekapitulasi poin dan jenis pelanggaran lengkap untuk setiap individu siswa.</p>
                <div class="mt-6 flex items-center text-blue-600 text-sm font-bold">
                    Lihat Laporan <span class="ml-2 group-hover:translate-x-2 transition-transform">→</span>
                </div>
            </div>
        </a>

        <a href="../laporan_surat/rekap_surat_panggilan/index.php" class="group relative bg-white p-6 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-orange-100 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                <span class="text-6xl text-orange-600">📨</span>
            </div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center mb-4 text-2xl font-bold">02</div>
                <h3 class="text-xl font-bold text-slate-800">Surat Panggilan</h3>
                <p class="text-slate-500 text-sm mt-2 leading-relaxed">Daftar pemanggilan orang tua siswa berdasarkan akumulasi poin tertentu.</p>
                <div class="mt-6 flex items-center text-orange-600 text-sm font-bold">
                    Lihat Rekap <span class="ml-2 group-hover:translate-x-2 transition-transform">→</span>
                </div>
            </div>
        </a>

        <a href="../laporan_surat/rekap_surat_perjanjian/index.php" class="group relative bg-white p-6 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-green-100 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                <span class="text-6xl text-green-600">📜</span>
            </div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center mb-4 text-2xl font-bold">03</div>
                <h3 class="text-xl font-bold text-slate-800">Surat Perjanjian</h3>
                <p class="text-slate-500 text-sm mt-2 leading-relaxed text-pretty">Arsip surat pernyataan siswa untuk tidak mengulangi pelanggaran (SP1 - SP3).</p>
                <div class="mt-6 flex items-center text-green-600 text-sm font-bold">
                    Buka Arsip <span class="ml-2 group-hover:translate-x-2 transition-transform">→</span>
                </div>
            </div>
        </a>

        <a href="../laporan_surat/rekap_surat_pindah/index.php" class="group relative bg-white p-6 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-red-100 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                <span class="text-6xl text-red-600">🏠</span>
            </div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center mb-4 text-2xl font-bold">04</div>
                <h3 class="text-xl font-bold text-slate-800">Mutasi / Pindah</h3>
                <p class="text-slate-500 text-sm mt-2 leading-relaxed text-pretty">Laporan siswa yang mengundurkan diri atau dikembalikan ke orang tua.</p>
                <div class="mt-6 flex items-center text-red-600 text-sm font-bold">
                    Cek Mutasi <span class="ml-2 group-hover:translate-x-2 transition-transform">→</span>
                </div>
            </div>
        </a>

    </div>

    <div class="mt-12 text-center">
        <a href="../dashboard/index.php" class="inline-flex items-center px-6 py-3 bg-slate-100 text-slate-600 rounded-full font-bold hover:bg-slate-200 transition-all group">
            <span class="mr-2 group-hover:-translate-x-1 transition-transform">←</span> Kembali ke Dashboard Utama
        </a>
    </div>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/footer.php'; ?>