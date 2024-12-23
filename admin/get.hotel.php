<?php
include '../filter_wisata/db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Get hotel data and facilities
    $sql = "SELECT h.*, GROUP_CONCAT(f.facility_name) as facilities 
            FROM hotels h 
            LEFT JOIN facilities f ON h.hotel_id = f.hotel_id 
            WHERE h.hotel_id = ?
            GROUP BY h.hotel_id";
            
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        // Convert facilities string to array
        $row['facilities'] = $row['facilities'] ? explode(',', $row['facilities']) : [];
        echo json_encode($row);
    } else {
        echo json_encode(["error" => "Hotel not found"]);
    }
    
    $stmt->close();
} else {
    echo json_encode(["error" => "No ID provided"]);
}

$db->close();
?>