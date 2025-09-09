<?php
require_once "includes/db.php";

// Step 1: Get all team IDs for dropdown
<<<<<<< Updated upstream
$teamResult = mysqli_query($conn, "SELECT team_id FROM point_table");

// Initialize variables
$selectedTeam = null;
$teamData = null;

// Step 2: Load selected team details
if (isset($_POST['load_team'])) {
    $selectedTeam = $_POST['team_id'];
    $result = mysqli_query($conn, "SELECT * FROM point_table WHERE team_id = '$selectedTeam'");
=======
$sql_1 = "SELECT team_id FROM point_table";
$teamResult = mysqli_query($conn, $sql_1);

// Step 2: Handle team selection
$selectedTeam = null;
$teamData = null;

if (isset($_POST['load_team'])) {
    $selectedTeam = $_POST['team_id'];
    $sql = "SELECT * FROM point_table WHERE team_id = '$selectedTeam'";
    $result = mysqli_query($conn, $sql);
>>>>>>> Stashed changes
    $teamData = mysqli_fetch_assoc($result);
}

// Step 3: Handle update
if (isset($_POST['update'])) {
<<<<<<< Updated upstream
    $team_id = $_POST['team_id'];
    $played = $_POST['played'];
    $won = $_POST['won'];
    $lost = $_POST['lost'];
    $no_result = $_POST['no_result'];
    $nrr = $_POST['nrr'];
    $points = $_POST['points'];
=======
    $team_id   = $_POST['team_id'];
    $played    = $_POST['played'];
    $won       = $_POST['won'];
    $lost      = $_POST['lost'];
    $no_result = $_POST['no_result'];
    $nrr       = $_POST['nrr'];
    $points    = $_POST['points'];
>>>>>>> Stashed changes

    $updateQuery = "UPDATE point_table 
                    SET played='$played', won='$won', lost='$lost', no_result='$no_result', nrr='$nrr', points='$points' 
                    WHERE team_id='$team_id'";

    if (mysqli_query($conn, $updateQuery)) {
<<<<<<< Updated upstream
        echo "<script>alert('Data updated successfully!');</script>";
    } else {
        echo "<script>alert('Error updating data!');</script>";
    }
}
?>

=======
       echo "<script>alert('Data updated successfully!');</script>";
    } else {
    echo "<script>alert('Error updating data!');</script>";
    }
}
?>
>>>>>>> Stashed changes
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Point Table</title>
    <link rel="stylesheet" href="CSS_File/updateTeamPointStyle.css">
</head>
<body>
<<<<<<< Updated upstream
<?php include 'adminDashboardNav.php'; ?>

<h2>Update Point Table</h2>

<div class="container">

    <!-- Left card: Select Team -->
    <div class="card">
        <form method="post">
            <label for="team_id">Select Team ID:</label>
            <select name="team_id" required>
                <option value="">Select team</option>
                <?php
                while ($row = mysqli_fetch_assoc($teamResult)) {
                    $selected = ($selectedTeam == $row['team_id']) ? 'selected' : '';
                    echo "<option value='{$row['team_id']}' $selected>{$row['team_id']}</option>";
                }
                ?>
            </select>
            <button type="submit" name="load_team">Load team details</button>
            <a href="adminDashboard.php" class="back-btn">Back</a>
        </form>
    </div>

    <!-- Right card: Team details form -->
    <?php if ($teamData): ?>
        <div class="card">
            <form method="post">
                <input type="hidden" name="team_id" value="<?= $teamData['team_id'] ?>">

                <label>Played:</label>
                <input type="number" name="played" value="<?= $teamData['played'] ?>">

                <label>Won:</label>
                <input type="number" name="won" value="<?= $teamData['won'] ?>">

                <label>Lost:</label>
                <input type="number" name="lost" value="<?= $teamData['lost'] ?>">

                <label>No Result:</label>
                <input type="number" name="no_result" value="<?= $teamData['no_result'] ?>">

                <label>NRR:</label>
                <input type="text" name="nrr" value="<?= $teamData['nrr'] ?>">

                <label>Points:</label>
                <input type="number" name="points" value="<?= $teamData['points'] ?>">

                <button type="submit" name="update">Update</button>
            </form>
        </div>
    <?php endif; ?>

</div>

=======
    <?php include 'adminDashboardNav.php'; ?>

    <h2>Update Point Table</h2>

    <div class="container">
        <!-- Left card: Select Team -->
        <div class="card">
            <form method="post">
                <label for="team_id">Select Team ID:</label>
                <select name="team_id" required>
                    <option value="">Select team</option>
                    <?php 
                    while ($row = mysqli_fetch_assoc($teamResult)) { 
                        echo '<option value="'.$row['team_id'].'"';
                        if ($selectedTeam == $row['team_id']) {
                            echo ' selected';
                        }
                        echo '>'.$row['team_id'].'</option>';
                    }
                    ?>
                </select>
                <button type="submit" name="load_team">Load team details</button>
                <a href="adminDashboard.php" class="back-btn">Back</a>

            </form>
        </div>

        <!-- Right card: Team details form -->
        <?php 
        if ($teamData) { 
            echo '<div class="card">';
            echo '<form method="post">';
            echo '<input type="hidden" name="team_id" value="'.$teamData['team_id'].'">';

            echo '<label>Played:</label>';
            echo '<input type="number" name="played" value="'.$teamData['played'].'">';

            echo '<label>Won:</label>';
            echo '<input type="number" name="won" value="'.$teamData['won'].'">';

            echo '<label>Lost:</label>';
            echo '<input type="number" name="lost" value="'.$teamData['lost'].'">';

            echo '<label>No Result:</label>';
            echo '<input type="number" name="no_result" value="'.$teamData['no_result'].'">';

            echo '<label>NRR:</label>';
            echo '<input type="text" name="nrr" value="'.$teamData['nrr'].'">';

            echo '<label>Points:</label>';
            echo '<input type="number" name="points" value="'.$teamData['points'].'">';

            echo '<button type="submit" name="update">Update</button>';
            echo '</form>';
            echo '</div>';
        } 
        ?>
    </div>
>>>>>>> Stashed changes
</body>
</html>
