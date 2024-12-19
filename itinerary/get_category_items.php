<?php
include "../filter_wisata/db_connect.php";

header('Content-Type: application/json');

$category = isset($_GET['category']) ? $_GET['category'] : '';

$query = "SELECT id, name, description, image_url, rating, category 
          FROM tourist_attractions 
          WHERE LOWER(category) = LOWER(?)";

$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "s", $category);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$items = array();
while ($row = mysqli_fetch_assoc($result)) {
    $items[] = array(
        'id' => $row['id'],
        'name' => $row['name'],
        'description' => $row['description'],
        'image_url' => $row['image_url'],
        'rating' => $row['rating'],
        'category' => $row['category']
    );
}

echo json_encode($items);
?>

