<?php
session_start();
include '../filter_wisata/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    die("Anda harus login terlebih dahulu.");
}

$trip_id = $_GET['trip_id'];

// Ambil data trip
$stmt = $db->prepare("SELECT * FROM saved_trips WHERE id = ?");
$stmt->bind_param("i", $trip_id);
$stmt->execute();
$trip = $stmt->get_result()->fetch_assoc();

// Ambil destinasi wisata untuk trip ini
$stmt_dest = $db->prepare("
    SELECT ta.* FROM trip_destinations td
    JOIN tourist_attractions ta ON td.attraction_id = ta.id
    WHERE td.trip_id = ?
");
$stmt_dest->bind_param("i", $trip_id);
$stmt_dest->execute();
$result = $stmt_dest->get_result();
?>

<h1><?php echo htmlspecialchars($trip['trip_name']); ?></h1>
<p>From <?php echo $trip['start_date']; ?> to <?php echo $trip['end_date']; ?></p>

<h2>Destinations</h2>
<?php while ($attraction = $result->fetch_assoc()): ?>
    <div>
        <h3><?php echo htmlspecialchars($attraction['name']); ?></h3>
        <p><?php echo htmlspecialchars($attraction['description']); ?></p>
    </div>
<?php endwhile; ?>
