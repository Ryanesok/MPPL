
<?php
if (!isset($_GET['id'])) {
    echo "Halaman tidak ditemukan.";
    exit;
}
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM objek_wisata WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $wisata = $result->fetch_assoc();
} else {
    echo "Objek wisata tidak ditemukan.";
    exit;
}
?>

<div class="detail-container">
    <img src="<?= htmlspecialchars($wisata['gambar_url']) ?>" alt="Gambar <?= htmlspecialchars($wisata['nama_wisata']) ?>" class="detail-img" onerror="this.onerror=null;this.src='https://placehold.co/800x500/e2e8f0/e2e8f0?text=Error';">
    <div class="detail-info">
        <h1><?= htmlspecialchars($wisata['nama_wisata']) ?></h1>
        <p class="location"><?= htmlspecialchars($wisata['lokasi']) ?></p>
        <p class="price-detail">Harga Tiket: <strong>Rp <?= number_format($wisata['harga_tiket'], 0, ',', '.') ?> / orang</strong></p>
        <h3>Deskripsi</h3>
        <p><?= nl2br(htmlspecialchars($wisata['deskripsi'])) ?></p>
        
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="index.php?page=pesan_tiket&id=<?= $wisata['id'] ?>" class="btn btn-primary">Pesan Tiket Sekarang</a>
        <?php else: ?>
            <p class="notice">Silakan <a href="index.php?page=login">login</a> untuk memesan tiket.</p>
        <?php endif; ?>
    </div>
</div>


