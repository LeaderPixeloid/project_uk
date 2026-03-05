<?php
session_start();
require_once '../config/database.php';

if (isset($_POST['submit'])) {
    $nis = $_POST['nis'];
    $id_jenis = $_POST['id_jenis_pelanggaran'];
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);

    // Query Insert (Tanggal otomatis pake NOW())
    $query = "INSERT INTO pelanggaran_siswa (nis, id_jenis_pelanggaran, keterangan, tanggal) 
              VALUES ('$nis', '$id_jenis', '$keterangan', NOW())";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Data pelanggaran berhasil dicatat!');
                window.location.href = 'index.php';
              </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}