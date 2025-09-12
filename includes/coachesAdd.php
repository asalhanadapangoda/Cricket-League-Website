<?php
include __DIR__ . '/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST['name'];  // single field from the new form
    $role = $_POST['role'];
    $team_id = $_POST['team_id'];

    // Split full name into first_name and last_name
    $nameParts = explode(' ', $fullName, 2);
    $first_name = $nameParts[0];
    $last_name = $nameParts[1] ?? ''; // if only one word, last_name is empty

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
