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

    </style>
</head>
<body>
    <header class="header">
        <h1>Trip Planner</h1>
        <div class="header-actions">
            <a href="saved_trips.php" class="view-saved-trips-btn">View Saved Trips</a>
        </div>
    </header>
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

                <!-- Day Sections -->
                <?php for ($day_index = 0; $day_index < $total_days; $day_index++): ?>
                    <div class="day-section">
                        <div class="day-header" onclick="toggleDay(<?php echo $day_index; ?>)">
                            <h2>Day <?php echo $day_index + 1; ?>, <?php echo $day_dates[$day_index]; ?></h2>
                            <span class="dropdown-arrow" id="arrow-<?php echo $day_index; ?>">▼</span>
                        </div>
                        <div class="day-content" id="day-<?php echo $day_index; ?>">
                            <div class="attractions-container">
                                <?php 
                                if (isset($daily_itinerary[$day_index])) {
                                    foreach ($daily_itinerary[$day_index] as $attraction): 
                                ?>
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
                                <?php 
                                    endforeach;
                                }
                                ?>
                            </div>
                            
                            <button class="add-attraction-btn" onclick="openCategorySelector(<?php echo $day_index + 1; ?>)">
                                + Add Attraction
                            </button>
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
    <!-- Attraction Sidebar -->
    <div id="attractionSidebar" class="sidebar">
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
        
        <div id="attractionsList" class="attractions-list">
            <!-- Attractions will be populated here -->
        </div>
    </div>
    <script>
        // Add this JavaScript code in your itinerary-results.php file
        document.getElementById('saveTripBtn').addEventListener('click', function() {
            document.getElementById('saveTripModal').style.display = 'flex';
        });

        document.querySelector('#saveTripModal form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Collect all attractions data with proper ordering
            let attractionsData = {};
            document.querySelectorAll('.day-content').forEach((dayContent, dayIndex) => {
                const dayAttractions = [];
                dayContent.querySelectorAll('.card').forEach((card, orderIndex) => {
                    const attractionId = card.getAttribute('data-attraction-id');
                    if (attractionId) {
                        dayAttractions.push({
                            id: attractionId,
                            order: orderIndex
                        });
                    }
                });
                if (dayAttractions.length > 0) {
                    attractionsData[dayIndex + 1] = dayAttractions.map(a => a.id);
                }
            });

            const formData = new FormData(this);
            formData.append('attractions', JSON.stringify(attractionsData));

            fetch('save_trip.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    document.getElementById('saveTripModal').style.display = 'none';
                    window.location.href = 'saved_trips.php';
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while saving the trip');
            });
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

    <script>
        let currentDayIndex = 0;
        let attractions = [];

        function fetchAttractions(search = '', category = 'all') {
            const url = new URL('../itinerary/get_attractions.php', window.location.href);
            url.searchParams.append('ajax', '1');
            if(search) url.searchParams.append('search', search);
            if(category !== 'all') url.searchParams.append('category', category);

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        throw new Error(data.error);
                    }
                    attractions = data;
                    displayAttractions(attractions);
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Tampilkan pesan error ke pengguna
                    const container = document.getElementById('attractionsList');
                    container.innerHTML = `<p>Error: ${error.message}</p>`;
                });
        }

        // Display attractions in the sidebar
        function displayAttractions(attractionsToShow) {
            const container = document.getElementById('attractionsList');
            container.innerHTML = '';
            
            attractionsToShow.forEach(attraction => {
                const item = document.createElement('div');
                item.className = 'attraction-item';
                item.onclick = () => addAttractionToDay(attraction);
                
                item.innerHTML = `
                    <img src="${attraction.image_url}" alt="${attraction.name}" class="attraction-image">
                    <div class="attraction-info">
                        <div class="attraction-name">${attraction.name}</div>
                        <div class="attraction-rating">
                            ${'★'.repeat(Math.floor(attraction.rating))}${'☆'.repeat(5-Math.floor(attraction.rating))}
                            ${attraction.rating}
                        </div>
                        <div class="attraction-category">
                            <img src="../icons/${attraction.category.toLowerCase()}.svg" class="category-icon">
                            ${attraction.category}
                        </div>
                    </div>
                `;
                
                container.appendChild(item);
            });
        }

        // Filter attractions by search term
        function searchAttractions() {
            const searchTerm = document.getElementById('searchAttraction').value.toLowerCase();
            const filteredAttractions = attractions.filter(attraction => 
                attraction.name.toLowerCase().includes(searchTerm) ||
                attraction.description.toLowerCase().includes(searchTerm)
            );
            displayAttractions(filteredAttractions);
        }

        // Filter attractions by category
        document.querySelectorAll('.category-btn').forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons
                document.querySelectorAll('.category-btn').forEach(btn => 
                    btn.classList.remove('active'));
                
                // Add active class to clicked button
                button.classList.add('active');
                
                const category = button.dataset.category;
                if (category === 'all') {
                    displayAttractions(attractions);
                } else {
                    const filteredAttractions = attractions.filter(attraction => 
                        attraction.category === category);
                    displayAttractions(filteredAttractions);
                }
            });
        });

        // Open sidebar for specific day
        function openCategorySelector(dayIndex) {
            currentDayIndex = dayIndex - 1;
            document.getElementById('attractionSidebar').classList.add('open');
            fetchAttractions();
        }

        // Close sidebar
        function closeSidebar() {
            document.getElementById('attractionSidebar').classList.remove('open');
        }

        // Add attraction to selected day
        function addAttractionToDay(attraction) {
            const dayContent = document.getElementById(`day-${currentDayIndex}`);
            const attractionsContainer = dayContent.querySelector('.attractions-container');
            
            const card = document.createElement('div');
            card.className = 'card';
            card.innerHTML = `
                <img src="${attraction.image_url}" alt="${attraction.name}" class="card-image">
                <div class="card-content">
                    <h3 class="card-title">${attraction.name}</h3>
                    <div class="card-rating">
                        <div class="rating-stars">
                            ${'★'.repeat(Math.floor(attraction.rating))}${'☆'.repeat(5-Math.floor(attraction.rating))}
                        </div>
                        <span>${attraction.rating}</span>
                    </div>
                    <div class="card-category">
                        <img src="../icons/${attraction.category.toLowerCase()}.svg" alt="Icon" class="category-icon">
                        ${attraction.category}
                    </div>
                    <p class="card-description">${attraction.description}</p>
                </div>
            `;
            
            attractionsContainer.appendChild(card);
            
            // Add marker to map
            L.marker([attraction.latitude, attraction.longitude])
                .addTo(map)
                .bindPopup(attraction.name);
            
            closeSidebar();
        }

        // Close sidebar when clicking outside
        window.addEventListener('click', (e) => {
            const sidebar = document.getElementById('attractionSidebar');
            if (e.target === sidebar) {
                closeSidebar();
            }
        });
    </script>
</body>
</html>