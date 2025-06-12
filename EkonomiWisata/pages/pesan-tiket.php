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
// Ambil jadwal operasional dari DB
$stmt_wisata = $conn->prepare("SELECT id, nama_wisata, harga_tiket, kuota_harian, jadwal_operasional FROM objek_wisata WHERE id = ?");
$stmt_wisata->bind_param("i", $id);
$stmt_wisata->execute();
$result_wisata = $stmt_wisata->get_result();
if ($result_wisata->num_rows > 0) {
    $wisata = $result_wisata->fetch_assoc();
} else {
    echo "Objek wisata tidak ditemukan.";
    exit;
}

$tanggal_dipilih = isset($_GET['tanggal']) ? $_GET['tanggal'] : '';
$sisa_kuota = null;
$is_open_on_selected_day = false; // Flag untuk status buka/tutup

// Lakukan pengecekan hanya jika tanggal telah dipilih
if (!empty($tanggal_dipilih)) {
    // 1. Cek apakah hari yang dipilih adalah hari operasional
    if (!empty($wisata['jadwal_operasional'])) {
        $jadwal = json_decode($wisata['jadwal_operasional'], true);
        // Validasi JSON dan ambil hari dalam format angka (1=Senin, 7=Minggu)
        if(is_array($jadwal)) {
            $dayOfWeek = date('N', strtotime($tanggal_dipilih));

            if ($jadwal['days'] == 'setiap_hari') {
                $is_open_on_selected_day = true;
            } elseif ($jadwal['days'] == 'senin_jumat' && $dayOfWeek >= 1 && $dayOfWeek <= 5) {
                $is_open_on_selected_day = true;
            } elseif ($jadwal['days'] == 'sabtu_minggu' && ($dayOfWeek == 6 || $dayOfWeek == 7)) {
                $is_open_on_selected_day = true;
            }
        } else {
             // Jika data jadwal tidak valid JSON, anggap selalu buka
            $is_open_on_selected_day = true;
        }
    } else {
        // Jika tidak ada jadwal yang diatur, asumsikan selalu buka
        $is_open_on_selected_day = true;
    }
    
    // 2. Jika hari operasional, cek sisa kuota
    if ($is_open_on_selected_day && !is_null($wisata['kuota_harian'])) {
        $stmt_kuota = $conn->prepare("SELECT SUM(jumlah_tiket) as total_terpesan FROM pemesanan WHERE wisata_id = ? AND tanggal_kunjungan = ?");
        $stmt_kuota->bind_param("is", $id, $tanggal_dipilih);
        $stmt_kuota->execute();
        $total_terpesan = $stmt_kuota->get_result()->fetch_assoc()['total_terpesan'] ?? 0;
        
        $sisa_kuota = $wisata['kuota_harian'] - $total_terpesan;
        if ($sisa_kuota < 0) $sisa_kuota = 0;
    }
}
?>

<div class="auth-container">
    <div class="auth-form" style="max-width: 600px;">
        <h2>Pesan Tiket untuk <?= htmlspecialchars($wisata['nama_wisata']) ?></h2>
        
        <!-- Form untuk memilih tanggal -->
        <form method="GET" action="index.php">
            <input type="hidden" name="page" value="pesan_tiket">
            <input type="hidden" name="id" value="<?= $id ?>">
            <div class="form-group">
                <label for="tanggal_kunjungan">Pilih Tanggal Kunjungan</label>
                <input type="date" id="tanggal_kunjungan" name="tanggal" required min="<?= date('Y-m-d'); ?>" value="<?= $tanggal_dipilih ?>" onchange="this.form.submit()">
            </div>
        </form>
        
        <hr>

        <?php if(!empty($tanggal_dipilih)): ?>
            <?php if (!$is_open_on_selected_day): ?>
                <p class="error-msg">Mohon maaf, objek wisata tutup pada hari yang Anda pilih (<?= date('l, d F Y', strtotime($tanggal_dipilih)) ?>).</p>
            <?php elseif (!is_null($sisa_kuota) && $sisa_kuota <= 0): ?>
                <p class="error-msg">Mohon maaf, kuota tiket untuk tanggal <?= date('d F Y', strtotime($tanggal_dipilih)) ?> telah habis.</p>
            <?php else: ?>
                <!-- Form pemesanan hanya muncul jika hari buka dan kuota tersedia -->
                <form action="actions/booking-process.php" method="post">
                    <input type="hidden" name="wisata_id" value="<?= $wisata['id'] ?>">
                    <input type="hidden" name="harga_tiket" value="<?= $wisata['harga_tiket'] ?>">
                    <input type="hidden" name="tanggal_kunjungan" value="<?= $tanggal_dipilih ?>">
                    
                    <?php if (!is_null($sisa_kuota)): ?>
                        <p class="success-msg">Sisa kuota untuk tanggal <?= date('d F Y', strtotime($tanggal_dipilih)) ?>: <strong><?= $sisa_kuota ?> tiket</strong></p>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="jumlah_tiket">Jumlah Tiket</label>
                        <input type="number" id="jumlah_tiket" name="jumlah_tiket" required min="1" <?php if(!is_null($sisa_kuota)) echo 'max="'.$sisa_kuota.'"'; ?> value="1">
                    </div>
                    <button type="submit" class="btn btn-primary">Proses Pembayaran</button>
                </form>
            <?php endif; ?>
        <?php else: ?>
            <p class="notice">Silakan pilih tanggal untuk melihat ketersediaan tiket.</p>
        <?php endif; ?>
    </div>
</div>