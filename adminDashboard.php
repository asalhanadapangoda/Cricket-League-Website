<?php
session_start();
// prevent unauthorized login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminLogin.php");
    exit;
}

require_once 'includes/db.php';

// Stats
$teamCount = 0;
$playerCount = 0;

// Get number of teams
$teamQuery = "SELECT COUNT(*) as count FROM team"; // ✅ fix: your table is "team", not "teams"
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
            <div class="welcome">Welcome, <?php echo $_SESSION['admin_name'] ?? 'Admin'; ?>!</div>
            <button class="logout-btn" onclick="location.href='logout.php'">Logout</button>
        </div>

        <?php
        // check if sidebar link selected
        if (isset($_GET['page'])) {
            $page = $_GET['page'];

            if ($page === "teams") {
                include "manage-teams.php";
            } elseif ($page === "players") {
                include "manage-players.php";
            } elseif ($page === "fixtures") {
                include "fixtures.php";   // your fixtures table here
            } elseif ($page === "results") {
                include "manage-results.php";
            } elseif ($page === "performance") {
                include "manage-player-performance.php";
            } elseif ($page === "livescore") {
                include "manage-live-score.php";
            } else {
                echo "<h2>Page not found</h2>";
            }
        } else {
            // Default dashboard (stats + quick actions)
            ?>
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
                <button class="action-btn" onclick="location.href='adminDashboard.php?page=teams'">Teams</button>
                <button class="action-btn" onclick="location.href='adminDashboard.php?page=players'">Players</button>
                <button class="action-btn" onclick="location.href='adminDashboard.php?page=fixtures'">Fixtures</button>
                <button class="action-btn" onclick="location.href='adminDashboard.php?page=results'">Match Results</button>
                <button class="action-btn" onclick="location.href='adminDashboard.php?page=performance'">Player Performance</button>
                <button class="action-btn" onclick="location.href='adminDashboard.php?page=livescore'">Update Live Score</button>
            </div>
            <?php
        }
        ?>
    </div>
</body>
</html>
