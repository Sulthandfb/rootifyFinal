<?php
session_start();
include "../filter_wisata/db_connect.php";

// Get trip ID from URL
$trip_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch trip details with ordered attractions
$query = "SELECT i.*, 
          ia.day,
          ia.display_order,
          ta.id as attraction_id,
          ta.name,
          ta.description,
          ta.image_url,
          ta.rating,
          ta.category,
          ta.latitude,
          ta.longitude,
          ta.address,
          ta.opening_time,
          ta.closing_time
          FROM itineraries i
          LEFT JOIN itinerary_attractions ia ON i.id = ia.itinerary_id
          LEFT JOIN tourist_attractions ta ON ia.attraction_id = ta.id
          WHERE i.id = ? AND i.user_id = ?
          ORDER BY ia.day, ia.display_order";

$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "ii", $trip_id, $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result) {
    $trip_details = null;
    $daily_attractions = array();

    while ($row = mysqli_fetch_assoc($result)) {
        if (!$trip_details) {
            $trip_details = array(
                'trip_name' => $row['trip_name'],
                'start_date' => $row['start_date'],
                'end_date' => $row['end_date'],
                'trip_type' => $row['trip_type'],
                'budget' => $row['budget']
            );
        }

        if ($row['attraction_id']) {
            $daily_attractions[$row['day']][] = array(
                'id' => $row['attraction_id'],
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
    }
    mysqli_free_result($result);

    // Calculate total days
    $total_days = round((strtotime($trip_details['end_date']) - strtotime($trip_details['start_date'])) / (60 * 60 * 24)) + 1;

    // Generate dates for each day
    $day_dates = array();
    for ($i = 0; $i < $total_days; $i++) {
        $day_dates[] = date('F j', strtotime($trip_details['start_date'] . " + $i days"));
    }

    include 'itinerary-results.php';
} else {
    echo "Trip not found or you don't have permission to view this trip.";
}
?>

