<?php
include __DIR__ . '/db.php'; 

// get team - id and name
$teamsQuery = "SELECT team_id, team_name FROM team";
$teamsResult = mysqli_query($conn, $teamsQuery);

$playersResult = null;
$performanceData = null;

// get add team players
if (isset($_POST['load_players'])) {
    $selectedTeam = $_POST['team_id'];
    $playersQuery = "SELECT player_id, first_name, last_name FROM player WHERE team_id = '$selectedTeam'";
    $playersResult = mysqli_query($conn, $playersQuery);
}

// load selected player preformrnce
if (isset($_POST['load_performance'])) {
    $selectedPlayer = $_POST['player_id'];
    $performanceQuery = "SELECT * FROM player_performance WHERE player_id = $selectedPlayer";
    $performanceResult = mysqli_query($conn, $performanceQuery);
    $performanceData = mysqli_fetch_assoc($performanceResult);
}

// if user change data update database
if (isset($_POST['update_performance'])) {
    $playerId = $_POST['player_id'];
    $matches = $_POST['number_of_match'];
    $runs = $_POST['runs'];
    $wickets = $_POST['wickets'];

    $updateQuery = "UPDATE player_performance 
                    SET number_of_match = $matches, runs = $runs, wickets = $wickets 
                    WHERE player_id = $playerId";
    mysqli_query($conn, $updateQuery);

    // display if data update successfully
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

    <h2>Select Team</h2>

    <form method="POST">
        <select name="team_id" required>
            <option value=""> Select Team </option>

            <!-- load team id and name to drop down -->

           <?php 
                while ($row = mysqli_fetch_assoc($teamsResult)) {
                    echo "<option value='{$row['team_id']}'>
                             {$row['team_name']} ({$row['team_id']})
                          </option>";
                }
            ?>

        </select>
        <button type="submit" name="load_players">Load Players</button>
        <a href="adminDashboard.php" class="back-btn">Back</a>
    </form>

            <!-- load player name to drop down -->
    <?php 
        if ($playersResult) { 
        echo '
        <h2>Select Player</h2>
        <form method="POST">
                <select name="player_id" required>
                <option value=""> Select Player </option>';
    
        while ($row = mysqli_fetch_assoc($playersResult)) {
         echo "<option value='{$row['player_id']}'>
                    {$row['first_name']} {$row['last_name']}
                </option>";
        }

        echo '
                </select>
            <button type="submit" name="load_performance">Load Performance</button>
        </form>';
} 
?>


                    
            <!-- update performance -->
 <?php 
if ($performanceData) {
    echo "
        <h2>Update Performance</h2>
        <form method='POST'>
        <input type='hidden' name='player_id' value='{$performanceData['player_id']}'>

        <label>Matches:</label>
        <input type='number' name='number_of_match' value='{$performanceData['number_of_match']}'><br>

        <label>Runs:</label>
        <input type='number' name='runs' value='{$performanceData['runs']}'><br>

        <label>Wickets:</label>
        <input type='number' name='wickets' value='{$performanceData['wickets']}'><br>

        <button type='submit' name='update_performance'>Update</button>
    </form>";
    }
?>

</body>
</html>
