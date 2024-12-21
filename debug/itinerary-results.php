<?php
include 'get_attractions.php';

// Process form data
$start_date = isset($_POST['startDate']) ? $_POST['startDate'] : '';
$end_date = isset($_POST['endDate']) ? $_POST['endDate'] : '';
$trip_type = isset($_POST['tripType']) ? $_POST['tripType'] : '';
$budget = isset($_POST['budget']) ? $_POST['budget'] : '';
$interests = isset($_POST['interests']) ? $_POST['interests'] : [];

// Format dates for display
$formatted_start_date = date('M d', strtotime($start_date));
$formatted_end_date = date('M d', strtotime($end_date));
$year = date('Y', strtotime($end_date));

// Get attractions based on user preferences
$attractions = getAttractions($trip_type, $budget, $interests);

// Get restaurants separately
$restaurants = getRestaurants();
shuffle($restaurants);

// Calculate total days
$total_days = round((strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24)) + 1;

// Split attractions into 4 destinations per day
$daily_itinerary = array_chunk($attractions, 4);

// Generate dates for each day
$day_dates = [];
for ($i = 0; $i < $total_days; $i++) {
    $day_dates[] = date('F j', strtotime($start_date . " + $i days"));
}

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
    <title>Trip Recommendations</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="itinerary-planning.css">
    <style>
        .day-section {
            margin-bottom: 1rem;
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .day-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f8f8f8;
            padding: 1rem;
            cursor: pointer;

        }

        .day-header h2 {
            margin: 0;
            font-size: 1.25rem;
        }

        .day-content {
            display: none;
            padding: 1rem;
            background-color: white;
        }

        .dropdown-arrow {
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }

        .day-content.open {
            display: block;
        }

        .dropdown-arrow.open {
            transform: rotate(180deg);
        }

        /* Button Sticky at the Bottom Center */
        #saveTripBtn {
            position: sticky;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            padding: 1rem;
            background-color: black;
            color: white;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            transition: background-color 0.3s ease;
        }

        #saveTripBtn:hover {
            background-color: #444;
        }

        /* Styling for the Modal */
        #saveTripModal {
            display: none; /* Initially hidden */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 999;
        }

        /* Modal Content Styling */
        #saveTripModal form {
            background-color: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 500px;
            text-align: center;
        }

        #saveTripModal label {
            font-size: 18px;
            margin-bottom: 10px;
            display: block;
        }

        #saveTripModal input[type="text"] {
            width: 100%;
            padding: 0.8rem;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        #saveTripModal button[type="submit"] {
            background-color: black;
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        #saveTripModal button[type="submit"]:hover {
            background-color: #444;
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="left-content">
            <header class="header">
                <div class="header-content">
                    <h1 class="header-title">Yogyakarta for <?php echo $total_days; ?> days</h1>
                    <div class="header-details">
                        <span><?php echo $formatted_start_date . ' - ' . $formatted_end_date . ', ' . $year; ?></span>
                        <span>•</span>
                        <span>Yogyakarta</span>
                    </div>
                </div>
            </header>
            <div class="content">
                <div class="tabs">
                    <div class="tab active">Auto Plan</div>
                    <div class="tab">Itinerary</div>
                </div>

                <!-- Tampilkan destinasi per hari -->
                <?php for ($day_index = 0; $day_index < $total_days; $day_index++): ?>
                    <div class="day-section">
                        <div class="day-header" onclick="toggleDay(<?php echo $day_index; ?>)">
                            <h2>Day <?php echo $day_index + 1; ?>, <?php echo $day_dates[$day_index]; ?></h2>
                            <span class="dropdown-arrow" id="arrow-<?php echo $day_index; ?>">▼</span>
                        </div>
                        <div class="day-content" id="day-<?php echo $day_index; ?>">
                            <?php 
                                // Ambil destinasi sesuai indeks hari
                                $current_day_attractions = isset($daily_itinerary[$day_index]) ? $daily_itinerary[$day_index] : [];

                                // Add one restaurant per day
                                if (!empty($restaurants)) {
                                    $current_day_attractions[] = array_shift($restaurants); 
                                }
                            ?>
                            <?php foreach ($current_day_attractions as $attraction): ?>
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
                                            <img src="<?php echo getCategoryIcon($attraction['category']); ?>" alt="Icon" class="category-icon">
                                            <?php echo htmlspecialchars($attraction['category']); ?>
                                        </div>
                                        <p class="card-description">
                                            <?php echo htmlspecialchars($attraction['description']); ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <?php if (empty($current_day_attractions)): ?>
                                <p>No destinations available for this day.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>

        <!-- Map Container -->
        <div class="map-container">
            <div id="map"></div>
        </div>
    </div>
    
    <div style="position: sticky; bottom: 0; background: white; text-align: center;">
        <button id="saveTripBtn" style="padding: 1rem; background-color: black; color: white; cursor: pointer;">Save</button>
    </div>

    <!-- Popup Form -->
    <div id="saveTripModal" style="display: none;">
        <form method="POST" action="save_trip.php">
            <label for="trip_name">Trip Name</label>
            <input type="text" name="trip_name" required />

            <input type="hidden" name="start_date" value="<?php echo $start_date; ?>">
            <input type="hidden" name="end_date" value="<?php echo $end_date; ?>">
            <input type="hidden" name="trip_type" value="<?php echo $trip_type; ?>">
            <input type="hidden" name="budget" value="<?php echo $budget; ?>">

            <!-- Kirim array ID destinasi -->
            <?php foreach ($attractions as $attraction): ?>
                <input type="hidden" name="attractions[]" value="<?php echo $attraction['id']; ?>">
            <?php endforeach; ?>

            <button type="submit">Save Trip</button>
        </form>
    </div>
    <script>
        document.getElementById('saveTripBtn').addEventListener('click', function () {
            document.getElementById('saveTripModal').style.display = 'flex'; // Show the modal
        });

        // To close the modal, we can use this code (for example, adding a close button or clicking outside the modal)
        window.addEventListener('click', function (event) {
            if (event.target === document.getElementById('saveTripModal')) {
                document.getElementById('saveTripModal').style.display = 'none'; // Hide the modal
            }
        });
    </script>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // Toggle day content
        function toggleDay(dayIndex) {
            const content = document.getElementById(`day-${dayIndex}`);
            const arrow = document.getElementById(`arrow-${dayIndex}`);

            content.classList.toggle('open');
            arrow.classList.toggle('open');
        }

        // Initialize map
        const map = L.map('map').setView([-7.7956, 110.3695], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add markers for each attraction
        <?php foreach ($attractions as $attraction): ?>
        L.marker([
            <?php echo $attraction['latitude']; ?>, 
            <?php echo $attraction['longitude']; ?>
        ]).addTo(map)
         .bindPopup("<?php echo htmlspecialchars($attraction['name']); ?>");
        <?php endforeach; ?>
    </script>
</body>
</html>