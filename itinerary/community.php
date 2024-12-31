<?php
include "../filter_wisata/db_connect.php";

// Simplified query to debug the issue
$query = "SELECT 
    cp.*,
    i.trip_name,
    i.start_date,
    i.end_date,
    i.trip_type,
    i.budget,
    u.username,
    (SELECT COUNT(*) FROM post_likes WHERE post_id = cp.id) as like_count,
    (SELECT COUNT(*) FROM post_comments WHERE post_id = cp.id) as comment_count,
    (SELECT COUNT(DISTINCT day) FROM itinerary_attractions WHERE itinerary_id = i.id) as total_days,
    (SELECT COUNT(DISTINCT attraction_id) FROM itinerary_attractions WHERE itinerary_id = i.id) as total_attractions,
    EXISTS(SELECT 1 FROM post_likes WHERE post_id = cp.id AND user_id = ?) as user_liked
    FROM community_posts cp
    JOIN itineraries i ON cp.itinerary_id = i.id
    JOIN users u ON cp.user_id = u.id
    ORDER BY cp.created_at DESC";

// Add error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $stmt = mysqli_prepare($db, $query);
    if ($stmt === false) {
        throw new Exception("Prepare failed: " . mysqli_error($db));
    }

    mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
    
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
    }

    $result = mysqli_stmt_get_result($stmt);
    if ($result === false) {
        throw new Exception("Get result failed: " . mysqli_error($db));
    }

} catch (Exception $e) {
    die("Database error: " . $e->getMessage());
}
?>

