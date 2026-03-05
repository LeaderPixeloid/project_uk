<?php
$host = "localhost";
$user = "root";
$pass = ""; // default laragon kosong
$db   = "poin_pelanggaran_siswa"; // GANTI sesuai phpMyAdmin

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
