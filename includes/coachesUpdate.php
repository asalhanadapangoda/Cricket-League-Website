<?php
include __DIR__ . '/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['coach_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $role = $_POST['role'];
    $team_id = $_POST['team_id'];

    $sql = "UPDATE coach 
            SET first_name='$first_name', last_name='$last_name', role='$role', team_id='$team_id'
            WHERE coach_id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../adminDashboard.php?page=coaches");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
