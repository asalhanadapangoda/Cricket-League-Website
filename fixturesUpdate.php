<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['match_id'];
    $home_team = $_POST['team1'];   // Home team ID
    $visit_team = $_POST['team2'];  // Visiting team ID
    $date = $_POST['match_date'];
    $time = $_POST['match_time'];

    // Update query for upcoming_match table
    $sql = "UPDATE upcoming_match 
            SET home_team_id='$home_team', visit_team_id='$visit_team', date='$date', time='$time' 
            WHERE match_id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: fixtures.php");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
