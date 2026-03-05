<?php
session_start();

// 1. CEK: Apakah ada session login sama sekali?
// Jika login_admin TIDAK ADA (NULL) dan login_siswa juga TIDAK ADA (NULL)
if (!isset($_SESSION['login_admin']) && !isset($_SESSION['login_siswa'])) {
    // Jika tidak ada keduanya, baru tendang ke login admin (sebagai default)
    header("Location: ../auth/login.php");
    exit;
}

// 2. LOGIKA REDIRECT (Opsional): 
// Jika kamu ingin memastikan user yang masuk ke folder 'admin' bukan siswa
if (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) {
    if (!isset($_SESSION['login_admin'])) {
        header("Location: ../auth/login.php");
        exit;
    }
}
