<?php
session_start();

if (!isset($_SESSION['login_admin']) || !isset($_SESSION['login_siswa'])) {
    if ($_SESSION['login_admin'] == '') {
        header("Location: ../auth/login.php");
        exit;
    } elseif ($_SESSION['login_siswa'] == '') {
        header("Location: ../auth_siswa/login.php");
        exit;
    }
}
