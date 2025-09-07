<?php include 'header.php'; ?>

<?php
require_once 'includes/db.php';
//rm.* means shortcut of rm.match_id, rm.date,.... this
$sql_1 = "SELECT rm.*, 
               t1.team_name AS home_team, t1.logo AS home_logo,
               t2.team_name AS visit_team, t2.logo AS visit_logo
        FROM recent_match rm
        JOIN teams t1 ON rm.home_team_id = t1.team_id
        JOIN teams t2 ON rm.visit_team_id = t2.team_id
        ORDER BY rm.date ASC";

$sql_2 = "SELECT um.*,
               t1.team_name AS home_team, t1.logo AS home_logo,
               t2.team_name AS visit_team, t2.logo AS visit_logo
          FROM upcoming_match um
          JOIN teams t1 ON um.home_team_id = t1.team_id
          JOIN teams t2 ON um.visit_team_id = t2.team_id
          ORDER BY date ASC, time ASC";

$result_pre = mysqli_query($conn, $sql_1);
$result_next = mysqli_query($conn, $sql_2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS_File/fixturesAndResultsStyle.css">
</head>
<body>
    <div class="Main-topic-header">Fixtures & Results</div>
    <?php
    //Recent matches
    while ($row = mysqli_fetch_assoc($result_pre)){
    echo '<div class="match-card">';
    echo '    <div class="match-date">' . date("F d, Y", strtotime($row['date'])) . '</div>';

    echo '    <div class="teams">';
    echo '        <div class="team">';
    echo '            <img src="' . $row['home_logo'] . '" alt="' . $row['home_team'] . ' Logo" width="80">';
    echo '            <p>' . $row['home_team'] . '</p>';
    echo '            <p>' . $row['home_team_runs'] . '/' . $row['home_team_wickets'] . '</p>';
    echo '            <p>(' . $row['home_team_overs'] . ')</p>';
    echo '        </div>';

    echo '        <div class="team">';
    echo '            <img src="' . $row['visit_logo'] . '" alt="' . $row['visit_team'] . ' Logo" width="80">';
    echo '            <p>' . $row['visit_team'] . '</p>';
    echo '            <p>' . $row['visit_team_runs'] . '/' . $row['visit_team_wickets'] . '</p>';
    echo '            <p>(' . $row['visit_team_overs'] . ')</p>';
    echo '        </div>';
    echo '    </div>';

    echo '    <div class="result"><strong>' . $row['final_result'] . '</strong></div>';
    echo '</div>';
}

    //Upcoming matches
    while($row = mysqli_fetch_assoc($result_next)){
        
    echo '  <div class="card">';
    echo '     <div class="match-details">';
    echo '        <div class="team">';
    echo '        <img src="' . $row['home_logo'] . '" alt="' . $row['home_team'] . ' Logo">';
    echo '        <p>' . $row['home_team'] . '</p>';
    echo '        </div>';

    echo '        <div class="vs">';
    echo '         VS';
    echo '        </div>';

    echo '        <div class="team">';
    echo '         <img src="' . $row['visit_logo'] . '" alt="' . $row['visit_team'] . ' Logo">';
    echo '         <p>' . $row['visit_team'] . '</p>';
    echo '        </div>';      
    echo '    </div>';
    echo '      <div class="NextMatchTime">';
    echo '         Match starts in: ' . date("F d, Y", strtotime($row['date'])) .' ' . $row['time'] . '';
    echo '      </div>';
    echo '    </div>';
    }

mysqli_close($conn);
?>
</body>
</html>
<?php include 'footer.php'; ?>