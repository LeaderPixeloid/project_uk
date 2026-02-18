<?php
session_start();
require '../config/database.php';

if (!isset($_SESSION['login_admin'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Auto generate kode guru
$last = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT kode_guru FROM guru 
    ORDER BY kode_guru DESC LIMIT 1
"));

if ($last) {
    $lastNumber = (int) substr($last['kode_guru'], -3);
    $newNumber = $lastNumber + 1;
} else {
    $newNumber = 1;
}

$kode_guru = "0021." . str_pad($newNumber, 3, "0", STR_PAD_LEFT);

// Ambil role unik dari database
$role_query = mysqli_query($conn, "
    SELECT DISTINCT role 
    FROM guru 
    WHERE role IS NOT NULL AND role != ''
");

// Ambil jabatan unik dari database
$jabatan_query = mysqli_query($conn, "
    SELECT DISTINCT jabatan 
    FROM guru 
    WHERE jabatan IS NOT NULL AND jabatan != ''
");

if (isset($_POST['simpan'])) {

    $nama     = $_POST['nama'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = $_POST['role'];
    $jabatan  = $_POST['jabatan'];
    $telp     = $_POST['telp'];

    mysqli_query($conn, "
        INSERT INTO guru (
            kode_guru, nama_pengguna, role,
            username, password, aktif, jabatan, telp
        )
        VALUES (
            '$kode_guru', '$nama', '$role',
            '$username', '$password', 'Y',
            '$jabatan', '$telp'
        )
    ");

    header("Location: index.php");
    exit;
}
$title = "Add Data Guru";
require '../layout/header.php';
?>

<!DOCTYPE html>
<html>

<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">

    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">

        <h2 class="text-xl font-bold mb-6">Tambah Guru</h2>

        <form method="POST" class="space-y-4">

            <!-- KODE -->
            <div>
                <label class="block text-sm font-medium mb-1">Kode Guru</label>
                <input value="<?= $kode_guru ?>"
                    class="w-full p-2 border rounded bg-gray-100"
                    readonly>
            </div>

            <!-- NAMA -->
            <div>
                <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
                <input name="nama"
                    class="w-full p-2 border rounded"
                    required>
            </div>

            <!-- USERNAME -->
            <div>
                <label class="block text-sm font-medium mb-1">Username</label>
                <input name="username"
                    class="w-full p-2 border rounded"
                    required>
            </div>

            <!-- PASSWORD -->
            <div>
                <label class="block text-sm font-medium mb-1">Password</label>
                <input type="password" name="password"
                    class="w-full p-2 border rounded"
                    required>
            </div>

            <!-- ROLE (DROPDOWN DARI DB) -->
            <div>
                <label class="block text-sm font-medium mb-1">Role</label>
                <select name="role"
                    class="w-full p-2 border rounded"
                    required>
                    <option value="">-- Pilih Role --</option>
                    <?php while ($r = mysqli_fetch_assoc($role_query)): ?>
                        <option value="<?= $r['role'] ?>">
                            <?= $r['role'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- JABATAN (DROPDOWN DARI DB) -->
            <div>
                <label class="block text-sm font-medium mb-1">Jabatan</label>
                <select name="jabatan"
                    class="w-full p-2 border rounded"
                    required>
                    <option value="">-- Pilih Jabatan --</option>
                    <?php while ($j = mysqli_fetch_assoc($jabatan_query)): ?>
                        <option value="<?= $j['jabatan'] ?>">
                            <?= $j['jabatan'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- TELP -->
            <div>
                <label class="block text-sm font-medium mb-1">No Telepon</label>
                <input name="telp"
                    class="w-full p-2 border rounded">
            </div>

            <!-- BUTTON -->
            <div class="flex justify-between pt-4">
                <a href="index.php"
                    class="text-gray-600 hover:underline">
                    Kembali
                </a>
                <button name="simpan"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Simpan
                </button>
            </div>

        </form>

    </div>

</body>

</html>
<?php require '../layout/footer.php'; ?>