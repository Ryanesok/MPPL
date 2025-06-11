
<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'dinas') {
    header("Location: index.php?page=login&error=Akses ditolak.");
    exit;
}

// Data untuk dinas: Semua pemesanan
$sql_pemesanan = "SELECT p.kode_booking, u.nama_lengkap, w.nama_wisata, p.tanggal_kunjungan, p.jumlah_tiket, p.status_pembayaran
                  FROM pemesanan p
                  JOIN users u ON p.user_id = u.id
                  JOIN objek_wisata w ON p.wisata_id = w.id
                  ORDER BY p.tanggal_kunjungan DESC";
$semua_pemesanan = $conn->query($sql_pemesanan);
?>

<div class="page-header">
    <h1>Dashboard Dinas Pariwisata</h1>
    <p>Halaman ini menampilkan semua data e-tiket yang telah diterbitkan untuk validasi.</p>
</div>

<div class="management-section">
    <h3>Validasi E-Tiket Wisatawan</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Kode Booking</th>
                <th>Nama Wisatawan</th>
                <th>Objek Wisata</th>
                <th>Tgl Kunjungan</th>
                <th>Jumlah</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($semua_pemesanan->num_rows > 0): ?>
                <?php while($row = $semua_pemesanan->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['kode_booking']) ?></td>
                    <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                    <td><?= htmlspecialchars($row['nama_wisata']) ?></td>
                    <td><?= htmlspecialchars($row['tanggal_kunjungan']) ?></td>
                    <td><?= htmlspecialchars($row['jumlah_tiket']) ?></td>
                    <td><span class="status-paid"><?= strtoupper(htmlspecialchars($row['status_pembayaran'])) ?></span></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6">Belum ada data pemesanan.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>


