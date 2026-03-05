<?php
session_start();
require '../config/database.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Cek login admin
if (!isset($_SESSION['login_admin'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Cek parameter
if (!isset($_GET['kode'])) {
    header("Location: index.php");
    exit;
}

$kode = mysqli_real_escape_string($conn, $_GET['kode']);

try {

    mysqli_begin_transaction($conn);

    // =============================
    // 1️⃣ Cek apakah guru jadi wali kelas
    // =============================
    $cek_kelas = mysqli_query($conn, "
        SELECT COUNT(*) as total
        FROM kelas
        WHERE kode_guru = '$kode'
    ");

    $row = mysqli_fetch_assoc($cek_kelas);

    if ($row['total'] > 0) {
        throw new Exception("Guru masih menjadi wali kelas!");
    }

    // =============================
    // 2️⃣ Hapus guru
    // =============================
    mysqli_query($conn, "
        DELETE FROM guru
        WHERE kode_guru = '$kode'
    ");

    mysqli_commit($conn);

    header("Location: index.php?status=deleted");
    exit;
} catch (Exception $e) {

    mysqli_rollback($conn);

    echo "Error: " . $e->getMessage();
    exit;
}
