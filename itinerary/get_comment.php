<?php
error_log("Request received for get_comments.php");
error_log("POST data: " . print_r($_POST, true));
error_log("GET data: " . print_r($_GET, true));
error_log("Session data: " . print_r($_SESSION, true));
session_start();
include "../filter_wisata/db_connect.php";

header('Content-Type: application/json');

// Error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Error logging
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');
error_log("get_comments.php was called");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit();
}

if (!isset($_GET['post_id'])) {
    echo json_encode(['success' => false, 'error' => 'No post ID provided']);
    exit();
}

$post_id = $_GET['post_id'];

$query = "SELECT pc.*, u.username 
          FROM post_comments pc
          JOIN users u ON pc.user_id = u.id
          WHERE pc.post_id = ?
          ORDER BY pc.created_at DESC";

try {
    $stmt = mysqli_prepare($db, $query);
    if ($stmt === false) {
        throw new Exception("Prepare failed: " . mysqli_error($db));
    }

    mysqli_stmt_bind_param($stmt, "i", $post_id);
    
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
    }

    $result = mysqli_stmt_get_result($stmt);
    if ($result === false) {
        throw new Exception("Get result failed: " . mysqli_error($db));
    }

    $comments = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $comments[] = [
            'id' => $row['id'],
            'username' => htmlspecialchars($row['username']),
            'comment' => htmlspecialchars($row['comment']),
            'created_at' => $row['created_at']
        ];
    }

    echo json_encode([
        'success' => true,
        'comments' => $comments
    ]);

} catch (Exception $e) {
    error_log("Error in get_comments.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}