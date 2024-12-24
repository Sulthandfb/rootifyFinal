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

/* Scroll effect */
.navbar.scrolled {
    background-color: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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

/* Hamburger menu */
.hamburger {
    display: none;
    flex-direction: column;
    cursor: pointer;
    z-index: 1001;
}

.hamburger span {
    display: block;
    width: 25px;
    height: 3px;
    background-color: #000;
    margin-bottom: 5px;
    transition: all 0.3s ease;
}

/* Responsive styles */
@media (max-width: 800px) {
    .nav-container {
        justify-content: space-between;
    }

    .hamburger {
        display: flex;
        order: 1;
    }

    .nav-brand {
        order: 2;
    }

    .nav-buttons {
        order: 3;
    }

    .nav-links {
        position: fixed;
        top: -100%;
        left: 0;
        right: 0;
        flex-direction: column;
        background-color: #fff;
        width: 100%;
        text-align: center;
        transition: 0.3s;
        box-shadow: 0 10px 27px rgba(0, 0, 0, 0.05);
        padding: 80px 0 30px;
        z-index: 1000;
    }

    .nav-links.active {
        top: 0;
    }

    .nav-item {
        margin: 1.5rem 0;
    }

    .btn {
        display: none;
    }

    .hamburger.active span:nth-child(1) {
        transform: rotate(45deg) translate(5px, 5px);
    }

    .hamburger.active span:nth-child(2) {
        opacity: 0;
    }

    .hamburger.active span:nth-child(3) {
        transform: rotate(-45deg) translate(7px, -6px);
    }
}
</style>

<nav class="navbar" id="navbar">
    <div class="nav-container">
        <div class="hamburger" id="hamburger-menu">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <a href="#" class="nav-brand">
            <img src="logo1.png" alt="Logo Tim" class="logo">
            <span class="brand-text">Rootify</span>
        </a>
        <ul class="nav-links" id="nav-links">
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

const hamburger = document.getElementById('hamburger-menu');
const navLinks = document.getElementById('nav-links');

hamburger.addEventListener('click', () => {
    navLinks.classList.toggle('active');
    hamburger.classList.toggle('active');
});

// Close mobile menu when a link is clicked
document.querySelectorAll('.nav-links a').forEach(link => {
    link.addEventListener('click', () => {
        navLinks.classList.remove('active');
        hamburger.classList.remove('active');
    });
});

// Close mobile menu when clicking outside
document.addEventListener('click', (event) => {
    const isClickInsideNavbar = navbar.contains(event.target);
    if (!isClickInsideNavbar && navLinks.classList.contains('active')) {
        navLinks.classList.remove('active');
        hamburger.classList.remove('active');
    }
});
</script>