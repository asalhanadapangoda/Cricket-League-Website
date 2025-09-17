<?php
session_start();

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
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

$query = "SELECT p.player_id, p.first_name, p.last_name, t.team_name
          FROM player p
          LEFT JOIN team t ON p.team_id = t.team_id
          WHERE CONCAT(p.first_name, ' ', p.last_name) LIKE ?";
$stmt = $conn->prepare($query);
$like_search = "%$search%";
$stmt->bind_param("s", $like_search);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="CSS_File/managePlayers.css">
</head>
<body>

<?php include 'adminDashboardNav.php'; ?>

<div class="container mt-4">

    <div class="d-flex justify-content-between mb-3">
        <form class="form-inline" method="GET" action="">
            <input type="text" name="search" class="form-control mr-2" placeholder="Search by first or last name" value="<?php echo htmlspecialchars($search); ?>">
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
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                <td><?php echo $row['team_name']; ?></td>
                <td>
                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this player?');">
                            <input type="hidden" name="player_id" value="<?php echo $row['player_id']; ?>">
                            <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

</div>
</body>
</html>
