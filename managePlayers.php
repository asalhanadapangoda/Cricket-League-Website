<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminLogin.php");
    exit;
}

require_once 'includes/db.php'; // Your database connection

// Delete player
if (isset($_POST['delete'])) {
    $delete_id = intval($_POST['player_id']); // Ensure it's an integer
    $stmt = $conn->prepare("DELETE FROM player WHERE player_id = ?");
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        $stmt->close();
        $success_msg = "Player deleted successfully!";
    } else {
        $error_msg = "Error deleting player: " . $stmt->error;
    }
}

// Search players
$search = "";
if (isset($_GET['player_search'])) {
    $search = trim($_GET['player_search']);
}

if ($search !== "") {
    $sql = "SELECT p.player_id, p.first_name, p.last_name, t.team_name
              FROM player p
              LEFT JOIN team t ON p.team_id = t.team_id
              WHERE LOWER(p.first_name) LIKE ?
                 OR LOWER(p.last_name) LIKE ?
                 OR LOWER(CONCAT(p.first_name, ' ', p.last_name)) LIKE ?
              ORDER BY p.first_name, p.last_name";
    $stmt = $conn->prepare($sql);
    $like = '%' . mb_strtolower($search, 'UTF-8') . '%';
    $stmt->bind_param("sss", $like, $like, $like);
} else {
    $sql = "SELECT p.player_id, p.first_name, p.last_name, t.team_name
              FROM player p
              LEFT JOIN team t ON p.team_id = t.team_id
              ORDER BY p.first_name, p.last_name";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

// Fetch all to avoid any cursor issues
$players = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $players[] = $row;
    }
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="CSS_File/managePlayers.css">
</head>
<body>

<?php include 'adminDashboardNav.php'; ?>

<div class="container">

    <div class="d-flex">
        <form class="form-inline" method="GET" action="managePlayers.php">
            <input type="text" name="player_search" class="form-control mr-2" placeholder="Search by first or last name" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
        <a href="player.php" class="btn btn-success">Add New Player</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="thead-primary">
            <tr class="bg-primary text-white">
                <th>Name</th>
                <th>Team</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($players as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                <td><?php echo htmlspecialchars($row['team_name']); ?></td>
                <td>
                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this player?');">
                            <input type="hidden" name="player_id" value="<?php echo $row['player_id']; ?>">
                            <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>
</body>
</html>
