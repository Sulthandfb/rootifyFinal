<?php
session_start();
include "../filter_wisata/db_connect.php";

if (!isset($_SESSION['user_id']) || !isset($_GET['trip_id'])) {
    header("Location: saved_trips.php");
    exit();
}

$trip_id = $_GET['trip_id'];

// Verify the trip belongs to the user
$query = "SELECT * FROM itineraries WHERE id = ? AND user_id = ?";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "ii", $trip_id, $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    header("Location: saved_trips.php");
    exit();
}

$trip = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $caption = $_POST['caption'];
    
    $query = "INSERT INTO community_posts (user_id, itinerary_id, caption) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "iis", $_SESSION['user_id'], $trip_id, $caption);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: community.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Share Trip - Rootify</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet"/>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            background-color: #f5f5f5;
        }

        .container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
        }

        .share-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .share-header {
            padding: 1rem;
            border-bottom: 1px solid #eee;
        }

        .share-header h1 {
            font-size: 1.5rem;
            color: #333;
        }

        .trip-preview {
            padding: 1rem;
            background: #f8f8f8;
            margin: 1rem;
            border-radius: 10px;
        }

        .trip-preview h2 {
            color: #333;
            margin-bottom: 0.5rem;
        }

        .trip-details {
            color: #666;
            font-size: 0.9rem;
        }

        .share-form {
            padding: 1rem;
        }

        .caption-input {
            width: 100%;
            min-height: 100px;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 10px;
            margin-bottom: 1rem;
            resize: vertical;
            font-family: inherit;
        }

        .button-group {
            display: flex;
            gap: 1rem;
        }

        .share-btn, .cancel-btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            text-decoration: none;
        }

        .share-btn {
            background-color: #333;
            color: white;
        }

        .share-btn:hover {
            background-color: #555;
        }

        .cancel-btn {
            background-color: #eee;
            color: #333;
        }

        .cancel-btn:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="share-card">
            <div class="share-header">
                <h1>Share Trip to Community</h1>
            </div>
            
            <div class="trip-preview">
                <h2><?php echo htmlspecialchars($trip['trip_name']); ?></h2>
                <div class="trip-details">
                    <p><?php echo date('M d', strtotime($trip['start_date'])) . ' - ' . date('M d, Y', strtotime($trip['end_date'])); ?></p>
                    <p><?php echo ucfirst($trip['trip_type']); ?> Trip • <?php echo ucfirst($trip['budget']); ?> Budget</p>
                </div>
            </div>

            <form class="share-form" method="POST">
                <textarea 
                    name="caption" 
                    class="caption-input" 
                    placeholder="Write something about your trip..."
                    maxlength="500"
                ></textarea>
                
                <div class="button-group">
                    <button type="submit" class="share-btn">Share Trip</button>
                    <a href="saved_trips.php" class="cancel-btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>