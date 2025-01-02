<?php
session_start();
?>
<style>
/* Base navbar styles */
.navbar {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    padding: 1rem 0;
    background-color: rgba(255, 255, 255, 0.9);
    z-index: 1000;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.nav-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* Logo and brand */
.nav-brand {
    display: flex;
    align-items: center;
    text-decoration: none;
    color: #000;
}

.logo {
    height: 40px;
    margin-right: 0.5rem;
}

.brand-text {
    font-size: 1.5rem;
    font-weight: bold;
}

/* Navigation links */
.nav-links {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 2rem;
}

.nav-links a {
    color: #000;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
}

.nav-links a:hover {
    color: #007bff;
}

/* Buttons */
.nav-buttons {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.btn {
    padding: 0.5rem 1.5rem;
    border: 2px solid #000;
    border-radius: 25px;
    color: #000;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn:hover {
    background: #000;
    color: #fff;
}

/* User profile */
.user-profile {
    position: relative;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
}

.user-avatar {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    object-fit: cover;
}

.user-name {
    color: #000;
}

.user-dropdown {
    display: none;
    position: absolute;
    top: 100%;
    right: 0;
    background: #fff;
    border-radius: 8px;
    padding: 0.5rem 0;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    min-width: 150px;
}

.user-profile:hover .user-dropdown {
    display: block;
}

.user-dropdown a {
    display: block;
    padding: 0.5rem 1rem;
    color: #333;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.user-dropdown a:hover {
    background: #f5f5f5;
}

/* Hamburger menu - Simplified */
.hamburger {
    display: none;
    background: none;
    border: none;
    cursor: pointer;
    padding: 0;
    margin-right: 1rem;
}

.hamburger span {
    display: block;
    width: 25px;
    height: 3px;
    background-color: #000;
    margin: 5px 0;
    transition: transform 0.3s ease;
}

/* Responsive styles */
@media (max-width: 800px) {
    .hamburger {
        display: block;
    }

    .nav-links {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        background: white;
        padding: 1rem 0;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .nav-links.active {
        display: block;
    }

    .nav-links li {
        display: block;
        margin: 1rem 2rem;
    }

    .nav-links a {
        display: block;
        padding: 0.5rem 0;
    }

    /* .btn {
        display: none;
    } */
}
</style>

<nav class="navbar">
    <div class="nav-container">
        <button class="hamburger" id="hamburger-menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <a href="#" class="nav-brand">
            <img src="../img/logo.png" alt="Logo Tim" class="logo">
            <span class="brand-text">Rootify</span>
        </a>
        <ul class="nav-links" id="nav-links">
            <li><a href="../landing/template.php">Home</a></li>
            <li><a href="../akomodasi/hotels.php">Akomodasi</a></li>
            <li><a href="../itinerary/saved_trips.php">MyTrips</a></li>
            <li><a href="../itinerary/itinerary.php">Itinerary</a></li>
            <li><a href="../itinerary/community.php">Community</a></li>
        </ul>
        <div class="nav-buttons">
            <?php if(isset($_SESSION['user_id'])): ?>
                <div class="user-profile">
                    <?php
                    $avatar_path = 'uploads/' . $_SESSION['user_avatar'];
                    if (!empty($_SESSION['user_avatar']) && file_exists($avatar_path)) {
                        $display_avatar = $avatar_path;
                    } else {
                        $display_avatar = '../img/default-avatar.jpg';
                    }
                    ?>
                    <img src="<?php echo htmlspecialchars($display_avatar); ?>" alt="User Avatar" class="user-avatar">
                    <span class="user-name"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
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
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.getElementById('hamburger-menu');
    const navLinks = document.getElementById('nav-links');

    hamburger.addEventListener('click', function() {
        navLinks.classList.toggle('active');
    });

    // Close menu when clicking outside
    document.addEventListener('click', function(e) {
        if (!navLinks.contains(e.target) && !hamburger.contains(e.target) && navLinks.classList.contains('active')) {
            navLinks.classList.remove('active');
        }
    });

    // Close menu when window is resized above mobile breakpoint
    window.addEventListener('resize', function() {
        if (window.innerWidth > 800 && navLinks.classList.contains('active')) {
            navLinks.classList.remove('active');
        }
    });
});
</script>