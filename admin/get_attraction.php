<?php
include '../filter_wisata/db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "SELECT a.*, ad.ticket_price, ad.embed_code, ad.photo_gallery 
            FROM tourist_attractions a 
            LEFT JOIN attraction_details ad ON a.id = ad.attraction_id 
            WHERE a.id = ?";
            
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo json_encode($row);
    } else {
        echo json_encode(["error" => "Attraction not found"]);
    }
    
    $stmt->close();
} else {
    echo json_encode(["error" => "No ID provided"]);
}

$db->close();
?>