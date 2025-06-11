<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=login&error=Anda harus login untuk melihat halaman ini.");
    exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT p.kode_booking, p.tanggal_kunjungan, p.jumlah_tiket, p.total_harga, w.nama_wisata, w.lokasi
        FROM pemesanan p
        JOIN objek_wisata w ON p.wisata_id = w.id
        WHERE p.user_id = ?
        ORDER BY p.tanggal_pemesanan DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<div class="page-header">
    <h1>E-Tiket Saya</h1>
    <p>Ini adalah daftar tiket yang telah Anda pesan.</p>
</div>
<?php if(isset($_GET['success'])): ?>
    <p class="success-msg"><?= htmlspecialchars($_GET['success']) ?></p>
<?php endif; ?>

<div class="ticket-list">
    <?php if ($result->num_rows > 0): ?>
        <?php while($tiket = $result->fetch_assoc()): ?>
            <div class="ticket-card">
                <div class="ticket-header">
                    <h3><?= htmlspecialchars($tiket['nama_wisata']) ?></h3>
                    <span class="ticket-code"><?= htmlspecialchars($tiket['kode_booking']) ?></span>
                </div>
                <div class="ticket-body">
                    <p><strong>Lokasi:</strong> <?= htmlspecialchars($tiket['lokasi']) ?></p>
                    <p><strong>Tanggal Kunjungan:</strong> <?= date('d F Y', strtotime($tiket['tanggal_kunjungan'])) ?></p>
                    <p><strong>Jumlah Tiket:</strong> <?= htmlspecialchars($tiket['jumlah_tiket']) ?> orang</p>
                </div>
                <div class="ticket-footer">
                    <p><strong>Total Bayar:</strong> Rp <?= number_format($tiket['total_harga'], 0, ',', '.') ?></p>
                    <span>Status: LUNAS</span>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Anda belum memiliki tiket. Ayo <a href="index.php?page=home">pesan sekarang</a>!</p>
    <?php endif; ?>
</div>



