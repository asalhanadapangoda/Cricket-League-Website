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

    <!-- Hamburger Checkbox -->
    <input type="checkbox" id="menu-toggle" class="menu-toggle">
    <label for="menu-toggle" class="hamburger">☰</label>

    <!-- Navigation Bar -->
    <nav class="main-nav">
      <ul class="nav-list">
        <li><a href="homePage.php">HOME</a></li>
        <li class="dropdown">
          <a href="teamPage.php">TEAMS ▾</a>
          <ul class="dropdown-menu">
            <li><a href="teamKandy.php">B-Love Kandy</a></li>
            <li><a href="teamDambulla.php">Dambulla Aura</a></li>
            <li><a href="teamGalle.php">Galle Titans</a></li>
            <li><a href="teamColombo.php">Colombo Strikers</a></li>
            <li><a href="teamJaffna.php">Jaffna Kings</a></li>
          </ul>
        </li>
        <li><a href="fixturesAndResults.php">FIXTURES & RESULTS</a></li>
        <li><a href="stats.php">STATS</a></li>
      </ul>
    </nav>

    <!-- Buy Ticket Button -->
    <div class="cta">
      <a href="https://lk.bookmyshow.com/events/lanka-premier-league-2023/ET00005021" class="btn-ticket">Buy Ticket</a>
    </div>
  </header>
</body>
</html>
