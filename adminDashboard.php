<?php
// Start session and check if admin is logged in
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminLogin.php");
    exit;
}

// Include database connection
require_once 'includes/db.php';

// Fetch data from database
$teamCount = 0;
$playerCount = 0;
$recentActivities = [];

// Get team count
$teamQuery = "SELECT COUNT(*) as count FROM teams";
$teamResult = mysqli_query($conn, $teamQuery);
if ($teamResult && mysqli_num_rows($teamResult) > 0) {
    $teamData = mysqli_fetch_assoc($teamResult);
    $teamCount = $teamData['count'];
}

// Get player count
$playerQuery = "SELECT COUNT(*) as count FROM players";
$playerResult = mysqli_query($conn, $playerQuery);
if ($playerResult && mysqli_num_rows($playerResult) > 0) {
    $playerData = mysqli_fetch_assoc($playerResult);
    $playerCount = $playerData['count'];
}

// Get recent activities (if you have an activities table)
$activitiesQuery = "SELECT activity, created_at FROM activities ORDER BY created_at DESC LIMIT 5";
$activitiesResult = mysqli_query($conn, $activitiesQuery);
if ($activitiesResult && mysqli_num_rows($activitiesResult) > 0) {
    while ($row = mysqli_fetch_assoc($activitiesResult)) {
        $recentActivities[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Cricket Website</title>
    <link rel="stylesheet" href="CSS_File/adminDashboard.css">
</head>
<body>
    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Admin Panel</h2>
        </div>
        
        <ul class="sidebar-menu">
            <li class="menu-section">DASHBOARD</li>
            <li><a href="adminDashboard.php">Dashboard</a></li>
            
            <li class="menu-section">TEAMS MANAGEMENT</li>
            <li><a href="manage_teams.php">Teams</a></li>
            <li><a href="manage_players.php">Players</a></li>
            
            <li class="menu-section">MATCHES</li>
            <li><a href="manage_fixtures.php"><strong>Fixtures</strong></a></li>
            <li><a href="manage_results.php">Match Results</a></li>
            
            <li class="menu-section">CONTENT</li>
            <li><a href="manage_gallery.php">Gallery</a></li>
            <li><a href="manage_news.php">News</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <div class="welcome">Welcome, <?php echo $_SESSION['admin_username'] ?? 'Admin'; ?>!</div>
            <button class="logout-btn" onclick="location.href='logout.php'">Logout</button>
        </div>

        <h1>Admin Dashboard</h1>

        <!-- Stats Cards -->
        <div class="dashboard-cards">
            <div class="card stat-card">
                <h3 class="stat-label">Teams</h3>
                <div class="stat-number"><?php echo $teamCount; ?></div>
            </div>
            
            <div class="card stat-card">
                <h3 class="stat-label">Players</h3>
                <div class="stat-number"><?php echo $playerCount; ?></div>
            </div>
        </div>

        <!-- Quick Actions -->
        <h2 class="section-title">Quick Actions</h2>
        <div class="quick-actions">
            <button class="action-btn" onclick="location.href='add_team.php'">Add Team</button>
            <button class="action-btn" onclick="location.href='add_player.php'">Add Player</button>
            <button class="action-btn" onclick="location.href='add_fixture.php'">Add Fixture</button>
            <button class="action-btn" onclick="location.href='update_result.php'">Update Result</button>
        </div>

        <!-- Recent Activities -->
        <h2 class="section-title">Recent Activities</h2>
        <ul class="activities-list">
            <?php if (!empty($recentActivities)): ?>
                <?php foreach ($recentActivities as $activity): ?>
                    <li class="activity-item">
                        <strong><?php echo htmlspecialchars($activity['activity']); ?></strong>
                        <div class="activity-time">
                            <?php 
                            // Format the timestamp
                            $timestamp = strtotime($activity['created_at']);
                            echo date('M j, Y \a\t g:i a', $timestamp); 
                            ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="activity-item">
                    <strong>No recent activities</strong>
                </li>
            <?php endif; ?>
        </ul>
    </div>

    <script>
        // Simple JavaScript for interactivity
        document.addEventListener('DOMContentLoaded', function() {
            // Add active class to current menu item
            const currentPage = window.location.pathname.split('/').pop();
            const menuItems = document.querySelectorAll('.sidebar-menu a');
            
            menuItems.forEach(item => {
                if (item.getAttribute('href') === currentPage) {
                    item.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>