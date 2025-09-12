<?php
// managePlayers.php
session_start();

// Access control
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminLogin.php");
    exit;
}

require_once 'includes/db.php';

$success = "";
$errors = [];

// Handle delete request safely
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    if ($delete_id > 0) {
        $stmt = $conn->prepare("DELETE FROM player WHERE player_id = ?");
        $stmt->bind_param("i", $delete_id);
        if ($stmt->execute()) $success = "Player deleted successfully.";
        else $errors[] = "Database error: " . $stmt->error;
        $stmt->close();
    } else $errors[] = "Invalid player ID.";
}

// Fetch all players
$players = [];
$sql = "SELECT p.player_id, CONCAT(p.first_name, ' ', p.last_name) AS full_name, t.team_name 
        FROM player p 
        LEFT JOIN teams t ON p.team_id = t.team_id
        ORDER BY p.player_id ASC";
$res = $conn->query($sql);
if ($res) while ($row = $res->fetch_assoc()) $players[] = $row;
else $errors[] = "Database error: " . $conn->error;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Manage Players</title>
  <link rel="stylesheet" href="managePlayers.css">
  <?php if($success): ?>
    <meta http-equiv="refresh" content="2;url=managePlayers.php">
  <?php endif; ?>
</head>
<body>
  <?php include 'adminDashboardNav.php'; ?>

  <div class="main-content">
    <h2>Manage Players</h2>

    <button class="btn add" onclick="location.href='player.php'">Add New Player</button>

    <?php if ($success): ?><div class="success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
    <?php if (!empty($errors)): ?><div class="error"><?php echo implode('<br>', array_map('htmlspecialchars',$errors)); ?></div><?php endif; ?>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Team</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($players)): ?>
          <tr><td colspan="4">No players found.</td></tr>
        <?php else: ?>
          <?php foreach ($players as $p): ?>
            <tr>
              <td><?php echo htmlspecialchars($p['player_id']); ?></td>
              <td><?php echo htmlspecialchars($p['full_name']); ?></td>
              <td><?php echo htmlspecialchars($p['team_name']); ?></td>
              <td>
                <a class="btn delete" href="managePlayers.php?delete_id=<?php echo $p['player_id']; ?>"
                   onclick="return confirm('Are you sure you want to delete this player?');">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
