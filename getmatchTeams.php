<?php
header('Content-Type: application/json');
require_once 'includes/db.php';

$match_id = $_GET['match_id'] ?? 0;

if (!$match_id) {
    echo json_encode(['error' => 'Match ID is required.']);
    exit;
}

$sql = "SELECT 
            t1.team_id AS home_team_id, t1.team_name AS home_team_name,
            t2.team_id AS visit_team_id, t2.team_name AS visit_team_name
        FROM upcoming_match um
        JOIN team t1 ON um.home_team_id = t1.team_id
        JOIN team t2 ON um.visit_team_id = t2.team_id
        WHERE um.match_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $match_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

echo json_encode($data);

$stmt->close();
$conn->close();
?>