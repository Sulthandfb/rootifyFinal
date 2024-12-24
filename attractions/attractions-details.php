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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($attraction['name']); ?> - Details</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="attractions.css">
    <style>
        /* * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        body {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
            background-color: #f5f5f5;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .title {
            font-size: 2rem;
            font-weight: bold;
        }

        .rating {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 10px 0;
        }

        .stars {
            color: #00aa6c;
        }

        .tags {
            display: flex;
            gap: 10px;
            margin: 10px 0;
            flex-wrap: wrap;
        }

        .tag {
            color: #666;
            font-size: 0.9rem;
        }

        .tag:not(:last-child):after {
            content: "•";
            margin-left: 10px;
        }

        .status {
            margin: 15px 0;
        }

        .closed {
            color: red;
            margin-right: 10px;
        }

        .open {
            color: #00aa6c;
            margin-right: 10px;
        }

        .main-content {
            display: grid;
            grid-template-columns: 350px 1fr;
            gap: 20px;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .about {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .about h2 {
            margin-bottom: 15px;
        }

        .gallery {
            position: relative;
        }

        .main-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 8px;
        }

        .thumbnails {
            display: flex;
            gap: 10px;
            margin-top: 10px;
            overflow-x: auto;
        }

        .thumbnail {
            width: 100px;
            height: 70px;
            object-fit: cover;
            border-radius: 4px;
            cursor: pointer;
        }

        .see-options {
            background: #000;
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            border: none;
            width: 100%;
            margin-top: 20px;
        } */

        .back-button {
            display: inline-block;
            margin-bottom: 20px;
            color: #666;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .location-section {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-top: 20px;
            padding-top: 20px;
        }

        .map-section {
            padding-right: 20px;
        }

        .map-container {
            width: 100%;
            height: 450px;
            border-radius: 8px;
            overflow: hidden;
        }

        .address-card {
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .address-card h2 {
            margin-bottom: 15px;
        }

        .address-details p {
            margin-bottom: 8px;
            color: #666;
        }

        #map {
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
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