
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

    // Validasi sederhana
    if (empty($tanggal_kunjungan) || empty($jumlah_tiket) || $jumlah_tiket < 1) {
        header("Location: ../index.php?page=pesan_tiket&id=$wisata_id&error=Data tidak valid.");
        exit;
    }

    $total_harga = $jumlah_tiket * $harga_tiket;
    $kode_booking = 'TIX-' . strtoupper(substr(uniqid(), 7, 6));
    $status_pembayaran = 'paid'; // Simulasi pembayaran berhasil

    $stmt = $conn->prepare("INSERT INTO pemesanan (user_id, wisata_id, kode_booking, tanggal_kunjungan, jumlah_tiket, total_harga, status_pembayaran) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissids", $user_id, $wisata_id, $kode_booking, $tanggal_kunjungan, $jumlah_tiket, $total_harga, $status_pembayaran);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: ../index.php?page=tiket_saya&success=Pemesanan berhasil!");
        exit();
    } else {
        $stmt->close();
        header("Location: ../index.php?page=pesan_tiket&id=$wisata_id&error=Gagal memproses pesanan.");
        exit();
    }
}
$conn->close();
?>


