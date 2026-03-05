<?php
require '/project_uk/modul/config/database.php';
require '/project_uk/modul/config/auth_check.php';

if ($_SESSION['role'] != 'Guru') {
    die("Akses ditolak");
}

$success = "";

if (isset($_POST['register'])) {
    $kode_guru = $_POST['kode_guru'];
    $nama      = $_POST['nama'];
    $username  = $_POST['username'];
    $password  = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role      = $_POST['role'];
    $jabatan   = $_POST['jabatan'];
    $telp      = $_POST['telp'];
    $aktif     = 'Y';

    mysqli_query($conn, "
        INSERT INTO guru 
        (kode_guru, nama_pengguna, role, username, password, aktif, jabatan, telp)
        VALUES
        ('$kode_guru', '$nama', '$role', '$username', '$password', '$aktif', '$jabatan', '$telp')
    ");

    $success = "User berhasil ditambahkan!";
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>Register Guru / User</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded shadow-md w-[420px]">
        <h2 class="text-xl font-bold mb-4 text-center">Register User</h2>

        <?php if ($success): ?>
            <div class="bg-green-100 text-green-600 p-2 mb-3 rounded">
                <?= $success ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-3">

            <input type="text" name="kode_guru" placeholder="Kode Guru"
                class="w-full p-2 border rounded" required>

            <input type="text" name="nama" placeholder="Nama Lengkap"
                class="w-full p-2 border rounded" required>

            <input type="text" name="username" placeholder="Username"
                class="w-full p-2 border rounded" required>

            <input type="password" name="password" placeholder="Password"
                class="w-full p-2 border rounded" required>

            <select name="role" class="w-full p-2 border rounded" required>
                <option value="">-- Pilih Role --</option>
                <option value="admin">Admin</option>
                <option value="guru">Guru</option>
                <option value="manajemen">BK</option>
            </select>

            <input type="text" name="jabatan" placeholder="Jabatan"
                class="w-full p-2 border rounded">

            <input type="text" name="telp" placeholder="No. Telp"
                class="w-full p-2 border rounded">

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