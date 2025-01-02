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

<?php include '../navfot/navbar.php'; ?>

<pre>




</pre>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results | Rootify</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        :root {
            --primary: #f85616;
            --background: #ffffff;
            --foreground: #000000;
            --muted: #f1f5f9;
            --muted-foreground: #64748b;
            --border: #e2e8f0;
        }

        /* Reset and base styles */
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

        /* Content Section */
        .content-section {
            flex: 1;
            max-width: 100%;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        /* Category Buttons */
        .category-form {
            display: flex;
            gap: 10px;
            margin: 20px 0;
            flex-wrap: wrap;
        }

        .category-btn {
            padding: 8px 15px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .category-btn:hover {
            background-color: #e44c0d;
            transform: translateY(-2px);
        }

        .category-btn.active {
            background-color: #d1440c;
        }

        /* Search Form */
        .search-filter-container {
            display: flex;
            gap: 10px;
            width: 100%;
            margin-bottom: 20px;
        }

        .search-input {
            flex: 2;
            padding: 8px 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            outline: none;
        }

        .search-btn {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            min-width: 100px;
            white-space: nowrap;
        }

        .search-btn:hover {
            background-color: #e44c0d;
        }

        /* Properties Grid */
        .properties-container {
            flex: 1;
            overflow-y: auto;
            max-height: calc(100vh - 40px);
            padding-right: 20px;
        }

        .properties-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 24px;
            padding: 0;
            margin-top: 24px;
        }

        /* Property Cards */
        .property-card {
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            background: white;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .property-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

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
            min-height: 300px;
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
                order: -1;
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

            .search-input {
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

            .category-btn {
                flex: 1 1 100%;
                margin: 5px 0;
            }

            .search-btn {
                width: 100%;
            }
        }
    </style>
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

<?php include '../navfot/footer.php'; ?>

<?php
$db->close(); // Menutup koneksi database
?>