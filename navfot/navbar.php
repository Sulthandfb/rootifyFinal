<?php
session_start();
?>
    <style>
/* Base navbar styles */
/* Base navbar styles */
.navbar {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    padding: 1rem 0;
    background-color: rgba(255, 255, 255, 0.1);
    transition: background-color 0.3s ease;
    z-index: 1000;
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
}

.user-dropdown a:hover {
    background: #f5f5f5;
}

/* Scroll effect */
.navbar.scrolled {
    background-color: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

.navbar.scrolled .nav-links a,
.navbar.scrolled .brand-text,
.navbar.scrolled .user-name {
    color: #000;
}

.navbar.scrolled .btn {
    border-color: #000;
    color: #000;
}

.navbar.scrolled .btn:hover {
    background: #000;
    color: #fff;
}
    </style>
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <a href="#" class="nav-brand">
                <img src="logo1.png" alt="Logo Tim" class="logo">
                <span class="brand-text">Rootify</span>
            </a>
            <ul class="nav-links">
                <li><a href="../landing/template.php">Home</a></li>
                <li><a href="../akomodasi/hotels.php">Akomodasi</a></li>
                <li><a href="../itinerary/saved_trips.php">MyTrips</a></li>
                <li><a href="../itinerary/itinerary.php">Itinerary</a></li>
            </ul>
            <div class="nav-buttons">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <div class="user-profile">
                        <img src="uploads/<?php echo $_SESSION['user_avatar']; ?>" alt="User Avatar" class="user-avatar">
                        <span class="user-name"><?php echo $_SESSION['username']; ?></span>
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
window.addEventListener('scroll', function() {
    const navbar = document.getElementById('navbar');
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});
    </script>