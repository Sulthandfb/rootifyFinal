<?php
session_start();

// Periksa apakah pengguna sudah login, jika belum, arahkan ke halaman login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../authentication/index.php"); // Redirect ke halaman login jika belum login
    exit();
}

// Sekarang Anda dapat mengakses data pengguna dari session
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Destinasi Favorit</title>
    <link rel="stylesheet" href="dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Listing content */
        .listing-content {
        padding: 20px;
        display: flex;
        flex-direction: column;
        flex: 1;
        }

        .listing-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 12px;
        color: #222;
        line-height: 1.4;
        }

        .listing-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 16px;
        color: #666;
        font-size: 14px;
        }

        /* Price section */
        .price-container {
        margin-top: auto;
        padding-top: 16px;
        }

        .price {
        font-size: 24px;
        font-weight: 600;
        color: #222;
        }

        .price-period {
        font-size: 16px;
        font-weight: normal;
        color: #666;
        }

        .price-details {
        color: #666;
        font-size: 14px;
        margin-top: 4px;
        }

        /* Buttons */
        .button-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-top: 20px;
        }

        .view-rooms-btn,
        .book-btn {
        padding: 12px 0;
        text-align: center;
        background: #333;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        }

        .footer {
        background: linear-gradient(to bottom right, #1e293b, #334155);
        color: white;
        padding: 40px 20px;
        }
        .container {
        max-width: 1200px;
        margin: 0 auto;
        }
        .footer-content {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        }
        .footer-column {
        flex: 1;
        min-width: 200px;
        margin-bottom: 20px;
        }
        .footer-column h3 {
        font-size: 18px;
        margin-bottom: 15px;
        }
        .footer-column ul {
        list-style-type: none;
        padding: 0;
        }
        .footer-column ul li {
        margin-bottom: 8px;
        }
        .footer-column a {
        color: #e2e8f0;
        text-decoration: none;
        transition: color 0.3s;
        }
        .footer-column a:hover {
        color: #ffffff;
        text-decoration: underline;
        }
        .logo {
        max-width: 180px;
        margin-bottom: 20px;
        }
        .badges {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        }
        .badge {
        background-color: white;
        width: 40px;
        height: 40px;
        border-radius: 4px;
        }
        .partner-button {
        display: inline-flex;
        align-items: center;
        background-color: #475569;
        color: white;
        padding: 10px 15px;
        border-radius: 8px;
        text-decoration: none;
        margin-bottom: 20px;
        transition: background-color 0.3s;
        }
        .partner-button:hover {
        background-color: #64748b;
        }
        .partner-button svg {
        margin-right: 8px;
        }
        .payment-partners {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
        }
        .payment-partner {
        background-color: white;
        aspect-ratio: 3/2;
        border-radius: 4px;
        }
        .social-links {
        display: flex;
        gap: 15px;
        }
        .social-links a {
        color: white;
        }
        .app-download img {
        max-width: 140px;
        border-radius: 4px;
        }
        @media (max-width: 768px) {
        .footer-content {
            flex-direction: column;
        }
        .footer-column {
            width: 100%;
        }
        }

        @media (max-width: 768px) {
        .header {
            padding: 16px;
            margin-bottom: 24px;
        }
        
        .listings {
            padding: 0 16px;
            gap: 24px;
        }

        .button-container {
            grid-template-columns: 1fr;
        }
        
        .title {
            font-size: 28px;
        }
        }

        @media (max-width: 480px) {
        .search-container {
            flex-direction: column;
        }
        
        .filters-btn {
            width: 100%;
        }
        
        .search-bar {
            width: 100%;
        }
        }
    </style>
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
                    <a href="/regristasi/login.html" class="btn">Login</a>
                    <a href="#" class="btn">Daftar</a>
                </div>
            </div>
        </div>
    </nav>

    <nav class="secondary-navbar">
        <div class="nav-container">
            <ul class="secondary-nav-links">
                <li><a href="#" class="secondary-nav-link active">Home</a></li>
                <li><a href="../akomodasi/hotels.php" class="secondary-nav-link">Akomodasi</a></li>
                <li><a href="../itinerary/itinerary.php" class="secondary-nav-link">Filter</a></li>
                <li><a href="#" class="secondary-nav-link">Makan</a></li>
                <li><a href="#" class="secondary-nav-link">Harga</a></li>
            </ul>
        </div>
    </nav>

    <div class="hero">
        <div class="hero-content">
            <h1>Grow Your Story</h1>
            <div class="search-container">
                <form class="search-form">
                    <div class="search-input">
                        <i class="fas fa-money-bill"></i>
                        <input type="number" placeholder="Budget (Rp)">
                    </div>
                    <div class="search-input">
                        <i class="fas fa-map-marker-alt"></i>
                        <select>
                            <option value="">Pilih Kabupaten</option>
                            <option value="sleman">Sleman</option>
                            <option value="bantul">Bantul</option>
                            <option value="kulonprogo">Kulon Progo</option>
                            <option value="gunungkidul">Gunung Kidul</option>
                            <option value="jogja">Yogyakarta</option>
                        </select>
                    </div>
                    <div class="search-input">
                        <i class="fas fa-users"></i>
                        <input type="number" placeholder="Jumlah Orang" min="1">
                    </div>
                    <button type="submit" class="search-button">Cari</button>
                </form>
            </div>
        </div>
    </div>

    <div class="main-container">
        <h2>Kunjungi Tempat Favoritmu Di Jogja</h2>
        <div class="destinations-wrapper">
            <div class="destination-card">
                <img src="parangtritis.jpg" class="destination-img" alt="Pantai Parangtritis">
                <p class="caption">Pantai Parangtritis</p>
            </div>
            <div class="destination-card">
                <img src="alun-alun.jpg" class="destination-img" alt="Alun-Alun Kidul">
                <p class="caption">Alun-Alun Kidul</p>
            </div>
            <div class="destination-card">
                <img src="malioboro.jpg" class="destination-img" alt="Malioboro">
                <p class="caption">Malioboro</p>
            </div>
            <div class="destination-card">
                <img src="borobudur.jpg" class="destination-img" alt="Candi Borobudur">
                <p class="caption">Candi Borobudur</p>
            </div>
            <div class="destination-card">
                <img src="prambanan.jpg" class="destination-img" alt="Candi Prambanan">
                <p class="caption">Candi Prambanan</p>
            </div>
            <div class="destination-card">
                <img src="parangtritis.jpg" class="destination-img" alt="Pantai Parangtritis">
                <p class="caption">Pantai Parangtritis</p>
            </div>
            <div class="destination-card">
                <img src="alun-alun.jpg" class="destination-img" alt="Alun-Alun Kidul">
                <p class="caption">Alun-Alun Kidul</p>
            </div>
        </div>
    </div>

    <div class="listings">
        <div class="listing-card">
            <div class="image-container">
                <img src="/api/placeholder/400/320" alt="Westrift Kretya home" class="listing-image">
                <button class="favorite-btn">♡</button>
                <div class="carousel-dots">
                    <div class="dot active"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                </div>
            </div>
            <div class="listing-content">
                <h2 class="listing-title">Westrift Kretya home living manhattan, Autography Collection</h2>
                <div class="listing-meta">
                    <span>⭐ 4.5 Rating</span>
                    <span>Bekasi</span>
                    <span>Jul 2-7</span>
                </div>
                <div class="price-container">
                    <div class="price">$140 <span class="price-period">/ Night</span></div>
                    <div class="price-details">Including taxes and fees</div>
                    <div class="button-container">
                        <a href="#" class="view-rooms-btn">View Rooms</a>
                        <a href="#" class="book-btn">Pesan</a>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="listing-card">
            <div class="image-container">
                <img src="/api/placeholder/400/320" alt="Awokewok Suites" class="listing-image">
                <button class="favorite-btn">♡</button>
                <div class="carousel-dots">
                    <div class="dot active"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                </div>
            </div>
            <div class="listing-content">
                <h2 class="listing-title">Awokewok Suites La Maison Jakarta dan Sekitarnya</h2>
                <div class="listing-meta">
                    <span>⭐ 5.0 Rating</span>
                    <span>Jakarta</span>
                    <span>Jul 2-7</span>
                </div>
                <div class="price-container">
                    <div class="price">$85 <span class="price-period">/ Night</span></div>
                    <div class="price-details">Including taxes and fees</div>
                    <div class="button-container">
                        <a href="#" class="view-rooms-btn">View Rooms</a>
                        <a href="#" class="book-btn">Pesan</a>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="listing-card">
            <div class="image-container">
                <img src="/api/placeholder/400/320" alt="The night of course Residence" class="listing-image">
                <button class="favorite-btn">♡</button>
                <div class="carousel-dots">
                    <div class="dot active"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                </div>
            </div>
            <div class="listing-content">
                <h2 class="listing-title">The night of course Residence Lembang</h2>
                <div class="listing-meta">
                    <span>⭐ 4.5 Rating</span>
                    <span>Lembang</span>
                    <span>Jul 2-7</span>
                </div>
                <div class="price-container">
                    <div class="price">$110 <span class="price-period">/ Night</span></div>
                    <div class="price-details">Including taxes and fees</div>
                    <div class="button-container">
                        <a href="detail_paket.php" class="view-rooms-btn">View Rooms</a>
                        <a href="pembayaran.php" class="book-btn">Pesan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <img src="https://dummyimage.com/180x40/000/fff&text=Traveloka" alt="Traveloka" class="logo">
                    <div class="badges">
                        <div class="badge"></div>
                        <div class="badge"></div>
                        <div class="badge"></div>
                    </div>
                    <a href="#" class="partner-button">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        Jadi Partner Traveloka
                    </a>
                    <h3>Partner Pembayaran</h3>
                    <div class="payment-partners">
                        <div class="payment-partner"></div>
                        <div class="payment-partner"></div>
                        <div class="payment-partner"></div>
                        <div class="payment-partner"></div>
                        <div class="payment-partner"></div>
                        <div class="payment-partner"></div>
                        <div class="payment-partner"></div>
                        <div class="payment-partner"></div>
                        <div class="payment-partner"></div>
                        <div class="payment-partner"></div>
                        <div class="payment-partner"></div>
                        <div class="payment-partner"></div>
                    </div>
                </div>
                <div class="footer-column">
                    <h3>Tentang Rootify</h3>
                    <ul>
                        <li><a href="#">Cara Pesan</a></li>
                        <li><a href="#">Hubungi Kami</a></li>
                        <li><a href="#">Pusat Bantuan</a></li>
                        <li><a href="#">Tentang Kami</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Produk</h3>
                    <ul>
                        <li><a href="#">Hotel</a></li>
                        <li><a href="#">Kuliner</a></li>
                        <li><a href="#">Tempat Wisata</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Lainnya</h3>
                    <ul>
                        <li><a href="#">Blog Rootify</a></li>
                        <li><a href="#">Pemberitahuan Privasi</a></li>
                        <li><a href="#">Syarat & Ketentuan</a></li>
                    </ul>
                    <h3>Follow kami di</h3>
                    <div class="social-links">
                        <a href="#" aria-label="Facebook">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                            </svg>
                        </a>
                        <a href="#" aria-label="Instagram">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                            </svg>
                        </a>
                        <a href="#" aria-label="Youtube">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path>
                                <polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon>
                            </svg>
                        </a>
                    </div>
                    <h3>Download Traveloka App</h3>
                    <a href="#" class="app-download">
                        <img src="https://dummyimage.com/140x42/000/fff&text=Google+Play" alt="Get it on Google Play">
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>