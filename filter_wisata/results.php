<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $start_date = $_POST['startDate'];
    $end_date = $_POST['endDate'];
    $trip_type = $_POST['tripType'];
    $budget = $_POST['budget'];
    $interests = isset($_POST['interests']) ? implode(',', $_POST['interests']) : '';

    // Simpan data perjalanan ke tabel `trips`
    $sql_trip = "INSERT INTO trips (start_date, end_date, trip_type, budget, interests) 
                 VALUES ('$start_date', '$end_date', '$trip_type', '$budget', '$interests')";
    mysqli_query($db, $sql_trip);

    // Query untuk mengambil tempat wisata berdasarkan preferensi secara acak
    $interest_query_parts = [];
    foreach (explode(',', $interests) as $interest) {
        $interest_query_parts[] = "FIND_IN_SET('$interest', interests_tags)";
    }
    $interest_query = implode(' OR ', $interest_query_parts);

    $sql_recommendations = "SELECT * FROM tempat_wisata 
                            WHERE FIND_IN_SET('$trip_type', trip_types) 
                            AND budget_range = '$budget' 
                            AND ($interest_query) 
                            AND kategori != 'Restoran'
                            ORDER BY RAND()"; // Mengacak hasil
    $result = mysqli_query($db, $sql_recommendations);

    // Simpan hasil rekomendasi dalam array
    $recommendations = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $recommendations[] = $row;
    }

    // Query untuk mengambil restoran berdasarkan budget
    $sql_restaurants = "SELECT * FROM tempat_wisata 
                        WHERE kategori = 'Restoran' 
                        AND budget_range = '$budget'";
    $result_restaurants = mysqli_query($db, $sql_restaurants);

    // Simpan restoran dalam array
    $restaurants = [];
    while ($row = mysqli_fetch_assoc($result_restaurants)) {
        $restaurants[] = $row;
    }

    // Menghitung durasi perjalanan
    $start_date_obj = new DateTime($start_date);
    $end_date_obj = new DateTime($end_date);
    $interval = $start_date_obj->diff($end_date_obj);
    $trip_duration = $interval->days + 1; // Tambahkan 1 untuk memasukkan hari terakhir

    // Membagi destinasi ke dalam hari-hari itinerary dan tambahkan restoran setiap hari
    $daily_itinerary = array_chunk($recommendations, ceil(count($recommendations) / $trip_duration));

    // Tambahkan satu restoran ke setiap hari
    foreach ($daily_itinerary as $day => &$places) {
        if (!empty($restaurants)) {
            $restaurant = $restaurants[array_rand($restaurants)]; // Pilih restoran acak dari array
            $places[] = $restaurant; // Tambahkan restoran ke itinerary hari tersebut
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trip Recommendations</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../includes/nav.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
</head>
<body>
    <header class="header">
      <nav>
        <div class="nav__bar">
          <div class="logo">
            <a href="#"><img src="../img/logo1.png" alt="logo" /></a>
          </div>
          <div class="nav__menu__btn" id="menu-btn">
            <i class="ri-menu-line"></i>
          </div>
        </div>
        <ul class="nav__links" id="nav-links">
          <li><a href="#home">Home</a></li>
          <li><a href="#about">About</a></li>
          <li><a href="#service">Services</a></li>
          <li><a href="#explore">Explore</a></li>
          <li><a href="#contact">Contact</a></li>
        </ul>
        <button class="btn nav__btn">Login</button>
      </nav>
    </header>
    <div class="container">
        <h1>Your Trip Itinerary</h1>
        
        <div class="left-column">
            <!-- Itinerary per hari -->
            <div id="itinerary-container">
                <?php for ($day = 0; $day < $trip_duration; $day++): ?>
                    <div class="day-section">
                        <button class="day-toggle" onclick="toggleDay(<?= $day ?>)">
                            <h2>Day <?= $day + 1 ?></h2>
                            <svg class="arrow-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="day-content" id="day-<?= $day ?>">
                            <?php if (!empty($daily_itinerary[$day])): ?>
                                <?php foreach ($daily_itinerary[$day] as $place): ?>
                                    <div class="card">
                                        <img src="<?= $place['url_gambar'] ?>" alt="<?= $place['nama'] ?>" class="card-img">
                                        <div class="card-content">
                                            <h3><?= $place['nama'] ?></h3>
                                            <div class="category">
                                                <p><img src="../img/bookmark.png" alt="bookmark"> <?= $place['kategori'] ?></p>
                                            </div>
                                            <p><strong>Rating:</strong>
                                                <span id="rating-<?= $place['id_wisata'] ?>"></span> <!-- Tempat untuk bintang -->
                                                <?= $place['rating'] ?> <!-- Tampilkan nilai rating numerik -->
                                            </p>
                                            <p><?= $place['deskripsi'] ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No destinations available for this day.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>

        <!-- Container untuk peta -->
        <div class="map-wrapper">
            <div id="map"></div>
        </div>
    </div>

    <script>
        // Untuk rating
        function createStarRating(rating) {
            const starContainer = document.createElement('div');
            starContainer.className = 'star-rating';

            const fullStars = Math.floor(rating);
            const halfStar = rating - fullStars >= 0.5;

            for (let i = 0; i < fullStars; i++) {
                const star = document.createElement('span');
                star.className = 'star full-star';
                star.innerHTML = '★';
                starContainer.appendChild(star);
            }

            if (halfStar) {
                const half = document.createElement('span');
                half.className = 'star half-star';
                half.innerHTML = '★';
                starContainer.appendChild(half);
            }

            for (let i = fullStars + (halfStar ? 1 : 0); i < 5; i++) {
                const emptyStar = document.createElement('span');
                emptyStar.className = 'star empty-star';
                emptyStar.innerHTML = '☆';
                starContainer.appendChild(emptyStar);
            }

            return starContainer;
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Loop melalui setiap hari dalam itinerary
            <?= json_encode($daily_itinerary) ?>.forEach((dayPlaces, dayIndex) => {
                dayPlaces.forEach(place => {
                    const ratingContainer = document.getElementById(`rating-${place.id_wisata}`);
                    if (ratingContainer) {
                        const stars = createStarRating(place.rating); // Buat elemen bintang berdasarkan rating
                        ratingContainer.appendChild(stars);
                    }
                });
            });

            toggleDay(0); // Buka hari pertama secara default
        });


        function toggleDay(dayNumber) {
            const content = document.getElementById(`day-${dayNumber}`);
            const button = content.previousElementSibling;
            const isExpanded = content.classList.contains('expanded');
            
            // Close all sections first
            document.querySelectorAll('.day-content').forEach(section => {
                section.classList.remove('expanded');
                section.previousElementSibling.classList.remove('active');
            });
            
            // Toggle the clicked section
            if (!isExpanded) {
                content.classList.add('expanded');
                button.classList.add('active');
            }
            displayMapForDay(dayNumber);
        }

        let map;

        function displayMapForDay(dayIndex) {
            const mapContainer = document.getElementById('map');
            
            // Jika peta sudah ada, hapus dan buat ulang untuk menghindari konflik
            if (map) {
                map.remove();
            }
            
            // Inisialisasi peta
            map = L.map(mapContainer).setView([-7.7956, 110.3695], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Ambil destinasi untuk hari yang dipilih
            const recommendationsForDay = <?= json_encode($daily_itinerary) ?>[dayIndex] || [];

            const markers = [];
            recommendationsForDay.forEach(place => {
                if (place.latitude && place.longitude) {
                    const marker = L.marker([place.latitude, place.longitude]).addTo(map);
                    marker.bindPopup(`<b>${place.nama}</b><br>${place.deskripsi}`);
                    markers.push(marker);
                }
            });

            if (markers.length > 0) {
                const group = L.featureGroup(markers);
                map.fitBounds(group.getBounds());
            } else {
                console.log('No valid markers for this day');
            }
        }

        // Open the first day by default
        document.addEventListener('DOMContentLoaded', () => {
            toggleDay(0);
        });
    </script>
</body>
</html>
