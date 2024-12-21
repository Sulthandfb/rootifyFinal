<?php
session_start();
include "../filter_wisata/db_connect.php";

function getSavedTrips() {
    global $db;
    $trips = array();

    // Pastikan pengguna sudah login
    if (!isset($_SESSION['user_id'])) {
        return $trips;
    }

    $user_id = $_SESSION['user_id'];

    $query = "SELECT i.*, 
              GROUP_CONCAT(DISTINCT ta.name) as attraction_names,
              GROUP_CONCAT(DISTINCT ta.image_url) as attraction_images
              FROM itineraries i
              LEFT JOIN itinerary_attractions ia ON i.id = ia.itinerary_id
              LEFT JOIN tourist_attractions ta ON ia.attraction_id = ta.id
              WHERE i.user_id = $user_id
              GROUP BY i.id
              ORDER BY i.start_date DESC";

    $result = mysqli_query($db, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $trips[] = array(
                'id' => $row['id'],
                'trip_name' => $row['trip_name'],
                'start_date' => $row['start_date'],
                'end_date' => $row['end_date'],
                'trip_type' => $row['trip_type'],
                'budget' => $row['budget'],
                'attraction_names' => explode(',', $row['attraction_names']),
                'attraction_images' => explode(',', $row['attraction_images'])
            );
        }
        mysqli_free_result($result);
    }

    return $trips;
}
?>

