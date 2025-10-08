<?php
require_once 'includes/db.php';

$sql = "SELECT ls.*, 
        t1.team_name AS batting_team_name,
        t2.team_name AS bowling_team_name,
        CONCAT(p1.first_name, ' ', p1.last_name) AS striker_name,
        CONCAT(p2.first_name, ' ', p2.last_name) AS non_striker_name,
        CONCAT(p3.first_name, ' ', p3.last_name) AS bowler_name
        FROM live_score ls 
        LEFT JOIN team t1 ON ls.batting_team_id = t1.team_id
        LEFT JOIN team t2 ON ls.bowling_team_id = t2.team_id
        LEFT JOIN player p1 ON ls.striker_id = p1.player_id
        LEFT JOIN player p2 ON ls.non_striker_id = p2.player_id
        LEFT JOIN player p3 ON ls.bowler_id = p3.player_id
        ORDER BY ls.id DESC 
        LIMIT 1";

$result = mysqli_query($conn, $sql);

if ($row = mysqli_fetch_assoc($result)) {
    $innings_no = $row['innings_no'];
    $target = $row['target'];
    $runs = $row['runs'];
    $wickets = $row['wickets'];
    $overs = $row['overs'];
    $batting_team_name = $row['batting_team_name'];
    $bowling_team_name = $row['bowling_team_name'];

    $match_over = false;
    $result_message = "";

    if ($innings_no == 2) {
        if ($runs >= $target) {
            $match_over = true;
            $wickets_left = 10 - $wickets;
            $result_message = "$batting_team_name won by $wickets_left wickets.";
        } elseif ($wickets >= 10 || $overs >= 20.0) {
            $match_over = true;
            if ($runs < $target - 1) {
                $run_diff = $target - $runs -1;
                $result_message = "$bowling_team_name won by $run_diff runs.";
            } else {
                $result_message = "Match tied.";
            }
        }
    }

    echo "<div class='score-card-live'>";
    echo "    <h2>{$row['batting_team_name']} vs {$row['bowling_team_name']}</h2>";
    if ($row['innings_no'] == 2 && $row['target'] > 0) {
        echo "    <div class='target' style='color: #f59e0b; font-size: 22px; margin-bottom: 15px;'>Target: {$row['target']}</div>";
    }
    echo "    <div class='main-score'>{$row['batting_team_name']}: <strong>{$row['runs']}/{$row['wickets']}</strong> ({$row['overs']})</div>";
    
    if ($match_over) {
        echo "<h2>$result_message</h2>";
    } else {
        echo "    <div class='player-details'>";
        echo "        <p><strong>{$row['striker_name']}*</strong></p>";
        echo "        <p>{$row['non_striker_name']}</p>";
        echo "        <p>Bowler: {$row['bowler_name']}</p>";
        echo "    </div>";
    }
    
    echo "</div>";
} else {
    echo "<p class='no-live-match'>No live match at the moment. Check back later!</p>";
}

mysqli_close($conn);
?>