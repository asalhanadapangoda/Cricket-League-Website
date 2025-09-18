<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminLogin.php");
    exit;
}

require_once "includes/db.php"; 

$teamsQuery = "SELECT team_id, team_name FROM team";
$teamsResult = mysqli_query($conn, $teamsQuery);

$playersResult = null;
$performanceData = null;

if (isset($_POST['load_players'])) {
    $selectedTeam = $_POST['team_id'];
    $playersQuery = "SELECT player_id, first_name, last_name FROM player WHERE team_id = '$selectedTeam'";
    $playersResult = mysqli_query($conn, $playersQuery);
}

if (isset($_POST['load_performance'])) {
    $selectedPlayer = $_POST['player_id'];
    $performanceQuery = "SELECT * FROM player_performance WHERE player_id = $selectedPlayer";
    $performanceResult = mysqli_query($conn, $performanceQuery);
    $performanceData = mysqli_fetch_assoc($performanceResult);
}

if (isset($_POST['update_performance'])) {
    $playerId = $_POST['player_id'];
    $matches = $_POST['number_of_match'];
    $runs = $_POST['runs'];
    $wickets = $_POST['wickets'];
    $updateQuery = "UPDATE player_performance 
                    SET number_of_match = $matches, runs = $runs, wickets = $wickets 
                    WHERE player_id = $playerId";
    mysqli_query($conn, $updateQuery);
    echo "<p style='color:green;'>Performance updated successfully!</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="CSS_File/addPlayerPreformance.css">
</head>
<body>
    <?php include 'adminDashboardNav.php'; ?>

    <!-- Select Team -->
    <div class="form-container">
        <h2>Select Team</h2>
        <form method="POST">
            <select name="team_id" required>
                <option value="">Select Team</option>
                <?php 
                    while ($row = mysqli_fetch_assoc($teamsResult)) {
                        echo "<option value='{$row['team_id']}'>{$row['team_name']}</option>";
                    }
                ?>
            </select>
            <button type="submit" name="load_players">Load Players</button>
            <a href="adminDashboard.php" class="back-btn">Back</a>
        </form>
    </div>

    <!-- Select Player -->
    <?php if ($playersResult): ?>
    <div class="form-container">
        <h2>Select Player</h2>
        <form method="POST">
            <select name="player_id" required>
                <option value="">Select Player</option>
                <?php while ($row = mysqli_fetch_assoc($playersResult)): ?>
                    <option value="<?= $row['player_id'] ?>"><?= $row['first_name'] ?> <?= $row['last_name'] ?></option>
                <?php endwhile; ?>
            </select>
            <button type="submit" name="load_performance">Load Performance</button>
        </form>
    </div>
    <?php endif; ?>

    <!-- Update Performance -->
    <?php if ($performanceData): ?>
    <div class="form-container">
        <h2>Update Performance</h2>
        <form method="POST">
            <input type="hidden" name="player_id" value="<?= $performanceData['player_id'] ?>">
            <label>Matches:</label>
            <input type="number" name="number_of_match" value="<?= $performanceData['number_of_match'] ?>"><br>

            <label>Runs:</label>
            <input type="number" name="runs" value="<?= $performanceData['runs'] ?>"><br>

            <label>Wickets:</label>
            <input type="number" name="wickets" value="<?= $performanceData['wickets'] ?>"><br>

            <button type="submit" name="update_performance">Update</button>
        </form>
    </div>
    <?php endif; ?>
</body>
</html>
