
<?php
require_once '../File Config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Login sukses
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $stmt->close();
            header("Location: ../index.php?page=home");
            exit();
        } else {
            $stmt->close();
            header("Location: ../index.php?page=login&error=Password salah.");
            exit();
        }
    } else {
        $stmt->close();
        header("Location: ../index.php?page=login&error=Username tidak ditemukan.");
        exit();
    }
}
$conn->close();
?>


