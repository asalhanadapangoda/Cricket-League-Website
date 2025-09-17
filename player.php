<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminLogin.php");
    exit;
}

require_once 'includes/db.php'; // Database connection

// Insert player into database
if (isset($_POST['add_player'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $type = $_POST['type'];
    $team_id = $_POST['team_id'];

    $stmt = $conn->prepare("INSERT INTO player (first_name, last_name, type, team_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $first_name, $last_name, $type, $team_id);

    if ($stmt->execute()) {
        $stmt->close();
        // Redirect to managePlayers.php after successful insert
        header("Location: managePlayers.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Cancel button
if (isset($_POST['cancel'])) {
    header("Location: managePlayers.php");
    exit;
}

// Fetch teams for dropdown
$teams = $conn->query("SELECT team_id, team_name FROM team ORDER BY team_name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="CSS_File/player.css">
</head>
<body>

  <?php include 'adminDashboardNav.php'; ?>

    <form method="POST">
      <h2>Add Player</h2>
        <label>First Name</label>
        <input type="text" name="first_name" required>

        <label>Last Name</label>
        <input type="text" name="last_name" required>

        <label>Type</label>
        <select name="type" required>
            <option value="">Select type</option>
            <option value="Batsman">Batsman</option>
            <option value="Captain/Batsman">Captain/Batsman</option>
            <option value="Bowler">Bowler</option>
            <option value="All-Rounder">All-Rounder</option>
            <option value="Wicket-Keeper">Wicket-Keeper</option>
        </select>

        <label>Team</label>
        <select name="team_id" required>
            <option value="">Select team</option>
            <?php while($row = $teams->fetch_assoc()): ?>
                <option value="<?= htmlspecialchars($row['team_id']) ?>"><?= htmlspecialchars($row['team_name']) ?></option>
            <?php endwhile; ?>
        </select>

        <div class="btn-group">
            <button type="submit" name="add_player" class="btn-add">Add Player</button>
             <button type="button" class="btn-cancel" onclick="window.location.href='managePlayers.php';">Cancel</button>
        </div>
    </form>
</body>
</html>
