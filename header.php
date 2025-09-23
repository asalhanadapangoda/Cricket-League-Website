<?php
require_once __DIR__ . '/includes/db.php';

$teams = [];
if (isset($conn) && $conn) {
  $sql = "SELECT team_id, team_name FROM team ORDER BY team_name";
  if ($result = mysqli_query($conn, $sql)) {
    while ($row = mysqli_fetch_assoc($result)) {
      $teams[] = $row;
    }
    mysqli_free_result($result);
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cricket Website</title>
  <link rel="stylesheet" href="CSS_File/headerStyle.css">
</head>
<body>
  <header class="header">
    <!-- LPL Logo -->
    <div class="logo-container">
      <a href="homePage.php">
        <img src="Pictures/lpl_logo.jpg" alt="Cricket Logo">
      </a>
    </div>

    

    <!-- Navigation Bar -->
    <nav class="main-nav">
      <ul class="nav-list">
        <li><a href="homePage.php">HOME</a></li>
        <li class="dropdown">
          <a href="">TEAMS ▾</a>
          <ul class="dropdown-menu">
<?php
foreach ($teams as $team) {
  echo '<li><a href="teamPage.php?team_id=' . htmlspecialchars($team['team_id']) . '">' . htmlspecialchars($team['team_name']) . '</a></li>';
}
?>
          </ul>
        </li>
        <li><a href="fixturesAndResults.php">FIXTURES & RESULTS</a></li>
        <li><a href="stats.php">STATS</a></li>
        <li><a href="playerCompare.php">PLAYER COMPARE</a></li>
        <li><a href="livescore.php">LIVE SCORE</a></li>
      </ul>
    </nav>

    <!-- Buy Ticket Button -->
    <div class="cta">
      <a href="https://lk.bookmyshow.com/events/lanka-premier-league-2023/ET00005021" class="btn-ticket">Buy Ticket</a>
    </div>
  </header>
</body>
</html>
