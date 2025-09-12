<?php
include __DIR__ . '/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $role = $_POST['role'];
    $team_id = $_POST['team_id'];

    $sql = "INSERT INTO coach (first_name, last_name, role, team_id)
            VALUES ('$first_name', '$last_name', '$role', '$team_id')";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../adminDashboard.php?page=coaches");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
