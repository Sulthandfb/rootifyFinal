<?php
session_start();
include "../filter_wisata/db_connect.php";

function getAttractions($trip_type = null, $budget = null, $interests = [], $search = null, $category = null) {
    global $db;
    $attractions = array();

    $query = "SELECT DISTINCT ta.id, ta.name, ta.description, ta.image_url, ta.rating, ta.category, 
              ta.latitude, ta.longitude, ta.address, ta.opening_time, ta.closing_time
              FROM tourist_attractions ta
              LEFT JOIN attraction_categories ac ON ta.id = ac.attraction_id
              LEFT JOIN trip_categories tc ON ac.trip_category_id = tc.id
              WHERE 1=1";

    $params = array();
    $types = "";

    // Filter based on search
    if ($search) {
        $query .= " AND (ta.name LIKE ? OR ta.description LIKE ?)";
        $searchParam = "%$search%";
        $params[] = $searchParam;
        $params[] = $searchParam;
        $types .= "ss";
    }

    // Filter based on category
    if ($category && $category !== 'all') {
        $query .= " AND ta.category = ?";
        $params[] = $category;
        $types .= "s";
    }

    // Filter existing
    if ($trip_type) {
        $query .= " AND tc.trip_type = ?";
        $params[] = $trip_type;
        $types .= "s";
    }
    if ($budget) {
        $query .= " AND tc.budget_range = ?";
        $params[] = $budget;
        $types .= "s";
    }
    if (!empty($interests)) {
        $placeholders = implode(',', array_fill(0, count($interests), '?'));
        $query .= " AND ta.category IN ($placeholders)";
        $params = array_merge($params, $interests);
        $types .= str_repeat("s", count($interests));
    }

    $stmt = mysqli_prepare($db, $query);
    if ($stmt === false) {
        error_log("Prepare failed: " . mysqli_error($db));
        return false;
    }

    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    if (!mysqli_stmt_execute($stmt)) {
        error_log("Execute failed: " . mysqli_stmt_error($stmt));
        mysqli_stmt_close($stmt);
        return false;
    }

    $result = mysqli_stmt_get_result($stmt);

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
        error_log("Result error: " . mysqli_stmt_error($stmt));
        mysqli_stmt_close($stmt);
        return false;
    }

    mysqli_stmt_close($stmt);
    return $attractions;
}

// Handle AJAX request for sidebar
if (isset($_GET['ajax'])) {
    header('Content-Type: application/json');
    $search = isset($_GET['search']) ? $_GET['search'] : null;
    $category = isset($_GET['category']) ? $_GET['category'] : null;
    $attractions = getAttractions(null, null, [], $search, $category);
    
    if ($attractions === false) {
        http_response_code(500);
        echo json_encode(['error' => 'An error occurred while fetching data.']);
    } else {
        echo json_encode($attractions);
    }
    exit;
}
?>