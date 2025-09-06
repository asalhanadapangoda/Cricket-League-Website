<?php
session_start();
//prevent unathorized login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminLogin.php");
    exit;
}

require_once 'includes/db.php';


$teamCount = 0;
$playerCount = 0;

// Get number of teams
$teamQuery = "SELECT COUNT(*) as count FROM teams";
$teamResult = mysqli_query($conn, $teamQuery);
if ($teamResult && mysqli_num_rows($teamResult) > 0) {
    $teamData = mysqli_fetch_assoc($teamResult);
    $teamCount = $teamData['count'];
}

// Get number of players
$playerQuery = "SELECT COUNT(*) as count FROM player";
$playerResult = mysqli_query($conn, $playerQuery);
if ($playerResult && mysqli_num_rows($playerResult) > 0) {
    $playerData = mysqli_fetch_assoc($playerResult);
    $playerCount = $playerData['count'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="CSS_File/adminDashboard.css">
</head>
<body>
    <!-- Sidebar Navigation -->
    <?php include 'adminDashboardNav.php'; ?>

    <!-- Body content -->
    <div class="main-content">
        <div class="header">
            <div class="welcome">Welcome, <?php echo  $_SESSION['admin_name'] ?? 'Admin'; ?>!</div>
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
            <button class="action-btn" onclick="location.href='#.php'">Teams</button>
            <button class="action-btn" onclick="location.href='#.php'">Players</button>
            <button class="action-btn" onclick="location.href='#.php'">Fixture</button>
            <button class="action-btn" onclick="location.href='#.php'">Match Results</button>
            <button class="action-btn" onclick="location.href='#.php'">Player Performance</button>
            <button class="action-btn" onclick="location.href='#.php'">Update Live Score</button>
        </div>
    </div>
</body>
</html>