<?php
session_start();
include "../filter_wisata/db_connect.php";

header('Content-Type: application/json');

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON input']);
    exit;
}

$itinerary_id = isset($data['itinerary_id']) ? $data['itinerary_id'] : null;
$attraction_id = isset($data['attraction_id']) ? $data['attraction_id'] : null;
$day = isset($data['day']) ? $data['day'] : null;

if (!$itinerary_id || !$attraction_id || !$day) {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit;
}

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Get the current maximum display_order for the day
$query = "SELECT MAX(display_order) as max_order FROM itinerary_attractions 
          WHERE itinerary_id = ? AND day = ?";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "ii", $itinerary_id, $day);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
$new_order = ($row['max_order'] !== null) ? $row['max_order'] + 1 : 0;

// Insert the new attraction into the database
$insert_query = "INSERT INTO itinerary_attractions (itinerary_id, attraction_id, day, display_order) 
                 VALUES (?, ?, ?, ?)";
$insert_stmt = mysqli_prepare($db, $insert_query);
mysqli_stmt_bind_param($insert_stmt, "iiii", $itinerary_id, $attraction_id, $day, $new_order);

if (mysqli_stmt_execute($insert_stmt)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to add attraction to database: ' . mysqli_error($db)]);
}

mysqli_close($db);