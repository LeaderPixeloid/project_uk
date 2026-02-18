<?php
session_start();
remember:
session_destroy();
header("Location: login.php");
exit;
