<?php
include '../filter_wisata/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
    
    try {
        $db->begin_transaction();
        
        // Basic validation
        if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
            throw new Exception('No image file uploaded');
        }

        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Upload error: ' . $_FILES['image']['error']);
        }

        // File upload handling
        $upload_dir = __DIR__ . '/uploads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_info = pathinfo($_FILES['image']['name']);
        $file_extension = strtolower($file_info['extension']);
        
        // Validate file type
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($file_extension, $allowed)) {
            throw new Exception('Invalid file type');
        }

        // Generate unique filename
        $new_filename = uniqid() . '.' . $file_extension;
        $upload_path = $upload_dir . $new_filename;
        $relative_path = 'uploads/' . $new_filename;

        // Move uploaded file
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            throw new Exception('Failed to move uploaded file');
        }

        // Update database
        $sql = "UPDATE tourist_attractions SET 
                image_url = ?
                WHERE id = ?";

        $stmt = $db->prepare($sql);
        $stmt->bind_param("si", $relative_path, $_POST['attraction_id']);
        
        if (!$stmt->execute()) {
            throw new Exception('Database update failed');
        }

        $db->commit();
        $response['status'] = 'success';
        $response['image_url'] = $relative_path;
        
    } catch (Exception $e) {
        $db->rollback();
        $response['status'] = 'error';
        $response['message'] = $e->getMessage();
    }
    
    echo json_encode($response);
}
?>