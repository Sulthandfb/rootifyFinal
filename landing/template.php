<?php 
include '../navfot/navbar.php'; 
include '../filter_wisata/db_connect.php'; 

error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rootify - Grow Your Story</title>
    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            overflow-x: hidden;
            padding-top: 80px; /* Spacing untuk navbar */
        }

        /* Hero section styles */
        .hero {
            display: flex;
            padding: 2rem 7%;
            position: relative;
            min-height: calc(100vh - 90px);
            justify-content: center;
            gap: 2rem;
            align-items: center;
        }

        .hero::after {
            content: '';
            position: absolute;
            top: -100px;
            right: -200px;
            width: 800px;
            height: 800px;
            background-color: #FFF1DA;
            border-radius: 50%;
            z-index: -1;
        }

        .hero-content {
            flex: 1;
            max-width: 600px;
            padding-right: 2rem;
            padding-top: 4rem;
        }

        .hero-image {
            flex: 1;
            position: relative;
            max-width: 500px;
            display: flex;
            justify-content: center;
        }

        .category-label {
            color: #DF6951;
            font-weight: 500;
            margin-bottom: 1rem;
        }

        .hero-title {
            font-size: 4rem;
            color: #181E4B;
            line-height: 1.2;
            margin-bottom: 2rem;
        }

        .hero-title span {
            position: relative;
        }

        .hero-title span::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 12px;
            background-color: #DF6951;
            opacity: 0.3;
            z-index: -1;
        }

        .hero-description {
            color: #5E6282;
            margin-bottom: 2rem;
            max-width: 500px;
        }

        .cta-buttons {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .find-more-btn {
            padding: 1rem 2rem;
            background-color: #F1A501;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 500;
            transition: background-color 0.3s;
        }

        .find-more-btn:hover {
            background-color: #e09600;
        }

        .play-demo-btn {
            display: flex;
            align-items: center;
            gap: 1rem;
            text-decoration: none;
            color: #686D77;
        }

        .play-icon {
            width: 52px;
            height: 52px;
            background-color: #DF6951;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .hero-image img {
            max-width: 100%;
            height: auto;
        }

        .floating-plane {
            position: absolute;
            width: 100px;
            opacity: 0.8;
        }

        .plane1 {
            top: 20%;
            right: 60%;
        }

        .plane2 {
            bottom: 30%;
            right: 10%;
        }

        /* Responsive styles */
        @media (max-width: 1024px) {
            .hero-title {
                font-size: 3rem;
            }
        }

        @media (max-width: 768px) {
            /* nav {
                padding: 1rem 5%;
            }

            .nav-links {
                display: none;
            } */

            .hero {
                flex-direction: column-reverse;
                padding: 1rem 5%;
                text-align: center;
                gap: 1rem;
            }

            .hero-content {
                padding-right: 0;
                padding-top: 2rem;
            }

            .hero-image {
                max-width: 300px;
                margin: 0 auto;
            }

            .cta-buttons {
                justify-content: center;
            }

            .hero::after {
                width: 400px;
                height: 400px;
                right: -100px;
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 2rem;
            }

            .cta-buttons {
                flex-direction: column;
            }

            .nav-buttons {
                display: none;
            }
        }
    </style>
</head>
<body>
    <section class="hero">
        <div class="hero-content">
            <p class="category-label">Best Destinations in Yogyakarta</p>
            <h1 class="hero-title">Travel, <span>enjoy</span> and live a new and full life</h1>
            <p class="hero-description">Built your story at Yogyakarta</p>
            <div class="cta-buttons">
                <a href="../attractions/attractions-search.php" class="find-more-btn">Find out more</a>
                <a href="https://youtu.be/9_FdcMhQwrI" class="play-demo-btn">
                    <div class="play-icon">
                        <svg width="12" height="14" viewBox="0 0 12 14" fill="none">
                            <path d="M1 2L10 7L1 12V2Z" stroke="white" stroke-width="2"/>
                        </svg>
                    </div>
                    Play Demo
                </a>
            </div>
        </div>
        <div class="hero-image">
            <img src="../img/Maskot.png" alt="Traveler with backpack">
        </div>
    </section>
    <!-- Previous code remains unchanged, add this section after the hero section -->
<section class="services">
    <div class="container">
        <p class="category">CATEGORY</p>
        <h2 class="section-title">We Offer Best Services</h2>
        <div class="services-grid">
            <div class="service-card">
                <div class="service-icon">
                    <img src="../img/destination.svg" alt="Weather Icon" width="80" height="80">
                </div>
                <h3>Trip Planner</h3>
                <ng>Build and share engaging and fun journeys with rootify  </p>
            </div>
            <div class="service-card">
                <div class="service-icon">
                    <img src="../img/traveler.svg" alt="Flight Icon" width="80" height="80">
                </div>
                <h3>Package Tour</h3>
                <p>Offer attractive and satisfying tour packages to users </p>
            </div>
            <div class="service-card">
                <div class="service-icon">
                    <img src="../img/flight.svg" alt="Events Icon" width="80" height="80">
                </div>
                <h3>Accommodation</h3>
                <p>Provide comfortable, clean and best accommodation.</p>
            </div>
        </div>
    </div>
</section>

<style>
/* Add these styles to your existing CSS */
.services {
    padding: 6rem 7%;
    text-align: center;
    background-color:rgb(255, 255, 255);
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
}

.category {
    color: #5E6282;
    font-size: 1.125rem;
    margin-bottom: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
}

.section-title {
    color: #14183E;
    font-size: 3.125rem;
    margin-bottom: 4rem;
    font-weight: bold;
    line-height: 1.2;
}

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2.5rem;
    padding: 1rem;
}

