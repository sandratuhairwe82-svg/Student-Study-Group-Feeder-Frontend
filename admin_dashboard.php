<?php
session_start();
include 'db_connection.php';

// 1. The Guard
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

// 2. Fetch Total Students
$student_query = "SELECT COUNT(*) as total FROM users WHERE role = 'student'";
$student_result = mysqli_query($conn, $student_query);
$student_data = mysqli_fetch_assoc($student_result);
$total_users = $student_data['total']; // Fixes "Undefined variable: total_users"

// 3. Fetch Total Study Groups
$group_query = "SELECT COUNT(*) as total FROM study_groups";
$group_result = mysqli_query($conn, $group_query);

if ($group_result) {
    $group_data = mysqli_fetch_assoc($group_result);
    $total_groups = $group_data['total']; 
} else {
    $total_groups = "Error"; // This helps you see if the query failed
}

// 4. Fetch Recent Groups for the table
$recent_query = "SELECT group_id, group_name, course_unit, created_at FROM study_groups ORDER BY created_at DESC LIMIT 5";
$recent_groups = mysqli_query($conn, $recent_query); // Fixes "Undefined variable: recent_groups"
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - Study Group Portal</title>
    <style>
        body { font-family: Arial; background: #2c3e50; color: white; padding: 20px; }
        .stat-card { background: #34495e; padding: 20px; border-radius: 8px; display: inline-block; min-width: 200px; margin-right: 20px; text-align: center; }
        .admin-table { width: 100%; background: white; color: black; border-collapse: collapse; margin-top: 20px; }
        .admin-table th, .admin-table td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        .admin-table th { background: #ecf0f1; }
    </style>
</head>
<body>
    <h1>🛡️ Administrative Overview</h1>
    <a href="dashboard.php" style="color: #3498db;">← Back to Main Dashboard</a>
    <hr>

    <div class="stat-card">
        <h3>Total Students</h3>
        <h2 style="color: #2ecc71;"><?php echo $total_users; ?></h2>
    </div>

    <div class="stat-card">
        <h3>Total Study Groups</h3>
        <h2 style="color: #3498db;"><?php echo $total_groups; ?></h2>
    </div>

    <h3>Recently Created Groups</h3>
    <table class="admin-table">
        <tr>
            <th>Group Name</th>
            <th>Course Unit</th>
            <th>Created Date</th>
        <tbody>
    <?php 
    // This checks if the database actually sent back any groups
    if ($recent_groups && mysqli_num_rows($recent_groups) > 0): 
        while($row = mysqli_fetch_assoc($recent_groups)): 
    ?>
        <tr>
            <td>
    <a href="group_details.php?id=<?php echo $row['group_id']; ?>" 
       style="color: #3498db; text-decoration: none; font-weight: bold;">
        <?php echo htmlspecialchars($row['group_name']); ?>
    </a>
</td>
            <td><?php echo htmlspecialchars($row['course_unit']); ?></td>
            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
        </tr>
    <?php 
        endwhile; 
    else: 
    ?>
        <tr>
            <td colspan="3" style="text-align: center; padding: 20px;">
                No study groups found in the database yet.
            </td>
        </tr>
    <?php endif; ?>
</tbody>
            <?php 
            if ($recent_groups && mysqli_num_rows($recent_groups) > 0): 
                while($row = mysqli_fetch_assoc($recent_groups)): 
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['group_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['course_unit']); ?></td>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                </tr>
            <?php 
                endwhile; 
            else: 
            ?>
                <tr>
                    <td colspan="3" style="text-align: center; padding: 20px;">
                        No study groups found in the database.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>