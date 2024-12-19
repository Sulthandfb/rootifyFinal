-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 18, 2024 at 05:29 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `erd_rootify`
--

-- --------------------------------------------------------

--
-- Table structure for table `accommodations`
--

CREATE TABLE `accommodations` (
  `accommodation_ID` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `total_rooms` int(11) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `price_per_night` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attraction_categories`
--

CREATE TABLE `attraction_categories` (
  `attraction_id` int(11) NOT NULL,
  `trip_category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attraction_categories`
--

INSERT INTO `attraction_categories` (`attraction_id`, `trip_category_id`) VALUES
(1, 2),
(1, 5),
(1, 8),
(2, 1),
(2, 4),
(2, 7),
(3, 1),
(3, 4),
(3, 7),
(4, 2),
(4, 5),
(4, 8),
(5, 1),
(5, 4),
(5, 7),
(6, 3),
(6, 6),
(6, 9),
(7, 2),
(7, 5),
(8, 2),
(8, 5),
(8, 8),
(9, 1),
(9, 4),
(9, 7),
(10, 8),
(11, 1),
(11, 4),
(11, 7),
(12, 2),
(12, 5),
(12, 8),
(13, 6),
(14, 1),
(14, 4),
(14, 7),
(15, 6),
(16, 1),
(16, 2),
(16, 4),
(16, 5),
(16, 7),
(16, 8),
(17, 2),
(17, 5),
(18, 1),
(18, 4),
(18, 7),
(19, 1),
(19, 4),
(19, 7),
(20, 1),
(20, 4),
(20, 7),
(21, 1),
(21, 2),
(21, 4),
(21, 5),
(21, 7),
(21, 8),
(22, 1),
(22, 4),
(22, 7),
(23, 8),
(23, 9),
(24, 8),
(25, 1),
(25, 2),
(25, 4),
(25, 5),
(25, 7),
(25, 8),
(26, 2),
(26, 5),
(26, 8),
(27, 8),
(28, 1),
(28, 2),
(28, 4),
(28, 5),
(28, 7),
(28, 8),
(29, 1),
(29, 4),
(29, 7),
(30, 1),
(30, 4),
(30, 7);

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_id` int(11) NOT NULL,
  `checkin_date` date NOT NULL,
  `checkout_date` date NOT NULL,
  `guest_type` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_tourpackage`
--

CREATE TABLE `booking_tourpackage` (
  `booking_ID` int(11) NOT NULL,
  `package_ID` int(11) NOT NULL,
  `package_date` date DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chatbot`
--

CREATE TABLE `chatbot` (
  `session_ID` int(11) NOT NULL,
  `question` text DEFAULT NULL,
  `user_ID` int(11) DEFAULT NULL,
  `response` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `event_ID` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `entry_fee` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `facility_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `facility_name` varchar(255) NOT NULL,
  `facility_icon` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `facilities`
--

INSERT INTO `facilities` (`facility_id`, `hotel_id`, `facility_name`, `facility_icon`) VALUES
(1, 1, 'Kolam Renang', '../icons/swimming-pool.svg'),
(2, 1, 'WiFi Gratis', '../icons/wi-fi-icon.svg'),
(3, 1, 'Spa', '../icons/facial-massage.svg'),
(4, 1, 'Restoran', '../icons/restaurant.svg'),
(5, 1, 'Pusat Kebugaran', '../icons/dumbbell.svg'),
(6, 1, 'Parkir Gratis', '../icons/parking-area.svg'),
(7, 2, 'Pemandangan Gunung', '../icons/mountain-view.svg'),
(8, 2, 'Kolam Renang Pribadi', '../icons/swimming-pool.svg'),
(9, 2, 'WiFi Gratis', '../icons/wi-fi-icon.svg'),
(10, 2, 'Hot Tub', '../icons/hot-tub.svg'),
(11, 2, 'Parkir Pribadi', '../icons/parking-area.svg'),
(12, 3, 'WiFi Gratis', '../icons/wi-fi-icon.svg'),
(13, 3, 'Dapur Lengkap', '../icons/kitchen.svg'),
(14, 3, 'Area Kerja', '../icons/desk.svg'),
(15, 3, 'Laundry', '../icons/laundry.svg'),
(16, 3, 'Parkir', '../icons/parking-area.svg'),
(17, 4, 'Kolam Renang', '../icons/swimming-pool.svg'),
(18, 4, 'WiFi Gratis', '../icons/wi-fi-icon.svg'),
(19, 4, 'Spa & Pijat', '../icons/facial-massage.svg'),
(20, 4, 'Restoran', '../icons/restaurant.svg'),
(21, 4, 'Parkir Gratis', '../icons/parking-area.svg');

-- --------------------------------------------------------

--
-- Table structure for table `hotels`
--

CREATE TABLE `hotels` (
  `hotel_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `latitude` decimal(9,6) DEFAULT NULL,
  `longitude` decimal(9,6) DEFAULT NULL,
  `google_map_embed_code` text DEFAULT NULL,
  `category` enum('hotel','villa','apartment') NOT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotels`
--

INSERT INTO `hotels` (`hotel_id`, `name`, `description`, `rating`, `price`, `latitude`, `longitude`, `google_map_embed_code`, `category`, `image_url`) VALUES
(1, 'Oceanview Resort', 'A luxurious resort with breathtaking views of the ocean, perfect for a relaxing getaway. So beautiful place', 4.90, 1500000.00, 9.082000, 123.456000, '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3092.7796397042084!2d-74.73202042353788!3d39.17973002999521!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c0bbb3d5d6ae6b%3A0x8deaf5ebeb1c49c8!2sOcean%20View%20Resort%20Campground!5e0!3m2!1sid!2sid!4v1734379384819!5m2!1sid!2sid\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 'hotel', '../akomodasi/hotel-img/oceanview1.jpg'),
(2, 'Mountain Retreat Villa', 'A private villa nestled in the mountains, offering a serene and peaceful environment for travelers.', 4.70, 2000000.00, 8.756000, 120.987000, '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15813.059450880179!2d110.33384336435627!3d-7.7617103658309174!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59acb8a69e07%3A0xf7c5860aef8278a4!2sBaturan%20Villa%20Family%20Retreat!5e0!3m2!1sid!2sid!4v1734379605279!5m2!1sid!2sid\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 'villa', '../akomodasi/hotel-img/villaretreat.jpg'),
(3, 'City Center Apartments', 'Modern apartments in the heart of the city, offering comfort and convenience for business travelers.', 4.50, 1200000.00, 9.439000, 123.123000, '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4701.4737128931265!2d110.3728927262677!3d-7.7417445305653!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a592cb4a9bec3%3A0x4a90a2346dd9f452!2sMataram%20City%20Yogyakarta%20(MICC)!5e0!3m2!1sid!2sid!4v1734379719840!5m2!1sid!2sid\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 'apartment', '../akomodasi/hotel-img/apartment1.jpeg'),
(4, 'Lakeside Hotel', 'A charming hotel located by the serene lake, perfect for family vacations and romantic getaways.', 4.80, 1800000.00, 10.324000, 122.678000, '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31669.669316711803!2d107.35492324870697!3d-7.159641379775448!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e688b96c30c7869%3A0xcc3d65320d369aa7!2sGlamping%20Lakeside%20Rancabali!5e0!3m2!1sid!2sid!4v1734379801426!5m2!1sid!2sid\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 'hotel', '../akomodasi/hotel-img/lakeside.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `hotel_facilities`
--

CREATE TABLE `hotel_facilities` (
  `hotel_id` int(11) NOT NULL,
  `facility_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hotel_images`
--

CREATE TABLE `hotel_images` (
  `image_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `is_primary` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotel_images`
--

INSERT INTO `hotel_images` (`image_id`, `hotel_id`, `image_url`, `is_primary`) VALUES
(1, 1, '../akomodasi/hotel-img/oceanview1.jpg', 1),
(2, 1, '../akomodasi/hotel-img/oceanview2.jpg', 0),
(3, 1, '../akomodasi/hotel-img/oceanview3.jpg', 0),
(4, 1, '../akomodasi/hotel-img/oceanview4.jpg', 0),
(5, 1, '../akomodasi/hotel-img/oceanview5.jpg', 0),
(6, 2, '../akomodasi/hotel-img/villaretreat.jpg', 1),
(7, 2, '../akomodasi/hotel-img/villaretreat2.jpg', 0),
(8, 2, '../akomodasi/hotel-img/villaretreat3.jpg', 0),
(9, 2, '../akomodasi/hotel-img/villaretreat4.jpg', 0),
(10, 2, '../akomodasi/hotel-img/villaretreat5.jpg', 0),
(11, 3, '../akomodasi/hotel-img/apartment1.jpeg', 1),
(12, 3, '../akomodasi/hotel-img/apartment2.jpg', 0),
(13, 3, '../akomodasi/hotel-img/apartment3.webp', 0),
(14, 3, '../akomodasi/hotel-img/apartment4.jpg', 0),
(15, 3, '../akomodasi/hotel-img/apartment5.jpg', 0),
(16, 4, '../akomodasi/hotel-img/lakeside.jpg', 1),
(17, 4, '../akomodasi/hotel-img/lakeside2.jpg', 0),
(18, 4, '../akomodasi/hotel-img/lakeside3.jpg', 0),
(19, 4, '../akomodasi/hotel-img/lakeside4.jpg', 0),
(20, 4, '../akomodasi/hotel-img/lakeside5.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `room_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `capacity` int(11) NOT NULL,
  `room_size` decimal(10,2) DEFAULT NULL,
  `bed_type` varchar(255) DEFAULT NULL,
  `availability` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_id`, `hotel_id`, `room_name`, `description`, `price`, `image_url`, `capacity`, `room_size`, `bed_type`, `availability`) VALUES
(1, 1, 'Deluxe Room', 'Kamar mewah dengan pemandangan laut', 1500000.00, '../akomodasi/hotel-img/oceanview1.jpg', 2, 30.50, 'Queen Bed', 5),
(2, 1, 'Suite Room', 'Suite dengan balkon pribadi dan akses kolam renang', 2500000.00, '../akomodasi/hotel-img/oceanview2.jpg', 4, 45.00, 'King Bed', 2),
(3, 1, 'Family Room', 'Kamar keluarga dengan ruang tamu', 1800000.00, '../akomodasi/hotel-img/oceanview3.jpg', 4, 40.00, '2 Queen Beds', 3),
(4, 2, '1-Bedroom Villa', 'Vila privat dengan kolam renang pribadi', 2000000.00, '../akomodasi/hotel-img/mountain1.jpg', 2, 50.00, 'King Bed', 4),
(5, 2, '2-Bedroom Villa', 'Vila luas dengan pemandangan pegunungan', 3500000.00, '../akomodasi/hotel-img/mountain2.jpg', 4, 80.00, '2 King Beds', 2),
(6, 2, 'Luxury Suite', 'Suite mewah dengan fasilitas lengkap', 2800000.00, '../akomodasi/hotel-img/mountain3.jpg', 3, 60.00, 'Queen Bed', 2),
(7, 3, 'Studio Apartment', 'Studio modern di pusat kota', 1200000.00, '../akomodasi/hotel-img/apartment1.jpg', 2, 25.00, 'Queen Bed', 8),
(8, 3, '2-Bedroom Apartment', 'Apartemen dua kamar dengan dapur lengkap', 1800000.00, '../akomodasi/hotel-img/apartment2.jpg', 4, 60.00, '2 Queen Beds', 3),
(9, 3, 'Penthouse', 'Penthouse eksklusif dengan balkon', 3000000.00, '../akomodasi/hotel-img/apartment3.jpg', 4, 90.00, 'King Bed', 1),
(10, 4, 'Standard Room', 'Kamar standar dengan pemandangan danau', 1300000.00, '../akomodasi/hotel-img/lakeside1.jpg', 2, 28.00, 'Queen Bed', 6),
(11, 4, 'Deluxe Room', 'Kamar deluxe dengan balkon pribadi', 1800000.00, '../akomodasi/hotel-img/lakeside2.jpg', 2, 35.00, 'King Bed', 3),
(12, 4, 'Family Suite', 'Suite keluarga dengan ruang tamu', 2500000.00, '../akomodasi/hotel-img/lakeside3.jpg', 4, 50.00, '2 Queen Beds', 2);

-- --------------------------------------------------------

--
-- Table structure for table `room_images`
--

CREATE TABLE `room_images` (
  `image_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `is_primary` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_images`
--

INSERT INTO `room_images` (`image_id`, `room_id`, `image_url`, `is_primary`) VALUES
(1, 1, '../akomodasi/hotel-img/deluxe.jpg', 1),
(2, 1, '../akomodasi/hotel-img/deluxe.jpg', 0),
(3, 1, '../akomodasi/hotel-img/deluxe.jpg', 0),
(4, 1, '../akomodasi/hotel-img/deluxe.jpg', 0),
(5, 1, '../akomodasi/hotel-img/deluxe.jpg', 0),
(6, 2, '../akomodasi/hotel-img/suite.jpg', 1),
(7, 2, '../akomodasi/hotel-img/suite.jpg', 0),
(8, 2, '../akomodasi/hotel-img/suite.jpg', 0),
(9, 2, '../akomodasi/hotel-img/suite.jpg', 0),
(10, 2, '../akomodasi/hotel-img/suite.jpg', 0),
(11, 3, '../akomodasi/hotel-img/family.jpg', 1),
(12, 3, '../akomodasi/hotel-img/family.jpg', 0),
(13, 3, '../akomodasi/hotel-img/family.jpg', 0),
(14, 3, '../akomodasi/hotel-img/family.jpg', 0),
(15, 3, '../akomodasi/hotel-img/family.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tempat_wisata`
--

CREATE TABLE `tempat_wisata` (
  `id_wisata` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `url_gambar` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `longitude` decimal(9,6) DEFAULT NULL,
  `latitude` decimal(9,6) DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT NULL,
  `jam_buka` time DEFAULT NULL,
  `jam_tutup` time DEFAULT NULL,
  `trip_types` varchar(50) DEFAULT NULL,
  `budget_range` varchar(10) DEFAULT NULL,
  `interests_tags` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tempat_wisata`
--

INSERT INTO `tempat_wisata` (`id_wisata`, `nama`, `deskripsi`, `url_gambar`, `alamat`, `kategori`, `longitude`, `latitude`, `rating`, `jam_buka`, `jam_tutup`, `trip_types`, `budget_range`, `interests_tags`) VALUES
(1, 'Candi Prambanan', 'Kompleks candi Hindu terbesar di Indonesia yang dibangun pada abad ke-9 Masehi, terkenal dengan arsitektur megah dan kisah Ramayana.', '../img/prambanan.jpg', 'Jalan Raya Solo - Yogyakarta No.16, Prambanan, Sleman, Yogyakarta', 'Budaya', 110.491073, -7.752020, 4.80, '06:00:00', '17:00:00', 'solo,family', 'medium', 'culture,history'),
(2, 'Malioboro', 'Jalan terkenal di pusat kota Yogyakarta yang menjadi ikon wisata belanja, dengan banyak toko, pusat oleh-oleh, dan pedagang kaki lima.', '../img/Malioboro.jpg', 'Jalan Malioboro, Sosromenduran, Gedong Tengen, Yogyakarta', 'Belanja', 110.367075, -7.793171, 4.50, '00:00:00', '23:59:00', 'solo,family,friends', 'low', 'shopping,culture'),
(3, 'Hutan Pinus Mangunan', 'Destinasi alam yang menawan dengan hutan pinus yang rimbun, cocok untuk rekreasi dan foto, serta memiliki pemandangan bukit hijau yang asri.', '../img/hutanpinus.jpg', 'Dlingo, Bantul, Yogyakarta', 'Alam', 110.440868, -7.926475, 4.60, '06:00:00', '17:00:00', 'solo,partner,family', 'low', 'nature,scenic'),
(4, 'Keraton Yogyakarta', 'Istana resmi Sultan Yogyakarta yang kaya akan budaya dan sejarah, tempat berlangsungnya berbagai acara tradisional dan pertunjukan seni.', '../img/keraton.jpeg', 'Jalan Rotowijayan No.1, Kraton, Yogyakarta', 'Budaya', 110.364121, -7.795576, 4.70, '08:00:00', '14:00:00', 'solo,family', 'medium', 'culture,history'),
(5, 'Taman Sari', 'Kompleks taman yang dulunya merupakan tempat pemandian raja dan keluarga keraton, memiliki arsitektur yang indah dan kolam yang menawan.', '../img/tamansari.jpg', 'Jalan Taman, Patehan, Kraton, Yogyakarta', 'Budaya', 110.363449, -7.794107, 4.60, '09:00:00', '15:00:00', 'solo,family', 'low', 'culture,history'),
(6, 'Pantai Parangtritis', 'Pantai yang terkenal dengan pasir hitam dan ombak besar, tempat yang ideal untuk bersantai dan menikmati matahari terbenam.', '../img/parangritis.jpg', 'Parangtritis, Kretek, Bantul, Yogyakarta', 'Alam', 110.360344, -8.018284, 4.40, '00:00:24', '00:00:24', 'solo,partner,friends', 'low', 'nature,beach'),
(7, 'Candi Borobudur', 'Candi Buddha terbesar di dunia dan situs warisan dunia UNESCO, terkenal dengan arsitektur megah dan relief yang indah.', '../img/borobudur.jpg', 'Borobudur, Magelang, Yogyakarta', 'Budaya', 110.202705, -7.607389, 4.90, '06:00:00', '17:00:00', 'solo,family', 'high', 'culture,history'),
(8, 'Bukit Bintang', 'Tempat wisata yang menawarkan pemandangan malam kota Yogyakarta yang menakjubkan, cocok untuk bersantai sambil menikmati kuliner lokal.', '../img/bukitbintang.jpg', 'Dlingo, Bantul, Yogyakarta', 'Alam', 110.410908, -7.927064, 4.50, '18:00:00', '23:00:00', 'solo,partner,friends', 'low', 'scenic,nature'),
(9, 'Pantai Indrayanti', 'Pantai pasir putih yang bersih dan cocok untuk liburan keluarga, dengan fasilitas lengkap seperti restoran dan area bermain.', '../img/indrayanti.jpg', 'Tepus, Gunung Kidul, Yogyakarta', 'Alam', 110.667084, -8.148953, 4.70, '00:00:00', '23:59:59', 'solo,partner,friends,family', 'low', 'nature,beach'),
(10, 'Gua Jomblang', 'Gua vertikal dengan fenomena cahaya surga yang terkenal, cocok untuk wisata petualangan.', '../img/jomblang.jpg', 'Jetis Wetan, Pacarejo, Semanu, Gunung Kidul', 'Alam', 110.632430, -8.030832, 4.80, '07:00:00', '15:00:00', 'solo,partner', 'medium', 'nature,adventure'),
(11, 'Museum Ullen Sentalu', 'Museum seni dan budaya Jawa yang penuh dengan koleksi seni tradisional dan sejarah.', '../img/ullensentalu.jpg', 'Kaliurang Barat, Sleman, Yogyakarta', 'Budaya', 110.423390, -7.600800, 4.60, '08:30:00', '15:30:00', 'solo,family', 'medium', 'culture,art'),
(12, 'Pantai Drini', 'Pantai yang memiliki pulau kecil di tengah laut dan terkenal dengan keindahan alamnya.', '../img/drini.png', 'Tanjungsari, Gunung Kidul, Yogyakarta', 'Alam', 110.598271, -8.144376, 4.50, '00:00:00', '23:59:59', 'solo,partner,family', 'low', 'nature,beach'),
(13, 'Pantai Pok Tunggal', 'Pantai dengan pemandangan tebing yang indah, sering dikunjungi untuk menikmati sunset.', '../img/poktunggal.jpg', 'Tepus, Gunung Kidul, Yogyakarta', 'Alam', 110.667908, -8.150697, 4.60, '00:00:00', '23:59:59', 'solo,partner,friends', 'low', 'nature,beach'),
(14, 'Tebing Breksi', 'Tebing kapur yang diubah menjadi tempat wisata, dengan pahatan artistik dan pemandangan indah.', '../img/tebingbreksi.jpg', 'Sambirejo, Prambanan, Sleman, Yogyakarta', 'Alam', 110.509657, -7.775102, 4.50, '06:00:00', '18:00:00', 'solo,partner,friends', 'low', 'nature,scenic'),
(15, 'Alun-Alun Kidul', 'Alun-alun yang ramai pada malam hari dengan hiburan lokal, becak lampu, dan makanan khas.', '../img/alunalunkidul.jpeg', 'Patehan, Kraton, Yogyakarta', 'Budaya', 110.359846, -7.812329, 4.40, '00:00:00', '23:59:59', 'solo,family,friends', 'low', 'culture,nightlife'),
(16, 'Taman Pintar Yogyakarta', 'Tempat rekreasi edukasi dengan berbagai wahana sains, cocok untuk anak-anak dan keluarga.', '../img/tamanpintar.jpg', 'Jl. Panembahan Senopati No.1-3, Yogyakarta', 'Edukasi', 110.367905, -7.801309, 4.50, '09:00:00', '16:00:00', 'family', 'medium', 'education,children'),
(17, 'De Mata Trick Eye Museum', 'Museum dengan koleksi ilusi optik dan seni tiga dimensi yang interaktif.', '../img/demata.jpg', 'XT Square, Jl. Veteran No.150-151, Pandeyan, Yogyakarta', 'Seni', 110.393364, -7.819437, 4.30, '10:00:00', '22:00:00', 'solo,partner,family', 'medium', 'art,photography'),
(18, 'Sindu Kusuma Edupark', 'Taman hiburan yang memiliki wahana seperti bianglala dan miniatur kota, cocok untuk keluarga.', '../img/sindukusuma.jpg', 'Jl. Jambon, Kragilan, Sinduadi, Mlati, Sleman', 'Hiburan', 110.361180, -7.769879, 4.20, '15:00:00', '21:00:00', 'family,friends', 'medium', 'amusement,children'),
(19, 'Pasar Beringharjo', 'Pasar tradisional terbesar di Yogyakarta yang menjual batik, makanan, dan oleh-oleh khas.', '../img/beringharjo.jpg', 'Jl. Pabringan No.1, Ngupasan, Gondomanan, Yogyakarta', 'Belanja', 110.367257, -7.801977, 4.50, '08:00:00', '17:00:00', 'solo,family', 'low', 'shopping,culture'),
(20, 'Warung Bu Ageng', 'Tempat makan tradisional yang menyajikan makanan khas Jawa dengan suasana sederhana namun ramah keluarga dan pasangan.', '../img/buageng.jpg', 'Jl. Tirtodipuran No.13, Mantrijeron, Yogyakarta', 'Restoran', 110.361424, -7.818049, 4.70, '10:00:00', '22:00:00', 'solo,family,partner', 'medium', 'food,culture'),
(21, 'The Manglung Café & Resto', 'Restoran dengan pemandangan kota Yogyakarta yang menawan, terkenal dengan suasana romantis dan menu khas Nusantara.', '../img/manglung.jpg', 'Jl. Ngoro-Ngoro Ombo No.16, Patuk, Gunung Kidul, Yogyakarta', 'Restoran', 110.466530, -7.889950, 4.70, '10:00:00', '22:00:00', 'partner,friends', 'high', 'food,scenic'),
(22, 'Canting Restaurant', 'Restoran elegan dengan konsep modern yang menawarkan pemandangan indah kota dari rooftop, menyajikan hidangan fusion internasional.', '../img/canting.jpg', 'Rooftop Galeria Mall, Jl. Sudirman No.99-101, Yogyakarta', 'Restoran', 110.374139, -7.782964, 4.80, '11:00:00', '23:00:00', 'partner,solo', 'high', 'food,romantic'),
(23, 'Gudeg Yu Djum', 'Salah satu tempat makan legendaris di Yogyakarta yang terkenal dengan gudeg asli Yogyakarta.', '../img/gudegyudjum.jpg', 'Jl. Wijilan No.167, Panembahan, Kraton, Yogyakarta', 'Restoran', 110.368319, -7.803563, 4.50, '06:00:00', '20:00:00', 'solo,family', 'low', 'food,culture'),
(24, 'Angkringan Lik Man', 'Angkringan khas Yogyakarta yang terkenal dengan kopi joss dan berbagai macam lauk khas.', '../img/likman.jpg', 'Jl. Wongsodirjan, Sosromenduran, Gedong Tengen, Yogyakarta', 'Restoran', 110.366803, -7.789126, 4.40, '17:00:00', '02:00:00', 'solo,friends', 'low', 'food,street'),
(25, 'Abhayagiri Restaurant', 'Restoran yang memiliki pemandangan Candi Prambanan dan Gunung Merapi, cocok untuk dinner romantis.', '../img/abhayagiri.jpg', 'Sumberwatu Heritage Resort, Sambirejo, Prambanan, Sleman, Yogyakarta', 'Restoran', 110.505643, -7.767293, 4.70, '11:00:00', '22:00:00', 'partner', 'high', 'food,romantic'),
(26, 'Bale Raos', 'Restoran yang menyajikan makanan khas Jawa dengan menu favorit dari Keraton Yogyakarta.', '../img/baleraos.jpg', 'Kompleks Keraton Yogyakarta, Jl. Magangan Kulon No.1, Yogyakarta', 'Restoran', 110.365558, -7.805908, 4.60, '10:00:00', '21:00:00', 'family,partner', 'medium', 'food,culture'),
(27, 'Honje Restaurant', 'Restoran yang menawarkan hidangan Asia dan Western dengan pemandangan langsung ke Jalan Malioboro.', '../img/honje.jpg', 'Jl. Margo Utomo No.129, Jetis, Yogyakarta', 'Restoran', 110.367229, -7.790280, 4.50, '11:00:00', '23:00:00', 'partner,solo', 'high', 'food,scenic'),
(28, 'House of Raminten', 'Restoran dengan konsep unik dan menyajikan makanan khas Jawa dengan suasana yang kental dengan budaya Yogyakarta.', '../img/raminten.jpg', 'Jl. FM Noto No.7, Kota Baru, Yogyakarta', 'Restoran', 110.379647, -7.782784, 4.40, '00:00:00', '23:59:59', 'partner,friends', 'low', 'food,culture'),
(29, 'Milas Vegetarian Resto', 'Restoran vegetarian yang menyajikan hidangan sehat dengan bahan-bahan organik dalam suasana taman yang nyaman.', '../img/milas.jpg', 'Jl. Prawirotaman IV No.127B, Yogyakarta', 'Restoran', 110.374532, -7.821469, 4.60, '11:00:00', '21:00:00', 'solo,family,partner', 'medium', 'food,vegetarian'),
(30, 'Warung Kopi Klotok', 'Warung sederhana yang menyajikan kopi klotok khas Jawa dan makanan tradisional dengan suasana pedesaan.', '../img/klotok.jpg', 'Jl. Kaliurang Km.16, Pakem, Sleman, Yogyakarta', 'Restoran', 110.413917, -7.671535, 4.70, '07:00:00', '22:00:00', 'solo,family', 'low', 'food,local');

-- --------------------------------------------------------

--
-- Table structure for table `tourguide`
--

CREATE TABLE `tourguide` (
  `Guide_ID` int(11) NOT NULL,
  `name_guide` varchar(100) DEFAULT NULL,
  `language` varchar(50) DEFAULT NULL,
  `contact` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tourist_attractions`
--

CREATE TABLE `tourist_attractions` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `longitude` decimal(9,6) DEFAULT NULL,
  `latitude` decimal(9,6) DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT NULL,
  `opening_time` time DEFAULT NULL,
  `closing_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tourist_attractions`
--

INSERT INTO `tourist_attractions` (`id`, `name`, `description`, `image_url`, `address`, `category`, `longitude`, `latitude`, `rating`, `opening_time`, `closing_time`) VALUES
(1, 'Candi Prambanan', 'Kompleks candi Hindu terbesar di Indonesia yang dibangun pada abad ke-9 Masehi.', '../img/prambanan.jpg', 'Jalan Raya Solo - Yogyakarta No.16, Prambanan, Sleman, Yogyakarta', 'History', 110.491073, -7.752020, 4.80, '06:00:00', '17:00:00'),
(2, 'Malioboro', 'Jalan terkenal di pusat kota Yogyakarta yang menjadi ikon wisata belanja.', '../img/Malioboro.jpg', 'Jalan Malioboro, Sosromenduran, Gedong Tengen, Yogyakarta', 'Shopping', 110.367075, -7.793171, 4.50, '00:00:00', '23:59:00'),
(3, 'Hutan Pinus Mangunan', 'Destinasi alam yang menawan dengan hutan pinus yang rimbun.', '../img/hutanpinus.jpg', 'Dlingo, Bantul, Yogyakarta', 'Nature', 110.440868, -7.926475, 4.60, '06:00:00', '17:00:00'),
(4, 'Keraton Yogyakarta', 'Istana resmi Sultan Yogyakarta yang kaya akan budaya dan sejarah.', '../img/keraton.jpeg', 'Jalan Rotowijayan No.1, Kraton, Yogyakarta', 'Culture', 110.364121, -7.795576, 4.70, '08:00:00', '14:00:00'),
(5, 'Pantai Parangtritis', 'Pantai yang terkenal dengan pasir hitam dan ombak besar.', '../img/parangritis.jpg', 'Parangtritis, Kretek, Bantul, Yogyakarta', 'Beach', 110.360344, -8.018284, 4.40, '00:00:00', '23:59:59'),
(6, 'Candi Borobudur', 'Candi Buddha terbesar di dunia dan situs warisan dunia UNESCO.', '../img/borobudur.jpg', 'Borobudur, Magelang, Yogyakarta', 'History', 110.202705, -7.607389, 4.90, '06:00:00', '17:00:00'),
(7, 'Gua Jomblang', 'Gua vertikal dengan fenomena cahaya surga yang terkenal.', '../img/jomblang.jpg', 'Jetis Wetan, Pacarejo, Semanu, Gunung Kidul', 'Nature', 110.632430, -8.030832, 4.80, '07:00:00', '15:00:00'),
(8, 'Museum Ullen Sentalu', 'Museum seni dan budaya Jawa yang penuh dengan koleksi seni tradisional.', '../img/ullensentalu.jpg', 'Kaliurang Barat, Sleman, Yogyakarta', 'Culture', 110.423390, -7.600800, 4.60, '08:30:00', '15:30:00'),
(9, 'Tebing Breksi', 'Tebing kapur yang diubah menjadi tempat wisata, dengan pahatan artistik.', '../img/tebingbreksi.jpg', 'Sambirejo, Prambanan, Sleman, Yogyakarta', 'Nature', 110.509657, -7.775102, 4.50, '06:00:00', '18:00:00'),
(10, 'Taman Pintar Yogyakarta', 'Tempat rekreasi edukasi dengan berbagai wahana sains.', '../img/tamanpintar.jpg', 'Jl. Panembahan Senopati No.1-3, Yogyakarta', 'Education', 110.367905, -7.801309, 4.50, '09:00:00', '16:00:00'),
(11, 'Pasar Beringharjo', 'Pasar tradisional terbesar di Yogyakarta yang menjual batik dan oleh-oleh.', '../img/beringharjo.jpg', 'Jl. Pabringan No.1, Ngupasan, Gondomanan, Yogyakarta', 'Shopping', 110.367257, -7.801977, 4.50, '08:00:00', '17:00:00'),
(12, 'Warung Bu Ageng', 'Tempat makan tradisional yang menyajikan makanan khas Jawa.', '../img/buageng.jpg', 'Jl. Tirtodipuran No.13, Mantrijeron, Yogyakarta', 'Restaurant', 110.361424, -7.818049, 4.70, '10:00:00', '22:00:00'),
(13, 'The Manglung Café & Resto', 'Restoran dengan pemandangan kota Yogyakarta yang menawan.', '../img/manglung.jpg', 'Jl. Ngoro-Ngoro Ombo No.16, Patuk, Gunung Kidul, Yogyakarta', 'Restaurant', 110.466530, -7.889950, 4.70, '10:00:00', '22:00:00'),
(14, 'Gudeg Yu Djum', 'Salah satu tempat makan legendaris di Yogyakarta yang terkenal dengan gudeg.', '../img/gudegyudjum.jpg', 'Jl. Wijilan No.167, Panembahan, Kraton, Yogyakarta', 'Restaurant', 110.368319, -7.803563, 4.50, '06:00:00', '20:00:00'),
(15, 'Abhayagiri Restaurant', 'Restoran yang memiliki pemandangan Candi Prambanan dan Gunung Merapi.', '../img/abhayagiri.jpg', 'Sumberwatu Heritage Resort, Sambirejo, Prambanan, Sleman, Yogyakarta', 'Restaurant', 110.505643, -7.767293, 4.70, '11:00:00', '22:00:00'),
(16, 'Pantai Indrayanti', 'Pantai pasir putih yang bersih dengan fasilitas lengkap.', '../img/indrayanti.jpg', 'Tepus, Gunung Kidul, Yogyakarta', 'Beach', 110.667084, -8.148953, 4.70, '00:00:00', '23:59:59'),
(17, 'Goa Pindul', 'Goa dengan sungai bawah tanah, terkenal untuk cave tubing.', '../img/goapindul.jpg', 'Bejiharjo, Karangmojo, Gunung Kidul, Yogyakarta', 'Nature', 110.644444, -7.967222, 4.60, '07:00:00', '17:00:00'),
(18, 'Benteng Vredeburg', 'Museum sejarah yang dulunya merupakan benteng Belanda.', '../img/bentengvredeburg.jpg', 'Jl. Margo Mulyo No.6, Ngupasan, Yogyakarta', 'History', 110.367500, -7.800556, 4.40, '08:00:00', '15:30:00'),
(19, 'Kampung Wisata Taman Sari', 'Bekas taman kerajaan dengan arsitektur unik dan kolam pemandian.', '../img/tamansari.jpg', 'Patehan, Kraton, Yogyakarta', 'Culture', 110.359167, -7.810000, 4.50, '09:00:00', '15:00:00'),
(20, 'Bukit Bintang', 'Tempat menikmati pemandangan malam kota Yogyakarta.', '../img/bukitbintang.jpg', 'Kecamatan Patuk, Gunung Kidul, Yogyakarta', 'Nature', 110.504444, -7.944722, 4.30, '00:00:00', '23:59:59'),
(21, 'Desa Wisata Tembi', 'Desa wisata yang menawarkan pengalaman budaya Jawa tradisional.', '../img/tembi.jpg', 'Timbulharjo, Sewon, Bantul, Yogyakarta', 'Culture', 110.350833, -7.850556, 4.40, '08:00:00', '17:00:00'),
(22, 'Kebun Buah Mangunan', 'Kebun buah dengan pemandangan indah di perbukitan Mangunan.', '../img/kebunbuahmangunan.jpg', 'Mangunan, Dlingo, Bantul, Yogyakarta', 'Nature', 110.421667, -7.941667, 4.50, '07:00:00', '17:00:00'),
(23, 'Jogja Bay Pirates Adventure Waterpark', 'Taman air terbesar di Indonesia dengan tema bajak laut.', '../img/jogjabay.jpg', 'Jl. Utara Stadion, Maguwoharjo, Depok, Sleman, Yogyakarta', 'Recreation', 110.416667, -7.750000, 4.30, '09:00:00', '18:00:00'),
(24, 'Sindu Kusuma Edupark', 'Taman hiburan keluarga dengan berbagai wahana.', '../img/sindukusuma.jpg', 'Jl. Jambon, Kragilan, Sinduadi, Mlati, Sleman, Yogyakarta', 'Recreation', 110.361180, -7.769879, 4.20, '09:00:00', '21:00:00'),
(25, 'Pantai Nglambor', 'Pantai dengan air jernih, cocok untuk snorkeling.', '../img/nglambor.jpg', 'Purwodadi, Tepus, Gunung Kidul, Yogyakarta', 'Beach', 110.699722, -8.183889, 4.60, '00:00:00', '23:59:59'),
(26, 'Ratu Boko', 'Situs arkeologi berupa reruntuhan istana di atas bukit.', '../img/ratuboko.jpg', 'Bokoharjo, Prambanan, Sleman, Yogyakarta', 'History', 110.489167, -7.770278, 4.50, '06:00:00', '17:00:00'),
(27, 'Gembira Loka Zoo', 'Kebun binatang terbesar di Yogyakarta.', '../img/gembiraLoka.jpg', 'Jl. Kebun Raya No.2, Rejowinangun, Yogyakarta', 'Education', 110.402778, -7.806389, 4.30, '08:00:00', '17:00:00'),
(28, 'Sate Klatak Pak Pong', 'Warung sate kambing khas Yogyakarta.', '../img/sateklatak.jpg', 'Jl. Imogiri Timur No.321, Giwangan, Yogyakarta', 'Restaurant', 110.395833, -7.833611, 4.60, '09:00:00', '22:00:00'),
(29, 'Pantai Wediombo', 'Pantai dengan bentuk teluk dan air yang tenang.', '../img/wediombo.jpg', 'Jepitu, Girisubo, Gunung Kidul, Yogyakarta', 'Beach', 110.733333, -8.216667, 4.40, '00:00:00', '23:59:59'),
(30, 'Museum Sonobudoyo', 'Museum yang menyimpan benda-benda bersejarah dan kebudayaan Jawa.', '../img/sonobudoyo.jpg', 'Jl. Trikora No.6, Panembahan, Kraton, Yogyakarta', 'Culture', 110.364167, -7.803889, 4.30, '08:00:00', '14:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tourpackage`
--

CREATE TABLE `tourpackage` (
  `package_ID` int(11) NOT NULL,
  `destination` int(11) DEFAULT NULL,
  `opening_hours` varchar(50) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `trip_type` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tourpackage_tourguide`
--

CREATE TABLE `tourpackage_tourguide` (
  `package_ID` int(11) NOT NULL,
  `Guide_ID` int(11) NOT NULL,
  `assigned_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `travelhistory`
--

CREATE TABLE `travelhistory` (
  `history_ID` int(11) NOT NULL,
  `user_ID` int(11) DEFAULT NULL,
  `destination_ID` int(11) DEFAULT NULL,
  `visit_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trips`
--

CREATE TABLE `trips` (
  `id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `trip_type` varchar(20) NOT NULL,
  `budget` varchar(20) NOT NULL,
  `interests` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trips`
--

INSERT INTO `trips` (`id`, `start_date`, `end_date`, `trip_type`, `budget`, `interests`, `created_at`) VALUES
(54, '2024-11-01', '2024-11-04', 'solo', 'low', 'nature,shopping,food', '2024-11-03 11:14:10'),
(55, '2024-11-01', '2024-11-04', 'solo', 'low', 'nature,shopping,food', '2024-11-03 11:14:57'),
(56, '2024-11-04', '2024-11-05', 'solo', 'low', 'culture,shopping,beach', '2024-11-03 11:15:42'),
(57, '2024-11-04', '2024-11-05', 'solo', 'low', 'culture,shopping,beach', '2024-11-03 11:16:02'),
(58, '2024-11-04', '2024-11-05', 'solo', 'low', 'culture,shopping,beach', '2024-11-03 11:42:38'),
(59, '2024-11-04', '2024-11-05', 'solo', 'low', 'culture,shopping,beach', '2024-11-03 11:50:20'),
(60, '2024-11-04', '2024-11-05', 'solo', 'low', 'culture,shopping,beach', '2024-11-03 11:50:53'),
(61, '2024-11-04', '2024-11-05', 'solo', 'low', 'culture,shopping,beach', '2024-11-03 11:52:43'),
(62, '2024-11-04', '2024-11-05', 'solo', 'low', 'culture,shopping,beach', '2024-11-03 11:53:39'),
(63, '2024-11-04', '2024-11-05', 'solo', 'low', 'culture,shopping,beach', '2024-11-03 11:53:57'),
(64, '2024-11-04', '2024-11-05', 'solo', 'low', 'culture,shopping,beach', '2024-11-03 11:56:59'),
(65, '2024-11-04', '2024-11-05', 'solo', 'low', 'culture,shopping,beach', '2024-11-03 12:27:43'),
(66, '2024-11-01', '2024-11-06', 'solo', 'low', 'nature,culture', '2024-11-04 00:51:25'),
(67, '2024-11-01', '2024-11-06', 'solo', 'low', 'nature,culture', '2024-11-04 00:56:22'),
(68, '2024-11-01', '2024-11-03', 'solo', 'low', 'culture,beach', '2024-11-04 00:57:04'),
(69, '2024-11-01', '2024-11-03', 'family', 'medium', 'shopping,food,beach', '2024-11-08 12:47:36'),
(70, '2024-11-01', '2024-11-03', 'family', 'low', 'shopping,beach', '2024-11-08 12:48:36'),
(71, '2024-11-01', '2024-11-03', 'solo', 'low', 'shopping,beach', '2024-11-08 12:48:53'),
(72, '2024-11-01', '2024-11-03', 'solo', 'low', 'shopping,beach', '2024-11-08 12:49:59'),
(73, '2024-12-10', '2024-12-12', 'solo', 'low', 'beach,history', '2024-12-09 20:13:32'),
(74, '2024-12-12', '2024-12-13', 'solo', 'low', 'nature,culture', '2024-12-12 13:43:20'),
(75, '2024-12-12', '2024-12-13', 'solo', 'low', 'nature,culture', '2024-12-12 13:46:57'),
(76, '2024-12-12', '2024-12-13', 'solo', 'low', 'nature,culture', '2024-12-12 13:47:49'),
(77, '2024-12-12', '2024-12-13', 'solo', 'low', 'nature,culture', '2024-12-12 13:49:01'),
(78, '2024-12-12', '2024-12-13', 'solo', 'low', 'food,scenic', '2024-12-12 13:55:23'),
(79, '2024-12-12', '2024-12-13', 'solo', 'low', 'food,scenic', '2024-12-12 13:59:52'),
(80, '2024-12-12', '2024-12-13', 'solo', 'low', 'food,scenic', '2024-12-12 14:02:12'),
(81, '2024-12-12', '2024-12-13', 'solo', 'low', 'food,scenic', '2024-12-12 14:02:36'),
(82, '2024-12-12', '2024-12-13', 'solo', 'low', 'food,scenic', '2024-12-12 14:02:46'),
(83, '2024-12-12', '2024-12-13', 'solo', 'low', 'food,scenic', '2024-12-12 14:32:29'),
(84, '2024-12-06', '2024-12-07', 'solo', 'low', 'nature,culture', '2024-12-13 03:07:32'),
(85, '2024-12-13', '2024-12-15', 'solo', 'low', 'nature,shopping', '2024-12-13 03:47:28'),
(86, '2024-12-18', '2024-12-19', 'solo', 'low', 'nature,shopping', '2024-12-17 17:36:48'),
(87, '2024-12-18', '2024-12-19', 'solo', 'low', 'nature,shopping', '2024-12-17 19:29:59');

-- --------------------------------------------------------

--
-- Table structure for table `trip_categories`
--

CREATE TABLE `trip_categories` (
  `id` int(11) NOT NULL,
  `trip_type` varchar(50) NOT NULL,
  `budget_range` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trip_categories`
--

INSERT INTO `trip_categories` (`id`, `trip_type`, `budget_range`) VALUES
(1, 'solo', 'low'),
(2, 'solo', 'medium'),
(3, 'solo', 'high'),
(4, 'partner', 'low'),
(5, 'partner', 'medium'),
(6, 'partner', 'high'),
(7, 'family', 'low'),
(8, 'family', 'medium'),
(9, 'family', 'high');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'sulthan', 'sulthanraihan701@gmail.com', '$2y$10$VfKkikPEXNJjmat2z7AtzO35M2MyFhy3M/7kG7BeHEQ/gD/iECUaW', '2024-11-01 15:37:33'),
(2, 'tio', 'tio@gmail.com', '$2y$10$1hB5lnWGJM3PDY4sbojmQO/cydDg6VnNr1GPrizSh834GHfmN0Rmu', '2024-11-01 16:02:10'),
(3, 'lingga', 'l@gmail.com', '$2y$10$RcGXSq1be4eNrd.Xuc6bJu40cYhf1UHpG7Sca6f6SCuGNUo4hx2MC', '2024-12-13 04:19:54'),
(4, 'abc', 'abc@gmail.com', '$2y$10$sq.NztKDKKMLXWnJJDaBv.QckdIHvE5Q.KUd.NAFLyCNJzJTinPyi', '2024-12-14 06:02:11'),
(6, 'nathan', 'nathan@gmail.com', '$2y$10$fmA/Gv2oYODikTAj2ITL7eA5QYrFMr9E2aPbF4j1RP9rF1dRmIwDW', '2024-12-14 06:29:03'),
(7, '123', '123@gmail.com', '$2y$10$BxrFXFyk90K2xCZg3xneUuqAEV/k1GgiJuQGyhPwnxTY3tJ93g58O', '2024-12-14 06:32:48'),
(8, 'napis', 'napis@gmail.com', '$2y$10$BsOCo30CBYKTlH1UaYDd1ua1FBoV0hnMQHS6e7clunEF9E8b.7RRW', '2024-12-14 08:02:27');

-- --------------------------------------------------------

--
-- Table structure for table `user_visits_destination`
--

CREATE TABLE `user_visits_destination` (
  `user_ID` int(11) NOT NULL,
  `destination_ID` int(11) NOT NULL,
  `review` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accommodations`
--
ALTER TABLE `accommodations`
  ADD PRIMARY KEY (`accommodation_ID`);

--
-- Indexes for table `attraction_categories`
--
ALTER TABLE `attraction_categories`
  ADD PRIMARY KEY (`attraction_id`,`trip_category_id`),
  ADD KEY `trip_category_id` (`trip_category_id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `booking_tourpackage`
--
ALTER TABLE `booking_tourpackage`
  ADD PRIMARY KEY (`booking_ID`,`package_ID`),
  ADD KEY `package_ID` (`package_ID`);

--
-- Indexes for table `chatbot`
--
ALTER TABLE `chatbot`
  ADD PRIMARY KEY (`session_ID`),
  ADD KEY `user_ID` (`user_ID`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`event_ID`);

--
-- Indexes for table `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`facility_id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `hotels`
--
ALTER TABLE `hotels`
  ADD PRIMARY KEY (`hotel_id`);

--
-- Indexes for table `hotel_facilities`
--
ALTER TABLE `hotel_facilities`
  ADD PRIMARY KEY (`hotel_id`,`facility_id`),
  ADD KEY `facility_id` (`facility_id`);

--
-- Indexes for table `hotel_images`
--
ALTER TABLE `hotel_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `room_images`
--
ALTER TABLE `room_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `tempat_wisata`
--
ALTER TABLE `tempat_wisata`
  ADD PRIMARY KEY (`id_wisata`);

--
-- Indexes for table `tourguide`
--
ALTER TABLE `tourguide`
  ADD PRIMARY KEY (`Guide_ID`);

--
-- Indexes for table `tourist_attractions`
--
ALTER TABLE `tourist_attractions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tourpackage`
--
ALTER TABLE `tourpackage`
  ADD PRIMARY KEY (`package_ID`),
  ADD KEY `destination` (`destination`);

--
-- Indexes for table `tourpackage_tourguide`
--
ALTER TABLE `tourpackage_tourguide`
  ADD PRIMARY KEY (`package_ID`,`Guide_ID`),
  ADD KEY `Guide_ID` (`Guide_ID`);

--
-- Indexes for table `travelhistory`
--
ALTER TABLE `travelhistory`
  ADD PRIMARY KEY (`history_ID`),
  ADD KEY `user_ID` (`user_ID`),
  ADD KEY `destination_ID` (`destination_ID`);

--
-- Indexes for table `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trip_categories`
--
ALTER TABLE `trip_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_visits_destination`
--
ALTER TABLE `user_visits_destination`
  ADD PRIMARY KEY (`user_ID`,`destination_ID`),
  ADD KEY `destination_ID` (`destination_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `facilities`
--
ALTER TABLE `facilities`
  MODIFY `facility_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `hotels`
--
ALTER TABLE `hotels`
  MODIFY `hotel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `hotel_images`
--
ALTER TABLE `hotel_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `room_images`
--
ALTER TABLE `room_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tempat_wisata`
--
ALTER TABLE `tempat_wisata`
  MODIFY `id_wisata` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tourist_attractions`
--
ALTER TABLE `tourist_attractions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `trips`
--
ALTER TABLE `trips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `trip_categories`
--
ALTER TABLE `trip_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attraction_categories`
--
ALTER TABLE `attraction_categories`
  ADD CONSTRAINT `attraction_categories_ibfk_1` FOREIGN KEY (`attraction_id`) REFERENCES `tourist_attractions` (`id`),
  ADD CONSTRAINT `attraction_categories_ibfk_2` FOREIGN KEY (`trip_category_id`) REFERENCES `trip_categories` (`id`);

--
-- Constraints for table `booking_tourpackage`
--
ALTER TABLE `booking_tourpackage`
  ADD CONSTRAINT `booking_tourpackage_ibfk_1` FOREIGN KEY (`booking_ID`) REFERENCES `bookings` (`booking_ID`),
  ADD CONSTRAINT `booking_tourpackage_ibfk_2` FOREIGN KEY (`package_ID`) REFERENCES `tourpackage` (`package_ID`);

--
-- Constraints for table `facilities`
--
ALTER TABLE `facilities`
  ADD CONSTRAINT `facilities_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`hotel_id`);

--
-- Constraints for table `hotel_facilities`
--
ALTER TABLE `hotel_facilities`
  ADD CONSTRAINT `hotel_facilities_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`hotel_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hotel_facilities_ibfk_2` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`facility_id`) ON DELETE CASCADE;

--
-- Constraints for table `hotel_images`
--
ALTER TABLE `hotel_images`
  ADD CONSTRAINT `hotel_images_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`hotel_id`);

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`hotel_id`);

--
-- Constraints for table `room_images`
--
ALTER TABLE `room_images`
  ADD CONSTRAINT `room_images_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
