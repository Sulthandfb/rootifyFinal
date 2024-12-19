<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'] ?? '';
    $fullname = $_POST['fullname'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    
    // Add your processing logic here
    // For example, database insertion, validation, etc.
    
    // Redirect to next step (uncomment when ready to use)
    // header("Location: payment.php");
    // exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pemesanan - tiket.com</title>
    <style>
        :root {
            --primary-blue: #0064D2;
            --border-color: #e0e0e0;
            --text-gray: #687176;
            --background-gray: #f6f7f8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: var(--background-gray);
            color: #1d2329;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header Styles */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .logo img {
            height: 32px;
        }

        .promo-button button {
            background: none;
            border: 1px solid var(--border-color);
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            color: var(--text-gray);
        }

        /* Progress Steps */
        .progress-steps {
            display: flex;
            margin-bottom: 30px;
            gap: 20px;
        }

        .step {
            display: flex;
            align-items: center;
            color: var(--text-gray);
            font-size: 14px;
        }

        .step-number {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background-color: #fff;
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
        }

        .step.active {
            color: var(--primary-blue);
        }

        .step.active .step-number {
            background-color: var(--primary-blue);
            color: white;
            border: none;
        }

        /* Content Layout */
        .content-wrapper {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 24px;
        }

        /* Login Prompt */
        .login-prompt {
            background: #fff;
            border-radius: 12px;
            padding: 16px;
            display: flex;
            align-items: center;
            margin-bottom: 24px;
        }

        .blibli-icon {
            width: 48px;
            height: 48px;
            margin-right: 16px;
        }

        .login-text {
            font-size: 14px;
        }

        .login-link {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 600;
        }

        /* Booking Form */
        .booking-form {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
        }

        .form-subtitle {
            color: var(--text-gray);
            font-size: 14px;
            margin-bottom: 24px;
        }

        .title-selection {
            display: flex;
            gap: 16px;
            margin-bottom: 24px;
        }

        .radio-container {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .radio-label {
            margin-left: 8px;
        }

        .form-group {
            position: relative;
            margin-bottom: 24px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 16px;
        }

        .form-group label {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-gray);
            transition: 0.2s ease all;
            pointer-events: none;
        }

        .form-group input:focus + label,
        .form-group input:not(:placeholder-shown) + label {
            top: -8px;
            left: 8px;
            font-size: 12px;
            background: #fff;
            padding: 0 4px;
        }

        .phone-group {
            display: flex;
            align-items: center;
        }

        .country-code {
            display: flex;
            align-items: center;
            padding: 0 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px 0 0 8px;
            background: #fff;
        }

        .flag-icon {
            width: 24px;
            margin-right: 8px;
        }

        .phone-group input {
            border-left: none;
            border-radius: 0 8px 8px 0;
        }

        /* Order Summary */
        .order-summary {
            background: #fff;
            border-radius: 12px;
            padding: 16px;
        }

        .hotel-info {
            display: flex;
            align-items: center;
            margin-bottom: 16px;
        }

        .hotel-thumb {
            width: 64px;
            height: 64px;
            border-radius: 8px;
            margin-right: 12px;
            object-fit: cover;
        }

        .booking-details {
            padding: 16px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .dates {
            font-weight: 600;
            margin-bottom: 4px;
        }

        .room-info {
            color: var(--text-gray);
            font-size: 14px;
        }

        .price-summary {
            padding-top: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .total-label {
            color: var(--text-gray);
            font-size: 14px;
        }

        .total-price {
            font-weight: 600;
            color: #ff5e1f;
        }

        .continue-btn {
            width: 100%;
            padding: 12px;
            background-color: var(--primary-blue);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
        }

        .continue-btn:hover {
            background-color: #0056b3;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .content-wrapper {
                grid-template-columns: 1fr;
            }
            
            .progress-steps {
                overflow-x: auto;
                padding-bottom: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header with Logo -->
        <header>
            <div class="logo">
                <img src="tiket-logo.png" alt="tiket.com">
            </div>
            <div class="promo-button">
                <button>Promo</button>
            </div>
        </header>

        <!-- Progress Steps -->
        <div class="progress-steps">
            <div class="step active">
                <span class="step-number">1</span>
                Detail Pesanan
            </div>
            <div class="step">
                <span class="step-number">2</span>
                Pelengkap Mengingap
            </div>
            <div class="step">
                <span class="step-number">3</span>
                Pilih Metode Bayar
            </div>
        </div>

        <!-- Main Content -->
        <div class="content-wrapper">
            <div class="main-content">
                <!-- Login Prompt -->
                <div class="login-prompt">
                    <img src="blibli-icon.png" alt="Blibli Icon" class="blibli-icon">
                    <div class="login-text">
                        <p>Log in atau buat akun sekarang untuk dapat <strong>Blibli Tiket Points</strong> dari transaksi ini!</p>
                        <a href="#" class="login-link">Log in sekarang</a>
                    </div>
                </div>

                <!-- Booking Form -->
                <div class="booking-form">
                    <h2>Detail Pemesan</h2>
                    <p class="form-subtitle">Detail kontak ini akan digunakan untuk pengiriman e-tiket dan keperluan refund/reschedule.</p>
                    
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <div class="title-selection">
                            <label class="radio-container">
                                <input type="radio" name="title" value="Tuan">
                                <span class="radio-label">Tuan</span>
                            </label>
                            <label class="radio-container">
                                <input type="radio" name="title" value="Nyonya">
                                <span class="radio-label">Nyonya</span>
                            </label>
                            <label class="radio-container">
                                <input type="radio" name="title" value="Nona">
                                <span class="radio-label">Nona</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <input type="text" id="fullname" name="fullname" required placeholder=" ">
                            <label for="fullname">Nama Lengkap Sesuai Identitas</label>
                        </div>

                        <div class="form-group phone-group">
                            <div class="country-code">
                                <img src="id-flag.png" alt="Indonesia" class="flag-icon">
                                <span>+62</span>
                            </div>
                            <input type="tel" id="phone" name="phone" required placeholder=" ">
                            <label for="phone">Nomor Ponsel</label>
                        </div>

                        <div class="form-group">
                            <input type="email" id="email" name="email" required placeholder=" ">
                            <label for="email">Alamat Email</label>
                        </div>

                        <button type="submit" class="continue-btn">Lanjutkan</button>
                    </form>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="order-summary">
                <div class="hotel-info">
                    <img src="hotel-thumb.jpg" alt="Ubud Raya Villa" class="hotel-thumb">
                    <h3>Ubud Raya Villa</h3>
                </div>
                <div class="booking-details">
                    <p class="dates">Jum, 20 Des 2024 - Sab, 21 Des 2024</p>
                    <p class="room-info">1 Malam • 1 Kamar</p>
                </div>
                <div class="price-summary">
                    <div class="total-label">Total (setelah pajak)</div>
                    <div class="total-price">IDR 2.154.546</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>