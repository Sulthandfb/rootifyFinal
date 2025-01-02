<?php
include '../navfot/navbar.php';
session_start();
include '../filter_wisata/db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="#" />
    <style>
      @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap");

      :root {
        --primary-color: #f85616;
        --primary-color-dark: #cc3a00;
        --text-dark: #0c0a09;
        --text-light: #78716c;
        --white: #ffffff;
        --max-width: 1200px;
      }

      * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
      }

      .section__container {
        max-width: var(--max-width);
        margin: auto;
        padding: 5rem 1rem;
      }

      .section__subheader {
        margin-bottom: 0.5rem;
        position: relative;
        font-weight: 500;
        letter-spacing: 2px;
        color: var(--text-dark);
      }

      .section__subheader::after {
        position: absolute;
        content: "";
        top: 50%;
        transform: translate(1rem, -50%);
        height: 2px;
        width: 4rem;
        background-color: var(--primary-color);
      }

      .section__header {
        max-width: 600px;
        margin-bottom: 1rem;
        font-size: 2.5rem;
        font-weight: 600;
        line-height: 3rem;
        color: var(--text-dark);
      }

      .section__description {
        max-width: 600px;
        margin-bottom: 1rem;
        color: var(--text-light);
      }

      .btn {
        padding: 0.75rem 1.5rem;
        outline: none;
        border: none;
        font-size: 1rem;
        font-weight: 500;
        color: var(--white);
        background-color: var(--primary-color);
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
      }

      .btn:hover {
        background-color: var(--primary-color-dark);
      }

      img {
        width: 100%;
        display: flex;
      }

      a {
        text-decoration: none;
      }

      .logo {
        max-width: 120px;
      }

      html,
      body {
        scroll-behavior: smooth;
      }

      body {
        font-family: "Poppins", sans-serif;
      }

      .header {
        background-image: url("../img/jalan.jpg");
        background-position: center center;
        background-size: cover;
        background-repeat: no-repeat;
      }

      /* nav {
        position: fixed;
        isolation: isolate;
        top: 0;
        width: 100%;
        margin: auto;
        z-index: 9;
        background-color: transparent;
        transition: none;
      }

      @keyframes fadeInWhite {
        from {
          background-color: transparent;
        }
        to {
          background-color: #fff;
          box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
      }

      nav.scrolled {
        animation: fadeInWhite 0.3s forwards;
      }

      .nav__links a {
        color: #fff;
        transition: color 0.3s ease;
      }

      nav.scrolled .nav__links a {
        color: #000;
      }


      .nav__bar {
        padding: 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 2rem;
        background-color: var(--primary-color);
      }

      .nav__menu__btn {
        font-size: 1.5rem;
        color: var(--white);
        cursor: pointer;
      }

      .nav__links {
        list-style: none;
        position: absolute;
        width: 100%;
        padding: 2rem;
        display: flex;
        align-items: center;
        flex-direction: column;
        gap: 2rem;
        background-color: rgba(228, 92, 14, 0.9);
        transform: translateY(-100%);
        transition: 0.5s;
        z-index: -1;
      }

      .nav__links.open {
        transform: translateY(0);
      }

      .nav__links a {
        position: relative;
        isolation: isolate;
        padding-bottom: 8px;
        color: var(--white);
        transition: 0.3s;
      }

      .nav__btn {
        display: none;
      } */

      .header__container {
        padding-block: 10rem 15rem;
      }

      .header__container p {
        margin-bottom: 1rem;
        font-size: 1.2rem;
        color: var(--white);
        text-align: center;
        opacity: 0.6;
      }

      .header__container h1 {
        font-size: 4rem;
        font-weight: 500;
        line-height: 4.5rem;
        color: var(--white);
        text-align: center;
      }

      .header__container h1 span {
        color: var(--primary-color);
      }

      .booking__container {
        padding-block: 0;
      }

      .booking__form {
        padding: 2rem;
        display: flex;
        gap: 1rem;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        background-color: var(--white);
        border-radius: 10px;
        transform: translateY(-50%);
        box-shadow: 5px 5px 20px rgba(0, 0, 0, 0.1);
      }

      .input__group {
        flex: 1 1 220px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
      }

      .input__group div {
        align-items: center;
        justify-content: center;
        width:50%;
        padding: 5px;
      }

      .input__group span {
        font-size: 1.75rem;
        color: var(--primary-color);
      }

      .input__group label {
        font-weight: 500;
        color: var(--text-dark);
        display: block;
        text-align: left;
        margin-bottom: 3px;
      }

      .input__group #tripType {
          width: 100%;
          padding: 5px;
          padding-left: 0px;
          padding-right: 5px;
          border: 0px;
          border-radius: 4px;
          font-size: 1rem;
      }

      .input__group #budget {
          width: 100%;
          padding: 5px;
          padding-left: 0px;
          border: 0px;
          border-radius: 4px;
          font-size: 1rem;
      }

      .form-divider {
          width: 100%; /* Panjang hr menyesuaikan dengan form */
          border: 0;
          border-top: 1px solid var(--text-light); /* Sesuaikan warna dan ketebalan */
          opacity: 0.5;
          margin: 0.5rem 0; /* Margin atas dan bawah */
      }  

      .input__group input {
        display: block;
        width: 100%;
        max-width: 150px;
        padding-block: 5px;
        color: var(--text-dark);
        font-size: 0.9rem;
        outline: none;
        border: none;
      }

      .input__group input::placeholder {
        color: var(--text-light);
      }

      .interests-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 1rem;
      }

      .interest-option {
        position: relative;
        display: inline-block;
      }

      .interest-option input[type="checkbox"] {
        display: none; /* Sembunyikan kotak checkbox default */
      }

      .interest-card {
        display: inline-block;
        padding: 10px 20px;
        border: 1px solid var(--text-light);
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s, border-color 0.3s;
        font-size: 0.9rem;
        color: var(--text-dark);
      }

      .interest-option input[type="checkbox"]:checked + .interest-card {
        background-color: var(--primary-color); /* Sesuaikan warna */
        color: #fff; /* Warna teks jika dipilih */
        border-color: var(--primary-color);
      }

      form button.btn {
        width: 60%;
        height: 60%;
      }
        

      .about__container {
        overflow: hidden;
        display: grid;
        gap: 2rem;
      }

      .about__image img {
        max-width: 450px;
        margin: auto;
        border-radius: 5px;
      }

      @media (max-width: 768px) {
        .booking__form {
          transform: translateY(-20%); /* Atur jarak vertikal yang diinginkan */
        }
      }

      @media (max-width: 576px) {
        .booking__form {
          transform: translateY(-40%); /* Atur kembali untuk layar yang lebih kecil */
        }
      }


      @media (width > 576px) {
        .room__grid {
          grid-template-columns: repeat(2, 1fr);
        }

        .footer__container {
          grid-template-columns: repeat(2, 1fr);
        }
      }

      @media (width > 768px) {

        .about__container {
          grid-template-columns: repeat(2, 1fr);
          align-items: center;
        }

        .room__grid {
          grid-template-columns: repeat(3, 1fr);
        }

        .service__content {
          grid-column: 2/3;
        }

        .footer__container {
          grid-template-columns: repeat(4, 1fr);
        }
      }

      @media (width > 1024px) {
        .room__grid {
          gap: 2rem;
        }
      }
    </style>
    <title>Web Design Mastery | Rayal Park</title>
  </head>
  <body>
    <header class="header">
      <div class="section__container header__container" id="home">
        <p>Simple - Unique - Friendly</p>
        <h1>Create a Story of Your <br />Journey with <span>Rootify</span>.</h1>
      </div>
    </header>

    <section class="section__container booking__container">
      <form action="itinerary-results.php" class="booking__form" method="POST">
        <div class="input__group">
          <span><i class="ri-calendar-2-fill"></i></span>
          <div>
            <label for="check-in">START-TRIP</label>
            <input type="date" id="startDate" name="startDate" required>
          </div>
        </div>
        <div class="input__group">
          <span><i class="ri-calendar-2-fill"></i></span>
          <div>
            <label for="check-out">END-TRIP</label>
            <input type="date" id="endDate" name="endDate" required>
          </div>
        </div>
        <div class="input__group">
          <span><i class="ri-user-fill"></i></span>
          <div>
            <label for="guest">TRIP TYPE</label>
            <select id="tripType" name="tripType" required>
                <option value="solo">Solo</option>
                <option value="partner">Partner</option>
                <option value="friends">Friends</option>
                <option value="family">Family</option>
            </select>
          </div>
        </div>
        <div class="input__group">
          <span><i class="ri-money-dollar-circle-fill"></i></span>
          <div>
            <label for="check-out">BUDGET</label>
            <select id="budget" name="budget" required>
              <option value="low">Low</option>
              <option value="medium">Medium</option>
              <option value="high">High</option>
          </select>
          </div>
        </div>
        <!-- Tambahkan elemen hr di sini -->
        <hr class="form-divider">

        <!-- interest -->
        <div class="form-section">
          <h3>What are you interested in?</h3>
          <div class="interests-grid">
              <label class="interest-option">
                  <input type="checkbox" name="interests[]" value="nature">
                  <span class="interest-card">Nature</span>
              </label>
              <label class="interest-option">
                  <input type="checkbox" name="interests[]" value="culture">
                  <span class="interest-card">Culture</span>
              </label>
              <label class="interest-option">
                  <input type="checkbox" name="interests[]" value="shopping">
                  <span class="interest-card">Shopping</span>
              </label>
              <label class="interest-option">
                  <input type="checkbox" name="interests[]" value="education">
                  <span class="interest-card">Education</span>
              </label>
              <label class="interest-option">
                  <input type="checkbox" name="interests[]" value="beach">
                  <span class="interest-card">Beach</span>
              </label>
              <label class="interest-option">
                  <input type="checkbox" name="interests[]" value="recreation">
                  <span class="interest-card">Recreation</span>
              </label>
              <label class="interest-option">
                  <input type="checkbox" name="interests[]" value="history">
                  <span class="interest-card">History</span>
              </label>
              <label class="interest-option">
                  <input type="checkbox" name="interests[]" value="restaurant">
                  <span class="interest-card">Restaurant</span>
              </label>
          </div>
      </div>
        <div class="input__group input__btn">
          <button class="btn" type="submit" name="submit">PLANNING</button>
        </div>
      </form>
    </section>    

    <section class="section__container about__container" id="about">
      <div class="about__image">
        <img src="../img/Maskot.png" alt="about" width="100px" height="700px"/>
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



    <?php include '../chatbot/chatbot.php'; ?>
    <script src="https://unpkg.com/scrollreveal"></script> 
    <script src="filter.js"></script>
    <?php include '../navfot/footer.php'; ?>
  </body>
</html>