.service-card {
    background: #FFFFFF;
    padding: 2.5rem 2rem;
    border-radius: 36px;
    transition: all 0.3s ease;
    position: relative;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
}

.service-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

.service-icon {
    width: 100px;
    height: 100px;
    margin: 0 auto 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #FFF1DA;
    border-radius: 20px;
    transition: all 0.3s ease;
    overflow: hidden;
}

.service-icon img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    transition: all 0.3s ease;
}

.service-card:hover .service-icon {
    background: #DF6951;
}

/* Menghapus filter brightness dan invert pada hover */
.service-card:hover .service-icon img {
    transform: scale(1.1);
}

.service-card h3 {
    color: #1E1D4C;
    font-size: 1.375rem;
    margin-bottom: 1rem;
    font-weight: 600;
}

.service-card p {
    color: #5E6282;
    font-size: 1rem;
    line-height: 1.6;
    margin: 0;
}

@media (max-width: 768px) {
    .services {
        padding: 4rem 5%;
    }

    .section-title {
        font-size: 2.5rem;
        margin-bottom: 3rem;
    }

    .services-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
    }
    
    .service-icon {
        width: 90px;
        height: 90px;
    }
    
    .service-icon img {
        width: 70px;
        height: 70px;
    }
}

@media (max-width: 480px) {
    .services {
        padding: 3rem 5%;
    }

    .section-title {
        font-size: 2rem;
        margin-bottom: 2.5rem;
    }

    .service-card {
        padding: 2rem 1.5rem;
    }
    
    .service-icon {
        width: 80px;
        height: 80px;
        margin-bottom: 1.5rem;
    }
    
    .service-icon img {
        width: 60px;
        height: 60px;
    }
}
</style>

<section class="choose-destination">
    <div class="overlay">
        <div class="content">
            <h2>Grow Your Story</h2>
            <p>Make your travel plans while in Yogyakarta and share your travel stories with Rootify</p>
        </div>
    </div>
</section>

<style>
/* New choose destination section styles */
.choose-destination {
    position: relative; 
    width: 100%; 
    height: 300px;
    background-image: url('../img/indrayanti.jpg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    z-index: 1; 
}

.overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
}

.content {
    max-width: 800px;
    padding: 0 20px;
}

.content h2 {
    color: #FFFFFF;
    font-size: 3rem;
    margin-bottom: 1rem;
    font-weight: 600;
}

.content p {
    color: rgba(255, 255, 255, 0.9);
    font-size: 1.1rem;
    margin-bottom: 2rem;
}

.search-container {
    max-width: 500px;
    margin: 0 auto;
}

.search-input {
    width: 100%;
    padding: 1rem 1.5rem;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
    background: #FFFFFF;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.search-input:focus {
    outline: none;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.2);
}

@media (max-width: 768px) {
    .content h2 {
        font-size: 2rem;
    }
    
    .content p {
        font-size: 1rem;
    }
}

@media (max-width: 480px) {
    .choose-destination {
        height: 300px;
    }

    .content h2 {
        font-size: 1.5rem;
    }

    .content p {
        font-size: 0.9rem;
    }
}
</style>

