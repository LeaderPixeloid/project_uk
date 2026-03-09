<?php
session_start();
require '../config/database.php';

if (!isset($_SESSION['login_admin'])) {
    header("Location: ../auth/login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Gunakan mysqli_real_escape_string untuk keamanan tambahan
    $id = mysqli_real_escape_string($conn, $id);

    $query = "DELETE FROM kelas WHERE id_kelas = '$id'";

    if (mysqli_query($conn, $query)) {
        header("Location: index.php?status=success");
    } else {
        // Jika gagal (misal karena masih ada siswa di kelas tersebut/FK constraint)
        echo "<script>
                alert('Gagal menghapus! Kelas mungkin masih memiliki data siswa.');
                window.location='index.php';
              </script>";
    }
} else {
    header("Location: index.php");
}
exit;
