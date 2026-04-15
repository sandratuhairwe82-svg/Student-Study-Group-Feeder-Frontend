<?php
session_start();
include 'db_connection.php'; 

if (isset($_POST['login_btn'])) {
    // 1. Get data and protect against SQL injection
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // 2. Query your 'users' table
    $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $query);

    // 3. Error checking
    if (!$result) {
        die("Database Error: " . mysqli_error($conn));
    }

    // 4. Verification logic
if (mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);

    // Storing data into the session
    $_SESSION['user_id'] = $user_data['user_id'];
    $_SESSION['full_name'] = $user_data['full_name'];
    $_SESSION['user_email'] = $user_data['email'];
    $_SESSION['role'] = $user_data['role']; // <-- THE KEY LINE

        echo "<div style='text-align:center; margin-top:50px; font-family:sans-serif;'>";
        echo "<h2 style='color:green;'>🎉 Login Successful!</h2>";
        echo "<h3>Welcome back, " . $user_data['full_name'] . "</h3>";
        echo "<p>You are now logged in as " . $user_data['email'] . "</p>";
        echo "<br><a href='dashboard.php' style='padding:10px; background:#2ecc71; color:white; text-decoration:none; border-radius:5px;'>Proceed to Dashboard</a>";
        echo "</div>";
    } else {
        echo "<div style='text-align:center; margin-top:50px; font-family:sans-serif;'>";
        echo "<h2 style='color:red;'>❌ Login Failed</h2>";
        echo "<p>The email or password does not match our records.</p>";
        echo "<a href='login.php'>Try Again</a>";
        echo "</div>";
    }
} else {
    header("Location: login.php");
    exit();
}
?>