<?php
// ############################################################################
// File BARU: pages/admin_form_wisata.php
// Deskripsi: Formulir untuk menambah atau mengedit data objek wisata.
// ############################################################################

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php?page=login&error=Akses ditolak.");
    exit;
}

// Default values untuk form tambah
$wisata = [
    'id' => '',
    'nama_wisata' => '',
    'deskripsi' => '',
    'lokasi' => '',
    'harga_tiket' => '',
    'gambar_url' => '',
    'kuota_harian' => 100, // Default kuota 100
    'jadwal_operasional' => ''
];
$jadwal_terpilih = [
    'days' => 'setiap_hari',
    'open' => '08:00',
    'close' => '17:00'
];

$form_action = 'actions/wisata-process.php?action=create';
$page_title = 'Tambah Objek Wisata Baru';
$button_text = 'Simpan Data';

// Jika ini adalah mode edit, ambil data dari database
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $id_to_edit = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM objek_wisata WHERE id = ?");
    $stmt->bind_param("i", $id_to_edit);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $wisata = $result->fetch_assoc();
        $form_action = "actions/wisata-process.php?action=update&id=" . $wisata['id'];
        $page_title = 'Edit Objek Wisata';
        $button_text = 'Update Data';

        // Parse JSON jadwal jika ada
        if (!empty($wisata['jadwal_operasional'])) {
            $decoded_jadwal = json_decode($wisata['jadwal_operasional'], true);
            if (is_array($decoded_jadwal)) {
                $jadwal_terpilih = $decoded_jadwal;
            }
        }
    } else {
        header("Location: index.php?page=admin_kelola_wisata&status=notfound");
        exit();
    }
}
?>

<div class="page-header">
    <h1><?= $page_title ?></h1>
</div>

<div class="auth-container">
    <form action="<?= $form_action ?>" method="post" class="auth-form" style="max-width: 800px;">
        <div class="form-group">
            <label for="nama_wisata">Nama Wisata</label>
            <input type="text" id="nama_wisata" name="nama_wisata" value="<?= htmlspecialchars($wisata['nama_wisata']) ?>" required>
        </div>
        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" rows="5" required oninput="this.style.height = 'auto'; this.style.height = (this.scrollHeight) + 'px';"><?= htmlspecialchars($wisata['deskripsi']) ?></textarea>
        </div>
        <div class="form-group">
            <label for="lokasi">Lokasi</label>
            <input type="text" id="lokasi" name="lokasi" value="<?= htmlspecialchars($wisata['lokasi']) ?>" required>
        </div>
        <div class="form-group">
            <label for="harga_tiket">Harga Tiket (Rp)</label>
            <input type="number" id="harga_tiket" name="harga_tiket" step="1000" value="<?= htmlspecialchars($wisata['harga_tiket']) ?>" required>
        </div>
        <div class="form-group">
            <label for="gambar_url">URL Gambar</label>
            <input type="text" id="gambar_url" name="gambar_url" value="<?= htmlspecialchars($wisata['gambar_url']) ?>">
        </div>
        
        <hr style="margin: 2rem 0;">

        <div class="form-group">
            <label for="kuota_harian">Kuota Tiket Harian</label>
            <input type="number" id="kuota_harian" name="kuota_harian" value="<?= htmlspecialchars($wisata['kuota_harian']) ?>" placeholder="Kosongkan jika tidak terbatas">
        </div>

        <div class="form-group">
            <label>Jadwal Operasional</label>
            <div style="display: flex; gap: 15px; align-items: center;">
                <select name="hari_operasional" style="flex: 1; padding: 0.75rem; border: 1px solid #ccc; border-radius: 5px;">
                    <option value="setiap_hari" <?= ($jadwal_terpilih['days'] == 'setiap_hari') ? 'selected' : '' ?>>Setiap Hari</option>
                    <option value="senin_jumat" <?= ($jadwal_terpilih['days'] == 'senin_jumat') ? 'selected' : '' ?>>Senin - Jumat</option>
                    <option value="sabtu_minggu" <?= ($jadwal_terpilih['days'] == 'sabtu_minggu') ? 'selected' : '' ?>>Sabtu - Minggu</option>
                </select>
                <input type="time" name="jam_buka" value="<?= htmlspecialchars($jadwal_terpilih['open']) ?>" required style="padding: 0.75rem; border: 1px solid #ccc; border-radius: 5px;">
                <span>-</span>
                <input type="time" name="jam_tutup" value="<?= htmlspecialchars($jadwal_terpilih['close']) ?>" required style="padding: 0.75rem; border: 1px solid #ccc; border-radius: 5px;">
            </div>
        </div>

        <button type="submit" class="btn btn-primary"><?= $button_text ?></button>
        <a href="index.php?page=admin_kelola_wisata" style="margin-left: 10px;">Batal</a>
    </form>
</div>
<script>
    // Trigger auto-resize saat halaman dimuat untuk form edit
    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.getElementById('deskripsi');
        if (textarea) {
            textarea.style.height = 'auto';
            textarea.style.height = (textarea.scrollHeight) + 'px';
        }
    });
</script>