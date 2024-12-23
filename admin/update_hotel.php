<?php
// update_hotel.php
include '../filter_wisata/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hotel_id = $_POST['hotel_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $rating = $_POST['rating'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    
    // Start transaction
    $db->begin_transaction();
    
    try {
        // Update main hotel data
        $sql = "UPDATE hotels SET name = ?, description = ?, rating = ?, 
                price = ?, category = ? WHERE hotel_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssddsi", $name, $description, $rating, $price, $category, $hotel_id);
        $stmt->execute();

        // Handle image upload if new image is provided
        if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
            $target_dir = "../akomodasi/hotel-img/";
            $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
            $new_filename = $hotel_id . "_" . time() . "." . $file_extension;
            $target_file = $target_dir . $new_filename;

            // Validate image
            $valid_types = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($_FILES['image']['type'], $valid_types)) {
                throw new Exception("Invalid file type. Only JPG, PNG & GIF allowed.");
            }
            if ($_FILES["image"]["size"] > 5000000) {
                throw new Exception("File too large. Maximum size is 5MB.");
            }

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Update image URL in database
                $sql = "UPDATE hotels SET image_url = ? WHERE hotel_id = ?";
                $stmt = $db->prepare($sql);
                $stmt->bind_param("si", $target_file, $hotel_id);
                $stmt->execute();
            }
        }

        // Update facilities
        if (isset($_POST['facilities'])) {
            // Remove existing facilities
            $sql = "DELETE FROM facilities WHERE hotel_id = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("i", $hotel_id);
            $stmt->execute();

            // Add new facilities
            $sql = "INSERT INTO facilities (hotel_id, facility_name) VALUES (?, ?)";
            $stmt = $db->prepare($sql);
            foreach ($_POST['facilities'] as $facility) {
                $stmt->bind_param("is", $hotel_id, $facility);
                $stmt->execute();
            }
        }

        $db->commit();
        echo json_encode(['success' => true, 'message' => 'Hotel updated successfully']);
    } catch (Exception $e) {
        $db->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}
?>