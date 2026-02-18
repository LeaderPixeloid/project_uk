<?php
$nis   = $_POST['nis'];
$nama  = $_POST['nama'];
$kelas = $_POST['kelas'];
$ayah  = $_POST['ayah'] ?? '-';
$ibu   = $_POST['ibu'] ?? '-';
?>

<!DOCTYPE html>
<html>

<head>
    <title>Surat Keterangan</title>

    <style>
        body {
            font-family: "Times New Roman", serif;
            margin: 60px;
        }

        .judul {
            text-align: center;
        }

        .isi {
            margin-top: 30px;
            line-height: 1.8;
        }
    </style>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>

</head>

<body>

    <div class="judul">
        <h3>SURAT PERNYATAAN SISWA</h3>
        <hr>
    </div>

    <div class="isi">

        <p>Yang bertanda tangan di bawah ini:</p>

        <table>
            <tr>
                <td>NIS</td>
                <td>: <?= $nis ?></td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>: <?= $nama ?></td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>: <?= $kelas ?></td>
            </tr>
            <tr>
                <td>Ayah</td>
                <td>: <?= $ayah ?></td>
            </tr>
            <tr>
                <td>Ibu</td>
                <td>: <?= $ibu ?></td>
            </tr>
        </table>

        <p>
            Adalah benar siswa aktif di sekolah kami.
            Demikian surat ini dibuat untuk dipergunakan sebagaimana mestinya.
        </p>

        <br><br>
        <p style="text-align:right;">
            Denpasar, <?= date("d-m-Y"); ?><br>
            Kepala Sekolah
            <br><br><br>
            (__________________)
        </p>

    </div>

</body>

</html>