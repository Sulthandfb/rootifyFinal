<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// db_connect.php
$hostname = "dbserver"; 
$username = "root";      
$password = "rootpassword"; 
$database_name = "erd_rootify";

$db = mysqli_connect($hostname, $username, $password, $database_name);

if ($db->connect_error) {
    die("Koneksi database rusak: " . $db->connect_error);
}

// Get packet ID from URL parameter
$packet_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// Fetch main package details
$sql = "SELECT tp.*, tg.name as guide_name, tg.photo_url as guide_photo, tg.rating as guide_rating,
               tg.languages_spoken, tg.years_experience, tg.specialization
        FROM tourist_packets tp 
        LEFT JOIN packet_guides pg ON tp.packet_id = pg.packet_id 
        LEFT JOIN tour_guides tg ON pg.guide_id = tg.guide_id 
        WHERE tp.packet_id = ? AND pg.is_primary = 1";

$stmt = $db->prepare($sql);
$stmt->bind_param("i", $packet_id);
$stmt->execute();
$result = $stmt->get_result();
$package = $result->fetch_assoc();

if (!$package) {
    die("Tour package not found");
}

// Fetch itinerary points
$sql = "SELECT ta.*, pa.sequence, pa.duration 
        FROM packet_attractions pa 
        JOIN tourist_attractions ta ON pa.attraction_id = ta.id 
        WHERE pa.packet_id = ? 
        ORDER BY pa.sequence";

$stmt = $db->prepare($sql);
$stmt->bind_param("i", $packet_id);
$stmt->execute();
$result = $stmt->get_result();
$attractions = [];
while ($row = $result->fetch_assoc()) {
    $attractions[] = $row;
}

// Fetch hotel information (assuming the first hotel in the database for this example)
$sql = "SELECT * FROM hotels LIMIT 1";
$result = $db->query($sql);
$hotel = $result->fetch_assoc();

