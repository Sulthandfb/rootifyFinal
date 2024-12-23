<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Destinasi Favorit</title>
    <link rel="icon" type="image/png" href="../img/logo.png">
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .review-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        .review-cards-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .review-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .review-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .reviewer-profile {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .reviewer-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-right: 15px;
            border: 2px solid #4a5568;
        }

        .reviewer-info h4 {
            margin: 0 0 5px 0;
            color: #333;
        }

        .rating {
            color: #fbbf24;
        }

        .review-text {
            color: #666;
            font-style: italic;
            line-height: 1.6;
        }

        @media (max-width: 768px) {
            .review-cards-container {
                flex-direction: column;
                align-items: center;
            }

            .review-card {
                width: 100%;
                max-width: 400px;
            }
        }

        .user-profile {
            position: relative;
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            margin-right: 8px;
        }

        .user-name {
            font-weight: 500;
            color: #333;
        }

        .user-dropdown {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background-color: white;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 10;
        }

        .user-profile:hover .user-dropdown {
            display: block;
        }

        .user-dropdown a {
            display: block;
            padding: 8px 16px;
            color: #4a5568;
            text-decoration: none;
        }

        .user-dropdown a:hover {
            background-color: #f7fafc;
        }
    </style>
</head>
<body>
    <?php
    session_start();
    ?>
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

    <nav class="secondary-navbar">
        <div class="nav-container">
            <ul class="secondary-nav-links">
                <li><a href="#" class="secondary-nav-link active">Home</a></li>
                <li><a href="../akomodasi/hotels.php" class="secondary-nav-link">Akomodasi</a></li>
                <li><a href="../itinerary/itinerary.php" class="secondary-nav-link">Itinerary</a></li>
                <li><a href="#" class="secondary-nav-link">Tempat Populer</a></li>
            </ul>
        </div>
    </nav>

    <div class="popular-destinations">
        <h2>Wisata Yang Sering Dikunjungi</h2>
        <div class="destination-gallery">
            <div class="destination-item">
                <img src="parangtritis.jpg" alt="Pantai Parangtritis" class="destination-image">
                <p>Pantai Parangtritis</p>
                <p class="destination-description">Salah satu pantai terkenal di Yogyakarta, terkenal dengan ombaknya yang besar dan pemandangan sunset yang indah.</p>
            </div>
            <div class="destination-item">
                <img src="borobudur.jpg" alt="Candi Borobudur" class="destination-image">
                <p>Candi Borobudur</p>
                <p class="destination-description">Candi Buddha terbesar di dunia, yang merupakan situs warisan dunia UNESCO dan terkenal dengan arsitekturnya yang megah.</p>
            </div>
            <div class="destination-item">
                <img src="malioboro.jpg" alt="Malioboro" class="destination-image">
                <p>Malioboro</p>
                <p class="destination-description">Jalan terkenal di Yogyakarta yang dipenuhi dengan toko-toko, restoran, dan kerajinan lokal.</p>
            </div>
            <div class="destination-item">
                <img src="prambanan.jpg" alt="Candi Prambanan" class="destination-image">
                <p>Candi Prambanan</p>
                <p class="destination-description">Kompleks candi Hindu terbesar di Indonesia, terkenal dengan arsitektur yang indah dan cerita Ramayana yang diukir di dindingnya.</p>
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

    <div class="header">
        <h1 class="title">INI BAGIAN PAKET<br>PAKAI PHP</h1>
        <div class="search-container">
            <div class="search-bar">
                <input type="text" placeholder="Search by Location">
            </div>
            <button class="filters-btn">Filters</button>
        </div>
    </div>
    
    <div class="listings">
        <div class="listing-card">
            <div class="image-container">
                <img src="../img/klotok.jpg" alt="Westrift Kretya home" class="listing-image">
                <button class="favorite-btn">♡</button>
                <div class="carousel-dots">
                    <div class="dot active"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                </div>
            </div>
            <div class="listing-content">
                <h2 class="listing-title">Paket Hemat</h2>
                <div class="listing-meta">
                    <span>⭐ 4.5 Rating</span>
                    <span>Bekasi</span>
                    <span>Jul 2-7</span>
                </div>
                <div class="price-container">
                    <div class="price">Rp. 350.000,-<span class="price-period"></span></div>
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
                <img src="../img/klotok.jpg" alt="Awokewok Suites" class="listing-image">
                <button class="favorite-btn">♡</button>
                <div class="carousel-dots">
                    <div class="dot active"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                </div>
            </div>
            <div class="listing-content">
                <h2 class="listing-title">Paket Menuju Indonesia Emas</h2>
                <div class="listing-meta">
                    <span>⭐ 5.0 Rating</span>
                    <span>Jakarta</span>
                    <span>Jul 2-7</span>
                </div>
                <div class="price-container">
                    <div class="price">Rp. 300.000,- <span class="price-period"></span></div>
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
                <img src="../img/klotok.jpg" alt="The night of course Residence" class="listing-image">
                <button class="favorite-btn">♡</button>
                <div class="carousel-dots">
                    <div class="dot active"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                </div>
            </div>
            <div class="listing-content">
                <h2 class="listing-title">Paket Selalu Bahagia</h2>
                <div class="listing-meta">
                    <span>⭐ 4.5 Rating</span>
                    <span>Lembang</span>
                    <span>Jul 2-7</span>
                </div>
                <div class="price-container">
                    <div class="price">Rp. 450.00,-<span class="price-period"></span></div>
                    <div class="price-details">Including taxes and fees</div>
                    <div class="button-container">
                        <a href="detail_paket.php" class="view-rooms-btn">View Rooms</a>
                        <a href="pembayaran.php" class="book-btn">Pesan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="features-container">
        <div class="feature-row">
            <div class="feature-item">
                <div class="feature-icon">
                    <img src="logo1.png" alt="Harga Murah">
                </div>
                <div class="feature-content">
                    <h3>Harga Terjangkau</h3>
                    <p>Nikmati perjalanan wisata dengan harga yang kompetitif dan terjangkau. Kami menawarkan berbagai pilihan paket yang sesuai dengan budget Anda.</p>
                </div>
            </div>
        </div>

        <div class="feature-row reverse">
            <div class="feature-item">
                <div class="feature-content">
                    <h3>Terpercaya</h3>
                    <p>Sebagai platform wisata terpercaya, kami telah melayani ribuan wisatawan dengan tingkat kepuasan yang tinggi dan review positif.</p>
                </div>
                <div class="feature-icon">
                    <img src="logo1.png" alt="Terpercaya">
                </div>
            </div>
        </div>

        <div class="feature-row">
            <div class="feature-item">
                <div class="feature-icon">
                    <img src="logo1.png" alt="Pelayanan Terbaik">
                </div>
                <div class="feature-content">
                    <h3>Pelayanan Terbaik</h3>
                    <p>Tim kami siap memberikan pelayanan terbaik 24/7 untuk memastikan perjalanan Anda menyenangkan dan tak terlupakan.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="review-container">
        <h2>Review Aplikasi</h2>
        <div class="review-cards-container">
            <div class="review-card">
                <div class="reviewer-profile">
                    <img src="ugm.png" alt="User" class="reviewer-avatar">
                    <div class="reviewer-info">
                        <h4>John Doe</h4>
                        <div class="rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </div>
                <p class="review-text">"Aplikasi yang sangat membantu untuk merencanakan liburan di Jogja. Paket wisatanya lengkap dan harganya terjangkau!"</p>
            </div>

            <div class="review-card">
                <div class="reviewer-profile">
                    <img src="ugm.png" alt="User" class="reviewer-avatar">
                    <div class="reviewer-info">
                        <h4>Jane Smith</h4>
                        <div class="rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                <p class="review-text">"Pelayanan sangat baik dan responsif. Tour guide-nya ramah dan professional. Recommended!"</p>
            </div>

            <div class="review-card">
                <div class="reviewer-profile">
                    <img src="ugm.png" alt="User" class="reviewer-avatar">
                    <div class="reviewer-info">
                        <h4>David Wilson</h4>
                        <div class="rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                        </div>
                    </div>
                </div>
                <p class="review-text">"Interface-nya user friendly dan mudah digunakan. Banyak pilihan destinasi wisata yang menarik."</p>
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
    <?php include '../chatbot/chatbot.php'; ?>

    <script src="script.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const userProfile = document.querySelector('.user-profile');
    const userDropdown = document.querySelector('.user-dropdown');

    if (userProfile && userDropdown) {
        userProfile.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.style.display = userDropdown.style.display === 'block' ? 'none' : 'block';
        });

        document.addEventListener('click', function() {
            userDropdown.style.display = 'none';
        });

        userDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
});
</script>
</body>
</html>

