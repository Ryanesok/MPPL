
<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php?page=login&error=Akses ditolak.");
    exit;
}

// Summary Report Sederhana
$total_wisata = $conn->query("SELECT COUNT(*) as total FROM objek_wisata")->fetch_assoc()['total'];
$total_pemesanan = $conn->query("SELECT COUNT(*) as total FROM pemesanan")->fetch_assoc()['total'];
$total_pendapatan_result = $conn->query("SELECT SUM(total_harga) as total FROM pemesanan WHERE status_pembayaran = 'paid'");
$total_pendapatan = $total_pendapatan_result->num_rows > 0 ? $total_pendapatan_result->fetch_assoc()['total'] : 0;

// Daftar pemesanan terakhir
$sql_pemesanan = "SELECT p.kode_booking, u.username, w.nama_wisata, p.tanggal_kunjungan, p.jumlah_tiket
                  FROM pemesanan p
                  JOIN users u ON p.user_id = u.id
                  JOIN objek_wisata w ON p.wisata_id = w.id
                  ORDER BY p.tanggal_pemesanan DESC LIMIT 5";
$pemesanan_terakhir = $conn->query($sql_pemesanan);
?>

<div class="page-header">
    <h1>Dashboard Admin</h1>
    <p>Selamat datang, <?= htmlspecialchars($_SESSION['username']) ?>!</p>
</div>

<h3>Summary Report</h3>
<div class="summary-grid">
    <div class="summary-card">
        <h4>Total Objek Wisata</h4>
        <p><?= $total_wisata ?></p>
    </div>
    <div class="summary-card">
        <h4>Total Pemesanan</h4>
        <p><?= $total_pemesanan ?></p>
    </div>
    <div class="summary-card">
        <h4>Total Pendapatan</h4>
        <p>Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></p>
    </div>
</div>

<div class="management-section">
    <h3>5 Pemesanan Terakhir</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Kode Booking</th>
                <th>Username</th>
                <th>Objek Wisata</th>
                <th>Tgl Kunjungan</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($pemesanan_terakhir->num_rows > 0): ?>
                <?php while($row = $pemesanan_terakhir->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['kode_booking']) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['nama_wisata']) ?></td>
                    <td><?= htmlspecialchars($row['tanggal_kunjungan']) ?></td>
                    <td><?= htmlspecialchars($row['jumlah_tiket']) ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">Belum ada data pemesanan.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>


