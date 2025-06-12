<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Pariwisata</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav>
            <a href="index.php?page=home" class="logo">WisataYuk</a>
            <ul>
                <li><a href="index.php?page=home">Beranda</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="index.php?page=tiket_saya">Tiket Saya</a></li>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <li><a href="index.php?page=admin_dashboard">Dashboard Admin</a></li>
                    <?php elseif ($_SESSION['role'] === 'dinas'): ?>
                        <li><a href="index.php?page=dinas_dashboard">Dashboard Dinas</a></li>
                    <?php endif; ?>
                    <li><a href="index.php?page=logout" class="btn-logout">Logout</a></li>
                <?php else: ?>
                    <li><a href="index.php?page=login">Login</a></li>
                    <li><a href="index.php?page=register" class="btn-register">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main class="container">


