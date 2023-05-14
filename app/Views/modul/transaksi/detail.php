<!DOCTYPE html>
<html>
<head>
    <title>Detail Gaji</title>
    <!-- Tambahkan referensi ke file JavaScript yang akan digunakan -->
    <script src="<?= base_url('js/gaji_detail.js') ?>"></script>
</head>
<body>
    <h1>Detail Gaji</h1>
    <table>
        <tr>
            <td>ID:</td>
            <td><?= $gaji['id'] ?></td>
        </tr>
        <tr>
            <td>Tanggal:</td>
            <td><?= $gaji['tanggal'] ?></td>
        </tr>
        <tr>
            <td>Nama Karyawan:</td>
            <td><?= $gaji['nama_karyawan'] ?></td>
        </tr>
        <tr>
            <td>Gaji Pokok:</td>
            <td><?= $gaji['gaji_pokok'] ?></td>
        </tr>
        <tr>
            <td>Tunjangan:</td>
            <td><?= $gaji['tunjangan'] ?></td>
        </tr>
        <tr>
            <td>Total Gaji:</td>
            <td><?= $gaji['total_gaji'] ?></td>
        </tr>
    </table>
    <!-- Tambahkan form untuk menghapus transaksi gaji -->
    <form id="form-delete" method="POST" action="<?= base_url('gaji/delete/' . $gaji['id']) ?>">
        <button type="submit">Hapus</button>
    </form>
</body>
</html>
