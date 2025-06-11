
<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=login&error=Anda harus login untuk memesan tiket.");
    exit;
}
if (!isset($_GET['id'])) {
    echo "Halaman tidak ditemukan.";
    exit;
}
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT id, nama_wisata, harga_tiket FROM objek_wisata WHERE id = ?");
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

<div class="auth-container">
    <form action="actions/booking-process.php" method="post" class="auth-form">
        <h2>Pesan Tiket untuk <?= htmlspecialchars($wisata['nama_wisata']) ?></h2>
        <?php if(isset($_GET['error'])): ?>
            <p class="error-msg"><?= htmlspecialchars($_GET['error']) ?></p>
        <?php endif; ?>
        <input type="hidden" name="wisata_id" value="<?= $wisata['id'] ?>">
        <input type="hidden" name="harga_tiket" value="<?= $wisata['harga_tiket'] ?>">

        <div class="form-group">
            <label for="tanggal_kunjungan">Tanggal Kunjungan</label>
            <input type="date" id="tanggal_kunjungan" name="tanggal_kunjungan" required min="<?= date('Y-m-d'); ?>">
        </div>
        <div class="form-group">
            <label for="jumlah_tiket">Jumlah Tiket</label>
            <input type="number" id="jumlah_tiket" name="jumlah_tiket" required min="1" value="1">
        </div>
        <button type="submit" class="btn">Proses Pembayaran</button>
    </form>
</div>


