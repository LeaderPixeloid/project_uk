<?php
session_start();

if(!isset($_SESSION['login']) || !isset($_SESSION['role'])){
    header("Location: ../auth/login.php");
    exit;
}
