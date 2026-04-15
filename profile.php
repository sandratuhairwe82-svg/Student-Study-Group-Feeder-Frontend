<?php
session_start();
include 'db_connection.php';

// 1. Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 2. Handle Profile Update
if (isset($_POST['update_profile'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $program = mysqli_real_escape_string($conn, $_POST['program']);
    $year = mysqli_real_escape_string($conn, $_POST['year']);

    $update_sql = "UPDATE users SET full_name='$full_name', program_of_study='$program', year_of_study='$year' WHERE id='$user_id'";
    
    if (mysqli_query($conn, $update_sql)) {
        $msg = "Profile updated successfully!";
    }
}

// 3. Fetch current user data SAFELY
$fetch_query = "SELECT * FROM users WHERE id = '$user_id'";
$fetch_result = mysqli_query($conn, $fetch_query);

// This check prevents the "bool given" error on Line 21
if ($fetch_result && mysqli_num_rows($fetch_result) > 0) {
    $user_data = mysqli_fetch_assoc($fetch_result);
} else {
    // Fallback if user isn't found
    $user_data = ['full_name' => '', 'program_of_study' => '', 'year_of_study' => ''];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 40px; }
        .profile-card { max-width: 400px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input, select { width: 95%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; }
        .btn-update { background: #27ae60; color: white; border: none; padding: 10px; width: 100%; cursor: pointer; }
    </style>
</head>
<body>
    <div class="profile-card">
        <h2>👤 User Profile</h2>
        <a href="dashboard.php">Back to Dashboard</a>
        <hr>
        
        <?php if(isset($msg)) echo "<p style='color: green;'>$msg</p>"; ?>

        <form method="POST">
            <label>Full Name:</label>
            <input type="text" name="full_name" value="<?php echo htmlspecialchars($user_data['full_name'] ?? ''); ?>" placeholder="Enter your full name">

            <label>Program of Study:</label>
            <input type="text" name="program" value="<?php echo htmlspecialchars($user_data['program_of_study'] ?? ''); ?>" placeholder="e.g. BSIT">

            <label>Year of Study:</label>
            <select name="year">
                <option value="Year 1" <?php if(($user_data['year_of_study'] ?? '') == 'Year 1') echo 'selected'; ?>>Year 1</option>
                <option value="Year 2" <?php if(($user_data['year_of_study'] ?? '') == 'Year 2') echo 'selected'; ?>>Year 2</option>
                <option value="Year 3" <?php if(($user_data['year_of_study'] ?? '') == 'Year 3') echo 'selected'; ?>>Year 3</option>
            </select>

            <button type="submit" name="update_profile" class="btn-update">Save Changes</button>
        </form>
    </div>
</body>
</html>