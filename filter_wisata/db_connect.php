<?php
// db_connect.php
$hostname = "localhost";
$username = "root";  // sesuaikan dengan username database Anda
$password = "";      // sesuaikan dengan password database Anda
$database_name = "erd_rootify"; // nama database Anda

$db = mysqli_connect($hostname, $username, $password, $database_name);

if ($db->connect_error) {
    echo "koneksi database rusak";
}
?>