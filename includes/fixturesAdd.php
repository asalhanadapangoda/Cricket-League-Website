<?php 
include __DIR__ . '/db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $home_team = $_POST['team1'];
    $visit_team = $_POST['team2'];
    $date = $_POST['match_date'];
    $time = $_POST['match_time'];

    // Insert using AUTO_INCREMENT; don't manually assign match_id
    $sql = "INSERT INTO upcoming_match (home_team_id, visit_team_id, date, time) 
            VALUES ('$home_team', '$visit_team', '$date', '$time')";

    if ($conn->query($sql) === TRUE) {
        // Redirect back to fixtures page
        header("Location: ../adminDashboard.php?page=fixtures");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