<!-- Add this section after the services section -->
<section class="destinations">
    <div class="container">
        <p class="subtitle">Favorite Destinations</p>
        <h2 class="section-title" style="text-align: center;">Top Destinations in Yogyakarta</h2>
        
        <div class="destinations-grid">
            <a href="#" class="destination-card">
                <div class="destination-image">
                    <img src="../img/borobudur.jpg" alt="Borobudur Temple">
                </div>
                <div class="destination-info">
                    <div class="destination-header">
                        <h3>Candi Borobudur</h3>
                        <span class="price">Rp 750k</span>
                    </div>
                    <div class="trip-duration">
                        <svg class="plane-icon" width="18" height="18" viewBox="0 0 18 18" fill="none">
                            <path d="M16.5 2.25L8.25 10.5" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16.5 2.25L11.25 16.5L8.25 10.5L2.25 7.5L16.5 2.25Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>1 Day Trip</span>
                    </div>
                </div>
            </a>

            <a href="#" class="destination-card">
                <div class="destination-image">
                    <img src="../img/malioboro.jpg" alt="Malioboro Street">
                </div>
                <div class="destination-info">
                    <div class="destination-header">
                        <h3>Malioboro Road</h3>
                        <span class="price">Rp 500k</span>
                    </div>
                    <div class="trip-duration">
                        <svg class="plane-icon" width="18" height="18" viewBox="0 0 18 18" fill="none">
                            <path d="M16.5 2.25L8.25 10.5" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16.5 2.25L11.25 16.5L8.25 10.5L2.25 7.5L16.5 2.25Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>Half Day Trip</span>
                    </div>
                </div>
            </a>

            <a href="#" class="destination-card">
                <div class="destination-image">
                    <img src="../img/prambanan.jpg" alt="Prambanan Temple">
                </div>
                <div class="destination-info">
                    <div class="destination-header">
                        <h3>Candi Prambananan</h3>
                        <span class="price">Rp 650k</span>
                    </div>
                    <div class="trip-duration">
                        <svg class="plane-icon" width="18" height="18" viewBox="0 0 18 18" fill="none">
                            <path d="M16.5 2.25L8.25 10.5" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16.5 2.25L11.25 16.5L8.25 10.5L2.25 7.5L16.5 2.25Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>1 Day Trip</span>
                    </div>
                </div>
            </a>
        </div>
    </div>
</section>

<style>
/* Add these styles to your existing CSS */
.destinations {
    padding: 6rem 7%;
    background-color: #FFFFFF;
}

.subtitle {
    color: #5E6282;
    font-size: 1.125rem;
    text-align: center;
    margin-bottom: 1rem;
}

.destinations-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    padding: 2rem 0;
}

.destination-card {
    background: #FFFFFF;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 20px 30px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    text-decoration: none;
    color: inherit;
    display: block;
}

.destination-card:hover {
    box-shadow: 0 25px 35px rgba(0, 0, 0, 0.15);
    
    .destination-header h3,
    .price,
    .trip-duration,
    .plane-icon path {
        color: #F1A501;
        transition: color 0.3s ease;
    }

    .plane-icon path {
        stroke: #F1A501;
        transition: stroke 0.3s ease;
    }
}


.destination-image {
    width: 100%;
    height: 300px;
    overflow: hidden;
}

.destination-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.destination-info {
    padding: 1.5rem;
}

.destination-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.destination-header h3,
.price,
.trip-duration,
.plane-icon path {
    transition: color 0.3s ease, stroke 0.3s ease;
}

.destination-header h3 {
    font-size: 1.25rem;
    color: #1E1D4C;
    font-weight: 600;
}

.price {
    color: #5E6282;
    font-weight: 500;
}

.trip-duration {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #5E6282;
    font-size: 0.95rem;
}

.plane-icon {
    width: 18px;
    height: 18px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .destinations {
        padding: 4rem 5%;
    }

    .destinations-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }
}

@media (max-width: 480px) {
    .destinations {
        padding: 3rem 5%;
    }

    .destination-image {
        height: 250px;
    }
}
</style>

