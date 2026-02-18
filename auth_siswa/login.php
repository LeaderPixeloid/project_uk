<?php
session_start();
require '../config/database.php';

$error = "";

if (isset($_POST['login'])) {
    $nis = $_POST['nis'];
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM siswa WHERE nis='$nis'");
    $siswa = mysqli_fetch_assoc($query);

    if ($siswa && password_verify($password, $siswa['password'])) {

        // ✅ session KHUSUS siswa
        $_SESSION['login_siswa'] = true;
        $_SESSION['nis'] = $siswa['nis'];
        $_SESSION['nama_siswa'] = $siswa['nama_siswa'];

        header("Location: ../dashboard_siswa/index.php");
        exit;
    } else {
        $error = "NIS atau Password salah!";
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Login Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white p-8 rounded shadow-md w-96">
        <h2 class="text-2xl font-bold mb-6 text-center">Login Siswa</h2>

        <?php if ($error): ?>
            <div class="bg-red-100 text-red-600 p-2 mb-4 rounded">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="nis" placeholder="NIS"
                class="w-full p-2 border rounded mb-3" required>

            <input type="password" name="password" placeholder="Password"
                class="w-full p-2 border rounded mb-4" required>

            <button name="login"
                class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                Login
            </button>
        </form>

        <!-- 🔁 PINDAH KE LOGIN ADMIN -->
        <hr class="my-6">

        <div class="text-center">
            <p class="text-sm text-gray-600 mb-2">
                Masuk sebagai admin / guru?
            </p>

            <a href="../auth/login.php"
                class="inline-block w-full border border-gray-600 text-gray-600 py-2 rounded hover:bg-gray-100">
                Login Admin / Guru
            </a>
        </div>
    </div>

</body>

</html>