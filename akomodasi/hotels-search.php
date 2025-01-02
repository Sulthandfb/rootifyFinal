<?php
session_start();
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
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <style>
        :root {
            --primary: #f85616;
            --background: #ffffff;
            --foreground: #000000;
            --muted: #f1f5f9;
            --muted-foreground: #64748b;
            --border: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            padding-top: 50px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--background);
            color: var(--foreground);
            min-height: 100vh;
        }

        .main-container {
            display: flex;
            max-width: 1800px;
            margin: 0 300px;
            padding: 20px;
            gap: 24px;
        }

        /* Left Section */
        .content-section {
            flex: 1;
            max-width: 100%;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        /* Search and Filters */
        .search-section {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .search-bar {
            display: flex;
            width: 100%;
        }

        .search-input {
            flex: 1;
            padding: 12px 16px;
            border: 1px solid var(--border);
            border-radius: 8px 0 0 8px;
            font-size: 16px;
        }

        .search-button {
            padding: 12px 24px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 0 8px 8px 0;
            cursor: pointer;
        }

        .price-range {
            margin-top: 16px;
        }

        .price-range h3 {
            margin-bottom: 12px;
            font-size: 16px;
            font-weight: 500;
        }

        .price-inputs {
            display: flex;
            gap: 16px;
            margin-bottom: 12px;
        }

        .price-input {
            flex: 1;
            position: relative;
        }

        .price-input span {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted-foreground);
        }

        .price-input input {
            width: 100%;
            padding: 12px 12px 12px 24px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
        }

        .amenities {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin: 16px 0;
        }

        .amenity-btn {
            padding: 8px 16px;
            border: 1px solid var(--border);
            border-radius: 20px;
            background: white;
            cursor: pointer;
            font-size: 14px;
        }

        .filter-actions {
            display: flex;
            gap: 12px;
        }

        .filter-btn {
            padding: 8px 16px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: white;
            cursor: pointer;
            font-size: 14px;
        }

        /* Properties Grid Container */
        .properties-container {
            flex: 1;
            overflow-y: auto; /* Menambahkan scroll hanya untuk properties grid */
            max-height: calc(100vh - 40px); /* Menjaga tinggi container properties grid sesuai dengan map */
            padding-right: 20px; /* Menambahkan space untuk scrollbar */
        }

        .properties-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 24px;
            padding: 0;
            margin-top: 24px;
        }

        .property-card {
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            background: white;
            transition: transform 0.2s;
        }

        .property-card:hover {
            transform: translateY(-4px);
        }

        .property-image {
            position: relative;
            width: 100%;
            padding-top: 66.67%; /* 3:2 aspect ratio */
        }

        .property-image img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .new-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: rgba(0, 0, 0, 0.75);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
        }

        .favorite-btn {
            position: absolute;
            top: 12px;
            right: 12px;
            background: white;
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .property-info {
            padding: 16px;
        }

        .property-title {
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .property-rating {
            display: flex;
            align-items: center;
            gap: 4px;
            color: #333;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .property-details {
            display: flex;
            gap: 8px;
            color: var(--muted-foreground);
            font-size: 14px;
            margin-bottom: 12px;
        }

        .property-price {
            font-size: 16px;
            font-weight: 600;
        }

        .price-period {
            color: var(--muted-foreground);
            font-size: 14px;
            font-weight: normal;
        }

        /* Map Section */
        .map-section {
            flex: 0 0 45%;
            height: calc(100vh - 40px);
            position: sticky;
            top: 20px;
        }

        #map {
            width: 100%;
            height: 100%;
            border-radius: 12px;
        }

        /* Styling untuk Check-In dan Check-Out */
        .date-selection {
            display: flex; /* Menjadikan tata letak horizontal */
            justify-content: flex-start; /* Rata kiri */
            gap: 20px; /* Jarak antar elemen */
            margin: 20px 0; /* Jarak atas dan bawah */
            padding: 10px 20px;
            background: #f9f9f9; /* Background warna terang */
            border-radius: 8px; /* Sudut melengkung */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Bayangan halus */
        }

        .date-selection p {
            margin: 0; /* Menghilangkan margin default */
            font-size: 16px;
            color: #333; /* Warna teks */
            font-weight: 500;
        }

        .date-selection strong {
            color: #555; /* Warna teks untuk label */
            font-weight: 600;
            margin-right: 5px;
        }

        .date-selection span {
            font-size: 14px;
            color: #888;
        }

        /* Styling untuk kategori button */
        .category-form {
            display: flex;
            gap: 10px;
            margin: 20px 0;
        }

        .category-btn {
            padding: 8px 15px;
            background-color: #f85616;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .category-btn:hover {
            background-color: #c04c1e;
        }

        /* Styling untuk form pencarian */
        .search-filter-container {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .search-input, .price-input {
            padding: 8px 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            outline: none;
        }

        .search-btn {
            background-color: #2789da;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-btn:hover {
            background-color: #008cff;
        }



        @media (max-width: 1200px) {
            .main-container {
                flex-direction: column;
            }

            .content-section {
                max-width: 100%;
            }

            .map-section {
                height: 400px;
                position: static;
            }
        }

        @media (max-width: 768px) {
            .properties-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Responsive Layout Adjustments */
        @media (max-width: 1600px) {
            .main-container {
                margin: 0 40px;
            }
        }

        @media (max-width: 1200px) {
            .main-container {
                flex-direction: column;
                margin: 0 20px;
            }

            .map-section {
                height: 400px;
                position: static;
                order: -1; /* Moves map to top on smaller screens */
            }

            .content-section {
                max-width: 100%;
            }

            .properties-container {
                max-height: none;
            }

            .properties-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .main-container {
                margin: 0 10px;
                padding: 10px;
            }

            .search-filter-container {
                flex-direction: column;
                gap: 15px;
            }

            .search-input, 
            .price-input {
                width: 100%;
            }

            .category-form {
                flex-wrap: wrap;
                justify-content: center;
            }

            .category-btn {
                flex: 1 1 calc(33.333% - 10px);
                min-width: 100px;
            }

            .date-selection {
                flex-direction: column;
                gap: 10px;
                padding: 15px;
            }

            .properties-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .property-card {
                max-width: 100%;
            }
        }

        @media (max-width: 480px) {
            .property-details {
                flex-wrap: wrap;
            }

            .property-info {
                padding: 12px;
            }

            .property-title {
                font-size: 14px;
            }

            .property-price {
                font-size: 14px;
            }

            .price-period {
                font-size: 12px;
            }

            .category-btn {
                flex: 1 1 100%;
                margin: 5px 0;
            }
        }

        /* Improved Search and Filter Responsiveness */
        .search-section {
            width: 100%;
        }

        .search-filter-container {
            display: flex;
            gap: 10px;
            width: 100%;
        }

        .search-input {
            flex: 2;
        }

        .price-input {
            flex: 1;
        }

        .search-btn {
            min-width: 100px;
            white-space: nowrap;
        }

        /* Map Responsiveness */
        #map {
            width: 100%;
            height: 100%;
            border-radius: 12px;
            min-height: 300px;
        }

        /* Card Hover Effects */
        .property-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .property-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Image Aspect Ratio Maintenance */
        .property-image {
            position: relative;
            width: 100%;
            padding-top: 66.67%;
            overflow: hidden;
        }

        .property-image img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .property-card:hover .property-image img {
            transform: scale(1.05);
        }
    </style>
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
