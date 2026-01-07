<?php
$host = "localhost";
$user = "root";
$pass = ""; // default laragon kosong
$db   = "pelanggaran_siswa_rpl4"; // GANTI sesuai phpMyAdmin

$conn = mysqli_connect($host, $user, $pass, $db);

if(!$conn){
    die("Koneksi gagal: " . mysqli_connect_error());
}
