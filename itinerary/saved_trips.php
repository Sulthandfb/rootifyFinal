<?php
session_start();
include "../filter_wisata/db_connect.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

// Handle trip name update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_trip_name'])) {
    $trip_id = $_POST['trip_id'];
    $new_name = $_POST['new_trip_name'];
    
    $update_query = "UPDATE itineraries SET trip_name = ? WHERE id = ? AND user_id = ?";
    $update_stmt = mysqli_prepare($db, $update_query);
    mysqli_stmt_bind_param($update_stmt, "sii", $new_name, $trip_id, $_SESSION['user_id']);
    
    if (mysqli_stmt_execute($update_stmt)) {
        header("Location: saved_trips.php");
        exit();
    }
}

// Handle trip deletion
if (isset($_GET['delete_trip'])) {
    $trip_id = $_GET['delete_trip'];
    
    // Begin transaction
    mysqli_begin_transaction($db);
    
    try {
        // Delete from itinerary_attractions first (child table)
        $delete_attractions = "DELETE FROM itinerary_attractions WHERE itinerary_id = ? AND 
                             EXISTS (SELECT 1 FROM itineraries WHERE id = ? AND user_id = ?)";
        $stmt = mysqli_prepare($db, $delete_attractions);
        mysqli_stmt_bind_param($stmt, "iii", $trip_id, $trip_id, $_SESSION['user_id']);
        mysqli_stmt_execute($stmt);

        // Then delete from itineraries (parent table)
        $delete_itinerary = "DELETE FROM itineraries WHERE id = ? AND user_id = ?";
        $stmt = mysqli_prepare($db, $delete_itinerary);
        mysqli_stmt_bind_param($stmt, "ii", $trip_id, $_SESSION['user_id']);
        mysqli_stmt_execute($stmt);

        // If we get here, commit the transaction
        mysqli_commit($db);
        
        // Redirect back to the page
        header("Location: saved_trips.php");
        exit();
        
    } catch (Exception $e) {
        // If there's an error, rollback the transaction
        mysqli_rollback($db);
        echo "Error deleting trip: " . $e->getMessage();
    }
}

// Fetch user's saved itineraries
$user_id = $_SESSION['user_id'];
$query = "SELECT i.*, 
        COUNT(DISTINCT ia.day) as total_days,
          COUNT(DISTINCT ia.attraction_id) as total_attractions
          FROM itineraries i
          LEFT JOIN itinerary_attractions ia ON i.id = ia.itinerary_id
          WHERE i.user_id = ?
          GROUP BY i.id
          ORDER BY i.created_at DESC";

