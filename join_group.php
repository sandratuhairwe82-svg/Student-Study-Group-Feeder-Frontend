<?php
session_start();
include 'db_connection.php';

if (isset($_GET['id'])) {
    $group_id = $_GET['id'];
    $student_id = $_SESSION['user_id'];

    // Check if already a member
    $check = mysqli_query($conn, "SELECT * FROM group_members WHERE group_id='$group_id' AND student_id='$student_id'");
    
    if (mysqli_num_rows($check) == 0) {
        $query = "INSERT INTO group_members (group_id, student_id) VALUES ('$group_id', '$student_id')";
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Successfully joined the group!'); window.location='dashboard.php';</script>";
        }
    } else {
        echo "<script>alert('You are already a member of this group!'); window.location='dashboard.php';</script>";
    }
}
?>