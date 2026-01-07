<?php
require '../config/auth_check.php';
require '../config/database.php';

if($_SESSION['role'] != 'admin'){
    die("Akses ditolak");
}

$success = "";

if(isset($_POST['register'])){
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    mysqli_query($conn, "INSERT INTO users VALUES(
        null,'$nama','$username','$password','$role'
    )");

    $success = "User berhasil ditambahkan!";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register User</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="bg-white p-8 rounded shadow-md w-96">
    <h2 class="text-xl font-bold mb-4 text-center">Register User</h2>

    <?php if($success): ?>
        <div class="bg-green-100 text-green-600 p-2 mb-3 rounded">
            <?= $success ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="nama" placeholder="Nama Lengkap"
            class="w-full p-2 border rounded mb-3" required>

        <input type="text" name="username" placeholder="Username"
            class="w-full p-2 border rounded mb-3" required>

        <input type="password" name="password" placeholder="Password"
            class="w-full p-2 border rounded mb-3" required>

        <select name="role" class="w-full p-2 border rounded mb-4" required>
            <option value="">-- Pilih Role --</option>
            <option value="guru">Guru / BK</option>
            <option value="manajemen">Manajemen</option>
        </select>

        <button name="register"
            class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">
            Simpan
        </button>
    </form>

    <a href="../dashboard/index.php"
       class="block text-center mt-4 text-blue-600">
        Kembali ke Dashboard
    </a>
</div>

</body>
</html>
