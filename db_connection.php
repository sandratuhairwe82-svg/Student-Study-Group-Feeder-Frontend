<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "study_group_db";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// THIS IS THE FEEDBACK LINE
//echo "<h1>Success!</h1>";
//echo "<p>Connected successfully to the study_group_db database.</p>"; 
?>