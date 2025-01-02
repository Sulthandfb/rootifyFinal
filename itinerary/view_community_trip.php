<?php
session_start();
include "../filter_wisata/db_connect.php";

// Get post ID from URL
$post_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$post_id) {
    header("Location: community.php");
    exit();
}

// First, get the itinerary_id from community_posts
$post_query = "SELECT cp.*, u.username as post_owner 
               FROM community_posts cp 
               JOIN users u ON cp.user_id = u.id 
               WHERE cp.id = ?";
$post_stmt = mysqli_prepare($db, $post_query);
mysqli_stmt_bind_param($post_stmt, "i", $post_id);
mysqli_stmt_execute($post_stmt);
$post_result = mysqli_stmt_get_result($post_stmt);
$post_data = mysqli_fetch_assoc($post_result);

if (!$post_data) {
    header("Location: community.php");
    exit();
}

// Then fetch itinerary details and attractions using the itinerary_id
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
mysqli_stmt_bind_param($stmt, "i", $post_data['itinerary_id']);
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

<?php include '../navfot/navbar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($trip_details['trip_name']); ?> - Rootify</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" href="#">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            padding-top: 80px;
            background-color:rgb(255, 255, 255);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
            display: flex;
            gap: 2rem;
        }

        .left-content {
            flex: 1;
            min-width: 0;
        }

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

        .post-info {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .post-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .post-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            overflow: hidden;
        }

        .post-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .post-user-info {
            flex-grow: 1;
        }

        .post-username {
            font-weight: 600;
            color: #333;
            font-size: 1.1rem;
        }

        .post-date {
            color: #666;
            font-size: 0.9rem;
        }

        .post-caption {
            color: #333;
            font-size: 1rem;
            line-height: 1.5;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }

        .header {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .header-title {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #333;
        }

        .header-details {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #666;
            font-size: 0.9rem;
            flex-wrap: wrap;
        }

        .day-section {
            background: white;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .day-header {
            padding: 1rem 1.5rem;
            background: #f8f8f8;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .day-header h2 {
            font-size: 1.1rem;
            color: #333;
        }

        .dropdown-arrow {
            transition: transform 0.3s;
        }

        .dropdown-arrow.open {
            transform: rotate(180deg);
        }

        .day-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .day-content.open {
            max-height: 2000px;
        }

        .attractions-container {
            padding: 1.5rem;
        }

        .card {
            display: flex;
            background: #f8f8f8;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 1rem;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .card-image {
            width: 200px;
            height: 150px;
            object-fit: cover;
        }

        .card-content {
            padding: 1rem;
            flex: 1;
        }

        .card-title {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .card-rating {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .rating-stars {
            color: #ffd700;
        }

        .card-category {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            color: #666;
        }

        .category-icon {
            width: 20px;
            height: 20px;
        }

        .card-description {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .map-container {
            position: sticky;
            top: 2rem;
            width: 400px;
            height: calc(100vh - 4rem);
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        #map {
            width: 100%;
            height: 100%;
        }

        .card-link {
            text-decoration: none;
            color: inherit;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .container {
                flex-direction: column;
            }

            .map-container {
                width: 100%;
                height: 400px;
                position: static;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .card {
                flex-direction: column;
            }

            .card-image {
                width: 100%;
                height: 200px;
            }
        }
    </style>
</head>
<body>
<div class="container">
        <div class="left-content">
            <a href="community.php" class="back-btn">
                <i class="ri-arrow-left-line"></i> Back to Community
            </a>

            <div class="post-info">
                <div class="post-header">
                    <div class="post-avatar">
                        <img src="../img/default-avatar.jpg" alt="Profile picture">
                    </div>
                    <div class="post-user-info">
                        <div class="post-username"><?php echo htmlspecialchars($post_data['post_owner']); ?></div>
                        <div class="post-date"><?php echo date('F j, Y', strtotime($post_data['created_at'])); ?></div>
                    </div>
                </div>
                <?php if ($post_data['caption']): ?>
                    <div class="post-caption"><?php echo htmlspecialchars($post_data['caption']); ?></div>
                <?php endif; ?>
            </div>
            
            <div class="header">
                <h1 class="header-title"><?php echo htmlspecialchars($trip_details['trip_name']); ?></h1>
                <div class="header-details">
                    <span><?php echo date('M d', strtotime($trip_details['start_date'])) . ' - ' . date('M d, Y', strtotime($trip_details['end_date'])); ?></span>
                    <span>•</span>
                    <span><?php echo ucfirst($trip_details['trip_type']); ?> Trip</span>
                    <span>•</span>
                    <span><?php echo ucfirst($trip_details['budget']); ?> Budget</span>
                </div>
            </div>

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
                                    <div class="card">
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
    <?php include '../navfot/footer.php'; ?>
</body>
</html>

