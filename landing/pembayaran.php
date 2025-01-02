<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection and handler
require_once('../filter_wisata/db_connect.php');
require_once('../includes/booking_handler.php');

// Get booking type and ID from URL
$booking_type = $_GET['type'] ?? '';
$id = $_GET['id'] ?? 0;

// Validate booking type and ID
if (empty($booking_type) || empty($id)) {
    header('Location: index.php');
    exit();
}

// Fetch item details based on booking type
$item = null;
switch ($booking_type) {
    case 'hotel':
        $sql = "SELECT h.*, r.room_id, r.room_name, r.price as room_price, r.capacity, r.availability 
                FROM hotels h 
                INNER JOIN rooms r ON h.hotel_id = r.hotel_id 
                WHERE r.room_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $item = $stmt->get_result()->fetch_assoc();
        break;
        
    case 'package':
        $sql = "SELECT p.*, p.price as base_price, p.discounted_price 
                FROM tourist_packets p 
                WHERE p.packet_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $item = $stmt->get_result()->fetch_assoc();
        break;
        
    case 'attraction':
        $sql = "SELECT ta.*, ad.ticket_price 
            FROM tourist_attractions ta
            LEFT JOIN attraction_details ad ON ta.id = ad.attraction_id
            WHERE ta.id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $item = $stmt->get_result()->fetch_assoc();
        break;
        
    default:
        header('Location: index.php');
        exit();
}

