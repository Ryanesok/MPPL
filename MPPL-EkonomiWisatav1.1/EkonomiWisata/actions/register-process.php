
<?php
require_once '../File Config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_lengkap = $_POST['nama_lengkap'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek jika username sudah ada
    $stmt_check = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt_check->bind_param("s", $username);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        header("Location: ../index.php?page=register&error=Username sudah digunakan.");
        exit();
    }
    $stmt_check->close();

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Default role is 'user'
    $role = 'user';

    $stmt = $conn->prepare("INSERT INTO users (nama_lengkap, username, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama_lengkap, $username, $hashed_password, $role);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: ../index.php?page=login&success=Registrasi berhasil! Silakan login.");
        exit();
    } else {
        $stmt->close();
        header("Location: ../index.php?page=register&error=Terjadi kesalahan. Coba lagi.");
        exit();
    }
}
$conn->close();
?>


