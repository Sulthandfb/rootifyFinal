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
$comment = $data['comment'];
$user_id = $_SESSION['user_id'];

$query = "INSERT INTO post_comments (post_id, user_id, comment) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "iis", $post_id, $user_id, $comment);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to add comment']);
}