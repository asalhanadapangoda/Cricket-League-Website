<?php include 'header.php'; ?>
 <!-- Get team data-->
<?php
require_once 'includes/db.php';

$sql = "SELECT * FROM teams ORDER BY points DESC, nrr DESC";
$result = mysqli_query($conn, $sql);
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

    <!-- Next Match-->

    <div class="card">
   
        <div class="card-header">
         Next Match
        </div>
    
  
      <div class="match-details">
          <div class="team">
            <img src="Pictures/DabullaLogo.png" alt="Team A Logo">
            <p>Dambulla Aura</p>
          </div>
      
          <div class="vs">
             VS
          </div>

          <div class="team">
             <img src="Pictures/Kandy-1.png" alt="Team B Logo">
             <p>B-Love Kandy</p>
          </div>      
      </div>
          <div class="NextMatchTime">
             Match starts in: August 20, 2023 8:30 PM
          </div>
      </div>

    <!-- Recent Results  -->

     <div class="match-card">
        <div class="card2-header">
         Recent Results
        </div>
        <div class="match-date">
            August 20, 2023
        </div>

        <div class="teams">
            <div class="team">
                <img src="Pictures/DabullaLogo.png" alt="Dambulla Aura Logo" width="80">
                <p>Dambulla Aura</p>
                <p>147/4</p>
                <p>(20)</p>
            </div>

            <div class="team">
                <img src="Pictures/Kandy-1.png" alt="B-Love Kandy Logo" width="80">
                <p>B-Love Kandy</p>
                <p>151/5</p>
                <p>(19.5)</p>
            </div>
        </div>

        <div class="result">
            <strong>B-Love Kandy won by 5 wickets</strong>
        </div>

        <div class="links">
            T20 24 of 24 
        </div>
    </div>

      <!-- Button-FIXTURES & RESULTS We have to add direction to FIXTURES & RESULTS-->

    <div>
      <button class="fixtures-results" onclick="">FIXTURES & RESULTS</button>
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
      ?>
    </tbody>
  </table>
</div>

       

      <!-- TEAMS We have to add direction to team page -->

    <div class="Main-topic-header">TEAMS</div>

    <div class="Teams-Logo">
      <a href="kandy.html">
        <img src="Pictures/Kandy-1.png" alt="B-Love Kandy Logo">
      </a>
      <a href="dambulla.html">
        <img src="Pictures/DabullaLogo.png" alt="Dambulla Aura Logo">
      </a>
      <a href="colombo.html">
       <img src="Pictures/Colombo-1.png" alt="Colombo Logo">
      </a>
      <a href="jaffna.html">
        <img src="Pictures/Jaffna-1.png" alt="Jaffna Logo">
      </a>
      <a href="galle.html">
       <img src="Pictures/Galle-1.png" alt="Galle Logo">
      </a>
   </div>

    <!-- MEDIA We have to add some media release -->


  </main>
</body>
</html>
<?php include 'footer.php'; ?>

