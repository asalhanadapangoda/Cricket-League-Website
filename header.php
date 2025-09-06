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
       <img src="Pictures/LPL_LOGO.webp" alt="Cricket Logo">
      </a>
    </div>

    <!-- Navigation Bar we have to add navigation bar links -->
    <nav class="main-nav">
      <ul class="nav-list">
        <li><a href="homePage.php">HOME</a></li>
        <li class="dropdown">
          <a href="teamPage.php">TEAMS ▾</a>
          <ul class="dropdown-menu">
            <li><a href="Teams/teamKandy.php">B-Love Kandy</a></li>
            <li><a href="Teams/teamDambulla.php">Dambulla Aura</a></li>
            <li><a href="Teams/teamGalle.php">Galle Titans</a></li>
            <li><a href="Teams/teamColombo.php">Colombo Strikers</a></li>
            <li><a href="Teams/teamJaffna.php">Jaffna Kings</a></li>
          </ul>
        </li>
        <li><a href="#">FIXTURES & RESULTS</a></li>
        <li><a href="#">STATS</a></li>
      </ul>
    </nav>

    <!-- Buy Ticket Button in Navigation Bar -->
    <div class="cta">
      <a href="https://lk.bookmyshow.com/events/lanka-premier-league-2023/ET00005021" class="btn-ticket">Buy Ticket</a>
    </div>
  </header>
</body>
</html>



