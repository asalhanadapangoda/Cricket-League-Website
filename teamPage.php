<?php include 'header.php'; ?>
<?php 
    require_once 'includes/db.php';

    $teamId = isset($_GET['team_id']) ? $_GET['team_id'] : '';

    $team = null;
    if (!empty($teamId)) {
        $sql_team = "SELECT team_id, team_name, logo FROM team WHERE team_id = '" . mysqli_real_escape_string($conn, $teamId) . "'";
        $result_team = mysqli_query($conn, $sql_team);
        if ($result_team && mysqli_num_rows($result_team) === 1) {
            $team = mysqli_fetch_assoc($result_team);
        }
    }

    $result_players = false;
    $result_coaches = false;

    if ($team) {
        $sql_players = "SELECT first_name, last_name, type AS role FROM player WHERE team_id = '" . mysqli_real_escape_string($conn, $team['team_id']) . "'";
        $result_players = mysqli_query($conn, $sql_players);

        $sql_coaches = "SELECT first_name, last_name, role FROM coach WHERE team_id = '" . mysqli_real_escape_string($conn, $team['team_id']) . "'";
        $result_coaches = mysqli_query($conn, $sql_coaches);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS_File/teamPage.css">
</head>
<body class="team-page">
<?php if (!$team) { ?>
    <div class="Main-topic-header">Team not found</div>
<?php } else { ?>
    <div class="team-image">
        <img src="<?php echo htmlspecialchars($team['logo']); ?>" alt="<?php echo htmlspecialchars($team['team_name']); ?> Logo">
    </div>

    <div class="Main-topic-header">TEAM MEMBERS</div>
    <table class="table">
        <thead>
            <tr>
                <th>Player Name</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result_players && mysqli_num_rows($result_players) > 0) {
                while($row = mysqli_fetch_assoc($result_players)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No players found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <div class="Main-topic-header">TEAM COACH</div>

    <table class="table">
        <thead>
            <tr>
                <th>Coach Name</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result_coaches && mysqli_num_rows($result_coaches) > 0) {
                while($row = mysqli_fetch_assoc($result_coaches)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No coaches found</td></tr>";
            }
            ?>
        </tbody>
    </table>
<?php } ?>
<?php mysqli_close($conn); ?>
</body>
</html>
<?php include 'footer.php'; ?>

