<?php
// 1. Memulai session untuk mengakses data login (seperti role, username, dll)
session_start();

// 2. Menghubungkan ke file koneksi database
require '../config/database.php';

// 3. PROTEKSI: Cek apakah yang mengakses halaman ini adalah Admin yang sudah login
// Jika tidak ada session 'login_admin', maka dilempar paksa ke halaman login
if (!isset($_SESSION['login_admin'])) {
    header("Location: ../auth/login.php");
    exit;
}

// 4. MENGAMBIL DATA: Mengambil ID jenis pelanggaran yang dikirim melalui URL (metode GET)
// Contoh: hapus.php?id=5
$id = $_GET['id'];

// 5. VALIDASI RELASI (Penting!):
// Sebelum menghapus, kita harus cek apakah "Jenis Pelanggaran" ini sudah pernah tercatat pada siswa.
// Jika asal hapus, data poin pelanggaran siswa di tabel lain bisa error/hilang.
$query_cek = mysqli_query($conn, "
    SELECT COUNT(*) as total
    FROM pelanggaran_siswa
    WHERE id_jenis_pelanggaran='$id'
");

// Mengambil hasil perhitungan dari database
$cek = mysqli_fetch_assoc($query_cek);

// 6. LOGIKA PENCEGAHAN:
// Jika jumlahnya (total) lebih dari 0, berarti ada siswa yang punya catatan pelanggaran jenis ini.
if ($cek['total'] > 0) {
    // Tampilkan peringatan dan batalkan penghapusan
    echo "<script>
        alert('Tidak bisa dihapus karena sudah dipakai oleh siswa! Hapus dulu data pelanggaran siswanya.');
        window.location='index.php';
    </script>";
    exit; // Berhenti di sini, kode di bawah tidak akan dijalankan
}

// 7. EKSEKUSI HAPUS:
// Jika pemeriksaan di atas lolos (total = 0), maka data boleh dihapus dari tabel induk
mysqli_query($conn, "
    DELETE FROM jenis_pelanggaran
    WHERE id_jenis_pelanggaran='$id'
");

// 8. SELESAI: Kembalikan tampilan ke halaman utama jenis pelanggaran
header("Location: index.php");
exit;
