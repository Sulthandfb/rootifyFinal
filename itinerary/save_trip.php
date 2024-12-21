<?php
session_start();
include '../filter_wisata/db_connect.php';

// Jika tidak ada sesi yang valid, arahkan ke halaman login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../authentication/index.php"); // Redirect ke halaman login jika belum login
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id']; // ID pengguna
    $trip_name = $_POST['trip_name']; // Nama trip dari form popup
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $trip_type = $_POST['trip_type'];
    $budget = $_POST['budget'];
    $attractions = $_POST['attractions']; // Array ID tempat wisata

    // Simpan data utama trip ke saved_trips
    $stmt = $db->prepare("INSERT INTO saved_trips (trip_name, start_date, end_date, trip_type, budget, user_id) 
                          VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $trip_name, $start_date, $end_date, $trip_type, $budget, $user_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $trip_id = $stmt->insert_id; // Ambil ID trip yang baru disimpan

        // Simpan destinasi wisata ke trip_destinations
        foreach ($attractions as $attraction_id) {
            $stmt_dest = $db->prepare("INSERT INTO trip_destinations (trip_id, attraction_id) VALUES (?, ?)");
            $stmt_dest->bind_param("ii", $trip_id, $attraction_id);
            $stmt_dest->execute();
        }

        echo "Trip berhasil disimpan!";
    } else {
        echo "Gagal menyimpan trip.";
    }

    $stmt->close();
    $db->close();
}
?>