if (!$item) {
    header('Location: index.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if ($booking_type === 'hotel') {
            // Validate hotel availability
            if ($item['availability'] <= 0) {
                throw new Exception("Kamar tidak tersedia untuk periode yang dipilih");
            }

            // Calculate total price for hotel
            $check_in = new DateTime($_POST['check_in']);
            $check_out = new DateTime($_POST['check_out']);
            $nights = $check_in->diff($check_out)->days;
            $total_price = $item['room_price'] * $nights;

            $booking_data = [
                'booking_type' => $booking_type,
                'reference_id' => $id,
                'title' => $_POST['title'],
                'full_name' => $_POST['fullname'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'num_adults' => $_POST['adults'],
                'num_children' => $_POST['children'],
                'payment_method' => $_POST['payment_method'],
                'total_price' => $total_price,
                'hotel_id' => $item['hotel_id'],
                'room_id' => $item['room_id'],
                'check_in_date' => $_POST['check_in'],
                'check_out_date' => $_POST['check_out']
            ];

        } else {
            // Handle original logic for packages and attractions
            $booking_data = [
                'booking_type' => $booking_type,
                'reference_id' => $id,
                'title' => $_POST['title'],
                'full_name' => $_POST['fullname'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'num_adults' => $_POST['adults'],
                'num_children' => $_POST['children'],
                'payment_method' => $_POST['payment_method']
            ];

            // Add specific date fields based on booking type
            switch ($booking_type) {
                case 'package':
                    $booking_data['tour_date'] = $_POST['tour_date'];
                    break;
                case 'attraction':
                    $booking_data['visit_date'] = $_POST['visit_date'];
                    break;
            }
        }

        // Process booking
        if ($booking_type === 'hotel') {
            $handler = new BookingHandler($db);
            $result = $handler->createBooking($booking_data);

            if ($result['success']) {
                $_SESSION['booking_success'] = true;
                $_SESSION['booking_id'] = $result['booking_id'];
                $_SESSION['success_message'] = "Pemesanan Anda telah berhasil diproses.";
                header('Location: booking_success.php');
                exit();
            } else {
                $error_message = $result['message'];
            }
        } else {
            // Perbaiki query dan bind_param
            $sql = "INSERT INTO bookings (booking_type, reference_id, title, full_name, email, phone, 
                    num_adults, num_children, payment_method, total_price, status, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";
                    
            $stmt = $db->prepare($sql);

        // Calculate total price based on booking type
        $total_price = 0;
        switch ($booking_type) {
            case 'package':
                $base_price = $item['price'];
                $total_price = ($base_price * $booking_data['num_adults']) + 
                            ($base_price * 0.7 * $booking_data['num_children']);
                break;
            case 'attraction':
                $basePrice = $item['ticket_price'];
                $total_price = ($basePrice * $booking_data['num_adults']) + 
                            ($basePrice * 0.5 * $booking_data['num_children']);
                break;
        }

        $sql = "INSERT INTO bookings (booking_type, reference_id, title, full_name, email, phone, 
                num_adults, num_children, total_price, payment_method, status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";

        $stmt = $db->prepare($sql);
        $stmt->bind_param("sissssiids", 
            $booking_data['booking_type'],
            $booking_data['reference_id'],
            $booking_data['title'],
            $booking_data['full_name'],
            $booking_data['email'],
            $booking_data['phone'],
            $booking_data['num_adults'],
            $booking_data['num_children'],
            $total_price,
            $booking_data['payment_method']
        );

        if ($stmt->execute()) {
            $booking_id = $stmt->insert_id;
            
            // Add specific date information to booking_details
            switch ($booking_type) {
                case 'package':
                    $date_sql = "INSERT INTO package_bookings (booking_id, packet_id, tour_date) VALUES (?, ?, ?)";
                    $stmt = $db->prepare($date_sql);
                    $stmt->bind_param("iis", 
                        $booking_id, 
                        $booking_data['reference_id'],  // reference_id adalah packet_id untuk booking paket
                        $booking_data['tour_date']
                    );
                    break;
                case 'attraction':
                    $date_sql = "INSERT INTO attraction_bookings (booking_id, attraction_id, visit_date) VALUES (?, ?, ?)";
                    $stmt = $db->prepare($date_sql);
                    $stmt->bind_param("iis", 
                        $booking_id, 
                        $booking_data['reference_id'],  // reference_id is the attraction_id
                        $booking_data['visit_date']
                    );
                    break;
            }
            
            if ($stmt->execute()) {
                $_SESSION['booking_success'] = true;
                $_SESSION['booking_id'] = $booking_id;
                $_SESSION['success_message'] = "Pemesanan Anda telah berhasil diproses.";
                header('Location: booking_success.php');
                exit();
            }
        }
            
            $error_message = "Terjadi kesalahan saat memproses pemesanan. Silakan coba lagi.";
        }
    } catch (Exception $e) {
        error_log("Booking error: " . $e->getMessage());
        $error_message = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Form</title>
    <link rel="stylesheet" href="../landing/booking-styles.css">
    <style>
        /* Modern Booking Form Styles */
        :root {
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --secondary-color: #64748b;
            --success-color: #22c55e;
            --error-color: #ef4444;
            --background: #f8fafc;
            --card-bg: #ffffff;
            --border: #e2e8f0;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--background);
            color: var(--text-primary);
            line-height: 1.5;
            min-height: 100vh;
        }

        .container {
            max-width: 800px;
            width: 90%;
            margin: 40px auto;
            padding: 20px;
        }

        /* Progress Steps Styling */
        .progress-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            position: relative;
            padding: 0 40px;
        }

        .progress-steps::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 60px;
            right: 60px;
            height: 3px;
            background: var(--border);
            transform: translateY(-50%);
            z-index: 1;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            position: relative;
            z-index: 2;
            background: var(--background);
            padding: 0 15px;
        }

        .step-number {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: var(--card-bg);
            border: 2px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .step.active .step-number {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            transform: scale(1.1);
        }

        .step-text {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-secondary);
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .step.active .step-text {
            color: var(--primary-color);
        }

        /* Form Section Styling */
        .form-section {
            display: none;
            background: var(--card-bg);
            padding: 40px;
            border-radius: 16px;
            box-shadow: var(--shadow-lg);
            margin-top: 30px;
        }

        .form-section.active {
            display: block;
            animation: slideIn 0.4s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 30px;
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 24px;
            position: relative;
        }

        .form-group label {
            display: block;
            font-weight: 500;
            margin-bottom: 8px;
            color: var(--text-secondary);
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="date"],
        input[type="number"],
        select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.2s ease;
            background-color: var(--card-bg);
            color: var(--text-primary);
            appearance: none;
            -webkit-appearance: none;
        }

        /* Specific styling for number inputs */
        input[type="number"] {
            -moz-appearance: textfield;
            padding-right: 8px;
        }

        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Custom select arrow */
        select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
            padding-right: 40px;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* Radio Button Group */
        .radio-group {
            display: flex;
            gap: 16px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .radio-group input[type="radio"] {
            display: none;
        }

        .radio-group label {
            padding: 10px 24px;
            border: 2px solid var(--border);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-weight: 500;
            text-align: center;
            min-width: 100px;
        }

        .radio-group input[type="radio"]:checked + label {
            border-color: var(--primary-color);
            background-color: rgba(37, 99, 235, 0.1);
            color: var(--primary-color);
        }

        .radio-group label:hover {
            border-color: var(--primary-color);
        }

        /* Navigation Buttons */
        .form-navigation {
            display: flex;
            justify-content: flex-end;
            margin-top: 40px;
            gap: 16px;
        }

        button {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.2s ease;
            cursor: pointer;
            min-width: 120px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-next,
        .btn-submit {
            background-color: var(--primary-color);
            color: white;
            border: none;
        }

        .btn-next:hover,
        .btn-submit:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
        }

        .btn-prev {
            background-color: transparent;
            border: 2px solid var(--border);
            color: var(--text-secondary);
        }

        .btn-prev:hover {
            background-color: var(--background);
            border-color: var(--text-secondary);
        }

        /* Booking Summary */
        .booking-summary {
            background-color: var(--background);
            border-radius: 12px;
            padding: 24px;
            margin: 30px 0;
        }

        .booking-summary h3 {
            color: var(--text-primary);
            font-size: 18px;
            margin-bottom: 16px;
            font-weight: 600;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid var(--border);
        }

        .summary-item:last-child {
            border-bottom: none;
            font-weight: 600;
            color: var(--primary-color);
        }

        /* Error States */
        .error {
            border-color: var(--error-color) !important;
        }

        .error-message {
            color: var(--error-color);
            font-size: 14px;
            margin-top: 4px;
            position: absolute;
            bottom: -20px;
        }

        /* Alert Styling */
        .alert {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-weight: 500;
        }

        .alert-danger {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* Responsive Design */
        @media (max-width: 640px) {
            .container {
                width: 100%;
                padding: 16px;
                margin: 20px auto;
            }

            .form-section {
                padding: 24px 20px;
            }

            .progress-steps {
                padding: 0 20px;
            }

            .progress-steps::before {
                left: 40px;
                right: 40px;
            }

            .step-text {
                font-size: 12px;
            }

            .radio-group {
                flex-direction: column;
            }

            .radio-group label {
                width: 100%;
                min-width: unset;
            }

            .form-navigation {
                flex-direction: column-reverse;
            }

            button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Progress Steps -->
        <div class="progress-steps">
            <div class="step active" data-step="1">
                <div class="step-number">1</div>
                <div class="step-text">Detail Pesanan</div>
            </div>
            <div class="step" data-step="2">
                <div class="step-number">2</div>
                <div class="step-text">Jumlah Tamu</div>
            </div>
            <div class="step" data-step="3">
                <div class="step-number">3</div>
                <div class="step-text">Pembayaran</div>
            </div>
        </div>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form id="bookingForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>?type=<?php echo $booking_type; ?>&id=<?php echo $id; ?>">
            <!-- Step 1: Detail Pesanan -->
            <div class="form-section active" data-step="1">
                <h2 class="form-title">Detail Pemesan</h2>
                
                <div class="radio-group">
                    <input type="radio" id="tuan" name="title" value="Tuan" required>
                    <label for="tuan">Tuan</label>
                    <input type="radio" id="nyonya" name="title" value="Nyonya">
                    <label for="nyonya">Nyonya</label>
                    <input type="radio" id="nona" name="title" value="Nona">
                    <label for="nona">Nona</label>
                </div>

                <div class="form-group">
                    <label for="fullname">Nama Lengkap</label>
                    <input type="text" id="fullname" name="fullname" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="phone">Nomor Telepon</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>

                <?php if ($booking_type === 'hotel'): ?>
                    <div class="form-group">
                        <label for="check_in">Check-in Date</label>
                        <input type="date" id="check_in" name="check_in" required>
                    </div>
                    <div class="form-group">
                        <label for="check_out">Check-out Date</label>
                        <input type="date" id="check_out" name="check_out" required>
                    </div>
                <?php elseif ($booking_type === 'attraction'): ?>
                    <div class="form-group">
                        <label for="visit_date">Visit Date</label>
                        <input type="date" id="visit_date" name="visit_date" required>
                    </div>
                <?php else: ?>
                    <div class="form-group">
                        <label for="tour_date">Tour Date</label>
                        <input type="date" id="tour_date" name="tour_date" required>
                    </div>
                <?php endif; ?>

                <div class="form-navigation">
                    <button type="button" class="btn-next" onclick="nextStep(1)">Lanjutkan</button>
                </div>
            </div>

            <!-- Step 2: Jumlah Tamu -->
            <div class="form-section" data-step="2">
                <h2 class="form-title">Jumlah Tamu</h2>
                
                <div class="form-group">
                    <label for="adults">Jumlah Dewasa</label>
                    <input type="number" id="adults" name="adults" min="1" value="1" required>
                </div>

                <div class="form-group">
                    <label for="children">Jumlah Anak-anak</label>
                    <input type="number" id="children" name="children" min="0" value="0">
                </div>

                <div class="form-navigation">
                    <button type="button" class="btn-prev" onclick="prevStep(2)">Kembali</button>
                    <button type="button" class="btn-next" onclick="nextStep(2)">Lanjutkan</button>
                </div>
            </div>

            <!-- Step 3: Pembayaran -->
            <div class="form-section" data-step="3">
                <h2 class="form-title">Pembayaran</h2>
                
                <div class="booking-summary">
                    <h3>Ringkasan Pemesanan</h3>
                    <div class="summary-item">
                        <span>Item:</span>
                        <span><?php echo htmlspecialchars($item['name']); ?></span>
                    </div>
                    <div class="summary-item">
                        <span>Tipe:</span>
                        <span><?php echo ucfirst($booking_type); ?></span>
                    </div>
                    <div id="price-summary">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </div>

                <div class="form-group">
                    <label for="payment_method">Metode Pembayaran</label>
                    <select name="payment_method" id="payment_method" required>
                        <option value="">Pilih metode pembayaran</option>
                        <option value="credit_card">Kartu Kredit</option>
                        <option value="bank_transfer">Transfer Bank</option>
                        <option value="e_wallet">E-Wallet</option>
                    </select>
                </div>

                <div class="form-navigation">
                    <button type="button" class="btn-prev" onclick="prevStep(3)">Kembali</button>
                    <button type="submit" class="btn-submit">Konfirmasi Pembayaran</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Form navigation
        function nextStep(currentStep) {
            if (validateSection(currentStep)) {
                // Fixed the querySelector syntax by removing the dot at the beginning
                document.querySelector(`.form-section[data-step="${currentStep}"]`).classList.remove('active');
                document.querySelector(`.form-section[data-step="${currentStep + 1}"]`).classList.add('active');
                document.querySelector(`.step[data-step="${currentStep}"]`).classList.remove('active');
                document.querySelector(`.step[data-step="${currentStep + 1}"]`).classList.add('active');
                updatePriceSummary();
            }
        }

        function prevStep(currentStep) {
            // Fixed the querySelector syntax by removing the dot at the beginning
            document.querySelector(`.form-section[data-step="${currentStep}"]`).classList.remove('active');
            document.querySelector(`.form-section[data-step="${currentStep - 1}"]`).classList.add('active');
            document.querySelector(`.step[data-step="${currentStep}"]`).classList.remove('active');
            document.querySelector(`.step[data-step="${currentStep - 1}"]`).classList.add('active');
        }

        function validateSection(step) {
            // Fixed the querySelector syntax by removing the dot at the beginning
            const section = document.querySelector(`.form-section[data-step="${step}"]`);
            const inputs = section.querySelectorAll('input[required], select[required]');
            let isValid = true;

            inputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('error');
                } else {
                    input.classList.remove('error');
                }
            });

            return isValid;
        }

        // Price calculation
        function updatePriceSummary() {
            const adults = parseInt(document.getElementById('adults').value) || 0;
            const children = parseInt(document.getElementById('children').value) || 0;
            
            <?php if ($booking_type === 'hotel'): ?>
                const checkIn = new Date(document.getElementById('check_in').value);
                const checkOut = new Date(document.getElementById('check_out').value);
                const nights = (checkOut - checkIn) / (1000 * 60 * 60 * 24);
                const basePrice = <?php echo $item['room_price']; ?>;
                const total = basePrice * nights;
            <?php elseif ($booking_type === 'attraction'): ?>
                const basePrice = <?php echo $item['ticket_price']; ?>;
                const total = (basePrice * adults) + (basePrice * 0.5 * children);
            <?php else: ?>
                const basePrice = <?php echo $item['price']; ?>;
                const total = (basePrice * adults) + (basePrice * 0.7 * children);
            <?php endif; ?>

            document.getElementById('price-summary').innerHTML = `
                <div class="summary-item">
                    <span>Harga dasar:</span>
                    <span>Rp ${basePrice.toLocaleString()}</span>
                </div>
                <div class="summary-item">
                    <span>Total:</span>
                    <span>Rp ${total.toLocaleString()}</span>
                </div>
            `;
        }

        // Initialize date inputs
        const dateInputs = document.querySelectorAll('input[type="date"]');
        dateInputs.forEach(input => {
            input.min = new Date().toISOString().split('T')[0];
        });

        // Update price when guest numbers change
        document.getElementById('adults').addEventListener('change', updatePriceSummary);
        document.getElementById('children').addEventListener('change', updatePriceSummary);
    </script>
</body>
</html>