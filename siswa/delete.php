<?php
session_start();
require '../config/database.php';

if (!isset($_SESSION['login_admin'])) {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_GET['nis'])) {
    header("Location: index.php");
    exit;
}

$nis = $_GET['nis'];

// Ambil id_ortu_wali dulu
$data = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT id_ortu_wali 
    FROM siswa 
    WHERE nis = '$nis'
"));

if (!$data) {
    header("Location: index.php");
    exit;
}

$id_ortu = $data['id_ortu_wali'];

// Mulai transaksi (biar aman)
mysqli_begin_transaction($conn);

try {

    // 1️⃣ Hapus pelanggaran siswa dulu
    mysqli_query($conn, "
        DELETE FROM pelanggaran_siswa 
        WHERE nis = '$nis'
    ");

    // 2️⃣ Hapus siswa
    mysqli_query($conn, "
        DELETE FROM siswa 
        WHERE nis = '$nis'
    ");

    // 3️⃣ Hapus ortu_wali jika ada
    if (!empty($id_ortu)) {
        mysqli_query($conn, "
            DELETE FROM ortu_wali 
            WHERE id_ortu_wali = '$id_ortu'
        ");
    }

    mysqli_commit($conn);

    header("Location: index.php?status=deleted");
    exit;
} catch (Exception $e) {

    mysqli_rollback($conn);
    header("Location: index.php?status=error");
    exit;
}
