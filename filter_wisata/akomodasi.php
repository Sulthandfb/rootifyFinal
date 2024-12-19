<?php 
session_start();
include "db_connect.php"; // Pastikan file db_connect.php berada di direktori yang sama



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akomodasi</title>
    <link rel="stylesheet" href="../akomodasi/akomodasi.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="#" class="nav-brand">
                <img src="logo1.png" alt="Logo Tim" class="logo">
                <span class="brand-text">Rootify</span>
            </a>
            <button class="nav-toggle" id="navToggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <div class="nav-menu" id="navMenu">
                <ul class="nav-links">
                    <li><a href="#">Trip</a></li>
                    <li><a href="#">Destinasi</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Bantuan</a></li>
                </ul>
                <div class="nav-buttons">
                    <a href="/regristasi/login.html" class="btnnav">Login</a>
                    <a href="#" class="btnnav">Daftar</a>
                </div>
            </div>
        </div>
    </nav>
    <nav class="secondary-navbar">
        <div class="nav-container">
            <ul class="secondary-nav-links">
                <li><a href="/homepage/yusuf.html" class="secondary-nav-link">Home</a></li>
                <li><a href="/akomodasi/akomodasi.html" class="secondary-nav-link active">Akomodasi</a></li>
                <li><a href="#" class="secondary-nav-link">Filter</a></li>
                <li><a href="#" class="secondary-nav-link">Makan</a></li>
                <li><a href="#" class="secondary-nav-link">Harga</a></li>
            </ul>
        </div>
    </nav>
    <main>
      <div class="hero">
        <div class="hero-content">
          <h1>Search Your Hotel</h1>
          <div class="search-container">
            <div class="search-input">
              <input type="text" placeholder="Mau nginep di mana?">
            </div>
            <div class="date-input">
              <input type="text" placeholder="Check-in - Check-out">
            </div>
            <div class="guests-input">
              <input type="text" placeholder="2 Tamu">
            </div>
            <button class="search-button">Ayo Cari</button>
          </div>
        </div>
      </div>
      <section class="packages">
        <h2>Available Packages</h2>
        <div class="package-list">
            <!-- Example of a package item -->
            <div class="package-item">
                <img src="/placeholder.svg?height=200&width=300" alt="Hotel Name 1" class="package-item-image">
                <div class="package-item-content">
                    <h3>Hotel Name 1</h3>
                    <p>Description of Hotel 1.</p>
                    <p>Price: $100/night</p>
                    <a href="/packages/packages.html" class="btn">Book Now</a>
                </div>
            </div>
            <div class="package-item">
                <img src="/placeholder.svg?height=200&width=300" alt="Hotel Name 2" class="package-item-image">
                <div class="package-item-content">
                    <h3>Hotel Name 2</h3>
                    <p>Description of Hotel 2.</p>
                    <p>Price: $150/night</p>
                    <a href="#" class="btn">Book Now</a>
                </div>
            </div>
            <div class="package-item">
                <img src="/placeholder.svg?height=200&width=300" alt="Hotel Name 2" class="package-item-image">
                <div class="package-item-content">
                    <h3>Hotel Name 2</h3>
                    <p>Description of Hotel 2.</p>
                    <p>Price: $150/night</p>
                    <a href="#" class="btn">Book Now</a>
                </div>
            </div>
            <div class="package-item">
                <img src="/placeholder.svg?height=200&width=300" alt="Hotel Name 2" class="package-item-image">
                <div class="package-item-content">
                    <h3>Hotel Name 2</h3>
                    <p>Description of Hotel 2.</p>
                    <p>Price: $150/night</p>
                    <a href="#" class="btn">Book Now</a>
                </div>
            </div>
            <div class="package-item">
                <img src="/placeholder.svg?height=200&width=300" alt="Hotel Name 2" class="package-item-image">
                <div class="package-item-content">
                    <h3>Hotel Name 2</h3>
                    <p>Description of Hotel 2.</p>
                    <p>Price: $150/night</p>
                    <a href="#" class="btn">Book Now</a>
                </div>
            </div>
            <div class="package-item">
                <img src="/placeholder.svg?height=200&width=300" alt="Hotel Name 2" class="package-item-image">
                <div class="package-item-content">
                    <h3>Hotel Name 2</h3>
                    <p>Description of Hotel 2.</p>
                    <p>Price: $150/night</p>
                    <a href="#" class="btn">Book Now</a>
                </div>
            </div>
            <div class="package-item">
                <img src="/placeholder.svg?height=200&width=300" alt="Hotel Name 2" class="package-item-image">
                <div class="package-item-content">
                    <h3>Hotel Name 2</h3>
                    <p>Description of Hotel 2.</p>
                    <p>Price: $150/night</p>
                    <a href="#" class="btn">Book Now</a>
                </div>
            </div>
            <div class="package-item">
                <img src="/placeholder.svg?height=200&width=300" alt="Hotel Name 2" class="package-item-image">
                <div class="package-item-content">
                    <h3>Hotel Name 2</h3>
                    <p>Description of Hotel 2.</p>
                    <p>Price: $150/night</p>
                    <a href="#" class="btn">Book Now</a>
                </div>
            </div>
        </div>
    </section>
    </main>
    <footer>
        <div class="footer-container">
            <p>&copy; 2023 Rootify. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>