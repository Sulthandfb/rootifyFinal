<?php
session_start();
session_destroy();
header("Location: ../landing/template.php");
exit();
?>

