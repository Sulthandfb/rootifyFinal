<?php
include '../filter_wisata/db_connect.php'; // Koneksi ke database

// Mengambil data dari form di hotels.php
$startDate = $_POST['startDate'] ?? null;
$endDate = $_POST['endDate'] ?? null;

// Pencarian tambahan
$searchName = $_POST['searchName'] ?? null; // Nama akomodasi
$minPrice = $_POST['minPrice'] ?? null;     // Harga minimum
$maxPrice = $_POST['maxPrice'] ?? null;     // Harga maksimum
$category = $_POST['category'] ?? null;     // Kategori akomodasi

// Query dasar untuk mengambil semua hotel
$sql = "SELECT * FROM hotels WHERE 1=1";

// Tambahkan filter berdasarkan nama akomodasi
if ($searchName) {
    $sql .= " AND name LIKE '%$searchName%'";
}

// Tambahkan filter berdasarkan harga
if ($minPrice) {
    $sql .= " AND price >= $minPrice";
}
if ($maxPrice) {
    $sql .= " AND price <= $maxPrice";
}

// Tambahkan filter kategori
if ($category) {
    $sql .= " AND category = '$category'";
}

$result = $db->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results | Rootify</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="hotels-search.css">
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
</head>
<body>
<?php include '../navfot/navbar.php'; ?>
    <pre>

    
    </pre>
    <div class="main-container">
        <div class="content-section">
            <h2>Available Properties</h2>

            <!-- Menampilkan tanggal Check-In dan Check-Out -->
            <div class="date-selection">
                <p>
                    <strong>Check-In:</strong> 
                    <span><?php echo htmlspecialchars($startDate ?? 'Not specified'); ?></span>
                </p>
                <p>
                    <strong>Check-Out:</strong> 
                    <span><?php echo htmlspecialchars($endDate ?? 'Not specified'); ?></span>
                </p>
            </div>

            <!-- Filter Kategori -->
            <form method="POST" action="hotels-search.php" class="category-form">
                <input type="hidden" name="startDate" value="<?php echo htmlspecialchars($startDate); ?>">
                <input type="hidden" name="endDate" value="<?php echo htmlspecialchars($endDate); ?>">
                <button type="submit" name="category" value="Villa" class="category-btn">Villa</button>
                <button type="submit" name="category" value="Hotel" class="category-btn">Hotel</button>
                <button type="submit" name="category" value="Apartment" class="category-btn">Apartment</button>
            </form>

            <!-- Form Pencarian dan Filter Harga -->
            <form method="POST" action="hotels-search.php" class="search-filter-form">
                <input type="hidden" name="startDate" value="<?php echo htmlspecialchars($startDate); ?>">
                <input type="hidden" name="endDate" value="<?php echo htmlspecialchars($endDate); ?>">
                <div class="search-filter-container">
                    <input type="text" name="searchName" placeholder="Search by name..." class="search-input">
                    <input type="number" name="minPrice" placeholder="Min price (IDR)" class="price-input">
                    <input type="number" name="maxPrice" placeholder="Max price (IDR)" class="price-input">
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
                            <a href="detail_hotel.php?hotel_id='.$row['hotel_id'].'" class="property-card-link">
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
                                            <span>3 guests</span>
                                            <span>•</span>
                                            <span>1 rooms</span>
                                            <span>•</span>
                                            <span>1 bath</span>
                                        </div>
                                        <div class="property-price">
                                            IDR '.number_format($row['price']).' <span class="price-period">/ night</span>
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
        const map = L.map('map').setView([-6.2088, 106.8456], 12); // Default location
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
                .bindPopup('<strong>".htmlspecialchars($row['name'])."</strong><br> IDR ".number_format($row['price'])." per night');
            ";
        }
        ?>
    </script>
    <pre>

    </pre>
    <?php include '../navfot/footer.php';?>
</body>
</html>

<?php
$db->close(); // Menutup koneksi database
?>
