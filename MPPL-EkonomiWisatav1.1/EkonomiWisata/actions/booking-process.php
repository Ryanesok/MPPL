
<?php
require_once '../File Config/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php?page=login&error=Sesi berakhir. Silakan login kembali.");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $wisata_id = $_POST['wisata_id'];
    $tanggal_kunjungan = $_POST['tanggal_kunjungan'];
    $jumlah_tiket = $_POST['jumlah_tiket'];
    $harga_tiket = $_POST['harga_tiket'];

    // --- VALIDASI SISI SERVER ---
    $stmt_wisata = $conn->prepare("SELECT kuota_harian, jadwal_operasional FROM objek_wisata WHERE id = ?");
    $stmt_wisata->bind_param("i", $wisata_id);
    $stmt_wisata->execute();
    $wisata = $stmt_wisata->get_result()->fetch_assoc();
    $stmt_wisata->close();

    // 1. Validasi Jadwal Operasional
    $is_open = false;
    if (!empty($wisata['jadwal_operasional'])) {
        $jadwal = json_decode($wisata['jadwal_operasional'], true);
        $dayOfWeek = date('N', strtotime($tanggal_kunjungan));
        if ($jadwal['days'] == 'setiap_hari' || ($jadwal['days'] == 'senin_jumat' && $dayOfWeek <= 5) || ($jadwal['days'] == 'sabtu_minggu' && $dayOfWeek >= 6)) {
            $is_open = true;
        }
    } else {
        $is_open = true; // Asumsikan buka jika tidak ada jadwal
    }

    if (!$is_open) {
        // Redirect dengan pesan error yang lebih spesifik
        header("Location: ../index.php?page=pesan_tiket&id=$wisata_id&tanggal=$tanggal_kunjungan");
        exit();
    }

    // 2. Validasi Kuota Tiket
    if (!is_null($wisata['kuota_harian'])) {
        $stmt_kuota = $conn->prepare("SELECT SUM(jumlah_tiket) as total_terpesan FROM pemesanan WHERE wisata_id = ? AND tanggal_kunjungan = ?");
        $stmt_kuota->bind_param("is", $wisata_id, $tanggal_kunjungan);
        $stmt_kuota->execute();
        $total_terpesan = $stmt_kuota->get_result()->fetch_assoc()['total_terpesan'] ?? 0;
        
        if (($total_terpesan + $jumlah_tiket) > $wisata['kuota_harian']) {
            // Redirect dengan pesan error yang lebih spesifik
            header("Location: ../index.php?page=pesan_tiket&id=$wisata_id&tanggal=$tanggal_kunjungan");
            exit();
        }
    }
    // --- AKHIR VALIDASI SISI SERVER ---


    $total_harga = $jumlah_tiket * $harga_tiket;
    $kode_booking = 'TIX-' . strtoupper(substr(uniqid(), 7, 6));
    $status_pembayaran = 'paid'; 

    $stmt = $conn->prepare("INSERT INTO pemesanan (user_id, wisata_id, kode_booking, tanggal_kunjungan, jumlah_tiket, total_harga, status_pembayaran) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissids", $user_id, $wisata_id, $kode_booking, $tanggal_kunjungan, $jumlah_tiket, $total_harga, $status_pembayaran);

    if ($stmt->execute()) {
        header("Location: ../index.php?page=tiket_saya&success=Pemesanan berhasil!");
    } else {
        header("Location: ../index.php?page=pesan_tiket&id=$wisata_id&tanggal=$tanggal_kunjungan&error=gagal_simpan");
    }
    $stmt->close();
}
$conn->close();
?>