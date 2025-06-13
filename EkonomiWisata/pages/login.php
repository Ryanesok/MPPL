
<div class="auth-container">
    <form action="actions/login-process.php" method="post" class="auth-form">
        <h2>Login Akun</h2>
        <?php if(isset($_GET['error'])): ?>
            <p class="error-msg"><?= htmlspecialchars($_GET['error']) ?></p>
        <?php endif; ?>
        <?php if(isset($_GET['success'])): ?>
            <p class="success-msg"><?= htmlspecialchars($_GET['success']) ?></p>
        <?php endif; ?>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn">Login</button>
        <p class="auth-switch">Belum punya akun? <a href="index.php?page=register">Register di sini</a></p>
    </form>
</div>


