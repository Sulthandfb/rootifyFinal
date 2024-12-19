<?php
session_start();
include "../filter_wisata/db_connect.php";

function getAttractions($trip_type = null, $budget = null, $interests = []) {
    global $db;
    $attractions = array();

    $query = "SELECT ta.id, ta.name, ta.description, ta.image_url, ta.rating, ta.category, ta.latitude, ta.longitude,
                     GROUP_CONCAT(DISTINCT tc.trip_type) as trip_types,
                     GROUP_CONCAT(DISTINCT tc.budget_range) as budget_ranges
              FROM tourist_attractions ta
              LEFT JOIN attraction_categories ac ON ta.id = ac.attraction_id
              LEFT JOIN trip_categories tc ON ac.trip_category_id = tc.id
              WHERE 1=1";

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

    $query .= " GROUP BY ta.id ORDER BY ta.rating DESC";

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
                'trip_types' => explode(',', $row['trip_types']),
                'budget_ranges' => explode(',', $row['budget_ranges'])
            );
        }
        mysqli_free_result($result);
    } else {
        echo "Error: " . mysqli_error($db);
    }

    // Acak destinasi wisata
    shuffle($attractions);

    return $attractions;
}

function getRestaurants() {
    global $db;
    $restaurants = [];

    $query = "SELECT * FROM tourist_attractions WHERE category = 'Restaurant'";
    $result = mysqli_query($db, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $restaurants[] = $row;
    }

    shuffle($restaurants);
    return $restaurants;
}