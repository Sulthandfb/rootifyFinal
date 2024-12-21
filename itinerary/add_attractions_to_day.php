<?php
session_start();
include "db_connect.php";

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$dayIndex = $data['dayIndex'];
$attractionIds = $data['attractionIds'];

if (!isset($_SESSION['itinerary'])) {
    $_SESSION['itinerary'] = [];
}

if (!isset($_SESSION['itinerary'][$dayIndex])) {
    $_SESSION['itinerary'][$dayIndex] = [];
}

// Add new attractions to the day
$_SESSION['itinerary'][$dayIndex] = array_merge(
    $_SESSION['itinerary'][$dayIndex],
    $attractionIds
);

echo json_encode(['success' => true]);