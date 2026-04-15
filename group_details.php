<?php
session_start();
include 'db_connection.php';

// 1. Get the Group ID from the URL
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}
$group_id = $_GET['id'];

// 2. Fetch Group Info (Name and Course)
$group_info_query = "SELECT * FROM study_groups WHERE group_id = '$group_id'";
$group_info_result = mysqli_query($conn, $group_info_query);
$group = mysqli_fetch_assoc($group_info_result);

// 3. Fetch all posts for this group
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
    <title><?php echo $group['group_name']; ?> - Discussion</title>
    <link rel="stylesheet" href="style.css"> </head>
<body>
    <div class="container">
        <h1><?php echo $group['group_name']; ?></h1>
        <p>Course: <?php echo $group['course_unit']; ?></p>
        <hr>

        <div class="post-form">
            <h3>Start a Discussion</h3>
            <form action="post_message.php" method="POST">
                <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
                <textarea name="message" placeholder="Ask a question or share an announcement..." required style="width:100%; height:80px;"></textarea><br>
                <button type="submit" name="submit_post">Post Message</button>
            </form>
        </div>

        <hr>

        <div class="discussion-feed">
            <h3>Recent Activity</h3>
            <?php while($post = mysqli_fetch_assoc($posts_result)): ?>
                <div class="post" style="background:#f9f9f9; padding:10px; border-radius:5px; margin-bottom:10px;">
                    <strong><?php echo htmlspecialchars($post['full_name']); ?>:</strong>
                    <p><?php echo htmlspecialchars($post['message']); ?></p>
                    <small style="color:gray;"><?php echo $post['created_at']; ?></small>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>