<div class="features-container">
    <div class="feature-row">
        <div class="feature-item">
            <div class="feature-icon">
                <img src="../img/kiosk.svg" alt="Affordable Pricing">
            </div>
            <div class="feature-content">
                <h3>Affordable Pricing</h3>
                <p>Experience incredible travel at competitive rates. We offer a wide range of customizable packages designed to fit your budget while ensuring exceptional value for your journey.</p>
            </div>
        </div>
    </div>

    <div class="feature-row">
        <div class="feature-item reverse">
            <div class="feature-content">
                <h3>Trusted Experience</h3>
                <p>As a leading travel platform, we've proudly served thousands of travelers worldwide, maintaining high satisfaction rates and consistently receiving positive reviews from our valued customers.</p>
            </div>
            <div class="feature-icon">
                <img src="../img/family-trip.svg" alt="Trusted Service">
            </div>
        </div>
    </div>

    <div class="feature-row">
        <div class="feature-item">
            <div class="feature-icon">
                <img src="../img/business.svg" alt="Premium Service">
            </div>
            <div class="feature-content">
                <h3>Premium Service</h3>
                <p>Our dedicated team provides round-the-clock support to ensure your travel experience is seamless and memorable. We're committed to exceeding your expectations at every step.</p>
            </div>
        </div>
    </div>
</div>
<style>
.features-container {
    max-width: 1200px;
    margin: 6rem auto;
    padding: 0 2rem;
    background-color: #ffffff;
}

.feature-row {
    margin-bottom: 4rem;
}

.feature-row:last-child {
    margin-bottom: 0;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 4rem;
    width: 100%;
    max-width: 1000px;
    padding: 3rem;
    margin: 0 auto;
    background: #ffffff;
    border-radius: 24px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.feature-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
}

.feature-item.reverse {
    flex-direction: row-reverse;
}

.feature-icon {
    flex-shrink: 0;
    width: 160px;
    height: 160px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    border-radius: 20px;
    padding: 2rem;
    transition: all 0.3s ease;
}

.feature-item:hover .feature-icon {
    background: #f0f2f5;
    transform: scale(1.05);
}

.feature-icon img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: all 0.3s ease;
}

.feature-content {
    flex: 1;
}

.feature-content h3 {
    color: #2d3436;
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    position: relative;
    padding-bottom: 1rem;
}

.feature-content h3::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 60px;
    height: 4px;
    background: #DF6951;
    border-radius: 2px;
}

.feature-content p {
    color: #636e72;
    line-height: 1.8;
    font-size: 1.1rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .features-container {
        margin: 4rem auto;
        padding: 0 1.5rem;
    }

    .feature-item {
        flex-direction: column;
        text-align: center;
        gap: 2rem;
        padding: 2rem;
    }

    .feature-item.reverse {
        flex-direction: column;
    }

    .feature-icon {
        width: 120px;
        height: 120px;
        padding: 1.5rem;
    }

    .feature-content h3 {
        font-size: 1.75rem;
    }

    .feature-content h3::after {
        left: 50%;
        transform: translateX(-50%);
    }

    .feature-content p {
        font-size: 1rem;
    }
}