$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Saved Trips - Rootify</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet"/>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            background-color:rgb(255, 255, 255);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .header h1 {
            font-size: 2rem;
            color: #333;
        }

        .trips-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
        }

        .trip-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .trip-card:hover {
            transform: translateY(-5px);
        }

        .trip-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .trip-content {
            padding: 1.5rem;
        }

        .trip-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .trip-details {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .trip-stats {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            color: #666;
            font-size: 0.9rem;
        }

        .trip-stat {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .trip-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .view-btn {
            padding: 0.5rem 1rem;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
            transition: background-color 0.3s ease;
        }

        .view-btn:hover {
            background-color: #555;
        }

        .create-btn {
            padding: 0.75rem 1.5rem;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .create-btn:hover {
            background-color: #555;
        }

        .no-trips {
            text-align: center;
            padding: 3rem;
            color: #666;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            min-width: 160px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            border-radius: 8px;
            z-index: 1;
        }

        .dropdown-content button {
            width: 100%;
            padding: 12px 16px;
            background: none;
            border: none;
            text-align: left;
            cursor: pointer;
            font-size: 0.9rem;
            color: #333;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .dropdown-content button:hover {
            background-color: #f5f5f5;
        }

        .dropdown-content button.delete-btn {
            color: #dc3545;
        }

        .dropdown-content button.delete-btn:hover {
            background-color: #ffebee;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 2rem;
            border-radius: 15px;
            width: 90%;
            max-width: 500px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .modal-header h3 {
            margin: 0;
            color: #333;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
        }

        .modal-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .modal-form input {
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .modal-form button {
            padding: 0.75rem;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        .modal-form button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>My Trips</h1>
            <a href="itinerary.php" class="create-btn">
                <i class="ri-add-line"></i>
                Create New Trip
            </a>
        </div>

        <div class="trips-grid">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($trip = mysqli_fetch_assoc($result)): ?>
                    <div class="trip-card">
                        <img src="../img/borobudur.jpg" alt="Trip thumbnail" class="trip-image">
                        <div class="trip-content">
                            <h2 class="trip-title"><?php echo htmlspecialchars($trip['trip_name']); ?></h2>
                            <div class="trip-details">
                                <p><?php echo date('M d', strtotime($trip['start_date'])) . ' - ' . date('M d, Y', strtotime($trip['end_date'])); ?></p>
                                <p><?php echo ucfirst($trip['trip_type']); ?> Trip • <?php echo ucfirst($trip['budget']); ?> Budget</p>
                            </div>
                            <div class="trip-stats">
                                <div class="trip-stat">
                                    <i class="ri-calendar-line"></i>
                                    <span><?php echo $trip['total_days']; ?> Days</span>
                                </div>
                                <div class="trip-stat">
                                    <i class="ri-map-pin-line"></i>
                                    <span><?php echo $trip['total_attractions']; ?> Places</span>
                                </div>
                            </div>
                            <div class="trip-actions">
                                <a href="view_itinerary.php?id=<?php echo $trip['id']; ?>" class="view-btn">View Trip</a>
                                <div class="dropdown">
                                    <i class="ri-more-2-fill" style="color: #666; cursor: pointer;" onclick="toggleDropdown(<?php echo $trip['id']; ?>)"></i>
                                    <div class="dropdown-content" id="dropdown-<?php echo $trip['id']; ?>">
                                        <button onclick="openEditModal(<?php echo $trip['id']; ?>, '<?php echo htmlspecialchars($trip['trip_name']); ?>')">
                                            <i class="ri-edit-line"></i>
                                            Edit Trip Name
                                        </button>
                                        <button onclick="confirmDelete(<?php echo $trip['id']; ?>)" style="color: #dc3545;">
                                            <i class="ri-delete-bin-line"></i>
                                            Delete Trip
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-trips">
                    <h2>No trips found</h2>
                    <p>Start planning your first adventure!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Edit Trip Name Modal -->
    <div class="modal" id="editModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Trip Name</h3>
                <button class="close-modal" onclick="closeEditModal()">&times;</button>
            </div>
            <form class="modal-form" method="POST">
                <input type="hidden" name="trip_id" id="editTripId">
                <input type="hidden" name="update_trip_name" value="1">
                <input type="text" name="new_trip_name" id="editTripName" placeholder="Enter new trip name" required>
                <button type="submit">Save Changes</button>
            </form>
        </div>
    </div>

    <script>
        // Toggle dropdown menu
        function toggleDropdown(tripId) {
            const dropdown = document.getElementById(`dropdown-${tripId}`);
            const allDropdowns = document.querySelectorAll('.dropdown-content');
            
            // Close all other dropdowns
            allDropdowns.forEach(d => {
                if (d.id !== `dropdown-${tripId}`) {
                    d.style.display = 'none';
                }
            });

            // Toggle current dropdown
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.matches('.ri-more-2-fill')) {
                const dropdowns = document.querySelectorAll('.dropdown-content');
                dropdowns.forEach(d => d.style.display = 'none');
            }
        });

        // Modal functions
        function openEditModal(tripId, currentName) {
            const modal = document.getElementById('editModal');
            const tripIdInput = document.getElementById('editTripId');
            const tripNameInput = document.getElementById('editTripName');
            
            tripIdInput.value = tripId;
            tripNameInput.value = currentName;
            
            modal.style.display = 'flex';
        }

        function confirmDelete(tripId) {
            if (confirm('Are you sure you want to delete this trip? This action cannot be undone.')) {
                window.location.href = `saved_trips.php?delete_trip=${tripId}`;
            }
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            modal.style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target === modal) {
                closeEditModal();
            }
        }
    </script>
</body>
</html>