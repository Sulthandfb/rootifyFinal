<?php
session_start();
// Koneksi database
include '../filter_wisata/db_connect.php';

// Fungsi untuk membersihkan input
function clean_input($data) {
    global $db;
    return mysqli_real_escape_string($db, trim($data));
}

// Inisialisasi variabel pencarian
$search = isset($_REQUEST['search']) ? clean_input($_REQUEST['search']) : '';
$category = isset($_REQUEST['category']) ? clean_input($_REQUEST['category']) : '';

// Query pencarian
$sql = "SELECT * FROM tourist_attractions WHERE 1=1";

if (!empty($search)) {
    $sql .= " AND (name LIKE '%$search%')";
}

if (!empty($category)) {
    $sql .= " AND category = '$category'";
}

$result = mysqli_query($db, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results | Rootify</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="attractions-search.css">
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
</head>
<body>
    <div class="main-container">
        <div class="content-section">
            <h2>Yogyakarta Attractions</h2>

            <!-- Filter Kategori -->
            <form method="GET" action="attractions-search.php" class="category-form">
                <button type="submit" name="category" value="History" class="category-btn <?php echo $category == 'History' ? 'active' : ''; ?>">History</button>
                <button type="submit" name="category" value="Nature" class="category-btn <?php echo $category == 'Nature' ? 'active' : ''; ?>">Nature</button>
                <button type="submit" name="category" value="Culture" class="category-btn <?php echo $category == 'Culture' ? 'active' : ''; ?>">Culture</button>
                <button type="submit" name="category" value="Beach" class="category-btn <?php echo $category == 'Beach' ? 'active' : ''; ?>">Beach</button>
                <button type="submit" name="category" value="Shopping" class="category-btn <?php echo $category == 'Shopping' ? 'active' : ''; ?>">Shopping</button>
                <button type="submit" name="category" value="Recreation" class="category-btn <?php echo $category == 'Recreation' ? 'active' : ''; ?>">Recreation</button>
                <button type="submit" name="category" value="Education" class="category-btn <?php echo $category == 'Education' ? 'active' : ''; ?>">Education</button>
                <button type="submit" name="category" value="Restaurant" class="category-btn <?php echo $category == 'Restaurant' ? 'active' : ''; ?>">Restaurant</button>
            </form>

            <!-- Form Pencarian dan Filter Harga -->
            <form method="GET" action="attractions-search.php" class="search-filter-form">
                <div class="search-filter-container">
                    <input type="text" name="search" placeholder="Search by name..." class="search-input" value="<?php echo htmlspecialchars($search); ?>">
                    <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
                    <button type="submit" class="btn search-btn">Search</button>
                </div>
            </form>

            <!-- Properti hasil pencarian -->
            <div class="properties-container">
                <div class="properties-grid">
                    <?php
                    // Mengecek apakah ada hasil pencarian
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '
                            <a href="attractions-details.php?id='.$row['id'].'" class="property-card-link">
                                <div class="property-card">
                                    <div class="property-image">
                                        <img src="'.htmlspecialchars($row['image_url']).'" alt="'.htmlspecialchars($row['name']).'">
                                        <span class="new-badge">New</span>
                                    </div>
                                    <div class="property-info">
                                        <h3 class="property-title">'.htmlspecialchars($row['name']).'</h3>
                                        <div class="property-rating">
                                            ★ '.htmlspecialchars($row['rating'] ?? '0').' (0 reviews)
                                        </div>
                                        <div class="property-details">
                                            <span>Category: '.htmlspecialchars($row['category']).'</span>
                                        </div>
                                    </div>
                                </div>
                            </a>';
                        }
                    } else {
                        echo '<p>No properties found.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- Map Section on the right -->
        <div class="map-section">
            <div id="map"></div>
        </div>
    </div>

    <script>
        // Initialize map
        const map = L.map('map').setView([-7.7956, 110.3695], 12); // Default location (Yogyakarta)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        <?php
        // Tambahkan marker hotel ke peta
        $result = $db->query($sql); // Ambil ulang hasil query untuk marker
        while ($row = $result->fetch_assoc()) {
            echo "
            L.marker([{$row['latitude']}, {$row['longitude']}])
                .addTo(map)
                .bindPopup('<strong>".htmlspecialchars($row['name'])."</strong><br>Category: ".htmlspecialchars($row['category'])."');
            ";
        }
        ?>
    </script>
</body>
</html>

<?php
$db->close(); // Menutup koneksi database
?>