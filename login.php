<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Study Group Finder</title>
    <link rel="stylesheet" href="style.css"> </head>
<body>
    <div class="login-container">
        <h2>Student Login</h2>
        <form action="login_handler.php" method="POST">
            <label>University Email:</label><br>
            <input type="email" name="email" required><br><br>
            
            <label>Password:</label><br>
            <input type="password" name="password" required><br><br>
            
            <button type="submit" name="login_btn">Login Now</button>
        </form>
        <p>New here? <a href="register.php">Create an account</a></p>
    </div>
</body>
</html>