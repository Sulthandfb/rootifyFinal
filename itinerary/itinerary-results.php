<?php
session_start();
include "../filter_wisata/db_connect.php";

// Process form data
$start_date = isset($_POST['startDate']) ? $_POST['startDate'] : '';
$end_date = isset($_POST['endDate']) ? $_POST['endDate'] : '';
$trip_type = isset($_POST['tripType']) ? $_POST['tripType'] : '';
$budget = isset($_POST['budget']) ? $_POST['budget'] : '';
$interests = isset($_POST['interests']) ? $_POST['interests'] : [];

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$user_id = $_SESSION['user_id'];

// Create new itinerary
$trip_name = "Trip to Yogyakarta"; // You might want to allow users to set this
$query = "INSERT INTO itineraries (user_id, trip_name, start_date, end_date, trip_type, budget) 
          VALUES (?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "isssss", $user_id, $trip_name, $start_date, $end_date, $trip_type, $budget);
mysqli_stmt_execute($stmt);

$itinerary_id = mysqli_insert_id($db);

// Calculate total days
$total_days = round((strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24)) + 1;

// Add initial attractions
for ($day = 1; $day <= $total_days; $day++) {
    $query = "SELECT id FROM tourist_attractions 
              WHERE category IN (" . implode(',', array_fill(0, count($interests), '?')) . ")
              ORDER BY RAND() LIMIT 4";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, str_repeat('s', count($interests)), ...$interests);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $order = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $attraction_id = $row['id'];
        $insert_query = "INSERT INTO itinerary_attractions (itinerary_id, attraction_id, day, display_order) 
                         VALUES (?, ?, ?, ?)";
        $insert_stmt = mysqli_prepare($db, $insert_query);
        mysqli_stmt_bind_param($insert_stmt, "iiii", $itinerary_id, $attraction_id, $day, $order);
        mysqli_stmt_execute($insert_stmt);
        $order++;
    }
}

// Fetch itinerary details
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
          ta.closing_time
          FROM itineraries i
          LEFT JOIN itinerary_attractions ia ON i.id = ia.itinerary_id
          LEFT JOIN tourist_attractions ta ON ia.attraction_id = ta.id
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
            'budget' => $row['budget']
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

