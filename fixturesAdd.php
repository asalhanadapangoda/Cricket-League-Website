<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $team1 = $_POST['team1'];
    $team2 = $_POST['team2'];
    $date = $_POST['match_date'];
    $time = $_POST['match_time'];
    $venue = $_POST['venue'];
    $status = $_POST['status'];

    $sql = "INSERT INTO fixtures (team1, team2, match_date, match_time, venue, status) 
            VALUES ('$team1', '$team2', '$date', '$time', '$venue', '$status')";
    if ($conn->query($sql) === TRUE) {
        header("Location: fixtures.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
