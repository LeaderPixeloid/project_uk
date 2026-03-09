<?php
session_start();
require '../config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/project_uk/asset/layout/header.php';

if (!isset($_SESSION['login_admin'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Ambil data untuk dropdown
$tingkat = mysqli_query($conn, "SELECT * FROM tingkat");
$jurusan = mysqli_query($conn, "SELECT * FROM program_keahlian");
$guru    = mysqli_query($conn, "SELECT kode_guru, nama_pengguna FROM guru WHERE aktif='Y'");

if (isset($_POST['submit'])) {
    $id_tingkat = $_POST['id_tingkat'];
    $id_jurusan = $_POST['id_program_keahlian'];
    $rombel     = $_POST['rombel'];
    $kode_guru  = $_POST['kode_guru'];

    $query = "INSERT INTO kelas (id_tingkat, id_program_keahlian, rombel, kode_guru) 
              VALUES ('$id_tingkat', '$id_jurusan', '$rombel', '$kode_guru')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data Kelas Berhasil Ditambah!'); window.location='index.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
$title = "Tambah Data Kelas";
?>

<body class="bg-gray-100 p-6">
    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-6 italic">Tambah Data Kelas Baru</h2>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1">Tingkat (X/XI/XII)</label>
                <select name="id_tingkat" class="w-full p-2 border rounded" required>
                    <option value="">-- Pilih Tingkat --</option>
                    <?php while ($t = mysqli_fetch_assoc($tingkat)): ?>
                        <option value="<?= $t['id_tingkat'] ?>"><?= $t['tingkat'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Program Keahlian</label>
                <select name="id_program_keahlian" class="w-full p-2 border rounded" required>
                    <option value="">-- Pilih Jurusan --</option>
                    <?php while ($j = mysqli_fetch_assoc($jurusan)): ?>
                        <option value="<?= $j['id_program_keahlian'] ?>"><?= $j['program_keahlian'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Rombel (Angka)</label>
                <input type="number" name="rombel" placeholder="Contoh: 1" class="w-full p-2 border rounded" required>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Wali Kelas</label>
                <select name="kode_guru" class="w-full p-2 border rounded" required>
                    <option value="">-- Pilih Guru --</option>
                    <?php while ($g = mysqli_fetch_assoc($guru)): ?>
                        <option value="<?= $g['kode_guru'] ?>"><?= $g['nama_pengguna'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="flex justify-between pt-4">
                <a href="index.php" class="text-gray-600 hover:underline">Kembali</a>
                <button name="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</body>
<?php require '../../asset/layout/footer.php'; ?>