// Generate dates for each day
$day_dates = array();
for ($i = 0; $i < $total_days; $i++) {
    $day_dates[] = date('F j', strtotime($start_date . " + $i days"));
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

        .add-attraction-btn {
            width: 100%;
            padding: 1rem;
            background: none;
            border: 2px dashed #ccc;
            border-radius: 10px;
            color: #666;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .add-attraction-btn:hover {
            background: #f5f5f5;
            border-color: #999;
        }

        .sidebar {
            position: fixed;
            right: -400px;
            top: 0;
            width: 400px;
            height: 100%;
            background: white;
            box-shadow: -2px 0 5px rgba(0,0,0,0.1);
            transition: right 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar.open {
            right: 0;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
        }

        .sidebar-search {
            padding: 15px;
        }

        .sidebar-search input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .sidebar-categories {
            padding: 10px 15px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            border-bottom: 1px solid #eee;
        }

        .category-btn {
            padding: 5px 10px;
            border: 1px solid #ddd;
            border-radius: 15px;
            background: white;
            cursor: pointer;
        }

        .category-btn.active {
            background: black;
            color: white;
            border-color: black;
        }

        .attractions-list {
            padding: 15px;
        }

        .attraction-item {
            display: flex;
            padding: 15px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
        }

        .attraction-item:hover {
            background: #f9f9f9;
        }

        .attraction-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 15px;
        }

        .attraction-info {
            flex: 1;
        }

        .attraction-name {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .attraction-rating {
            color: #666;
            font-size: 14px;
        }

        .attraction-category {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #666;
            font-size: 14px;
            margin-top: 5px;
        }

        .category-icon {
            width: 16px;
            height: 16px;
        }

        .view-saved-trips-btn {
            display: inline-block;
            padding: 8px 16px;
            background-color: #f0f0f0;
            color: #333;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .view-saved-trips-btn:hover {
            background-color: #e0e0e0;
        }

        /* Add your CSS styles here */
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
        .day-content {
            display: none;
            padding: 1rem;
            background-color: white;
        }
        .day-content.open {
            display: block;
        }
    </style>
</head>
<body>
    <pre>


    
    </pre>
    <div class="container">
        <div class="left-content">
            <header class="header">
                <div class="header-content">
                    <h1 class="header-title"><?php echo htmlspecialchars($trip_details['trip_name']); ?></h1>
                    <div class="header-details">
                        <span><?php echo date('M d', strtotime($trip_details['start_date'])) . ' - ' . date('M d, Y', strtotime($trip_details['end_date'])); ?></span>
                        <span>•</span>
                        <span>Yogyakarta</span>
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
                            <button class="add-attraction-btn" onclick="openCategorySelector(<?php echo $day; ?>)">
                                + Add Attraction
                            </button>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
        <div class="map-container">
            <div id="map"></div>
        </div>
    </div>
    
    <div id="attractionSidebar" class="sidebar">
        <!-- Sidebar content will be dynamically populated -->
    </div>

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
        <?php foreach ($daily_attractions as $day => $attractions): ?>
            <?php foreach ($attractions as $attraction): ?>
                L.marker([
                    <?php echo $attraction['latitude']; ?>, 
                    <?php echo $attraction['longitude']; ?>
                ]).addTo(map)
                 .bindPopup("<?php echo htmlspecialchars($attraction['name']); ?>");
            <?php endforeach; ?>
        <?php endforeach; ?>

        // Function to open category selector with proper event listeners
        function openCategorySelector(day) {
            const sidebar = document.getElementById('attractionSidebar');
            sidebar.innerHTML = `
                <div class="sidebar-header">
                    <h2>Add tourist attraction</h2>
                    <button onclick="closeSidebar()" class="close-btn">&times;</button>
                </div>
                <div class="sidebar-search">
                    <input type="text" id="searchAttraction" placeholder="Search attractions..." onkeyup="searchAttractions()">
                </div>
                <div class="sidebar-categories">
                    <button class="category-btn active" data-category="all">All</button>
                    <button class="category-btn" data-category="History">History</button>
                    <button class="category-btn" data-category="Nature">Nature</button>
                    <button class="category-btn" data-category="Culture">Culture</button>
                    <button class="category-btn" data-category="Beach">Beach</button>
                    <button class="category-btn" data-category="Shopping">Shopping</button>
                    <button class="category-btn" data-category="Restaurant">Restaurant</button>
                </div>
                <div id="attractionsList" class="attractions-list"></div>
            `;
            
            // Add event listeners for category buttons
            const categoryButtons = sidebar.querySelectorAll('.category-btn');
            categoryButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    categoryButtons.forEach(btn => btn.classList.remove('active'));
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    // Get search term and selected category
                    const searchTerm = document.getElementById('searchAttraction').value;
                    const selectedCategory = this.dataset.category;
                    
                    // Fetch attractions with updated filters
                    fetchAttractions(day, searchTerm, selectedCategory);
                });
            });
            
            sidebar.classList.add('open');
            // Initial fetch of all attractions
            fetchAttractions(day);
        }

        // Function to close sidebar
        function closeSidebar() {
            document.getElementById('attractionSidebar').classList.remove('open');
        }

        // Function to fetch attractions
        function fetchAttractions(day, search = '', category = 'all') {
            // Store current day globally
            window.currentDay = day;
            
            // Add ajax parameter to indicate this is an AJAX request
            fetch(`get_attractions.php?ajax=true&search=${encodeURIComponent(search)}&category=${encodeURIComponent(category)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(attractions => {
                    if (Array.isArray(attractions)) {
                        displayAttractions(attractions);
                    } else if (attractions.error) {
                        console.error('Server error:', attractions.error);
                        alert('Error loading attractions. Please try again.');
                    } else {
                        throw new Error('Invalid response format');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading attractions. Please try again.');
                });
        }

        // Function to display attractions in sidebar
        function displayAttractions(attractions) {
            const container = document.getElementById('attractionsList');
            if (!container) return;
            
            container.innerHTML = '';
            attractions.forEach(attraction => {
                const item = document.createElement('div');
                item.className = 'attraction-item';
                item.onclick = () => addAttractionToDay(attraction, window.currentDay);
                
                // Ensure all properties exist before using them
                const name = attraction.name || 'Unnamed Attraction';
                const rating = parseFloat(attraction.rating) || 0;
                const category = attraction.category || 'Uncategorized';
                const imageUrl = attraction.image_url || '#';
                
                item.innerHTML = `
                    <img src="${imageUrl}" alt="${name}" class="attraction-image">
                    <div class="attraction-info">
                        <div class="attraction-name">${name}</div>
                        <div class="attraction-rating">
                            ${'★'.repeat(Math.floor(rating))}${'☆'.repeat(5-Math.floor(rating))}
                            ${rating.toFixed(1)}
                        </div>
                        <div class="attraction-category">
                            <img src="${getCategoryIcon(category)}" class="category-icon">
                            ${category}
                        </div>
                    </div>
                `;
                
                container.appendChild(item);
            });
        }

        // Function to add attraction to day
        function addAttractionToDay(attraction, day) {
            if (!attraction || !day) {
                console.error('Missing required parameters');
                return;
            }

            const data = {
                itinerary_id: <?php echo $itinerary_id; ?>,
                attraction_id: attraction.id,
                day: day
            };

            fetch('add_attractions_to_day.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const dayContent = document.getElementById(`day-${day - 1}`);
                    const attractionsContainer = dayContent.querySelector('.attractions-container');
                    
                    const card = document.createElement('div');
                    card.className = 'card';
                    card.setAttribute('data-attraction-id', attraction.id);
                    card.innerHTML = `
                        <img src="${attraction.image_url || ''}" alt="${attraction.name}" class="card-image">
                        <div class="card-content">
                            <h3 class="card-title">${attraction.name}</h3>
                            <div class="card-rating">
                                <div class="rating-stars">
                                    ${'★'.repeat(Math.floor(attraction.rating))}${'☆'.repeat(5-Math.floor(attraction.rating))}
                                </div>
                                <span>${attraction.rating}</span>
                            </div>
                            <div class="card-category">
                                <img src="${getCategoryIcon(attraction.category)}" alt="Icon" class="category-icon">
                                ${attraction.category}
                            </div>
                            <p class="card-description">${attraction.description || ''}</p>
                        </div>
                    `;
                    
                    attractionsContainer.appendChild(card);
                    
                    // Add marker to map if coordinates exist
                    if (attraction.latitude && attraction.longitude) {
                        L.marker([attraction.latitude, attraction.longitude])
                            .addTo(map)
                            .bindPopup(attraction.name);
                    }
                    
                    closeSidebar();
                } else {
                    console.error('Failed to add attraction:', data.message);
                    alert('Failed to add attraction. Please try again.');
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                alert('Error adding attraction. Please try again.');
            });
        }

        // Function to search attractions
        function searchAttractions() {
            const searchTerm = document.getElementById('searchAttraction').value.toLowerCase();
            const activeCategory = document.querySelector('.category-btn.active').dataset.category;
            fetchAttractions(null, searchTerm, activeCategory);
        }

        // Event listeners for category buttons
        document.querySelectorAll('.category-btn').forEach(button => {
            button.addEventListener('click', () => {
                document.querySelectorAll('.category-btn').forEach(btn => 
                    btn.classList.remove('active'));
                button.classList.add('active');
                const category = button.dataset.category;
                const searchTerm = document.getElementById('searchAttraction').value.toLowerCase();
                fetchAttractions(null, searchTerm, category);
            });
        });

        // Helper function to get category icon
        function getCategoryIcon(category) {
            const icons = {
                'nature': '../icons/leaves.svg',
                'culture': '../icons/masks.svg',
                'shopping': '../icons/online-shopping.svg',
                'education': '../icons/graduation-cap.svg',
                'beach': '../icons/vacations.svg',
                'recreation': '../icons/park.svg',
                'history': '../icons/history.svg',
                'restaurant': '../icons/restaurant.svg',
            };
            return icons[category.toLowerCase()] || '../icons/leaves.svg';
        }
    </script>
    <?php include '../navfot/footer.php'; ?>
</body>
</html>