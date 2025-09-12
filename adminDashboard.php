<?php
session_start();

// Prevent unauthorized access
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminLogin.php");
    exit;
}

require_once 'includes/db.php';

// Stats
$teamCount = 0;
$playerCount = 0;

// Get number of teams
$teamQuery = "SELECT COUNT(*) as count FROM team"; 
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
    <?php
    // Check if a sidebar link is selected
    if (isset($_GET['page'])) {
        $page = $_GET['page'];

        // Only allow specific pages
        switch($page) {
            case 'coaches':
                include __DIR__ . '/includes/coaches.php';
                break;
            case 'players':
                include __DIR__ . '/includes/players.php';
                break;
            case 'fixtures':
                include __DIR__ . '/includes/fixtures.php';
                break;
            case 'results':
                include __DIR__ . '/includes/manage-results.php';
                break;
            case 'performance':
                include __DIR__ . '/includes/manage-player-performance.php';
                break;
            case 'livescore':
                include __DIR__ . '/includes/manage-live-score.php';
                break;
            default:
                echo "<h2>Page not found</h2>";
        }

    } else {
        // Default dashboard view
        ?>
        <div class="header">
            <div class="welcome">Welcome, <?php echo $_SESSION['admin_name'] ?? 'Admin'; ?>!</div>
            <button class="logout-btn" onclick="location.href='homePage.php'">Logout</button>
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
            <button class="action-btn" onclick="location.href='updateTeamPoint.php'">Update Team Point</button>
            <button class="action-btn" onclick="location.href='adminDashboard.php?page=coaches'">Team Coach</button>
            <button class="action-btn" onclick="location.href='adminDashboard.php?page=players'">Players</button>
            <button class="action-btn" onclick="location.href='addPlayerPreformance.php'">Player Performance</button>
            <button class="action-btn" onclick="location.href='adminDashboard.php?page=fixtures'">Fixtures</button>
            <button class="action-btn" onclick="location.href='adminDashboard.php?page=results'">Match Results</button>
            <button class="action-btn" onclick="location.href='adminDashboard.php?page=livescore'">Update Live Score</button>
        </div>
        <?php
    }
    ?>
</div>

</body>
</html>
