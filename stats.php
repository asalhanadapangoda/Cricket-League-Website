<?php include 'header.php'; ?>

<?php
require_once 'includes/db.php';

$sql_1 = "SELECT * FROM teams ORDER BY points DESC, nrr DESC";
$result_point = mysqli_query($conn, $sql_1);

$sql_2 = "SELECT 
        t.logo AS team_logo,
        t.team_name,
        p.first_name,
        p.last_name,
        p.runs AS total_runs
    FROM player p
    JOIN teams t ON p.team_id = t.team_id
    ORDER BY p.runs DESC
    LIMIT 10";

$result_runs = mysqli_query($conn, $sql_2);

$sql_3 = "SELECT 
        t.logo AS team_logo,
        t.team_name,
        p.first_name,
        p.last_name,
        p.wickets AS total_wickets
    FROM player p
    JOIN teams t ON p.team_id = t.team_id
    ORDER BY p.wickets DESC
    LIMIT 5";

$result_wickets = mysqli_query($conn, $sql_3);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS_File/statsStyle.css">
</head>
<body>

    <!-- Most runs -->
    <div class="Main-topic-header">MOST RUNS</div>
    <div class="table">
        <table>
            <thead>
                <tr>
                    <th>POS</th>
                    <th>Player Name</th>
                    <th>Runs</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $pos = 1;
                while($row = mysqli_fetch_assoc($result_runs)) {
                    echo "<tr>";
                    echo "<td>".$pos."</td>";
                    echo "<td><img src='".$row['team_logo']."' alt='logo' class='table-team-logo'> ".$row['first_name']." ".$row['last_name']."</td>";
                    echo "<td>".$row['total_runs']."</td>";
                    echo "</tr>";
                    $pos++;
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Most wickets -->
    <div class="Main-topic-header">MOST WICKETS</div>
    <div class="table">
        <table>
            <thead>
                <tr>
                    <th>POS</th>
                    <th>Player Name</th>
                    <th>Wickets</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $pos = 1;
                while($row = mysqli_fetch_assoc($result_wickets)) {
                    echo "<tr>";
                    echo "<td>".$pos."</td>";
                    echo "<td><img src='".$row['team_logo']."' alt='logo' class='table-team-logo'> ".$row['first_name']." ".$row['last_name']."</td>";
                    echo "<td>".$row['total_wickets']."</td>";
                    echo "</tr>";
                    $pos++;
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Point table -->
    <div class="Main-topic-header">POINTS TABLE</div>
    <div class="table">
        <table>
            <thead>
                <tr>
                    <th>POS</th>
                    <th>TEAM</th>
                    <th>P</th>
                    <th>W</th>
                    <th>L</th>
                    <th>NR</th>
                    <th>NRR</th>
                    <th>PTS</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $pos = 1;
                mysqli_data_seek($result_point, 0); // reset pointer
                while($row = mysqli_fetch_assoc($result_point)) {
                    echo "<tr>";
                    echo "<td>".$pos."</td>";
                    echo "<td><img src='".$row['logo']."' alt='logo' class='table-team-logo'> ".$row['team_name']."</td>";
                    echo "<td>".$row['played']."</td>";
                    echo "<td>".$row['won']."</td>";
                    echo "<td>".$row['lost']."</td>";
                    echo "<td>".$row['no_result']."</td>";
                    echo "<td>".$row['nrr']."</td>";
                    echo "<td><strong>".$row['points']."</strong></td>";
                    echo "</tr>";
                    $pos++;
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>
