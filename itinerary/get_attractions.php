<?php
session_start();
include "../filter_wisata/db_connect.php";

function getAttractions($trip_type = null, $budget = null, $interests = [], $search = null, $category = null) {
    global $db;
    $attractions = array();

    $query = "SELECT ta.id, ta.name, ta.description, ta.image_url, ta.rating, ta.category, 
              ta.latitude, ta.longitude, ta.address, ta.opening_time, ta.closing_time
              FROM tourist_attractions ta
              LEFT JOIN attraction_categories ac ON ta.id = ac.attraction_id
              LEFT JOIN trip_categories tc ON ac.trip_category_id = tc.id
              WHERE 1=1";

    // Filter berdasarkan pencarian
    if ($search) {
        $search = mysqli_real_escape_string($db, $search);
        $query .= " AND (ta.name LIKE '%$search%' OR ta.description LIKE '%$search%')";
    }

    // Filter berdasarkan kategori
    if ($category && $category !== 'all') {
        $category = mysqli_real_escape_string($db, $category);
        $query .= " AND ta.category = '$category'";
    }

    // Filter existing
    if ($trip_type) {
        $query .= " AND tc.trip_type = '$trip_type'";
    }
    if ($budget) {
        $query .= " AND tc.budget_range = '$budget'";
    }
    if (!empty($interests)) {
        $interests_str = implode("','", array_map('mysqli_real_escape_string', array_fill(0, count($interests), $db), $interests));
        $query .= " AND ta.category IN ('$interests_str')";        
    }

    // Tambahkan GROUP BY untuk menghindari duplikasi
    $query .= " GROUP BY ta.id";

    $result = mysqli_query($db, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $attractions[] = array(
                'id' => $row['id'],
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
        mysqli_free_result($result);
    } else {
        // Log error ke file atau sistem log
        error_log("MySQL Error: " . mysqli_error($db));
        return false;
    }

    return $attractions;
}

// Handle AJAX request untuk sidebar
if(isset($_GET['ajax'])) {
    header('Content-Type: application/json');
    $search = isset($_GET['search']) ? $_GET['search'] : null;
    $category = isset($_GET['category']) ? $_GET['category'] : null;
    $attractions = getAttractions(null, null, [], $search, $category);
    
    if ($attractions === false) {
        // Jika terjadi error, kirim respons error
        echo json_encode(['error' => 'Terjadi kesalahan saat mengambil data.']);
    } else {
        // Jika berhasil, kirim data atraksi
        echo json_encode($attractions);
    }
    exit;
}
?>