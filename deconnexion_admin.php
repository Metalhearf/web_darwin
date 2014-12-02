<?php
/**
 * @Author: Adrien
 * @Date:   2014-12-01 09:55:37
 * @Last Modified by:   Adrien
 * @Last Modified time: 2014-12-01 09:59:20
 */
session_start(); //to ensure you are using same session
session_destroy(); //destroy the session
header("Location: admin_login.php"); //to redirect back to "admin_login.php" after logging out
exit();
?>