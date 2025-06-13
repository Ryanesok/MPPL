
<div class="auth-container">
    <form action="actions/register-process.php" method="post" class="auth-form">
        <h2>Buat Akun Baru</h2>
        <?php if(isset($_GET['error'])): ?>
            <p class="error-msg"><?= htmlspecialchars($_GET['error']) ?></p>
        <?php endif; ?>
        <div class="form-group">
            <label for="nama_lengkap">Nama Lengkap</label>
            <input type="text" id="nama_lengkap" name="nama_lengkap" required>
        </div>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn">Register</button>
        <p class="auth-switch">Sudah punya akun? <a href="index.php?page=login">Login di sini</a></p>
    </form>
</div>


