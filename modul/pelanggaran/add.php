<?php
// 1. Memulai session untuk memastikan status login user
session_start();

// 2. Menghubungkan ke database agar bisa mengirim data (INSERT)
require '../config/database.php';
// Cara ini lebih aman karena mencari dari folder utama project
require_once $_SERVER['DOCUMENT_ROOT'] . '/project_uk/layout/header.php';


// 3. KEAMANAN: Cek apakah user adalah Admin.
// Jika tidak login sebagai admin, dilarang masuk ke halaman tambah data ini.
if (!isset($_SESSION['login_admin'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Menyiapkan variabel kosong untuk menampung pesan error jika input tidak valid
$error = "";

// 4. CEK TOMBOL: Memeriksa apakah user sudah menekan tombol bertipe submit dengan name 'simpan'
if (isset($_POST['simpan'])) {

    // 5. AMBIL DATA FORM: Mengambil inputan dari user melalui metode POST
    // trim() digunakan untuk menghapus spasi kosong di awal/akhir tulisan agar data bersih
    $jenis = trim($_POST['jenis']);
    // (int) adalah type casting untuk memastikan data yang masuk adalah angka bulat
    $poin  = (int) $_POST['poin'];

    // 6. VALIDASI: Cek apakah inputan sudah sesuai aturan
    // Nama pelanggaran tidak boleh kosong, dan poin minimal harus angka 1 ke atas
    if ($jenis == "" || $poin <= 0) {
        $error = "Jenis dan poin wajib diisi dengan benar!";
    } else {

        // 7. QUERY INSERT: Perintah SQL untuk memasukkan data baru ke tabel 'jenis_pelanggaran'
        // Kolom id_jenis_pelanggaran biasanya Auto Increment, jadi tidak perlu ditulis di sini
        mysqli_query($conn, "
            INSERT INTO jenis_pelanggaran (jenis, poin)
            VALUES ('$jenis', '$poin')
        ");

        // 8. REDIRECT: Setelah berhasil menyimpan ke database, pindahkan user kembali ke halaman utama (index)
        header("Location: index.php");
        exit;
    }
}

// 9. LAYOUT: Mengatur judul tab browser dan memanggil file header (atas)
$title = "Add Data Pelanggaran";
require '../asset/layout/header.php';
?>

<!DOCTYPE html>
<html>

<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">

    <div class="max-w-md mx-auto bg-white p-6 rounded shadow">

        <h2 class="text-xl font-bold mb-4 text-gray-800">Tambah Jenis Pelanggaran</h2>

        <?php if ($error): ?>
            <div class="bg-red-100 text-red-600 p-2 mb-4 rounded border border-red-200 text-sm">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">

            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700">Nama Pelanggaran</label>
                <input type="text" name="jenis"
                    placeholder="Contoh: Terlambat, Rambut Panjang"
                    class="w-full p-2 border rounded focus:ring-2 focus:ring-blue-500 outline-none"
                    required>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700">Poin</label>
                <input type="number" name="poin"
                    placeholder="Masukkan angka poin"
                    class="w-full p-2 border rounded focus:ring-2 focus:ring-blue-500 outline-none"
                    min="1"
                    required>
            </div>

            <div class="flex justify-between items-center pt-2">
                <a href="index.php" class="text-gray-500 hover:text-gray-700 text-sm">Kembali</a>

                <button name="simpan"
                    class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 shadow-md transition duration-200">
                    Simpan Data
                </button>
            </div>

        </form>
    </div>

</body>

</html>

<?php
// 10. Memanggil file footer untuk menutup tag HTML dan layout
require '../asset/layout/footer.php'; ?>