<?php
session_start();
include '../filter_wisata/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: landingpage.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$email = $_SESSION['user_email'];
$avatar = $_SESSION['user_avatar'];

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_username'])) {
        $new_username = trim($_POST['new_username']);
        if (!empty($new_username)) {
            // Check if the new username already exists
            $check_stmt = $db->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
            $check_stmt->bind_param("si", $new_username, $user_id);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            
            if ($check_result->num_rows > 0) {
                $message = "This username is already taken. Please choose a different one.";
                $message_type = "error";
            } else {
                $stmt = $db->prepare("UPDATE users SET username = ? WHERE id = ?");
                $stmt->bind_param("si", $new_username, $user_id);
                if ($stmt->execute()) {
                    $_SESSION['username'] = $new_username;
                    $username = $new_username;
                    $message = "Username updated successfully!";
                    $message_type = "success";
                } else {
                    $message = "Error updating username. Please try again.";
                    $message_type = "error";
                }
                $stmt->close();
            }
            $check_stmt->close();
        }
    } elseif (isset($_FILES['avatar'])) {
        $target_dir = __DIR__ . "/uploads/";
        $file_extension = pathinfo($_FILES["avatar"]["name"], PATHINFO_EXTENSION);
        $new_filename = $user_id . "_" . time() . "." . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
            $sql = "UPDATE users SET avatar = ? WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("si", $new_filename, $user_id);
            
            if ($stmt->execute()) {
                $_SESSION['user_avatar'] = $new_filename;
                $avatar = $new_filename;
                $message = "Profile picture updated successfully!";
                $message_type = "success";
            } else {
                $message = "Error updating profile picture in the database.";
                $message_type = "error";
            }
            $stmt->close();
        } else {
            $message = "Error uploading file. Please try again.";
            $message_type = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Rootify</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            color: #333;
        }
        .container {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 20px;
        }
        .main-content {
            flex: 1;
            padding: 40px;
        }
        .profile-header {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 40px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
        }
        .profile-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 40px;
            border: 5px solid #3498db;
        }
        .profile-info h1 {
            margin: 0 0 10px 0;
            color: #2c3e50;
            font-size: 2.5em;
        }
        .profile-info p {
            margin: 0;
            color: #7f8c8d;
            font-size: 1.1em;
        }
        .profile-content {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 40px;
        }
        .profile-content h2 {
            margin-top: 0;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .edit-form {
            margin-top: 20px;
        }
        .edit-form input[type="file"],
        .edit-form input[type="text"] {
            margin-bottom: 15px;
            width: 100%;
            padding: 10px;
            border: 1px solid #bdc3c7;
            border-radius: 5px;
            font-size: 16px;
        }
        .btn {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        .message {
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .sidebar-menu {
            list-style-type: none;
            padding: 0;
        }
        .sidebar-menu li {
            margin-bottom: 15px;
        }
        .sidebar-menu a {
            color: #ecf0f1;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: color 0.3s;
        }
        .sidebar-menu a:hover {
            color: #3498db;
        }
        .sidebar-menu i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #3498db;
            margin-bottom: 30px;
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <span class="logo">Rootify</span>
            <ul class="sidebar-menu">
                <li><a href="../landing/template.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
                <li><a href="bookings.php"><i class="fas fa-calendar-check"></i> My Bookings</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        <div class="main-content">
            <div class="profile-header">
                <?php
                // Check if avatar exists and is not empty
                $avatar_path = 'uploads/' . $avatar;
                if (!empty($avatar) && file_exists($avatar_path)) {
                    $display_avatar = $avatar_path;
                } else {
                    $display_avatar = '../img/default-avatar.jpg'; // Replace with your default avatar path
                }
                ?>
                <img src="<?php echo htmlspecialchars($display_avatar); ?>" alt="Profile Picture" class="profile-avatar">
                <div class="profile-info">
                    <h1><?php echo htmlspecialchars($username); ?></h1>
                    <p><?php echo htmlspecialchars($email); ?></p>
                </div>
            </div>
            
            <div class="profile-content">
                <h2>Edit Profile</h2>
                <?php if (!empty($message)): ?>
                    <div class="message <?php echo $message_type; ?>"><?php echo $message; ?></div>
                <?php endif; ?>
                <form class="edit-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
                    <h3>Change Profile Picture</h3>
                    <input type="file" name="avatar" accept="image/*">
                    <button type="submit" class="btn">Upload New Picture</button>
                </form>
                <form class="edit-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                    <h3>Change Username</h3>
                    <input type="text" name="new_username" placeholder="New Username" required>
                    <button type="submit" name="update_username" class="btn">Update Username</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

