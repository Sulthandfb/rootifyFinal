<?php
// Koneksi ke database
$host = 'localhost';
$username = 'root'; // Sesuaikan dengan username database Anda
$password = ''; // Sesuaikan dengan password database Anda
$dbname = 'erd_rootify'; // Sesuaikan dengan nama database Anda

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mengambil data tempat wisata berdasarkan ID
$id = 1; // ID tempat wisata yang ingin ditampilkan, bisa diambil dari URL atau parameter lainnya
$sql = "SELECT * FROM tempat_wisata WHERE id_wisata = $id";
$result = $conn->query($sql);
$data = $result->fetch_assoc();

// Jika data tidak ditemukan
if (!$data) {
    die("Data tidak ditemukan");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['nama']; ?> Tour Package</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f0f0f0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #1e90ff;
            color: white;
        }

        .title {
            font-size: 24px;
        }

        .gallery {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 10px;
            margin-bottom: 30px;
            height: 500px;
        }

        .main-image {
            height: 100%;
            overflow: hidden;
        }

        .side-images {
            display: grid;
            grid-template-rows: repeat(3, 1fr);
            gap: 10px;
            height: 100%;
        }

        .image-container {
            position: relative;
            overflow: hidden;
        }

        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .rating {
            background: #fff;
            padding: 10px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin: 20px 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .rating-score {
            background: #007bff;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
        }

        .tour-details {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }

        .itinerary {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
        }

        .booking-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            margin: 20px 0;
        }

        .calendar-day {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
            cursor: pointer;
        }

        .calendar-day:hover {
            background: #f0f0f0;
        }

        .calendar-day.selected {
            background: #007bff;
            color: white;
        }

        .price {
            font-size: 24px;
            color: #ff4d4d;
            margin: 20px 0;
        }

        .book-button {
            background: #ff4d4d;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        .book-button:hover {
            background: #ff3333;
        }

        @media (max-width: 768px) {
            .gallery {
                grid-template-columns: 1fr;
                height: auto;
            }
            
            .tour-details {
                grid-template-columns: 1fr;
            }

            .main-image {
                height: 300px;
            }

            .side-images {
                grid-template-columns: repeat(3, 1fr);
                grid-template-rows: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="title"><?php echo $data['nama']; ?> by Rootify</h1>
        </div>

        <div class="gallery">
            <div class="main-image image-container">
                <img src="../img/<?php echo $data['url_gambar']; ?>" alt="<?php echo $data['nama']; ?>">
            </div>
            <div class="side-images">
                <div class="image-container">
                    <img src="/img/<?php echo $data['url_gambar']; ?>" alt="Image 1">
                </div>
                <div class="image-container">
                    <img src="/img/<?php echo $data['url_gambar']; ?>" alt="Image 2">
                </div>
                <div class="image-container">
                    <img src="/img/<?php echo $data['url_gambar']; ?>" alt="Image 3">
                </div>
            </div>
        </div>

        <div class="rating">
            <span class="rating-score"><?php echo $data['rating']; ?></span>
            <span>Excellent • From reviews</span>
        </div>

        <div class="tour-details">
            <div class="itinerary">
                <h2>Description</h2>
                <p><?php echo $data['deskripsi']; ?></p>
            </div>

            <div class="booking-section">
                <h3>Booking Information</h3>
                <p>Address: <?php echo $data['alamat']; ?></p>
                <p>Opening Hours: <?php echo $data['jam_buka']; ?> - <?php echo $data['jam_tutup']; ?></p>
                <p>Category: <?php echo $data['kategori']; ?></p>
                <p>Trip Type: <?php echo $data['trip_types']; ?></p>
                <p>Budget Range: <?php echo $data['budget_range']; ?></p>
                <p>Interest Tags: <?php echo $data['interests_tags']; ?></p>

                <div class="price">
                    <span>Starts from</span>
                    <br>
                    <strong>Rp 2.400.000</strong> <span style="text-decoration: line-through; color: #888;">Rp 3.000.000</span> <span style="color: #ff4d4d;">-20%</span>
                </div>
                <button class="book-button">Select Ticket</button>
            </div>
        </div>
    </div>

    <script>
        const calendar = document.getElementById('calendar');
        const months = ['Dec'];
        const days = ['Thu', 'Fri', 'Sat', 'Sun', 'Mon', 'Tue', 'Wed'];
        
        days.forEach(day => {
            const dayElement = document.createElement('div');
            dayElement.className = 'calendar-day';
            dayElement.textContent = day;
            calendar.appendChild(dayElement);
        });

        for(let i = 19; i <= 25; i++) {
            const dayElement = document.createElement('div');
            dayElement.className = 'calendar-day';
            dayElement.textContent = i;
            dayElement.addEventListener('click', () => {
                document.querySelectorAll('.calendar-day').forEach(el => 
                    el.classList.remove('selected'));
                dayElement.classList.add('selected');
            });
            calendar.appendChild(dayElement);
        }
    </script>
</body>
</html>
