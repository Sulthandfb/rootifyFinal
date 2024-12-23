<?php
// db_connect.php
$hostname = "localhost";  // Ganti localhost dengan nama container MariaDB
$username = "root";      // Sesuaikan dengan username database Anda
$password = ""; // Sesuaikan dengan password database Anda
$database_name = "erd_rootify"; // Nama database Anda

$db = mysqli_connect($hostname, $username, $password, $database_name);

if ($db->connect_error) {
    echo "Koneksi database rusak: " . $db->connect_error;
}
?>
