<?php
header('Content-Type: application/json');
require_once 'includes/db.php';

$team_id = $_GET['team_id'] ?? '';

if (empty($team_id)) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT player_id, first_name, last_name FROM player WHERE team_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $team_id);
$stmt->execute();
$result = $stmt->get_result();

$players = [];
while ($row = $result->fetch_assoc()) {
    $players[] = $row;
}

echo json_encode($players);

$stmt->close();
$conn->close();
?>