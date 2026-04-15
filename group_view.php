<?php
session_start();
include 'db_connection.php';

if (!isset($_GET['id'])) { header("Location: dashboard.php"); exit(); }
$group_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// 1. Handle New Post Submission
if (isset($_POST['post_msg'])) {
    $msg = mysqli_real_escape_string($conn, $_POST['message']);
    $insert = "INSERT INTO group_posts (group_id, user_id, message) VALUES ('$group_id', '$user_id', '$msg')";
    mysqli_query($conn, $insert);
}

// 2. Get Group Details
$group_info = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM study_groups WHERE group_id = '$group_id'"));

// 3. Fetch all announcements for this group
$posts_query = "SELECT group_posts.*, users.full_name 
                FROM group_posts 
                JOIN users ON group_posts.user_id = users.user_id 
                WHERE group_id = '$group_id' 
                ORDER BY created_at DESC";
$posts_result = mysqli_query($conn, $posts_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($group_info['group_name']); ?> - Discussion</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 20px; }
        .chat-container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px; }
        .post { border-bottom: 1px solid #eee; padding: 10px 0; }
        .user-name { font-weight: bold; color: #2c3e50; font-size: 0.9em; }
        .time { color: #95a5a6; font-size: 0.75em; }
        textarea { width: 100%; height: 60px; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="chat-container">
        <h2>💬 <?php echo htmlspecialchars($group_info['group_name']); ?></h2>
        <a href="dashboard.php">Back to Dashboard</a>
        <hr>

        <form method="POST">
            <textarea name="message" placeholder="Share an announcement or ask a question..." required></textarea><br>
            <button type="submit" name="post_msg" style="background:#27ae60; color:white; border:none; padding:10px; cursor:pointer;">Post to Group</button>
        </form>

        <div style="margin-top: 30px;">
    <h4>Recent Announcements</h4>
    <?php
    // Fetch posts and join with Users table
    $posts_query = "SELECT group_posts.*, users.full_name 
                FROM group_posts 
                JOIN users ON group_posts.user_id = users.user_id 
                WHERE group_id = '$group_id' 
                ORDER BY created_at DESC";
$posts_result = mysqli_query($conn, $posts_query);

    // Check if the query actually worked before trying to fetch data
    if ($posts_result && mysqli_num_rows($posts_result) > 0) {
        while($post = mysqli_fetch_assoc($posts_result)) {
            echo "<div class='post'>
                    <span class='user-name'>" . htmlspecialchars($post['full_name']) . "</span> 
                    <span class='time'>" . $post['created_at'] . "</span><br>
                    <p>" . htmlspecialchars($post['message']) . "</p>
                  </div>";
        }
    } else {
        echo "<p style='color: #7f8c8d;'>No announcements yet. Be the first to post!</p>";
    }
    ?>
</div>
    </div>
</body>
</html>