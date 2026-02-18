<?php
session_start();
require '../config/database.php';

if (!isset($_SESSION['login_admin'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id = $_GET['id'];

// Cek apakah dipakai di pelanggaran_siswa
$cek = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) as total
    FROM pelanggaran_siswa
    WHERE id_jenis_pelanggaran='$id'
"));

if ($cek['total'] > 0) {
    echo "<script>
        alert('Tidak bisa dihapus karena sudah dipakai oleh siswa!');
        window.location='index.php';
    </script>";
    exit;
}

mysqli_query($conn, "
    DELETE FROM jenis_pelanggaran
    WHERE id_jenis_pelanggaran='$id'
");

header("Location: index.php");
exit;
