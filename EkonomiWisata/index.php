
<?php
require_once 'File Config/config.php';
require_once 'partials/header.php';

// Simple router
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

switch ($page) {
    case 'login':
        include 'pages/login.php';
        break;
    case 'register':
        include 'pages/register.php';
        break;
    case 'detail_wisata':
        include 'pages/detail-wisata.php';
        break;
    case 'pesan_tiket':
        include 'pages/pesan-tiket.php';
        break;
    case 'tiket_saya':
        include 'pages/tiket-saya.php';
        break;
    case 'admin_dashboard':
        include 'pages/admin-dashboard.php';
        break;
    case 'admin_kelola_wisata':
        include 'pages/admin-kelolawisata.php';
        break;
    case 'admin_form_wisata':
        include 'pages/admin-formwisata.php';
        break;
    case 'dinas_dashboard':
        include 'pages/dinas-dashboard.php';
        break;
    case 'logout':
        include 'actions/logout-process.php';
        break;
    default:
        include 'pages/home.php';
        break;
}

require_once 'partials/footer.php';
?>
