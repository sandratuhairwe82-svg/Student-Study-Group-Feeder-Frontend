<?php
session_start();
include 'db_connection.php';

if (isset($_POST['create_group_btn'])) {
    // Get form data
    $group_name = mysqli_real_escape_string($conn, $_POST['group_name']);
    $course_unit = mysqli_real_escape_string($conn, $_POST['course_unit']);
    
    // Get the ID of the student currently logged in
    $creator_id = $_SESSION['user_id']; 

    // Insert into the new table we just verified exists
    $query = "INSERT INTO study_groups (group_name, course_unit, creator_id) 
              VALUES ('$group_name', '$course_unit', '$creator_id')";

    if (mysqli_query($conn, $query)) {
        // Use JavaScript to show a success message and send you back to the dashboard
        echo "<script>
                alert('Success! Your study group has been created.');
                window.location.href='dashboard.php';
              </script>";
    } else {
        echo "Error creating group: " . mysqli_error($conn);
    }
} else {
    header("Location: dashboard.php");
    exit();
}
?>