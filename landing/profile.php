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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['avatar'])) {
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
            $upload_message = "Gambar profil berhasil diperbarui.";
        } else {
            $upload_message = "Maaf, terjadi kesalahan saat memperbarui database.";
        }
        $stmt->close();
    } else {
        $upload_message = "Maaf, terjadi kesalahan saat mengunggah file. Error: " . error_get_last()['message'];
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
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .profile-header {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 40px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        .profile-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 40px;
        }
        .profile-info h1 {
            margin: 0 0 10px 0;
            color: #333;
        }
        .profile-info p {
            margin: 0;
            color: #666;
        }
        .profile-content {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 40px;
        }
        .profile-content h2 {
            margin-top: 0;
            color: #333;
        }
        .edit-avatar-form {
            margin-top: 20px;
        }
        .edit-avatar-form input[type="file"] {
            margin-bottom: 10px;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
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
            background-color: #45a049;
        }
        .message {
            margin-top: 10px;
            padding: 10px;
            border-radius: 5px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="profile-header">
            <img src="uploads/<?php echo $avatar; ?>" alt="Profile Picture" class="profile-avatar">
            <div class="profile-info">
                <h1><?php echo $username; ?></h1>
                <p><?php echo $email; ?></p>
            </div>
        </div>
        
        <div class="profile-content">
            <h2>Edit Profile Picture</h2>
            <form class="edit-avatar-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
                <input type="file" name="avatar" accept="image/*" required>
                <button type="submit" class="btn">Upload New Picture</button>
            </form>
            <?php if (isset($upload_message)): ?>
                <div class="message"><?php echo $upload_message; ?></div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

