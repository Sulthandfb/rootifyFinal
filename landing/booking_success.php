<?php
session_start();
require_once '../filter_wisata/db_connect.php';

// Redirect if no booking success
if (!isset($_SESSION['booking_success']) || !isset($_SESSION['booking_id'])) {
    header("Location: template.php");
    exit();
}

$booking_id = $_SESSION['booking_id'];
$success_message = $_SESSION['success_message'] ?? '';

// Get booking details from database
$stmt = $db->prepare("SELECT * FROM bookings WHERE id = ?");
if (!$stmt) {
    die("Prepare failed: " . $db->error);
}

$stmt->bind_param("i", $booking_id);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();

if (!$booking) {
    header("Location: template.php");
    exit();
}

// Clear session variables after getting the data
unset($_SESSION['booking_success']);
unset($_SESSION['booking_id']);
unset($_SESSION['success_message']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan Berhasil</title>
    <link rel="stylesheet" href="../landing/booking-styles.css">
    <style>
        .success-container {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 40px auto;
        }
        
        .success-icon {
            color: #4CAF50;
            font-size: 48px;
            margin-bottom: 20px;
        }
        
        .success-message {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }
        
        .booking-details {
            text-align: left;
            margin: 20px 0;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            background-color: #dff0d8;
            border: 1px solid #d6e9c6;
            color: #3c763d;
        }
        
        .home-button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #0064D2;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin-top: 20px;
            transition: background-color 0.3s;
        }
        
        .home-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-icon">✓</div>
        
        <?php if ($success_message): ?>
            <div class="alert">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>
        
        <h1 class="success-message">Pemesanan Berhasil!</h1>
        
        <div class="booking-details">
            <h2>Detail Pemesanan:</h2>
            <p>Nomor Pemesanan: <?php echo $booking_id; ?></p>
            <p>Nama: <?php echo htmlspecialchars($booking['full_name']); ?></p>
            <p>Total: Rp <?php echo number_format($booking['total_price'], 0, ',', '.'); ?></p>
            <p>Status: <?php echo ucfirst($booking['status']); ?></p>
            <p>Tanggal Pemesanan: <?php echo date('d/m/Y H:i', strtotime($booking['created_at'])); ?></p>
        </div>
        
        <?php if ($booking['payment_method'] === 'bank_transfer'): ?>
            <div class="payment-instructions">
                <h3>Instruksi Pembayaran:</h3>
                <p>Silakan transfer ke rekening berikut:</p>
                <p>Bank XYZ</p>
                <p>No. Rekening: 1234-5678-9000</p>
                <p>Atas Nama: PT Travel Company</p>
                <p>Jumlah: Rp <?php echo number_format($booking['total_price'], 0, ',', '.'); ?></p>
                <p><strong>Mohon transfer sesuai dengan jumlah yang tertera</strong></p>
            </div>
        <?php endif; ?>
        
        <a href="../landing/template.php" class="home-button">Kembali ke Beranda</a>
    </div>
    
    <script>
        // Prevent form resubmission
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>
</html>