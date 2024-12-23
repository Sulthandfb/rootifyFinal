<?php
session_start();
session_destroy();
header("Location: ../landing/landingpage.php");
exit();
?>

