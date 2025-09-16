<?php include 'header.php'; ?>
<?php 
    require_once 'includes/db.php';
    $sql_1 = "SELECT 
            p.first_name,
            p.last_name,
            p.type AS role
          FROM player p
          JOIN player_performance pp ON p.player_id = pp.player_id
          WHERE p.team_id = 'T04'";


    $result_players = mysqli_query($conn, $sql_1);

    $sql_2 = "SELECT 
                c.first_name,
                c.last_name,
                c.role 
              FROM coach c
              JOIN team t ON c.team_id = t.team_id
              WHERE t.team_id = 'T04'";

    $result_coaches = mysqli_query($conn, $sql_2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS_File/teamStyle.css">
</head>
<body class="jaffna">
    <div class="team-image">
        <img src="Pictures/Jaffna-1.png" alt="Colombo Kings Logo">
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
            if (mysqli_num_rows($result_players) > 0) {
                while($row = mysqli_fetch_assoc($result_players)) {
                    echo "<tr>";
                    echo "<td>" . $row['first_name'] . " " . $row['last_name'] . "</td>";
                    echo "<td>" . $row['role'] . "</td>";
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
            if (mysqli_num_rows($result_coaches) > 0) {
                while($row = mysqli_fetch_assoc($result_coaches)) {
                    echo "<tr>";
                    echo "<td>" . $row['first_name'] . " " . $row['last_name'] . "</td>";
                    echo "<td>" . $row['role'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No Coachers found</td></tr>";
            }
            mysqli_close($conn);
            ?>
        </tbody>
    </table>
</body>
</html>
<?php include 'footer.php'; ?>