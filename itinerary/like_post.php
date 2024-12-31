<?php
session_start();
include "../filter_wisata/db_connect.php";

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$post_id = $data['post_id'];
$user_id = $_SESSION['user_id'];

// Check if user already liked the post
$check_query = "SELECT id FROM post_likes WHERE post_id = ? AND user_id = ?";
$stmt = mysqli_prepare($db, $check_query);
mysqli_stmt_bind_param($stmt, "ii", $post_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    // Unlike
    $delete_query = "DELETE FROM post_likes WHERE post_id = ? AND user_id = ?";
    $stmt = mysqli_prepare($db, $delete_query);
    mysqli_stmt_bind_param($stmt, "ii", $post_id, $user_id);
    mysqli_stmt_execute($stmt);
    $liked = false;
} else {
    // Like
    $insert_query = "INSERT INTO post_likes (post_id, user_id) VALUES (?, ?)";
    $stmt = mysqli_prepare($db, $insert_query);
    mysqli_stmt_bind_param($stmt, "ii", $post_id, $user_id);
    mysqli_stmt_execute($stmt);
    $liked = true;
}

// Get updated like count
$count_query = "SELECT COUNT(*) as count FROM post_likes WHERE post_id = ?";
$stmt = mysqli_prepare($db, $count_query);
mysqli_stmt_bind_param($stmt, "i", $post_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

echo json_encode([
    'success' => true,
    'liked' => $liked,
    'like_count' => $row['count']
]);