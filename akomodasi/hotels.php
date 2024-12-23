<?php
include '../filter_wisata/db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css"
      rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css"
      rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="hotels.css" />
    <title>Web Design Mastery | Rayal Park</title>
  </head>
  <body>
    <header class="header">
      <nav>
        <div class="nav__bar">
          <div class="logo">
            <a href="#"><img src="../img/logo1.png" alt="logo" /></a>
          </div>
          <div class="nav__menu__btn" id="menu-btn">
            <i class="ri-menu-line"></i>
          </div>
        </div>
        <ul class="nav__links" id="nav-links">
          <li><a href="../landing/dashboard.php">Home</a></li>
          <li><a href="#about">About</a></li>
          <li><a href="#service">Services</a></li>
          <li><a href="#explore">Explore</a></li>
          <li><a href="#contact">Contact</a></li>
        </ul>
        <button class="btn nav__btn">Login</button>
      </nav>
      <div class="section__container header__container" id="home">
        <p>Simple - Unique - Friendly</p>
        <h1>Create a Story of Your <br />Journey with <span>Rootify</span>.</h1>
      </div>
    </header>

    <section class="section__container booking__container">
      <form action="../akomodasi/hotels-search.php" method="POST" class="booking__form">
        <div class="input__group">
            <span><i class="ri-calendar-2-fill"></i></span>
            <div>
                <label for="check-in">CHECK-IN</label>
                <input type="date" id="startDate" name="startDate" required>
            </div>
        </div>
        <div class="input__group">
            <span><i class="ri-calendar-2-fill"></i></span>
            <div>
                <label for="check-out">CHECK-OUT</label>
                <input type="date" id="endDate" name="endDate" required>
            </div>
        </div>
        <div class="input__group input__btn">
            <button class="btn" type="submit" name="submit">PLANNING</button>
        </div>
      </form>
    </section>    

    <section class="section__container about__container" id="about">
      <div class="about__image">
        <img src="../img/recept.jpg" alt="about" />
      </div>
      <div class="about__content">
        <p class="section__subheader">ABOUT US</p>
        <h2 class="section__header">The Best Holidays Start Here!</h2>
        <p class="section__description">
          With a focus on quality accommodations, personalized experiences, and
          seamless booking, our platform is dedicated to ensuring that every
          traveler embarks on their dream holiday with confidence and
          excitement.
        </p>
        <div class="about__btn">
          <button class="btn">Read More</button>
        </div>
      </div>
    </section>

    <section class="section__container room__container">
      <p class="section__subheader">OUR LIVING ROOM</p>
      <h2 class="section__header">The Most Memorable Rest Time Starts Here.</h2>
      <div class="room__grid">
      <?php
        include '../filter_wisata/db_connect.php'; // Hubungkan database

        // Query untuk mengambil data dari tabel
        $sql = "SELECT name, description, rating, price, category, image_url FROM hotels LIMIT 3";
        $result = $db->query($sql); // Pastikan Anda menggunakan $db dari db_connect.php

        if ($result && $result->num_rows > 0) {
            // Tampilkan data menggunakan perulangan
            while($row = $result->fetch_assoc()) {
                echo '
                <div class="room__card">
                  <div class="room__card__image">
                    <img src="'.htmlspecialchars($row['image_url']).'" alt="room" />
                    <div class="room__card__icons">
                      <span class="rating"><i class="ri-star-fill"></i> '.htmlspecialchars($row['rating']).'</span>
                    </div>
                  </div>
                  <div class="room__card__details">
                    <h4>'.htmlspecialchars($row['name']).'</h4>
                    <span class="room-category '.htmlspecialchars($row['category']).'">
                      '.htmlspecialchars($row['category']).'
                    </span>
                    <p>'.htmlspecialchars($row['description']).'</p>
                    <h5>Price <span>IDR '.number_format($row['price']).'</span></h5>
                    <button class="btn">Book Now</button>
                  </div>
                </div>';
            }
        } else {
            echo "<p>No rooms available at the moment.</p>";
        }
        $db->close();
      ?>
      </div>
    </section>

    <section class="service" id="service">
      <div class="section__container service__container">
        <div class="service__content">
          <p class="section__subheader">SERVICES</p>
          <h2 class="section__header">Strive Only For The Best.</h2>
          <ul class="service__list">
            <li>
              <span><i class="ri-shield-star-line"></i></span>
              High Class Security
            </li>
            <li>
              <span><i class="ri-24-hours-line"></i></span>
              24 Hours Room Service
            </li>
            <li>
              <span><i class="ri-headphone-line"></i></span>
              Conference Room
            </li>
            <li>
              <span><i class="ri-map-2-line"></i></span>
              Tourist Guide Support
            </li>
          </ul>
        </div>
      </div>
    </section>

    <section class="section__container banner__container">
      <div class="banner__content">
        <div class="banner__card">
          <h4>25+</h4>
          <p>Properties Available</p>
        </div>
        <div class="banner__card">
          <h4>350+</h4>
          <p>Bookings Completed</p>
        </div>
        <div class="banner__card">
          <h4>600+</h4>
          <p>Happy Customers</p>
        </div>
      </div>
    </section>

    <section class="explore" id="explore">
      <p class="section__subheader">EXPLORE</p>
      <h2 class="section__header">What's New Today.</h2>
      <div class="explore__bg">
        <div class="explore__content">
          <p class="section__description">10th MAR 2023</p>
          <h4>A New Menu Is Available In Our Hotel.</h4>
          <button class="btn">Continue</button>
        </div>
      </div>
    </section>


    <?php include '../chatbot/chatbot.php'; ?>
    <script src="https://unpkg.com/scrollreveal"></script> 
    <script src="../js/hotel.js"></script>
    <?php include '../navfot/footer.php'; ?>
  </body>
</html>
