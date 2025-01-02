<?php
include '../filter_wisata/db_connect.php';

// Get attraction ID from URL
$attraction_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch attraction details
$query = "SELECT ta.*, ad.ticket_price, ad.embed_code, ad.photo_gallery
          FROM tourist_attractions ta
          LEFT JOIN attraction_details ad ON ta.id = ad.attraction_id
          WHERE ta.id = ?";

$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "i", $attraction_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$attraction = mysqli_fetch_assoc($result);

// If attraction not found, redirect back
if (!$attraction) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}
?>

<?php include '../navfot/navbar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($attraction['name']); ?> - Details</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="attractions.css">
    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        body {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
            background-color:rgb(255, 255, 255);
            color: while;
            line-height: 1.6;
        }

        /* Back Button */
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #555;
            text-decoration: none;
            font-weight: 500;
            margin: 20px 0;
            padding: 8px 16px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: all 0.2s ease;
        }

        .back-button:hover {
            background-color: #f0f0f0;
            transform: translateX(-2px);
        }

        /* Header Section */
        .header {
            margin-bottom: 24px;
        }

        .title {
            font-size: clamp(1.5rem, 4vw, 2.5rem);
            font-weight: 700;
            color: #1a1a1a;
            line-height: 1.2;
            margin-bottom: 12px;
        }

        /* Rating and Tags */
        .rating {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }

        .stars {
            color: #00aa6c;
            font-size: 1.2rem;
            letter-spacing: 2px;
        }

        .tags {
            display: flex;
            gap: 16px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .tag {
            color: #666;
            font-size: 0.95rem;
            position: relative;
            padding-right: 16px;
        }

        .tag:not(:last-child)::after {
            content: "•";
            position: absolute;
            right: 0;
            color: #ccc;
        }

        /* Status Section */
        .status {
            background-color: white;
            padding: 16px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 24px;
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            align-items: center;
        }

        .open, .closed {
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .open { color: #00aa6c; }
        .closed { color: #dc3545; }

        /* Main Content Grid */
        .main-content {
            display: grid;
            grid-template-columns: minmax(300px, 1fr) 2fr;
            gap: 24px;
            margin-bottom: 32px;
        }

        /* About Section */
        .about {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            height: fit-content;
        }

        .about h2 {
            font-size: 1.5rem;
            margin-bottom: 16px;
            color: #1a1a1a;
        }

        .about p {
            color: #555;
            margin-bottom: 20px;
            line-height: 1.8;
        }

        .about h3 {
            font-size: 1.2rem;
            margin: 24px 0 12px;
            color: #1a1a1a;
        }

        /* Gallery Section */
        .gallery {
            position: relative;
            background: white;
            padding: 16px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .main-image {
            width: 100%;
            height: 500px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 16px;
        }

        /* Location Section */
        .location-section {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
            margin-top: 32px;
        }

        .map-section h2, 
        .address-card h2 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #1a1a1a;
        }

        .map-container {
            background: white;
            padding: 16px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            height: 450px;
            overflow: hidden;
        }

        #map {
            width: 100%;
            height: 100%;
            border-radius: 8px;
        }

        .address-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .address-details p {
            color: #666;
            line-height: 1.8;
        }

        /* Book Button */
        .book-button {
            display: inline-block;
            background: #00aa6c;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 20px;
            transition: background-color 0.2s ease;
        }

        .book-button:hover {
            background: #008c58;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .main-content {
                grid-template-columns: 1fr;
            }
            
            .location-section {
                grid-template-columns: 1fr;
            }
            
            .map-container {
                height: 350px;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 16px;
            }
            
            .status {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            
            .main-image {
                height: 300px;
            }
        }

        @media (max-width: 480px) {
            .rating, .tags {
                gap: 8px;
            }
            
            .tag {
                font-size: 0.85rem;
            }
            
            .about, .gallery, .address-card {
                padding: 16px;
            }
        }
    </style>
</head>
<body>
    <pre>

    
    </pre>
    <a href="javascript:history.back()" class="back-button">← Back to Itinerary</a>

    <div class="header">
        <h1 class="title"><?php echo htmlspecialchars($attraction['name']); ?></h1>
    </div>

    <div class="rating">
        <div class="stars">
            <?php
            $rating = $attraction['rating'];
            for ($i = 1; $i <= 5; $i++) {
                echo $i <= $rating ? '★' : '☆';
            }
            ?>
        </div>
        <span><?php echo number_format($attraction['rating'], 1); ?> rating</span>
    </div>

    <div class="tags">
        <span class="tag"><?php echo htmlspecialchars($attraction['category']); ?></span>
        <?php if ($attraction['rating'] >= 4.5): ?>
            <span class="tag">Popular Destination in Yogyakarta</span>
        <?php endif; ?>
    </div>

    <div class="status">
        <?php
        $current_time = date('H:i:s');
        $is_open = ($current_time >= $attraction['opening_time'] && $current_time <= $attraction['closing_time']);
        ?>
        <span class="<?php echo $is_open ? 'open' : 'closed'; ?>">
            Currently: <?php echo $is_open ? 'Open' : 'Closed'; ?>
        </span>
        <span>Opening Hours: <?php echo date("g:i A", strtotime($attraction['opening_time'])); ?> - <?php echo date("g:i A", strtotime($attraction['closing_time'])); ?></span>
    </div>

    <div class="main-content">
        <div class="about">
            <h2>About</h2>
            <p><?php echo nl2br(htmlspecialchars($attraction['description'])); ?></p>
            <?php if (isset($attraction['ticket_price'])): ?>
                <h3>Tickets</h3>
                <p>Price: <?php echo $attraction['ticket_price'] > 0 ? "Rp " . number_format($attraction['ticket_price'], 0, ',', '.') : "Free"; ?></p>
                <a href="../landing/pembayaran.php?type=attraction&id=<?php echo $attraction['id']; ?>" class="book-button">Buy Tickets</a>
            <?php endif; ?>
        </div>

        <div class="gallery">
            <img src="<?php echo htmlspecialchars($attraction['image_url']); ?>" 
                 alt="Main view of <?php echo htmlspecialchars($attraction['name']); ?>" 
                 class="main-image">
        </div>
    </div>

    <hr>

    <div class="location-section">
        <div class="map-section">
            <h2>Location</h2>
            <pre>

            </pre>
            <div class="map-container">
                <div id="map"></div>
            </div>
        </div>
        <div class="address-card">
            <h2>Address</h2>
            <div class="address-details">
                <p><?php echo htmlspecialchars($attraction['address']); ?></p>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // Initialize map
        const map = L.map('map').setView([<?php echo $attraction['latitude']; ?>, <?php echo $attraction['longitude']; ?>], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        // Add marker
        L.marker([<?php echo $attraction['latitude']; ?>, <?php echo $attraction['longitude']; ?>])
            .addTo(map)
            .bindPopup("<?php echo htmlspecialchars($attraction['name']); ?>");

        // Function to update main image
        function updateMainImage(src) {
            document.querySelector('.main-image').src = src;
        }
    </script>
</body>
</html>