<?php
session_start();

// 1. CEK: Apakah ada session login sama sekali?
if (!isset($_SESSION['login_admin']) && !isset($_SESSION['login_siswa'])) {
    header("Location: ../../login.php");
    exit;
}

// 2. TAMBAHAN: Logika Session Timeout (Contoh: 10 Menit)
$timeout_duration = 300; // 600 detik = 10 menit

if (isset($_SESSION['last_activity'])) {
    // Hitung selisih waktu sekarang dengan aktivitas terakhir
    $elapsed_time = time() - $_SESSION['last_activity'];
    
    if ($elapsed_time > $timeout_duration) {
        // Jika sudah melewati batas, hapus session dan tendang keluar
        session_unset();
        session_destroy();
        header("Location: ../../login.php?pesan=timeout");
        exit;
    }
}

// Update waktu aktivitas terakhir setiap kali halaman diakses
$_SESSION['last_activity'] = time();

// 3. LOGIKA REDIRECT (Folder Protection): 
if (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) {
    if (!isset($_SESSION['login_admin'])) {
        header("Location: ../../login.php");
        exit;
    }
}