<?php include '../navfot/navbar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community - Rootify</title>
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
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .share-btn {
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

        .share-btn:hover {
            background-color: #555;
        }

        .post-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .post-header {
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .post-header img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .post-header .user-info {
            flex-grow: 1;
        }

        .post-header .username {
            font-weight: 600;
            color: #333;
        }

        .post-header .post-date {
            font-size: 0.85rem;
            color: #666;
        }

        .post-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }

        .post-content {
            padding: 1rem;
        }

        .trip-details {
            margin: 1rem 0;
            padding: 1rem;
            background: #f8f8f8;
            border-radius: 10px;
        }

        .trip-stats {
            display: flex;
            gap: 1rem;
            margin-top: 0.5rem;
            color: #666;
            font-size: 0.9rem;
        }

        .post-actions {
            padding: 1rem;
            border-top: 1px solid #eee;
            display: flex;
            gap: 1rem;
        }

        .action-btn {
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .action-btn:hover {
            color: #333;
        }

        .action-btn.liked {
            color: #e74c3c;
        }

        .comments-section {
            padding: 1rem;
            border-top: 1px solid #eee;
        }

        .comment-form {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .comment-input {
            flex-grow: 1;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 20px;
            outline: none;
        }

        .comment-submit {
            padding: 0.5rem 1rem;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 20px;
            cursor: pointer;
        }

        .comments-list {
            margin-top: 1rem;
        }

        .comment {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .comment-content {
            background: #f8f8f8;
            padding: 0.5rem 1rem;
            border-radius: 15px;
            flex-grow: 1;
        }

        .comment-username {
            font-weight: 600;
            margin-right: 0.5rem;
        }

        .view-trip-btn {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            background-color: #f0f0f0;
            color: #333;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
            margin-top: 1rem;
        }

        .view-trip-btn:hover {
            background-color: #e0e0e0;
        }

        .view-trip-btn i {
            margin-right: 5px;
        }
    </style>
</head>
<body>
<div class="container">
<pre>
    

    </pre>
        <div class="header">
            <h1>Community</h1>
            <a href="saved_trips.php" class="share-btn">
                <i class="ri-add-line"></i>
                Share Your Trip
            </a>
        </div>

        <div class="posts-container">
            <?php while ($post = mysqli_fetch_assoc($result)): ?>
                <div class="post-card">
                    <div class="post-header">
                        <img src="../img/default-avatar.jpg" alt="Profile picture">
                        <div class="user-info">
                            <div class="username"><?php echo htmlspecialchars($post['username']); ?></div>
                            <div class="post-date"><?php echo date('F j, Y', strtotime($post['created_at'])); ?></div>
                        </div>
                    </div>
                    
                    <img src="../img/borobudur.jpg" alt="Trip thumbnail" class="post-image">
                    
                    <div class="post-content">
                        <?php if ($post['caption']): ?>
                            <p><?php echo htmlspecialchars($post['caption']); ?></p>
                        <?php endif; ?>
                        
                        <div class="trip-details">
                            <h3><?php echo htmlspecialchars($post['trip_name']); ?></h3>
                            <p><?php echo date('M d', strtotime($post['start_date'])) . ' - ' . date('M d, Y', strtotime($post['end_date'])); ?></p>
                            <p><?php echo ucfirst($post['trip_type']); ?> Trip • <?php echo ucfirst($post['budget']); ?> Budget</p>
                            
                            <div class="trip-stats">
                                <div class="trip-stat">
                                    <i class="ri-calendar-line"></i>
                                    <span><?php echo $post['total_days']; ?> Days</span>
                                </div>
                                <div class="trip-stat">
                                    <i class="ri-map-pin-line"></i>
                                    <span><?php echo $post['total_attractions']; ?> Places</span>
                                </div>
                            </div>
                            <a href="view_community_trips.php?id=<?php echo $post['id']; ?>" class="view-trip-btn">
                                <i class="ri-eye-line"></i> See Trip 
                            </a>
                            <!-- revisi nanti brok -->
                        </div>
                    </div>

                    <div class="post-actions">
                        <button class="action-btn <?php echo $post['user_liked'] ? 'liked' : ''; ?>" 
                                onclick="toggleLike(<?php echo $post['id']; ?>, this)">
                            <i class="ri-heart-<?php echo $post['user_liked'] ? 'fill' : 'line'; ?>"></i>
                            <span class="like-count"><?php echo $post['like_count']; ?></span>
                        </button>
                        <button class="action-btn" onclick="toggleComments(<?php echo $post['id']; ?>)">
                            <i class="ri-chat-1-line"></i>
                            <span><?php echo $post['comment_count']; ?></span>
                        </button>
                    </div>

                    <div class="comments-section" id="comments-<?php echo $post['id']; ?>" style="display: none;">
                        <div class="comments-list" id="comments-list-<?php echo $post['id']; ?>">
                            <!-- Comments will be loaded here -->
                        </div>
                        <form class="comment-form" onsubmit="submitComment(event, <?php echo $post['id']; ?>)">
                            <input type="text" class="comment-input" placeholder="Add a comment...">
                            <button type="submit" class="comment-submit">Post</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script>
        function toggleLike(postId, button) {
            fetch('like_post.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ post_id: postId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const icon = button.querySelector('i');
                    const count = button.querySelector('.like-count');
                    
                    if (data.liked) {
                        button.classList.add('liked');
                        icon.classList.replace('ri-heart-line', 'ri-heart-fill');
                    } else {
                        button.classList.remove('liked');
                        icon.classList.replace('ri-heart-fill', 'ri-heart-line');
                    }
                    count.textContent = data.like_count;
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function toggleComments(postId) {
            const commentsSection = document.getElementById(`comments-${postId}`);
            const commentsList = document.getElementById(`comments-list-${postId}`);
            
            if (commentsSection.style.display === 'none') {
                commentsSection.style.display = 'block';
                loadComments(postId);
            } else {
                commentsSection.style.display = 'none';
            }
        }
        
        function loadComments(postId) {
            fetch(`get_comment.php?post_id=${postId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    const commentsList = document.getElementById(`comments-list-${postId}`);
                    if (data.success && data.comments && Array.isArray(data.comments)) {
                        commentsList.innerHTML = data.comments.map(comment => `
                            <div class="comment">
                                <div class="comment-content">
                                    <span class="comment-username">${comment.username}</span>
                                    ${comment.comment}
                                </div>
                            </div>
                        `).join('');
                    } else {
                        commentsList.innerHTML = '<p>No comments yet.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const commentsList = document.getElementById(`comments-list-${postId}`);
                    commentsList.innerHTML = `<p>Error loading comments: ${error.message}</p>`;
                });
        }

        function submitComment(event, postId) {
            event.preventDefault();
            const form = event.target;
            const input = form.querySelector('input');
            
            fetch('add_comment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    post_id: postId,
                    comment: input.value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    input.value = '';
                    loadComments(postId);
                }
            });
        }
    </script>
</body>
</html>

