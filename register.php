<?php
include 'db_connection.php'; // This connects to your database

if (isset($_POST['register'])) {
    $student_id = $_POST['student_id'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure password

    $sql = "INSERT INTO users (student_id, full_name, email, password) VALUES ('$student_id', '$full_name', '$email', '$password')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Registration Successful!');</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Registration</title>
    <style>
        body { font-family: 'Trebuchet MS', sans-serif; padding: 20px; }
        form { width: 300px; display: flex; flex-direction: column; gap: 10px; }
        input { padding: 8px; }
        button { background: #004a99; color: white; padding: 10px; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Register for Study Groups</h2>
    <form method="POST">
        <input type="text" name="student_id" placeholder="Student ID (e.g. 2400...)" required>
        <input type="text" name="full_name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="University Email" required>
        <input type="password" name="password" placeholder="Create Password" required>
        <button type="submit" name="register">Register Now</button>
    </form>
</body>
</html>