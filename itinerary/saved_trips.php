<?php
session_start();
include 'get_saved_trips.php';

// Redirect jika pengguna belum login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$trips = getSavedTrips();

// Fungsi untuk menghitung durasi perjalanan
function calculateDuration($start_date, $end_date) {
    $start = new DateTime($start_date);
    $end = new DateTime($end_date);
    $interval = $start->diff($end);
    return $interval->days + 1;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Saved Trips</title>
    <style>
        

.container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .button-container {
            display: flex;
            gap: 20px;
        }

        .create-trip-btn {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: white;
            cursor: pointer;
            text-decoration: none;
            color: black;
        }

        .ai-trip-btn {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: white;
            cursor: pointer;
            text-decoration: none;
            color: black;
        }

        .sort-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .trips-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .trip-card {
            border: 1px solid #ddd;
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.2s;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }

        .trip-card:hover {
            transform: translateY(-5px);
        }

        .trip-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .trip-content {
            padding: 15px;
        }

        .trip-title {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .trip-details {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #666;
            font-size: 0.9rem;
        }

        .current-trip-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>My Trips</h1>
            <div class="button-container">
                <a href="create-trip.php" class="create-trip-btn">
                    <span>+</span>
                    <span>Create a new trip</span>
                </a>
                <a href="ai-trip.php" class="ai-trip-btn">
                    <span>🤖</span>
                    <span>Build a trip with AI</span>
                </a>
            </div>
        </div>

        <div class="sort-container">
            <select id="sortTrips" onchange="sortTrips(this.value)">
                <option value="date">Sort by: Trip start date</option>
                <option value="name">Sort by: Trip name</option>
                <option value="duration">Sort by: Duration</option>
            </select>
        </div>

        <div class="trips-grid">
            <?php foreach ($trips as $trip): ?>
                <a href="view-trip.php?id=<?php echo $trip['id']; ?>" class="trip-card">
                    <div style="position: relative;">
                        <img 
                            src="<?php echo $trip['attraction_images'][0] ?? 'default-trip-image.jpg'; ?>" 
                            alt="<?php echo htmlspecialchars($trip['trip_name']); ?>"
                            class="trip-image"
                        >
                        <?php if (strtotime($trip['start_date']) <= time() && strtotime($trip['end_date']) >= time()): ?>
                            <div class="current-trip-badge">Current Trip</div>
                        <?php endif; ?>
                    </div>
                    <div class="trip-content">
                        <div class="trip-title">
                            <?php 
                            $duration = calculateDuration($trip['start_date'], $trip['end_date']);
                            echo htmlspecialchars($trip['trip_name']) . " for " . $duration . " days";
                            ?>
                        </div>
                        <div class="trip-details">
                            <span>
                                <?php 
                                echo date('M d', strtotime($trip['start_date'])) . ' → ' . 
                                     date('M d, Y', strtotime($trip['end_date']));
                                ?>
                            </span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        function sortTrips(criteria) {
            // Implementasi pengurutan sisi klien atau muat ulang halaman dengan parameter pengurutan baru
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('sort', criteria);
            window.location.href = currentUrl.toString();
        }
    </script>
</body>
</html>