$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($package['name']); ?></title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color:rgb(255, 255, 255);
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .tour-header {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .tour-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 16px;
            color: #2c3e50;
        }

        .tour-meta {
            display: flex;
            align-items: center;
            gap: 24px;
            flex-wrap: wrap;
        }

        .rating-badge {
            background-color: #3498db;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 15px;
            margin-bottom: 30px;
            height: 500px;
        }

        .gallery-main {
            grid-row: span 2;
            border-radius: 10px;
            overflow: hidden;
        }

        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }

        .gallery-item:hover {
            transform: scale(1.05);
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }

        .main-content {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .about-section, .itinerary-section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 1.8rem;
            color: #2c3e50;
            margin-bottom: 20px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }

        .timeline {
            margin-top: 24px;
        }

        .timeline-item {
            position: relative;
            padding-left: 28px;
            padding-bottom: 24px;
            border-left: 2px solid #3498db;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -9px;
            top: 0;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #3498db;
            border: 2px solid white;
        }

        .timeline-item h3 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .booking-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            position: sticky;
            top: 24px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .price-display {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 16px;
            color: #e74c3c;
        }

        .book-button {
            width: 100%;
            padding: 16px;
            background: #2ecc71;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .book-button:hover {
            background: #27ae60;
        }

        #map {
            height: 400px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .guide-info {
            background-color: #f1f8ff;
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
        }

        .guide-info img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
        }

        .hotel-info {
            background-color: #fff8f1;
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
        }

        @media (max-width: 768px) {
            .gallery-grid, .content-grid {
                grid-template-columns: 1fr;
            }

            .gallery-grid {
                height: auto;
            }

            .booking-card {
                position: static;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="tour-header">
            <h1 class="tour-title"><?php echo htmlspecialchars($package['name']); ?></h1>
            <div class="tour-meta">
                <span class="rating-badge"><?php echo number_format($package['rating'], 1); ?> ★</span>
                <span><?php echo $package['total_reviews']; ?> reviews</span>
                <span>Duration: <?php echo htmlspecialchars($package['duration']); ?></span>
                <span>Age range: <?php echo htmlspecialchars($package['age_range']); ?></span>
            </div>
        </div>

        <div class="gallery-grid">
            <?php foreach($attractions as $index => $attraction): ?>
                <?php if($index === 0): ?>
                    <div class="gallery-item gallery-main">
                        <img src="<?php echo htmlspecialchars($attraction['image_url']); ?>" 
                             alt="<?php echo htmlspecialchars($attraction['name']); ?>">
                    </div>
                <?php elseif($index <= 2): ?>
                    <div class="gallery-item">
                        <img src="<?php echo htmlspecialchars($attraction['image_url']); ?>" 
                             alt="<?php echo htmlspecialchars($attraction['name']); ?>">
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <div class="content-grid">
            <div class="main-content">
                <div class="about-section">
                    <h2 class="section-title">About This Tour</h2>
                    <p><?php echo nl2br(htmlspecialchars($package['description'])); ?></p>
                </div>

                <div class="itinerary-section">
                    <h2 class="section-title">Tour Itinerary</h2>
                    <div id="map"></div>
                    <div class="timeline">
                        <?php foreach($attractions as $attraction): ?>
                            <div class="timeline-item">
                                <h3><?php echo htmlspecialchars($attraction['name']); ?></h3>
                                <p><strong>Duration:</strong> <?php echo htmlspecialchars($attraction['duration']); ?></p>
                                <p><?php echo htmlspecialchars($attraction['description']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="guide-info">
                    <h2 class="section-title">Your Tour Guide</h2>
                    <?php if($package['guide_photo']): ?>
                        <img src="<?php echo htmlspecialchars($package['guide_photo']); ?>" alt="<?php echo htmlspecialchars($package['guide_name']); ?>">
                    <?php endif; ?>
                    <h3><?php echo htmlspecialchars($package['guide_name']); ?></h3>
                    <p><strong>Languages:</strong> <?php echo htmlspecialchars($package['languages_spoken']); ?></p>
                    <p><strong>Experience:</strong> <?php echo htmlspecialchars($package['years_experience']); ?> years</p>
                    <p><strong>Specialization:</strong> <?php echo htmlspecialchars($package['specialization']); ?></p>
                    <p><strong>Rating:</strong> <?php echo number_format($package['guide_rating'], 1); ?> ★</p>
                </div>

                <?php if($hotel): ?>
                <div class="hotel-info">
                    <h2 class="section-title">Accommodation</h2>
                    <h3><?php echo htmlspecialchars($hotel['name']); ?></h3>
                    <p><?php echo htmlspecialchars($hotel['description']); ?></p>
                    <p><strong>Rating:</strong> <?php echo number_format($hotel['rating'], 1); ?> ★</p>
                    <p><strong>Category:</strong> <?php echo ucfirst(htmlspecialchars($hotel['category'])); ?></p>
                    <?php if($hotel['image_url']): ?>
                        <img src="<?php echo htmlspecialchars($hotel['image_url']); ?>" alt="<?php echo htmlspecialchars($hotel['name']); ?>" style="max-width: 100%; border-radius: 10px; margin-top: 15px;">
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>

            <div class="booking-card">
                <div class="price-display">
                    Rp <?php echo number_format($package['discounted_price'], 0, ',', '.'); ?>
                    <?php if($package['discounted_price'] < $package['price']): ?>
                        <span style="text-decoration: line-through; color: #666; font-size: 0.7em;">
                            Rp <?php echo number_format($package['price'], 0, ',', '.'); ?>
                        </span>
                    <?php endif; ?>
                </div>
                <p>per person</p>
                <ul style="margin: 16px 0; list-style-position: inside;">
                    <li>Max participants: <?php echo $package['max_participants']; ?></li>
                    <li>Start time: <?php echo date('g:i A', strtotime($package['start_time'])); ?></li>
                    <li>Meeting point: <?php echo htmlspecialchars($package['meeting_point']); ?></li>
                </ul>
                <h3 style="margin-top: 20px;">What's Included:</h3>
                <p><?php echo nl2br(htmlspecialchars($package['includes'])); ?></p>
                <button class="book-button">Book Now</button>
                <p style="margin-top: 16px; font-size: 0.9em; color: #666;">
                    <?php echo htmlspecialchars($package['cancellation_policy']); ?>
                </p>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        const attractions = <?php echo json_encode($attractions); ?>;
        
        document.addEventListener('DOMContentLoaded', () => {
            const map = L.map('map');
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            const markers = [];
            const coordinates = attractions.map(attraction => [
                parseFloat(attraction.latitude),
                parseFloat(attraction.longitude)
            ]);

            // Add markers and connect them with lines
            attractions.forEach((attraction, index) => {
                const marker = L.marker([
                    parseFloat(attraction.latitude),
                    parseFloat(attraction.longitude)
                ])
                .bindPopup(`<b>${attraction.name}</b><br>${attraction.duration}`)
                .addTo(map);
                
                markers.push(marker);
            });

            // Create a line connecting all points
            if (coordinates.length > 0) {
                const polyline = L.polyline(coordinates, {color: '#3498db'}).addTo(map);
                map.fitBounds(polyline.getBounds());
            }
        });
    </script>
</body>
</html>