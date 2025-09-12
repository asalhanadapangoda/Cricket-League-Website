<?php
include __DIR__ . '/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['match_id'];
    $home_team = $_POST['team1'];
    $visit_team = $_POST['team2'];
    $date = $_POST['match_date'];
    $time = $_POST['match_time'];

    $sql = "UPDATE upcoming_match 
            SET home_team_id='$home_team', visit_team_id='$visit_team', date='$date', time='$time' 
            WHERE match_id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: /Cricket-League-Website/adminDashboard.php?page=fixtures");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

