<?php
session_start(); //to ensure you are using same session
session_destroy(); //destroy the session
header("Location: admin_login.php"); //to redirect back to "admin_login.php" after logging out
exit();
?>