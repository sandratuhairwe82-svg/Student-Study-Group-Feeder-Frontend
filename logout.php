<?php
session_start();
session_destroy(); // Clears all user data
header("Location: login.php"); // Redirects back to login
exit();
?>