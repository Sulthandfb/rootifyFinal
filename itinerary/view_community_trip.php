<?php
session_start();
include "../filter_wisata/db_connect.php";

// Get itinerary ID from URL
$itinerary_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$itinerary_id) {
    header("Location: community.php");
    exit();
}

// Fetch itinerary details and attractions
$query = "SELECT i.*, 
          ia.day,
          ia.display_order,
          ta.id as attraction_id,
          ta.name,
          ta.description,
          ta.image_url,
          ta.rating,
          ta.category,
          ta.latitude,
          ta.longitude,
          ta.address,
          ta.opening_time,
          ta.closing_time,
          u.username
          FROM itineraries i
          LEFT JOIN itinerary_attractions ia ON i.id = ia.itinerary_id
          LEFT JOIN tourist_attractions ta ON ia.attraction_id = ta.id
          LEFT JOIN users u ON i.user_id = u.id
          WHERE i.id = ?
          ORDER BY ia.day, ia.display_order";

$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "i", $itinerary_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$trip_details = null;
$daily_attractions = array();

while ($row = mysqli_fetch_assoc($result)) {
    if (!$trip_details) {
        $trip_details = array(
            'trip_name' => $row['trip_name'],
            'start_date' => $row['start_date'],
            'end_date' => $row['end_date'],
            'trip_type' => $row['trip_type'],
            'budget' => $row['budget'],
            'username' => $row['username']
        );
    }

    if ($row['attraction_id']) {
        $daily_attractions[$row['day']][] = array(
            'id' => $row['attraction_id'],
            'name' => $row['name'],
            'description' => $row['description'],
            'image_url' => $row['image_url'],
            'rating' => $row['rating'],
            'category' => $row['category'],
            'latitude' => $row['latitude'],
            'longitude' => $row['longitude'],
            'address' => $row['address'],
            'opening_time' => $row['opening_time'],
            'closing_time' => $row['closing_time']
        );
    }
}

// Calculate total days
$total_days = round((strtotime($trip_details['end_date']) - strtotime($trip_details['start_date'])) / (60 * 60 * 24)) + 1;

// Generate dates for each day
$day_dates = array();
for ($i = 0; $i < $total_days; $i++) {
    $day_dates[] = date('F j', strtotime($trip_details['start_date'] . " + $i days"));
}

// Helper function for category icons
function getCategoryIcon($category) {
    $icons = [
        'nature' => '../icons/leaves.svg',
        'culture' => '../icons/masks.svg',
        'shopping' => '../icons/online-shopping.svg',
        'education' => '../icons/graduation-cap.svg',
        'beach' => '../icons/vacations.svg',
        'recreation' => '../icons/park.svg',
        'history' => '../icons/history.svg',
        'restaurant' => '../icons/restaurant.svg',
    ];
    return isset($icons[strtolower($category)]) ? $icons[strtolower($category)] : '../icons/leaves.svg';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($trip_details['trip_name']); ?> - Rootify</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" href="itinerary-planning.css">
    <style>
        /* Add your CSS styles here (same as in view_itinerary.php) */
        .back-btn {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            background-color: #f0f0f0;
            color: #333;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
            margin-bottom: 1rem;
        }

        .back-btn:hover {
            background-color: #e0e0e0;
        }

        .back-btn i {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="community.php" class="back-btn">
            <i class="ri-arrow-left-line"></i> Back to Community
        </a>
        
        <div class="left-content">
            <header class="header">
                <div class="header-content">
                    <h1 class="header-title"><?php echo htmlspecialchars($trip_details['trip_name']); ?></h1>
                    <div class="header-details">
                        <span><?php echo date('M d', strtotime($trip_details['start_date'])) . ' - ' . date('M d, Y', strtotime($trip_details['end_date'])); ?></span>
                        <span>•</span>
                        <span><?php echo ucfirst($trip_details['trip_type']); ?> Trip</span>
                        <span>•</span>
                        <span><?php echo ucfirst($trip_details['budget']); ?> Budget</span>
                    </div>
                    <div class="trip-creator">
                        Created by: <?php echo htmlspecialchars($trip_details['username']); ?>
                    </div>
                </div>
            </header>

            <div class="content">
                <?php for ($day = 1; $day <= $total_days; $day++): ?>
                    <div class="day-section">
                        <div class="day-header" onclick="toggleDay(<?php echo $day - 1; ?>)">
                            <h2>Day <?php echo $day; ?>, <?php echo $day_dates[$day - 1]; ?></h2>
                            <span class="dropdown-arrow" id="arrow-<?php echo $day - 1; ?>">▼</span>
                        </div>
                        <div class="day-content" id="day-<?php echo $day - 1; ?>">
                            <div class="attractions-container">
                                <?php 
                                if (isset($daily_attractions[$day])) {
                                    foreach ($daily_attractions[$day] as $attraction): 
                                ?>
                                <a href="../attractions/attractions-details.php?id=<?php echo $attraction['id']; ?>" class="card-link">
                                    <div class="card" data-attraction-id="<?php echo $attraction['id']; ?>">
                                        <img src="<?php echo htmlspecialchars($attraction['image_url']); ?>" 
                                            alt="<?php echo htmlspecialchars($attraction['name']); ?>" 
                                            class="card-image">
                                        <div class="card-content">
                                            <h3 class="card-title"><?php echo htmlspecialchars($attraction['name']); ?></h3>
                                            <div class="card-rating">
                                                <div class="rating-stars">
                                                    <?php
                                                    $rating = $attraction['rating'];
                                                    for ($i = 1; $i <= 5; $i++) {
                                                        echo $i <= $rating ? '★' : '☆';
                                                    }
                                                    ?>
                                                </div>
                                                <span><?php echo $attraction['rating']; ?></span>
                                            </div>
                                            <div class="card-category">
                                                <img src="<?php echo getCategoryIcon($attraction['category']); ?>" 
                                                     alt="Icon" class="category-icon">
                                                <?php echo htmlspecialchars($attraction['category']); ?>
                                            </div>
                                            <p class="card-description">
                                                <?php echo htmlspecialchars($attraction['description']); ?>
                                            </p>
                                        </div>
                                    </div>
                                </a>
                                <?php 
                                    endforeach;
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
        
        <div class="map-container">
            <div id="map"></div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // Initialize map
        const map = L.map('map').setView([-7.7956, 110.3695], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add markers for existing attractions
        <?php foreach ($daily_attractions as $day => $attractions): ?>
            <?php foreach ($attractions as $attraction): ?>
                L.marker([
                    <?php echo $attraction['latitude']; ?>, 
                    <?php echo $attraction['longitude']; ?>
                ]).addTo(map)
                 .bindPopup("<?php echo htmlspecialchars($attraction['name']); ?>");
            <?php endforeach; ?>
        <?php endforeach; ?>

        function toggleDay(dayIndex) {
            const content = document.getElementById(`day-${dayIndex}`);
            const arrow = document.getElementById(`arrow-${dayIndex}`);
            content.classList.toggle('open');
            arrow.classList.toggle('open');
        }
    </script>
</body>
</html>

