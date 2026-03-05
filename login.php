<?php
session_start();
require_once __DIR__ . '/modul/config/database.php';

$error = "";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM guru WHERE username='$username'");
    $user = mysqli_fetch_assoc($query);

    if ($user && password_verify($password, $user['password'])) {

        // ✅ session KHUSUS admin
        $_SESSION['login_admin'] = true;
        $_SESSION['kode_guru'] = $user['kode_guru'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        header("Location: modul/dashboard/index.php");
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
        <h2 class="text-2xl font-bold mb-6 text-center">Login Admin / Guru</h2>

        <?php if ($error): ?>
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

        <!-- 🔽 TOMBOL PINDAH KE LOGIN SISWA (TAMBAHAN AMAN) -->
        <hr class="my-6">

        <div class="text-center">
            <p class="text-sm text-gray-600 mb-2">
                Masuk sebagai siswa?
            </p>

            <a href="modul/auth_siswa/login.php"
                class="inline-block w-full border border-blue-600 text-blue-600 py-2 rounded hover:bg-blue-50">
                Login Siswa
            </a>
        </div>
    </div>

</body>

</html>