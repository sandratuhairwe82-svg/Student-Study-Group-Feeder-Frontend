<?php
session_start();
include 'db_connection.php';

// 1. Safety Check: If not logged in, send to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$current_user = $_SESSION['user_id'];

// 2. Fetch User Data Safely
$user_query = "SELECT * FROM users WHERE id = '$current_user'";
$user_result = mysqli_query($conn, $user_query);

if ($user_result && mysqli_num_rows($user_result) > 0) {
    $user_data = mysqli_fetch_assoc($user_result);
    // Use full_name if it exists, otherwise use a fallback
    $display_name = !empty($user_data['full_name']) ? $user_data['full_name'] : "Student";
} else {
    $display_name = "Student";
}

// 3. Original Group Fetch (Before Search SQL)
$query = "SELECT * FROM study_groups";
$groups_result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Study Group Feeder - Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background: #2c3e50; color: white; }
        .btn-schedule { background: #3498db; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 0.9em; }
        .btn-join { background: #27ae60; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 0.9em; }
        .search-box { margin: 20px 0; padding: 15px; background: #eee; border-radius: 5px; }
        input[type="text"] { padding: 8px; width: 250px; border: 1px solid #ccc; border-radius: 4px; }
    </style>
</head>
<body>

<div class="container">
    <div class="container" style="margin-top: 20px;">
    <h2>Welcome back, <?php echo htmlspecialchars($display_name); ?>!</h2>
    <p>BSIT Study Group Portal - Bishop Barham University College</p>
</div>
    <div style="float: right; margin-top: -50px; background: #f9f9f9; padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
    <a href="profile.php" style="color: #2980b9; text-decoration: none; font-weight: bold;">👤 My Profile</a>
    
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <span style="margin: 0 10px; color: #ccc;">|</span>
        <a href="admin_dashboard.php" style="color: #e67e22; text-decoration: none; font-weight: bold;">🛡️ Admin Panel</a>
    <?php endif; ?>
    
    <span style="margin: 0 10px; color: #ccc;">|</span>
    <a href="logout.php" style="color: #c0392b; text-decoration: none; font-weight: bold;">Logout</a>
</div>
<div class="search-container" style="margin-bottom: 30px; background: #fff; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
    <form method="GET" action="dashboard.php" style="display: flex; gap: 10px;">
        <input type="text" name="search" placeholder="Search by Course, Faculty, or Group..." 
               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
               style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
        <button type="submit" style="padding: 10px 20px; background: #2c3e50; color: white; border: none; border-radius: 4px; cursor: pointer;">
            Search
        </button>
        <?php if(isset($_GET['search'])): ?>
            <a href="dashboard.php" style="padding: 10px; color: #7f8c8d; text-decoration: none;">Clear</a>
        <?php endif; ?>
    </form>
</div>
    <div style="margin-bottom: 30px; border-bottom: 2px solid #eee; padding-bottom: 20px;">
        <h3>➕ Create a New Study Group</h3>
        <form action="create_group.php" method="POST">
            <input type="text" name="group_name" placeholder="Group Name (e.g. PHP Masters)" required>
            <input type="text" name="course_unit" placeholder="Course Unit (e.g. BSIT 2201)" required>
            <button type="submit" name="create_group_btn" style="padding: 9px 15px; background: #2980b9; color: white; border: none; border-radius: 4px; cursor: pointer;">Create Group</button>
        </form>
    </div>

    <h3>Your Active Groups</h3>
    <table>
        <tr>
            <th>Group Name</th>
            <th>Course Unit</th>
            <th>Created Date</th>
            <th>Action</th>
        </tr>
        <?php
        $your_query = "SELECT * FROM study_groups WHERE creator_id = '$current_user'";
        $your_result = mysqli_query($conn, $your_query);

        if (mysqli_num_rows($your_result) > 0) {
            while($row = mysqli_fetch_assoc($your_result)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['group_name']) . "</td>
                    <td>" . htmlspecialchars($row['course_unit']) . "</td>
                    <td>" . $row['created_at'] . "</td>
                    <td>
                        <a href='schedule.php?id=" . $row['group_id'] . "' class='btn-schedule'>📅 Schedule</a>
                        <a href='group_view.php?id=" . $row['group_id'] . "' style='background: #8e44ad; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 0.9em; margin-left: 5px;'>💬 Chat</a>
                    </td>
                  </tr>";
        }
        } else {
            echo "<tr><td colspan='4' style='text-align:center;'>You haven't created any groups yet.</td></tr>";
        }
        ?>
    </table>

    <hr style="margin-top: 40px;">

    <div class="search-box">
        <form method="GET" action="dashboard.php">
            <strong>🔍 Find a Group:</strong>
            <input type="text" name="search" placeholder="Search by Course or Name..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit" style="padding: 7px 15px; cursor: pointer;">Search</button>
            <?php if(isset($_GET['search'])): ?>
                <a href="dashboard.php" style="margin-left:10px; color: #7f8c8d; text-decoration: none;">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <h3>🌍 Explore Available Study Groups</h3>
    <table>
        <tr>
            <th>Group Name</th>
            <th>Course Unit</th>
            <th>Action</th>
        </tr>
        <?php
        $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
        $explore_query = "SELECT * FROM study_groups WHERE creator_id != '$current_user'";
        
        if (!empty($search)) {
            $explore_query .= " AND (group_name LIKE '%$search%' OR course_unit LIKE '%$search%')";
        }

        $explore_result = mysqli_query($conn, $explore_query);

        if (mysqli_num_rows($explore_result) > 0) {
            while($group = mysqli_fetch_assoc($explore_result)) {
                echo "<tr>
                        <td>" . htmlspecialchars($group['group_name']) . "</td>
                        <td>" . htmlspecialchars($group['course_unit']) . "</td>
                        <td>
    <a href='join_group.php?id=" . $group['group_id'] . "' class='btn-join'>Join Group</a>
    <a href='group_view.php?id=" . $group['group_id'] . "' style='background: #8e44ad; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 0.9em; margin-left: 5px;'>View Chat</a>
</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='3' style='text-align:center;'>No groups available to join.</td></tr>";
        }
        ?>
    </table>
<hr style="margin-top: 40px;">
    
    <h3>📅 Upcoming Study Sessions</h3>
    <table style="background: #fff;">
        <tr style="background: #8e44ad; color: white;">
            <th>Group Name</th>
            <th>Date</th>
            <th>Time</th>
            <th>Location</th>
        </tr>
        <?php
        // This query joins the sessions table with the groups table 
        // to show meetings for ANY group the user is involved in.
        $sessions_query = "SELECT s.*, g.group_name 
                          FROM study_sessions s 
                          JOIN study_groups g ON s.group_id = g.group_id 
                          ORDER BY s.session_date ASC";
        
        $sessions_result = mysqli_query($conn, $sessions_query);

        if (mysqli_num_rows($sessions_result) > 0) {
            while($session = mysqli_fetch_assoc($sessions_result)) {
                echo "<tr>
                        <td>" . htmlspecialchars($session['group_name']) . "</td>
                        <td>" . date('D, M d, Y', strtotime($session['session_date'])) . "</td>
                        <td>" . date('h:i A', strtotime($session['session_time'])) . "</td>
                        <td>" . htmlspecialchars($session['location']) . "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4' style='text-align:center;'>No sessions scheduled yet.</td></tr>";
        }
        ?>
    </table>
</div>

</body>
</html>