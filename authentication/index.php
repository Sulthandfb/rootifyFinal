<?php
include '../filter_wisata/db_connect.php'; // Koneksi ke database
include 'auth_process.php'; // Menyertakan proses login dan registrasi

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Double Slider Sign in/up Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" />
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <div class="container" id="container">
        <!-- Form Registrasi -->
        <div class="form-container sign-up-container">
            <form method="POST" action="index.php">
                <h1>Create Account</h1>
                <div class="social-container">
                    <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                    <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <span>or use your email for registration</span>
                <input type="text" name="username" placeholder="Name" required />
                <input type="email" name="email" placeholder="Email" required />
                <input type="password" name="password" placeholder="Password" required />
                <input type="password" name="confirmPassword" placeholder="Confirm Password" required />
                <button type="submit" name="register">Sign Up</button>
                <?php if ($registerMessage): ?>
                    <div class="error-message"><?php echo $registerMessage; ?></div>
                <?php endif; ?>
            </form>
        </div>

        <!-- Form Login -->
        <div class="form-container sign-in-container">
            <form method="POST" action="index.php">
                <h1>Sign in</h1>
                <div class="social-container">
                    <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                    <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <span>or use your account</span>
                <input type="email" name="email" placeholder="Email" required />
                <input type="password" name="password" placeholder="Password" required />
                <a href="#">Forgot your password?</a>
                <button type="submit" name="login">Sign In</button>
                <?php if ($loginMessage): ?>
                    <div class="error-message"><?php echo $loginMessage; ?></div>
                <?php endif; ?>
            </form>
        </div>

        <!-- Overlay -->
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <img src="../img/Maskot.png" alt="">
                    <h1>Welcome Back!</h1>
                    <p>To keep connected with us please login with your personal info</p>
                    <button class="ghost" id="signIn">Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Hello, Friend!</h1>
                    <p>Enter your personal details and start your journey with us</p>
                    <img src="../img/Maskot 1.png" alt="">
                    <button class="ghost" id="signUp">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script JS -->
    <script>
        document.getElementById("signUp").addEventListener("click", function () {
            document.getElementById("container").classList.add("right-panel-active");
        });

        document.getElementById("signIn").addEventListener("click", function () {
            document.getElementById("container").classList.remove("right-panel-active");
        });
    </script>
</body>
</html>
