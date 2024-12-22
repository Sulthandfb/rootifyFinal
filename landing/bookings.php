<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: landingpage.php");
    exit();
}

// Dummy data for bookings
$bookings = [
    [
        'id' => 1,
        'destination' => 'Borobudur Temple',
        'date' => '2023-07-15',
        'status' => 'Confirmed',
        'image' => 'borobudur.jpg'
    ],
    [
        'id' => 2,
        'destination' => 'Parangtritis Beach',
        'date' => '2023-08-02',
        'status' => 'Pending',
        'image' => 'parangtritis.jpg'
    ],
    [
        'id' => 3,
        'destination' => 'Prambanan Temple',
        'date' => '2023-09-10',
        'status' => 'Confirmed',
        'image' => 'prambanan.jpg'
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - Rootify</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .bookings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .booking-card {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        .booking-card:hover {
            transform: translateY(-5px);
        }
        .booking-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .booking-details {
            padding: 20px;
        }
        .booking-destination {
            font-size: 1.2em;
            font-weight: 600;
            color: #333;
            margin: 0 0 10px 0;
        }
        .booking-date, .booking-status {
            font-size: 0.9em;
            color: #666;
            margin: 5px 0;
        }
        .booking-status.confirmed {
            color: #4CAF50;
        }
        .booking-status.pending {
            color: #FFA500;
        }
        .view-details-btn {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            margin-top: 10px;
            transition: background-color 0.3s;
        }
        .view-details-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>My Bookings</h1>
        <div class="bookings-grid">
            <?php foreach ($bookings as $booking): ?>
                <div class="booking-card">
                    <img src="<?php echo $booking['image']; ?>" alt="<?php echo $booking['destination']; ?>" class="booking-image">
                    <div class="booking-details">
                        <h2 class="booking-destination"><?php echo $booking['destination']; ?></h2>
                        <p class="booking-date">Date: <?php echo $booking['date']; ?></p>
                        <p class="booking-status <?php echo strtolower($booking['status']); ?>">Status: <?php echo $booking['status']; ?></p>
                        <button class="view-details-btn" onclick="viewDetails(<?php echo $booking['id']; ?>)">View Details</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        function viewDetails(bookingId) {
            alert('Viewing details for booking ' + bookingId + '. This functionality will be implemented soon!');
        }
    </script>
</body>
</html>

