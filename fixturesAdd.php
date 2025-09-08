<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $home_team = $_POST['team1'];   // Home team
    $visit_team = $_POST['team2'];  // Visiting team
    $date = $_POST['match_date'];
    $time = $_POST['match_time'];

    // Insert into upcoming_match table
    $sql = "INSERT INTO upcoming_match (home_team_id, visit_team_id, date, time) 
            VALUES ('$home_team', '$visit_team', '$date', '$time')";

    if ($conn->query($sql) === TRUE) {
        header("Location: fixtures.php");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
