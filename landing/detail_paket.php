<?php
// Koneksi ke database
$host = 'localhost';
$username = 'root'; // Sesuaikan dengan username database Anda
$password = ''; // Sesuaikan dengan password database Anda
$dbname = 'erd_rootify'; // Sesuaikan dengan nama database Anda

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mengambil data tempat wisata berdasarkan ID
$id = 1; // ID tempat wisata yang ingin ditampilkan, bisa diambil dari URL atau parameter lainnya
$sql = "SELECT * FROM tempat_wisata WHERE id_wisata = $id";
$result = $conn->query($sql);
$data = $result->fetch_assoc();

// Jika data tidak ditemukan
if (!$data) {
    die("Data tidak ditemukan");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['nama']; ?> Tour Package</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color:rgb(255, 255, 255);
        }

        .container {
            max-width: 1350px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #1e90ff;
            color: white;
        }

        .title {
            font-size: 24px;
        }

        .gallery {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 10px;
            margin-bottom: 30px;
            height: 500px;
        }

        .main-image {
            height: 100%;
            overflow: hidden;
        }

        .side-images {
            display: grid;
            grid-template-rows: repeat(3, 1fr);
            gap: 10px;
            height: 100%;
        }

        .image-container {
            position: relative;
            overflow: hidden;
        }

        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .rating {
            background: #fff;
            padding: 10px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin: 20px 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .rating-score {
            background: #007bff;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
        }

        .tour-details {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }

        .itinerary {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
        }

        .booking-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            height: fit-content;
        }

        .calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            margin: 20px 0;
        }

        .calendar-day {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
            cursor: pointer;
        }

        .calendar-day:hover {
            background: #f0f0f0;
        }

        .calendar-day.selected {
            background: #007bff;
            color: white;
        }

        .price {
            font-size: 24px;
            color: #ff4d4d;
            margin: 20px 0;
        }

        .book-button {
            background: #ff4d4d;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        .book-button:hover {
            background: #ff3333;
        }

        .section-content {
            padding: 15px 0;
            display: none;
        }

        .section-content.active {
            display: block;
        }

        .rotate {
            transform: rotate(180deg);
            transition: transform 0.3s ease;
        }

        /* Styles for the map-based itinerary */
        #map {
            width: 100%;
            height: 400px;
            margin-top: 20px;
        }

        .timeline {
            position: relative;
            padding-left: 30px;
            margin-top: 20px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background-color: #ccc;
        }

        .location-card {
            background-color: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: relative;
            transition: box-shadow 0.3s ease;
        }

        .location-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .location-card::before {
            content: attr(data-id);
            position: absolute;
            left: -40px;
            top: 50%;
            transform: translateY(-50%);
            width: 30px;
            height: 30px;
            background-color: #007bff;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .roadmap .trip-road {
            display: grid;
            justify-content: flex
        }

        @media (max-width: 768px) {
            .gallery {
                grid-template-columns: 1fr;
                height: auto;
            }
            
            .tour-details {
                grid-template-columns: 1fr;
            }

            .main-image {
                height: 300px;
            }

            .side-images {
                grid-template-columns: repeat(3, 1fr);
                grid-template-rows: 1fr;
            }
        }

    .review-section {
        max-width: 100%;
        margin: 40px 0;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .review-title {
        font-size: 24px;
        color: #333;
    }

    .review-summary {
        display: flex;
        align-items: center;
    }

    .review-average {
        font-size: 48px;
        font-weight: bold;
        color: #1e90ff;
        margin-right: 10px;
    }

    .review-stars {
        color: #ffd700;
        font-size: 24px;
    }

    .review-count {
        color: #666;
        margin-left: 10px;
    }

    .review-list {
        display: grid;
        gap: 20px;
    }

    .review-item {
        background-color: #fff;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
    }

    .review-item:hover {
        transform: translateY(-5px);
    }

    .review-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .reviewer-name {
        font-weight: bold;
        color: #333;
    }

    .review-date {
        color: #999;
        font-size: 0.9em;
    }

    .review-rating {
        color: #ffd700;
    }

    .review-text {
        color: #666;
        line-height: 1.6;
    }

    .review-photos {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }

    .review-photo {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 4px;
    }

    .show-more-btn {
        display: block;
        width: 100%;
        padding: 10px;
        margin-top: 20px;
        background-color: #1e90ff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .show-more-btn:hover {
        background-color: #1a7ae8;
    }

    @media (max-width: 768px) {
        .review-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .review-summary {
            margin-top: 10px;
        }
    }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="title"><?php echo $data['nama']; ?> by Rootify</h1>
        </div>

        <div class="gallery">
            <div class="main-image image-container">
                <img src="../img/<?php echo $data['url_gambar']; ?>" alt="<?php echo $data['nama']; ?>">
            </div>
            <div class="side-images">
                <div class="image-container">
                    <img src="/img/<?php echo $data['url_gambar']; ?>" alt="Image 1">
                </div>
                <div class="image-container">
                    <img src="/img/<?php echo $data['url_gambar']; ?>" alt="Image 2">
                </div>
                <div class="image-container">
                    <img src="/img/<?php echo $data['url_gambar']; ?>" alt="Image 3">
                </div>
            </div>
        </div>

        <div class="rating">
            <span class="rating-score"><?php echo $data['rating']; ?></span>
            <span>Excellent • From reviews</span>
        </div>

        <div class="tour-details">
            <div class="itinerary">
                <h2>Description</h2>
                <p><?php echo $data['deskripsi']; ?></p>
                
                <!-- Tour Information -->
                <div class="tour-info" style="margin-top: 20px;">
                    <div class="info-item" style="display: flex; align-items: center; margin-bottom: 15px;">
                        <span style="margin-right: 10px;">👥</span>
                        <span>Ages 7-65</span>
                    </div>
                    
                    <div class="info-item" style="display: flex; align-items: center; margin-bottom: 15px;">
                        <span style="margin-right: 10px;">⏱</span>
                        <span>Duration: 12h</span>
                    </div>
                    
                    <div class="info-item" style="display: flex; align-items: center; margin-bottom: 15px;">
                        <span style="margin-right: 10px;">🕒</span>
                        <span>Start time: Check availability</span>
                    </div>
                    
                    <div class="info-item" style="display: flex; align-items: center; margin-bottom: 15px;">
                        <span style="margin-right: 10px;">📱</span>
                        <span>Mobile ticket</span>
                    </div>
                    
                    <div class="info-item" style="display: flex; align-items: center; margin-bottom: 15px;">
                        <span style="margin-right: 10px;">🌐</span>
                        <span>Live guide: English</span>
                    </div>
                    
                    <div class="info-item" style="display: flex; align-items: center; margin-bottom: 15px;">
                        <span style="margin-right: 10px; visibility: hidden;">📝</span>
                        <span>Written guide ℹ: German, Italian, French, Spanish</span>
                    </div>
                </div>

                <!-- Expandable Sections -->
                <div class="expandable-sections" style="margin-top: 30px;">
                    <div class="section" style="border-top: 1px solid #e0e0e0; padding: 20px 0;">
                        <div class="section-header" style="display: flex; justify-content: space-between; align-items: center; cursor: pointer;">
                            <h3>What's included</h3>
                            <span class="toggle-icon">▼</span>
                        </div>
                        <div class="section-content">
                            <!-- Add content here -->
                            <p>Content for What's included section</p>
                        </div>
                    </div>

                    <div class="section" style="border-top: 1px solid #e0e0e0; padding: 20px 0;">
                        <div class="section-header" style="display: flex; justify-content: space-between; align-items: center; cursor: pointer;">
                            <h3>What to expect</h3>
                            <span class="toggle-icon">▼</span>
                        </div>
                        <div class="section-content">
                            <!-- Add content here -->
                            <p>Content for What to expect section</p>
                        </div>
                    </div>

                    <div class="section" style="border-top: 1px solid #e0e0e0; padding: 20px 0;">
                        <div class="section-header" style="display: flex; justify-content: space-between; align-items: center; cursor: pointer;">
                            <h3>Departure and return</h3>
                            <span class="toggle-icon">▼</span>
                        </div>
                        <div class="section-content">
                            <!-- Add content here -->
                            <p>Content for Departure and return section</p>
                        </div>
                    </div>

                    <div class="section" style="border-top: 1px solid #e0e0e0; padding: 20px 0;">
                        <div class="section-header" style="display: flex; justify-content: space-between; align-items: center; cursor: pointer;">
                            <h3>Accessibility</h3>
                            <span class="toggle-icon">▼</span>
                        </div>
                        <div class="section-content">
                            <!-- Add content here -->
                            <p>Content for Accessibility section</p>
                        </div>
                    </div>

                    <div class="section" style="border-top: 1px solid #e0e0e0; padding: 20px 0;">
                        <div class="section-header" style="display: flex; justify-content: space-between; align-items: center; cursor: pointer;">
                            <h3>Additional information</h3>
                            <span class="toggle-icon">▼</span>
                        </div>
                        <div class="section-content">
                            <!-- Add content here -->
                            <p>Content for Additional information section</p>
                        </div>
                    </div>

                    <div class="section" style="border-top: 1px solid #e0e0e0; border-bottom: 1px solid #e0e0e0; padding: 20px 0;">
                        <div class="section-header" style="display: flex; justify-content: space-between; align-items: center; cursor: pointer;">
                            <h3>Cancellation policy</h3>
                            <span class="toggle-icon">▼</span>
                        </div>
                        <div class="section-content">
                            <!-- Add content here -->
                            <p>Content for Cancellation policy section</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="booking-section"><div class="booking-section">
                <h3>Booking Information</h3>
                <p>Address: <?php echo $data['alamat']; ?></p>
                <p>Opening Hours: <?php echo $data['jam_buka']; ?> - <?php echo $data['jam_tutup']; ?></p>
                <p>Category: <?php echo $data['kategori']; ?></p>
                <p>Trip Type: <?php echo $data['trip_types']; ?></p>
                <p>Budget Range: <?php echo $data['budget_range']; ?></p>
                <p>Interest Tags: <?php echo $data['interests_tags']; ?></p>

                <div class="price">
                    <span>Starts from</span>
                    <br>
                    <strong>Rp 2.400.000</strong> <span style="text-decoration: line-through; color: #888;">Rp 3.000.000</span> <span style="color: #ff4d4d;">-20%</span>
                </div>
                <button class="book-button">Select Ticket</button>
            </div>
        </div>
    </div>

    <div class="roadmap">
        <!-- Map-based Itinerary -->
        <h2 style="margin-top: 30px;">Tour Itinerary</h2>
        <div class="trip-road">
            <div id="map"></div>
            <div class="timeline" id="timeline">
            <!-- Timeline items will be inserted here by JavaScript -->
            </div>
        </div>
    </div>

    

<!-- Add this section where you want the reviews to appear, for example, after the roadmap section -->
<div class="review-section">
    <div class="review-header">
        <h2 class="review-title">Customer Reviews</h2>
        <div class="review-summary">
            <span class="review-average">4.7</span>
            <div class="review-stars">★★★★★</div>
            <span class="review-count">(1,234 reviews)</span>
        </div>
    </div>
    <div class="review-list" id="reviewList">
        <!-- Review items will be dynamically added here -->
    </div>
    <button class="show-more-btn" id="showMoreBtn">Show More Reviews</button>
</div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        const locations = [
            {
                id: 1,
                name: "Punthuk Setumbu",
                coordinates: [-7.6082, 110.2032],
                duration: "2 hours",
                admission: "Included",
                description: "Witness the breathtaking Borobudur sunrise views. When the air is fresh and as the mist begins to lift the sun will surround the volcanoes and terraced fields.",
                image: "/placeholder.svg?height=200&width=400"
            },
            {
                id: 2,
                name: "Borobudur Temple",
                coordinates: [-7.6079, 110.2038],
                duration: "2 hours",
                admission: "Included",
                description: "Visit the magnificent Borobudur Temple, the world's largest Buddhist temple."
            },
            {
                id: 3,
                name: "Merapi Volcano",
                coordinates: [-7.5407, 110.4457],
                duration: "60 minutes",
                admission: "Included",
                description: "Explore the mighty Merapi Volcano and learn about its geological significance."
            },
            {
                id: 4,
                name: "Prambanan Temples",
                coordinates: [-7.7520, 110.4915],
                duration: "2 hours",
                admission: "Included",
                description: "Discover the ancient Hindu temples of Prambanan, a UNESCO World Heritage site."
            }
        ];

        function createLocationCard(location) {
            const card = document.createElement('div');
            card.className = 'location-card';
            card.setAttribute('data-id', location.id);

            card.innerHTML = `
                <h2>${location.name}</h2>
                <p>Stop: ${location.duration} - Admission ${location.admission}</p>
                ${location.image ? `<img src="${location.image}" alt="${location.name}">` : ''}
                <p>${location.description}</p>
                <button class="details-button">See details & photo</button>
            `;

            return card;
        }

        function initializeTimeline() {
            const timeline = document.getElementById('timeline');
            locations.forEach(location => {
                const card = createLocationCard(location);
                timeline.appendChild(card);
            });
        }

        function initializeMap() {
            const map = L.map('map').setView([-7.6079, 110.2038], 11);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            locations.forEach(location => {
                L.marker(location.coordinates)
                    .addTo(map)
                    .bindPopup(`<b>${location.name}</b><br>${location.duration}`);
            });

            return map;
        }

        document.addEventListener('DOMContentLoaded', () => {
            initializeTimeline();
            const map = initializeMap();

            // Add hover effect to highlight corresponding map marker
            const timeline = document.getElementById('timeline');
            timeline.addEventListener('mouseover', (e) => {
                const card = e.target.closest('.location-card');
                if (card) {
                    const locationId = parseInt(card.getAttribute('data-id'));
                    const location = locations.find(loc => loc.id === locationId);
                    if (location) {
                        const latLng = L.latLng(location.coordinates);
                        const marker = findMarkerByLatLng(map, latLng);
                        if (marker) {
                            marker.openPopup();
                        }
                    }
                }
            });

            timeline.addEventListener('mouseout', () => {
                map.closePopup();
            });
        });

        function findMarkerByLatLng(map, latLng) {
            let foundMarker = null;
            map.eachLayer((layer) => {
                if (layer instanceof L.Marker) {
                    if (layer.getLatLng().equals(latLng)) {
                        foundMarker = layer;
                    }
                }
            });
            return foundMarker;
        }

        // Calendar functionality
        const calendar = document.getElementById('calendar');
        const months = ['Dec'];
        const days = ['Thu', 'Fri', 'Sat', 'Sun', 'Mon', 'Tue', 'Wed'];
        
        if (calendar) {
            days.forEach(day => {
                const dayElement = document.createElement('div');
                dayElement.className = 'calendar-day';
                dayElement.textContent = day;
                calendar.appendChild(dayElement);
            });

            for(let i = 19; i <= 25; i++) {
                const dayElement = document.createElement('div');
                dayElement.className = 'calendar-day';
                dayElement.textContent = i;
                dayElement.addEventListener('click', () => {
                    document.querySelectorAll('.calendar-day').forEach(el => 
                        el.classList.remove('selected'));
                    dayElement.classList.add('selected');
                });
                calendar.appendChild(dayElement);
            }
        }

        // Expandable sections functionality
        document.querySelectorAll('.section-header').forEach(header => {
            header.addEventListener('click', () => {
                const content = header.nextElementSibling;
                const icon = header.querySelector('.toggle-icon');
                
                // Toggle content
                content.classList.toggle('active');
                
                // Toggle icon rotation
                icon.style.transform = content.classList.contains('active') 
                    ? 'rotate(180deg)' 
                    : 'rotate(0deg)';
            });
        });

        // Optional: Automatically close other sections when one is opened
        document.querySelectorAll('.section-header').forEach(header => {
            header.addEventListener('click', (e) => {
                const clickedContent = header.nextElementSibling;
                const allContents = document.querySelectorAll('.section-content');
                const allIcons = document.querySelectorAll('.toggle-icon');
                
                // Close all other sections
                allContents.forEach(content => {
                    if (content !== clickedContent && content.classList.contains('active')) {
                        content.classList.remove('active');
                        const icon = content.previousElementSibling.querySelector('.toggle-icon');
                        icon.style.transform = 'rotate(0deg)';
                    }
                });
            });
        });

        const reviews = [
        {
            name: "John D.",
            date: "August 2023",
            rating: 5,
            text: "Amazing experience! The tour was well-organized and our guide was knowledgeable. Borobudur at sunrise was breathtaking.",
            photos: ["../img/prambanan.jpg", "../img/borobudur.jpg"]
        },
        {
            name: "Sarah M.",
            date: "July 2023",
            rating: 4,
            text: "Great tour overall. Loved the Prambanan temples. Wish we had a bit more time at each stop.",
            photos: ["/placeholder.svg?height=80&width=80"]
        },
        {
            name: "Akira T.",
            date: "June 2023",
            rating: 5,
            text: "Unforgettable journey through Java's cultural heritage. The sunrise at Borobudur was magical!",
            photos: []
        },
        {
            name: "Emma L.",
            date: "May 2023",
            rating: 4,
            text: "Excellent tour with a good mix of history and natural beauty. The Merapi volcano tour was a highlight.",
            photos: ["/placeholder.svg?height=80&width=80", "/placeholder.svg?height=80&width=80", "/placeholder.svg?height=80&width=80"]
        }
    ];

    function createReviewItem(review) {
        const reviewItem = document.createElement('div');
        reviewItem.className = 'review-item';
        reviewItem.innerHTML = `
            <div class="review-item-header">
                <span class="reviewer-name">${review.name}</span>
                <span class="review-date">${review.date}</span>
            </div>
            <div class="review-rating">${'★'.repeat(review.rating)}${'☆'.repeat(5 - review.rating)}</div>
            <p class="review-text">${review.text}</p>
            ${review.photos.length > 0 ? `
                <div class="review-photos">
                    ${review.photos.map(photo => `<img src="${photo}" alt="Review photo" class="review-photo">`).join('')}
                </div>
            ` : ''}
        `;
        return reviewItem;
    }

    function loadReviews(start = 0, count = 2) {
        const reviewList = document.getElementById('reviewList');
        for (let i = start; i < start + count && i < reviews.length; i++) {
            reviewList.appendChild(createReviewItem(reviews[i]));
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        loadReviews();

        const showMoreBtn = document.getElementById('showMoreBtn');
        let currentCount = 2;

        showMoreBtn.addEventListener('click', () => {
            loadReviews(currentCount, 2);
            currentCount += 2;
            if (currentCount >= reviews.length) {
                showMoreBtn.style.display = 'none';
            }
        });
    });
    </script>
</body>
</html>

