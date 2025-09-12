<?php include 'header.php'; ?>
 <!-- Get team data-->
<?php
require_once 'includes/db.php';

$sql ="SELECT t.team_id, t.team_name, t.logo, p.played, p.won, p.lost, p.no_result, p.nrr, p.points
          FROM team t
          JOIN point_table p ON t.team_id = p.team_id
          ORDER BY p.points DESC, p.nrr DESC";

$result = mysqli_query($conn, $sql);

//rm.* means shortcut of rm.match_id, rm.date,.... this
$sql_1 = "SELECT rm.*, 
               t1.team_name AS home_team, t1.logo AS home_logo,
               t2.team_name AS visit_team, t2.logo AS visit_logo
        FROM recent_match rm
        JOIN team t1 ON rm.home_team_id = t1.team_id
        JOIN team t2 ON rm.visit_team_id = t2.team_id
        ORDER BY rm.date DESC
        LIMIT 1";

$sql_2 = "SELECT um.*,
               t1.team_name AS home_team, t1.logo AS home_logo,
               t2.team_name AS visit_team, t2.logo AS visit_logo
          FROM upcoming_match um
          JOIN team t1 ON um.home_team_id = t1.team_id
          JOIN team t2 ON um.visit_team_id = t2.team_id
          ORDER BY date ASC, time ASC
          LIMIT 1";

$result_pre = mysqli_query($conn, $sql_1);
$result_next = mysqli_query($conn, $sql_2);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="CSS_File/homePageStyle.css">
</head>
<body>

  <main>
    <!-- Home Page Image -->
    <div class="banner">
      <img src="Pictures/Home_img.jpg" alt="Cricket Banner">
    </div>

    <?php

      // Next Match
      if($row = mysqli_fetch_assoc($result_next)){
        
    echo '  <div class="card">';
    echo '     <div class="card-header">';
    echo '          Next Match';
    echo '      </div>';
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

    // Recent Results  

    if ($row = mysqli_fetch_assoc($result_pre)){
    echo '<div class="match-card">';
    echo '    <div class="card2-header">';
    echo '            Recent Results';
    echo '    </div>';
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
    ?>

      <!-- Button-FIXTURES & RESULTS -->

    <div>
      <a href="fixturesAndResults.php" class="button-fixtures-results">Fixtures and Results</a>
    </div>

      <!-- POINTS TABLE  -->

    <div class="Main-topic-header">POINTS TABLE</div>

    <div class="points-table">
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
      while($row = mysqli_fetch_assoc($result)) {
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
      mysqli_close($conn);
      ?>
    </tbody>
  </table>
</div>

       

      <!-- TEAMS -->

    <div class="Main-topic-header">TEAMS</div>

    <div class="Teams-Logo">
      <a href="teamKandy.php">
        <img src="Pictures/Kandy-1.png" alt="B-Love Kandy Logo">
      </a>
      <a href="teamDambulla.php">
        <img src="Pictures/DabullaLogo.png" alt="Dambulla Aura Logo">
      </a>
      <a href="teamColombo.php">
       <img src="Pictures/Colombo-1.png" alt="Colombo Logo">
      </a>
      <a href="teamJaffna.php">
        <img src="Pictures/Jaffna-1.png" alt="Jaffna Logo">
      </a>
      <a href="teamGalle.php">
       <img src="Pictures/Galle-1.png" alt="Galle Logo">
      </a>
   </div>
  </main>
</body>
</html>
<?php include 'footer.php'; ?>