@media (max-width: 480px) {
    .features-container {
        margin: 3rem auto;
        padding: 0 1rem;
    }

    .feature-item {
        padding: 1.5rem;
    }

    .feature-icon {
        width: 100px;
        height: 100px;
        padding: 1rem;
    }

    .feature-content h3 {
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
}
</style>

</style>
<!-- Add this section after the destinations section -->
<section class="packages">
    <div class="container">
        <h2 class="section-title">Our Tour Packages</h2>
        <!-- Previous packages section code remains the same -->
        <div class="packages-grid">
            <?php
            // Fetch tour packages from the database
            $sql = "SELECT * FROM tourist_packets LIMIT 3";
            $result = $db->query($sql);
            
            $images = [
                '../img/borobudur.jpg',
                '../img/malioboro.jpg',
                '../img/ullensentalu.jpg'
            ];
            
            $i = 0; // Counter for images array
            while ($package = $result->fetch_assoc()):
            ?>
            <div class="package-card">
                <div class="package-image">
                    <img src="<?php echo $images[$i]; ?>" alt="<?php echo htmlspecialchars($package['name'] ?? 'Tour Package'); ?>">
                    <button class="favorite-btn">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                    </button>
                </div>
                <div class="package-info">
                    <h3><?php echo htmlspecialchars($package['name'] ?? 'Package Name'); ?></h3>
                    <div class="rating">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="#FFD700">
                            <path d="M8 0l2.5 5 5.5.8-4 3.9 1 5.3L8 12.5 3 15l1-5.3-4-3.9 5.5-.8z"/>
                        </svg>
                        <span><?php echo number_format($package['rating'] ?? 0, 1); ?> Rating</span>
                        <span class="location"><?php echo htmlspecialchars($package['meeting_point'] ?? 'Location'); ?></span>
                        <span class="dates"><?php echo htmlspecialchars($package['duration'] ?? 'Duration'); ?></span>
                    </div>
                    <div class="price">
                        <h4>Rp <?php echo number_format($package['discounted_price'] ?? 0, 0, ',', '.'); ?>,-</h4>
                        <p>Including taxes and fees</p>
                    </div>
                    <div class="package-buttons">
                        <a href="detail_paket.php?id=<?php echo $package['packet_id']; ?>" class="view-btn">View Details</a>
                        <a href="pembayaran.php?type=package&id=<?php echo $package['packet_id']; ?>" class="book-btn">Book Now</a>
                    </div>
                </div>
            </div>
            <?php 
            $i++; // Increment counter
            endwhile; 
            ?>
        </div>
    </div>
</section>

<style>
/* Add these styles to your existing CSS */
.packages {
    padding: 6rem 7%;
    background-color:rgb(255, 255, 255);
}

.packages-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.package-card {
    background: #FFFFFF;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.package-image {
    position: relative;
    height: 200px;
}

.package-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.favorite-btn {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: white;
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.package-info {
    padding: 1.5rem;
}

.package-info h3 {
    font-size: 1.25rem;
    color: #1E1D4C;
    margin-bottom: 1rem;
}

.rating {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #5E6282;
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

.location, .dates {
    position: relative;
    padding-left: 0.5rem;
}

.location:before, .dates:before {
    content: '•';
    position: absolute;
    left: -0.2rem;
}

.price {
    margin-bottom: 1.5rem;
}

.price h4 {
    font-size: 1.5rem;
    color: #1E1D4C;
    margin-bottom: 0.25rem;
}

.price p {
    font-size: 0.875rem;
    color: #5E6282;
}

.package-buttons {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.view-btn, .book-btn {
    padding: 0.75rem;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.3s;
}

.view-btn {
    background: #F9F9F9;
    border: 1px solid #E7E7E7;
    color: #1E1D4C;
}

.book-btn {
    background: #1E1D4C;
    border: none;
    color: white;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .packages {
        padding: 4rem 5%;
    }

    .packages-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }
}

@media (max-width: 480px) {
    .packages {
        padding: 3rem 5%;
    }

    .package-buttons {
        grid-template-columns: 1fr;
    }
}
</style>

<!-- 
<style>
        .review-container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 600px;
            padding: 20px;
        }

        .review-container h2 {
            margin: 0;
            margin-bottom: 20px;
            color: #333333;
            text-align: center;
        }

        .review-form {
            display: flex;
            flex-direction: column;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
margin-bottom: 5px;
            font-weight: bold;
            color: #555555;
        }

        input, textarea, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #dddddd;
            border-radius: 5px;
            font-size: 14px;
        }

        textarea {
            resize: none;
        }

        .review-list {
            margin-top: 30px;
        }

        .review-item {
            background-color: #f4f4f4;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .review-item strong {
            color: #333333;
            font-size: 16px;
        }

        .review-item .rating {
            color: #f4c542;
            font-size: 14px;
        }

        .review-item p {
            margin: 5px 0 0 0;
            color: #555555;
            font-size: 14px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px;
            cursor: pointer;
            font-size: 16px;
        }

        button:active {
            background-color: #45a049;
        }

    </style>
</head>
<body>
    <div class="review-container">
        <h2>Leave a Review</h2>
        <form class="review-form" id="reviewForm">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" required>
            </div>
            <div class="form-group">
                <label for="rating">Rating</label>
                <select id="rating" required>
                    <option value="">Select Rating</option>
                    <option value="1">1 Star</option>
                    <option value="2">2 Stars</option>
                    <option value="3">3 Stars</option>
                    <option value="4">4 Stars</option>
                    <option value="5">5 Stars</option>
                </select>
            </div>
            <div class="form-group">
                <label for="comment">Comment</label>
                <textarea id="comment" rows="4" required></textarea>
            </div>
            <button type="submit">Submit Review</button>
        </form>

        <div class="review-list" id="reviewList">
            Reviews will be appended here
        </div>
    </div>

    <script>
        const reviewForm = document.getElementById('reviewForm');
        const reviewList = document.getElementById('reviewList');

        reviewForm.addEventListener('submit', (event) => {
            event.preventDefault();

            const username = document.getElementById('username').value;
            const rating = document.getElementById('rating').value;
            const comment = document.getElementById('comment').value;

            if (username && rating && comment) {
                const reviewItem = document.createElement('div');
                reviewItem.classList.add('review-item');
                reviewItem.innerHTML = `
                    <strong>${username}</strong> <span class="rating">${'⭐'.repeat(rating)}</span>
                    <p>${comment}</p>
                `;
                reviewList.appendChild(reviewItem);

                reviewForm.reset();
            } else {
                alert('Please fill out all fields!');
            }
        });
    </script>   
    <style>
        .comments-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .title {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 24px;
        }

        .comment {
            border-bottom: 1px solid #eee;
            padding: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: #2563eb;
            margin-right: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: white;
        }

        .username {
            font-weight: bold;
            color: #333;
        }

        .rating {
            display: flex;
            gap: 2px;
            margin: 8px 0;
        }

        .star {
            color: #fbbf24;
            font-size: 18px;
        }

        .comment-text {
            color: #555;
            line-height: 1.5;
        }

        .date {
            color: #888;
            font-size: 0.9em;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="comments-container">
        <h2 class="title">Ulasan Pelanggan</h2>
        
        <div class="comment">
            <div class="user-info">
                <div class="avatar">S</div>
                <div>
                    <div class="username">Sarah</div>
                    <div class="rating">
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                    </div>
                </div>
            </div>
            <div class="comment-text">
                Sangat puas dengan produk ini! Kualitasnya luar biasa dan pengirimannya cepat.
            </div>
            <div class="date">23 Dec 2024</div>
        </div>

        <div class="comment">
            <div class="user-info">
                <div class="avatar" style="background: #0891b2;">B</div>
                <div>
                    <div class="username">Budi</div>
                    <div class="rating">
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star" style="color: #ddd;">★</span>
                    </div>
                </div>
            </div>
            <div class="comment-text">
                Produknya bagus, sesuai dengan deskripsi. Pelayanan ramah dan responsif.
            </div>
            <div class="date">22 Dec 2024</div>
        </div>

        <div class="comment">
            <div class="user-info">
                <div class="avatar" style="background: #059669;">R</div>
                <div>
                    <div class="username">Rina</div>
                    <div class="rating">
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                    </div>
                </div>
            </div>
            <div class="comment-text">
                Rekomendasi banget! Harganya worth it dengan kualitas yang didapat.
            </div>
            <div class="date">21 Dec 2024</div>
        </div>
    </div>


<?php include '../chatbot/chatbot.php'; ?>
<?php include '../navfot/footer.php'; ?>
<style>
/* Footer styles */
.footer {
    background-color: #1E1D4C;
    color: #FFFFFF;
    padding: 5rem 7% 2rem;
}

.footer-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 3rem;
    margin-bottom: 3rem;
}

.footer-column h3 {
    font-size: 1.25rem;
    margin-bottom: 1.5rem;
    font-weight: 500;
}

.footer-column h3 span {
    color: #F1A501;
}

.footer-column p {
    color: rgba(255, 255, 255, 0.7);
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.social-links {
    display: flex;
    gap: 1rem;
}

.social-link {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #FFFFFF;
    transition: background-color 0.3s;
}

.social-link:hover {
    background-color: #F1A501;
}

.footer-links {
    list-style: none;
    padding: 0;
}

.footer-links li {
    margin-bottom: 0.75rem;
}

.footer-links a {
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    transition: color 0.3s;
    display: flex;
    align-items: center;
}

.footer-links a:before {
    content: '→';
    margin-right: 0.5rem;
}

.contact-info {
    list-style: none;
    padding: 0;
}

.contact-info li {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1rem;
    color: rgba(255, 255, 255, 0.7);
}

.contact-info svg {
    flex-shrink: 0;
    stroke: #F1A501;
}

.footer-bottom {
    text-align: center;
    padding-top: 2rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.875rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .footer {
        padding: 4rem 5% 2rem;
    }

    .footer-content {
        gap: 2rem;
    }
}

@media (max-width: 480px) {
    .footer {
        padding: 3rem 5% 2rem;
    }

    .footer-column {
        text-align: center;
    }

    .social-links {
        justify-content: center;
    }

    .contact-info li {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
}
</style>

    <script>
        // Add smooth scrolling to navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>

