<?php
// ############################################################################
// File BARU: actions/wisata_process.php
// Deskripsi: Memproses data dari form tambah/edit dan menyimpannya ke database.
// ############################################################################

require_once '../File Config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "Akses ditolak.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_wisata = $_POST['nama_wisata'];
    $deskripsi = $_POST['deskripsi'];
    $lokasi = $_POST['lokasi'];
    $harga_tiket = $_POST['harga_tiket'];
    $gambar_url = !empty($_POST['gambar_url']) ? $_POST['gambar_url'] : 'https://placehold.co/600x400/e2e8f0/e2e8f0?text=Gambar';
    $kuota_harian = !empty($_POST['kuota_harian']) ? (int)$_POST['kuota_harian'] : NULL;
    
    // Gabungkan data jadwal menjadi JSON
    $jadwal_data = [
        'days' => $_POST['hari_operasional'],
        'open' => $_POST['jam_buka'],
        'close' => $_POST['jam_tutup']
    ];
    $jadwal_operasional_json = json_encode($jadwal_data);

    // Aksi Create (Tambah Baru)
    if (isset($_GET['action']) && $_GET['action'] == 'create') {
        $stmt = $conn->prepare("INSERT INTO objek_wisata (nama_wisata, deskripsi, lokasi, harga_tiket, gambar_url, kuota_harian, jadwal_operasional) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssdsis", $nama_wisata, $deskripsi, $lokasi, $harga_tiket, $gambar_url, $kuota_harian, $jadwal_operasional_json);
        
        if ($stmt->execute()) {
            header("Location: ../index.php?page=admin_kelola_wisata&status=success");
        } else {
            header("Location: ../index.php?page=admin_form_wisata&status=error");
        }
        $stmt->close();
    }
    
    // Aksi Update (Edit)
    elseif (isset($_GET['action']) && $_GET['action'] == 'update' && isset($_GET['id'])) {
        $id_to_update = $_GET['id'];
        $stmt = $conn->prepare("UPDATE objek_wisata SET nama_wisata=?, deskripsi=?, lokasi=?, harga_tiket=?, gambar_url=?, kuota_harian=?, jadwal_operasional=? WHERE id=?");
        // PERBAIKAN: String tipe data diubah dari "ssdsisi" menjadi "sssdsisi" agar cocok dengan 8 variabel.
        $stmt->bind_param("sssdsisi", $nama_wisata, $deskripsi, $lokasi, $harga_tiket, $gambar_url, $kuota_harian, $jadwal_operasional_json, $id_to_update);
        
        if ($stmt->execute()) {
            header("Location: ../index.php?page=admin_kelola_wisata&status=success");
        } else {
            header("Location: ../index.php?page=admin_form_wisata&action=edit&id=$id_to_update&status=error");
        }
        $stmt->close();
    }
}
$conn->close();
?>
