<?php
// ############################################################################
// File BARU: pages/admin_kelola_wisata.php
// Deskripsi: Menampilkan daftar semua objek wisata dalam tabel
//            dan menyediakan tombol untuk Tambah, Edit, dan Hapus.
// ############################################################################
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php?page=login&error=Akses ditolak.");
    exit;
}

// Proses Hapus Data (jika ada permintaan)
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id_to_delete = $_GET['id'];
    // Periksa foreign key di tabel anak (pemesanan & rating_ulasan)
    $stmt_check_pemesanan = $conn->prepare("SELECT id FROM pemesanan WHERE wisata_id = ?");
    $stmt_check_pemesanan->bind_param("i", $id_to_delete);
    $stmt_check_pemesanan->execute();
    $result_check_pemesanan = $stmt_check_pemesanan->get_result();

    if($result_check_pemesanan->num_rows > 0) {
        header("Location: index.php?page=admin_kelola_wisata&status=error_fk");
        exit();
    }
    $stmt_check_pemesanan->close();

    // Hapus data dari database
    $stmt = $conn->prepare("DELETE FROM objek_wisata WHERE id = ?");
    $stmt->bind_param("i", $id_to_delete);
    if ($stmt->execute()) {
        header("Location: index.php?page=admin_kelola_wisata&status=deleted");
    } else {
        header("Location: index.php?page=admin_kelola_wisata&status=error");
    }
    $stmt->close();
    exit();
}


$sql = "SELECT id, nama_wisata, kuota_harian, jadwal_operasional FROM objek_wisata ORDER BY id ASC";
$result = $conn->query($sql);
?>

<div class="page-header">
    <h1>Kelola Objek Wisata</h1>
    <p>Tambah, ubah, atau hapus data objek wisata di seluruh sistem.</p>
</div>

<?php if(isset($_GET['status']) && $_GET['status'] == 'success'): ?>
    <p class="success-msg">Data wisata berhasil disimpan!</p>
<?php elseif(isset($_GET['status']) && $_GET['status'] == 'deleted'): ?>
    <p class="success-msg">Data wisata berhasil dihapus!</p>
<?php elseif(isset($_GET['status']) && $_GET['status'] == 'error'): ?>
    <p class="error-msg">Terjadi kesalahan. Coba lagi.</p>
<?php elseif(isset($_GET['status']) && $_GET['status'] == 'error_fk'): ?>
    <p class="error-msg">Gagal menghapus! Data wisata ini sudah memiliki riwayat pemesanan.</p>
<?php endif; ?>

<div class="management-section">
    <a href="index.php?page=admin_form_wisata" class="btn btn-primary" style="margin-bottom: 20px; display: inline-block;">+ Tambah Objek Wisata Baru</a>
    
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Wisata</th>
                <th>Kuota Harian</th> <!-- Kolom Baru -->
                <th>Jadwal</th> <!-- Kolom Baru -->
                <th width="15%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['nama_wisata']) ?></td>
                    <td><?= !is_null($row['kuota_harian']) ? htmlspecialchars($row['kuota_harian']) : 'Tidak Terbatas' ?></td>
                    <td>
                        <?php 
                        if (!empty($row['jadwal_operasional'])) {
                            $jadwal = json_decode($row['jadwal_operasional'], true);
                            if(is_array($jadwal)) {
                                $hari_map = [
                                    'setiap_hari' => 'Setiap Hari',
                                    'senin_jumat' => 'Senin - Jumat',
                                    'sabtu_minggu' => 'Sabtu - Minggu'
                                ];
                                $hari_teks = $hari_map[$jadwal['days']] ?? '';
                                echo htmlspecialchars($hari_teks . ' (' . $jadwal['open'] . ' - ' . $jadwal['close'] . ')');
                            } else {
                                echo htmlspecialchars($row['jadwal_operasional']);
                            }
                        } else {
                            echo 'N/A';
                        }
                        ?>
                    </td>
                    <td>
                        <a href="index.php?page=admin_form_wisata&action=edit&id=<?= $row['id'] ?>" class="btn-action btn-edit">Edit</a>
                        <a href="index.php?page=admin_kelola_wisata&action=delete&id=<?= $row['id'] ?>" class="btn-action btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">Belum ada data objek wisata.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>