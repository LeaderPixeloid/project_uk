<?php
session_start();
require '../config/database.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (!isset($_SESSION['login_admin'])) {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_GET['nis'])) {
    header("Location: index.php");
    exit;
}

$nis = mysqli_real_escape_string($conn, $_GET['nis']);

try {
    mysqli_begin_transaction($conn);

    // 1. Ambil id_ortu_wali SEBELUM siswa dihapus
    $result = mysqli_query($conn, "SELECT id_ortu_wali FROM siswa WHERE nis = '$nis'");
    $data = mysqli_fetch_assoc($result);

    if (!$data) {
        throw new Exception("Data siswa tidak ditemukan");
    }

    $id_ortu = $data['id_ortu_wali'];

    // 2. Hapus data relasi (surat_keluar & pelanggaran)
    mysqli_query($conn, "DELETE FROM surat_keluar WHERE nis = '$nis'");
    mysqli_query($conn, "DELETE FROM pelanggaran_siswa WHERE nis = '$nis'");

    // 3. Hapus data siswa
    mysqli_query($conn, "DELETE FROM siswa WHERE nis = '$nis'");

    // 4. Hapus data ortu_wali
    if (!empty($id_ortu)) {
        // Kita cek: apakah id_ortu ini masih punya anak lain di tabel siswa?
        $cek_anak_lain = mysqli_query($conn, "SELECT nis FROM siswa WHERE id_ortu_wali = '$id_ortu'");

        // Jika jumlah barisnya 0, berarti orang tua ini sudah tidak punya anak lagi di sekolah ini
        if (mysqli_num_rows($cek_anak_lain) == 0) {
            mysqli_query($conn, "DELETE FROM ortu_wali WHERE id_ortu_wali = '$id_ortu'");
        }
    }

    mysqli_commit($conn);
    header("Location: index.php?status=deleted");
    exit;
} catch (Exception $e) {
    mysqli_rollback($conn);
    echo "Gagal menghapus data: " . $e->getMessage();
    exit;
}
