<?php
session_start();
require '../config/database.php';

$error = "";

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    $user = mysqli_fetch_assoc($query);

    if($user && password_verify($password, $user['password'])){
        $_SESSION['login'] = true;
        $_SESSION['id'] = $user['id'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];

        header("Location: ../dashboard/index.php");
        exit;
    } else {
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

<div class="bg-white p-8 rounded shadow-md w-96">
    <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>

    <?php if($error): ?>
        <div class="bg-red-100 text-red-600 p-2 mb-4 rounded">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username"
            class="w-full p-2 border rounded mb-3" required>

        <input type="password" name="password" placeholder="Password"
            class="w-full p-2 border rounded mb-4" required>

        <button name="login"
            class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
            Login
        </button>
    </form>
</div>

</body>
</html>
