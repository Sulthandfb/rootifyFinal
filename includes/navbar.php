<?php
    session_start();
?>
<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-logo">
            <img src="logo1.png" alt="Rootify Logo" class="logo-image">
            <span class="logo-text">Rootify</span>
        </div>
        <div class="navbar-menu">
            <a href="#" class="nav-link">Trip</a>
            <a href="#" class="nav-link">Destinasi</a>
            <a href="#" class="nav-link">Blog</a>
            <a href="#" class="nav-link">Bantuan</a>
        </div>
        <div class="navbar-profile">
            <?php if(isset($_SESSION['user_id'])): ?>
                <div class="user-profile">
                    <img src="uploads/<?php echo $_SESSION['user_avatar']; ?>" alt="User Avatar" class="profile-image">
                    <span class="profile-name"><?php echo $_SESSION['username']; ?></span>
                    <div class="user-dropdown">
                        <a href="../profile/profile.php">My Profile</a>
                        <a href="../profile/bookings.php">My Bookings</a>
                        <a href="../profile/logout.php">Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="../authentication/index.php" class="btn">Login</a>
                <a href="../authentication/index.php" class="btn">Daftar</a>
            <?php endif; ?>
        </div>
        <div class="navbar-toggle">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
    </div>
</nav>