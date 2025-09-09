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
$sql = "SELECT p.player_id, p.first_name, p.last_name, p.type, p.number_of_match, p.runs, p.wickets, t.team_name 
        FROM player p 
        LEFT JOIN team t ON p.team_id = t.team_id
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
  <style>
    body{font-family:Arial,Helvetica,sans-serif;padding:0;margin:0}
    .main-content{margin-left:250px;padding:20px} /* Adjust if sidebar width changes */
    table{width:100%;border-collapse:collapse;margin-top:20px}
    th, td{border:1px solid #ccc;padding:8px;text-align:left}
    th{background:#f4f4f4}
    .btn{padding:6px 12px;border:0;border-radius:4px;cursor:pointer}
    .btn.delete{background:#b00020;color:#fff}
    .btn.add{background:#1976d2;color:#fff;margin-bottom:10px}
    .success{color:green;font-weight:bold;margin-bottom:10px}
    .error{color:#b00020;font-weight:bold;margin-bottom:10px}
  </style>
  <?php if($success): ?>
    <!-- Auto refresh after 2 seconds to show success message briefly -->
    <meta http-equiv="refresh" content="2;url=managePlayers.php">
  <?php endif; ?>
</head>
<body>
  <!-- Sidebar Navigation -->
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
          <th>First Name</th>
          <th>Last Name</th>
          <th>Type</th>
          <th>Matches</th>
          <th>Runs</th>
          <th>Wickets</th>
          <th>Team</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($players)): ?>
          <tr><td colspan="9">No players found.</td></tr>
        <?php else: ?>
          <?php foreach ($players as $p): ?>
            <tr>
              <td><?php echo htmlspecialchars($p['player_id']); ?></td>
              <td><?php echo htmlspecialchars($p['first_name']); ?></td>
              <td><?php echo htmlspecialchars($p['last_name']); ?></td>
              <td><?php echo htmlspecialchars($p['type']); ?></td>
              <td><?php echo htmlspecialchars($p['number_of_match']); ?></td>
              <td><?php echo htmlspecialchars($p['runs']); ?></td>
              <td><?php echo htmlspecialchars($p['wickets']); ?></td>
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
