<?php
session_start();
include 'db_connection.php';

$group_id = $_GET['id']; // Get the group ID from the URL

if (isset($_POST['schedule'])) {
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $desc = mysqli_real_escape_string($conn, $_POST['desc']);

    // Ensure the column names below match your phpMyAdmin exactly
    $query = "INSERT INTO study_sessions (group_id, session_date, session_time, location, description) 
              VALUES ('$group_id', '$date', '$time', '$location', '$desc')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Session Scheduled Successfully!'); window.location='dashboard.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<form method="POST" style="max-width: 400px; margin: 50px auto; font-family: sans-serif;">
    <h2>📅 Schedule New Session</h2>
    <input type="date" name="date" required style="width:100%; margin-bottom:10px; padding:8px;">
    <input type="time" name="time" required style="width:100%; margin-bottom:10px; padding:8px;">
    <input type="text" name="location" placeholder="Location (Room or Zoom Link)" required style="width:100%; margin-bottom:10px; padding:8px;">
    <textarea name="desc" placeholder="What will you study?" style="width:100%; margin-bottom:10px; padding:8px;"></textarea>
    <button type="submit" name="schedule" style="padding:10px 20px; background:#3498db; color:white; border:none; cursor:pointer; width:100%; border-radius:4px;">Confirm Session</button>
</form>