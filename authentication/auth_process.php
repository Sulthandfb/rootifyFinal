<?php
// Define admin credentials
define('ADMIN_EMAIL', 'admin@vortex.com');
define('ADMIN_PASSWORD', 'admin123');

session_start();
include '../filter_wisata/db_connect.php';

$loginMessage = '';
$registerMessage = '';

// Proses login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check for admin credentials
    if ($email === ADMIN_EMAIL && $password === ADMIN_PASSWORD) {
        // Admin login successful
        $_SESSION["user_id"] = 0; // Use 0 or any specific ID for admin
        $_SESSION["username"] = "Admin";
        $_SESSION["user_email"] = ADMIN_EMAIL;
        $_SESSION["is_admin"] = true;
        header("Location: ../admin/admin.php"); // Redirect to admin dashboard
        exit();
    }

    // If not admin, proceed with regular user login
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {
            // Regular user login successful
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["user_email"] = $user["email"];
            $_SESSION["user_avatar"] = $user["avatar"] ?? 'default-avatar.png';
            $_SESSION["is_admin"] = false;
            header("Location: template.php");
            exit();
        } else {
            $loginMessage = "Password salah.";
        }
    } else {
        $loginMessage = "Email tidak ditemukan.";
    }

    $stmt->close();
}

// Proses registrasi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];

    if ($password !== $confirmPassword) {
        $registerMessage = "Password tidak cocok.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Cek apakah email atau username sudah terdaftar
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $registerMessage = "Username atau email sudah terdaftar.";
        } else {
            // Insert pengguna baru ke database
            $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashedPassword);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $registerMessage = "Registrasi berhasil! Silakan login.";
            } else {
                $registerMessage = "Registrasi gagal. Silakan coba lagi.";
            }
        }

        $stmt->close();
    }
}
?>