<?php
session_start();

/*
 Hapus data user saja
 Jangan session_destroy()
*/
unset($_SESSION['id']);
unset($_SESSION['nama']);
unset($_SESSION['role']);
unset($_SESSION['login']);

// Redirect ke login
header("Location: login.php");
exit;
