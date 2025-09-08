<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['match_id'];
    $team1 = $_POST['team1'];
    $team2 = $_POST['team2'];
    $date = $_POST['match_date'];
    $time = $_POST['match_time'];
    $venue = $_POST['venue'];
    $status = $_POST['status'];

    $sql = "UPDATE fixtures SET team1='$team1', team2='$team2', match_date='$date', match_time='$time', venue='$venue', status='$status' WHERE match_id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: fixtures.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
