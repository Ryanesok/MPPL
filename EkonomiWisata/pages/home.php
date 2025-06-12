
<?php
$sql = "SELECT id, nama_wisata, lokasi, harga_tiket, gambar_url FROM objek_wisata";
$result = $conn->query($sql);
?>
<div class="page-header">
    <h1>Selamat Datang di WisataYuk!</h1>
    <p>Temukan destinasi wisata impian Anda di seluruh Indonesia.</p>
</div>

<div class="wisata-grid">
    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="wisata-card">
                <img src="<?= htmlspecialchars($row['gambar_url']) ?>" alt="Gambar <?= htmlspecialchars($row['nama_wisata']) ?>" onerror="this.onerror=null;this.src='https://placehold.co/600x400/e2e8f0/e2e8f0?text=Error';">
                <div class="card-content">
                    <h3><?= htmlspecialchars($row['nama_wisata']) ?></h3>
                    <p class="location"><?= htmlspecialchars($row['lokasi']) ?></p>
                    <p class="price">Rp <?= number_format($row['harga_tiket'], 0, ',', '.') ?></p>
                    <a href="index.php?page=detail_wisata&id=<?= $row['id'] ?>" class="btn">Lihat Detail</a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Belum ada data objek wisata.</p>
    <?php endif; ?>
</div>


