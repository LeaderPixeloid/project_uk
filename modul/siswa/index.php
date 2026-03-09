<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/project_uk');
require_once ROOTPATH . '/asset/layout/header.php';
include ROOTPATH . "/modul/config/database.php";

$result = mysqli_query($conn, "
    SELECT siswa.*, ortu_wali.*, kelas.*, tingkat.tingkat, program_keahlian.program_keahlian, guru.nama_pengguna as nama_wali_kelas
    FROM siswa 
    JOIN ortu_wali ON siswa.id_ortu_wali = ortu_wali.id_ortu_wali 
    JOIN kelas ON siswa.id_kelas = kelas.id_kelas 
    JOIN tingkat ON kelas.id_tingkat = tingkat.id_tingkat 
    JOIN program_keahlian ON kelas.id_program_keahlian = program_keahlian.id_program_keahlian 
    JOIN guru ON kelas.kode_guru = guru.kode_guru
    ORDER BY tingkat.id_tingkat ASC, kelas.rombel ASC, siswa.nama_siswa ASC
");
?>

<div class="max-w-7xl mx-auto px-4 py-8 space-y-6">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Database Siswa</h2>
            <p class="text-slate-500 text-sm">Total terdaftar: <span class="font-bold text-blue-600"><?= mysqli_num_rows($result) ?> Siswa</span></p>
        </div>
        <div class="flex gap-3">
            <a href="../dashboard/index.php" class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-xl transition font-medium text-sm flex items-center">
                ← Dashboard
            </a>
            <a href="add.php" class="bg-blue-600 text-white px-5 py-2 rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-100 font-bold text-sm flex items-center gap-2">
                <span>+</span> Tambah Siswa
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 text-[11px] uppercase tracking-wider text-slate-400 font-bold border-b border-slate-100">
                        <th class="px-6 py-4 text-center">No</th>
                        <th class="px-6 py-4">Biodata Siswa</th>
                        <th class="px-6 py-4">Kelas & Wali</th>
                        <th class="px-6 py-4">Orang Tua / Wali</th>
                        <th class="px-6 py-4">Alamat</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php $no = 1;
                    while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-6 py-4 text-sm text-slate-400 text-center font-mono"><?= $no++ ?></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xs shadow-sm">
                                        <?= substr($row['nama_siswa'], 0, 1) ?>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-700"><?= htmlspecialchars($row['nama_siswa']) ?></div>
                                        <div class="text-[10px] text-slate-400 font-mono tracking-tighter">NIS: <?= $row['nis'] ?> • <?= $row['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs font-bold text-blue-700 bg-blue-50 px-2 py-1 rounded inline-block mb-1">
                                    <?= $row['tingkat'] . ' ' . $row['program_keahlian'] . ' ' . $row['rombel'] ?>
                                </div>
                                <div class="text-[10px] text-slate-400 flex items-center gap-1">
                                    👨‍🏫 <?= $row['nama_wali_kelas'] ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-[11px] text-slate-600 space-y-1">
                                    <?php if (!empty($row['ayah'])): ?>
                                        <p><span class="text-slate-400 font-medium">Bapak:</span> <?= htmlspecialchars($row['ayah']) ?></p>
                                    <?php endif; ?>

                                    <?php if (!empty($row['ibu'])): ?>
                                        <p><span class="text-slate-400 font-medium">Ibu:</span> <?= htmlspecialchars($row['ibu']) ?></p>
                                    <?php endif; ?>

                                    <div class="mt-2 flex items-center gap-1">
                                        <span class="p-1 bg-green-100 text-green-600 rounded-md">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.445 0 .081 5.363.079 11.969c0 2.112.552 4.171 1.597 6.01L0 24l6.193-1.623a11.814 11.814 0 005.852 1.554h.005c6.604 0 11.967-5.363 11.97-11.97a11.815 11.815 0 00-3.393-8.474" />
                                            </svg>
                                        </span>
                                        <span class="text-blue-600 font-bold tracking-wide"><?= !empty($row['no_telp']) ? $row['no_telp'] : '-' ?></span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-[11px] text-slate-500 max-w-[150px] truncate" title="<?= $row['alamat'] ?>">
                                <?= $row['alamat'] ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="edit.php?id=<?= $row['nis'] ?>" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition shadow-sm" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>
                                    <a href="delete.php?nis=<?= $row['nis'] ?>" onclick="return confirm('Hapus data siswa?')" class="p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-600 hover:text-white transition shadow-sm" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require ROOTPATH . '/asset/layout/footer.php'; ?>