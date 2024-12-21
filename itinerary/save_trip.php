<?php
session_start();
include "../filter_wisata/db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate user session
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(["success" => false, "message" => "User not logged in"]);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $trip_name = mysqli_real_escape_string($db, $_POST['trip_name']);
    $start_date = mysqli_real_escape_string($db, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($db, $_POST['end_date']);
    $trip_type = mysqli_real_escape_string($db, $_POST['trip_type']);
    $budget = mysqli_real_escape_string($db, $_POST['budget']);
    
    // Start transaction
    mysqli_begin_transaction($db);
    
    try {
        // Insert into itineraries table
        $query = "INSERT INTO itineraries (user_id, trip_name, start_date, end_date, trip_type, budget) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "isssss", $user_id, $trip_name, $start_date, $end_date, $trip_type, $budget);
        mysqli_stmt_execute($stmt);
        
        $itinerary_id = mysqli_insert_id($db);
        
        // Get attractions data
        $attractions = json_decode($_POST['attractions'], true);
        
        if (is_array($attractions)) {
            // Prepare statement for inserting attractions
            $query = "INSERT INTO itinerary_attractions (itinerary_id, attraction_id, day, display_order) 
                      VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($db, $query);
            
            foreach ($attractions as $day => $day_attractions) {
                if (is_array($day_attractions)) {
                    foreach ($day_attractions as $order => $attraction_id) {
                        mysqli_stmt_bind_param($stmt, "iiii", 
                            $itinerary_id, 
                            $attraction_id, 
                            $day,
                            $order
                        );
                        mysqli_stmt_execute($stmt);
                    }
                }
            }
        }
        
        // Commit transaction
        mysqli_commit($db);
        echo json_encode(["success" => true, "message" => "Trip saved successfully!"]);
        
    } catch (Exception $e) {
        mysqli_rollback($db);
        echo json_encode(["success" => false, "message" => "Error saving trip: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}
?>

