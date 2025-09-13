<?php
header('Content-Type: application/json');
require_once 'includes/db.php';

// --- Input Data ---
$match_id = $_POST['match_id'];
$is_setup = filter_var($_POST['is_setup'] ?? 'false', FILTER_VALIDATE_BOOLEAN);

// --- Get current score from DB ---
$current_score = ['runs' => 0, 'wickets' => 0, 'balls' => 0, 'innings_no' => 1, 'target' => 0];
$innings_over = false;

$check_sql = "SELECT * FROM live_score WHERE match_id = ?";
$stmt = $conn->prepare($check_sql);
$stmt->bind_param("i", $match_id);
$stmt->execute();
$result = $stmt->get_result();
$is_existing_match = $result->num_rows > 0;

if ($is_existing_match) {
    $row = $result->fetch_assoc();
    $current_score['runs'] = (int)$row['runs'];
    $current_score['wickets'] = (int)$row['wickets'];
    $current_score['innings_no'] = (int)$row['innings_no'];
    $current_score['target'] = (int)$row['target'];
    $overs_parts = explode('.', (string)$row['overs']);
    $full_overs = (int)$overs_parts[0];
    $balls_in_over = isset($overs_parts[1]) ? (int)$overs_parts[1] : 0;
    $current_score['balls'] = ($full_overs * 6) + $balls_in_over;
}
$stmt->close();

// --- Process Ball and Calculate New Score ---
if (!$is_setup) {
    $runs_scored = (int)($_POST['runs_scored'] ?? 0);
    $extras_type = $_POST['extras_type'] ?? 'none';
    $is_wicket = isset($_POST['is_wicket']);

    if ($extras_type === 'wide' || $extras_type === 'noball') {
        $current_score['runs'] += 1;
    }
    $current_score['runs'] += $runs_scored;

    if ($extras_type !== 'wide' && $extras_type !== 'noball') {
        $current_score['balls']++;
    }

    if ($is_wicket) {
        $current_score['wickets']++;
    }

    // --- CHECK FOR END OF INNINGS ---
    // An innings ends after 120 legal balls (20 overs) or 10 wickets.
    if ($current_score['innings_no'] == 1 && ($current_score['balls'] >= 120 || $current_score['wickets'] >= 10)) {
        $innings_over = true;
        $current_score['target'] = $current_score['runs'] + 1; // Set the target for the next team
    }
}

// Convert total balls back to overs format for DB storage
$overs_whole = floor($current_score['balls'] / 6);
$overs_balls = $current_score['balls'] % 6;
$overs_decimal = (float)($overs_whole . '.' . $overs_balls);

// --- Update Database ---
$batting_team_id = $_POST['batting_team_id'];
$bowling_team_id = $_POST['bowling_team_id'];
$striker_id = $_POST['striker_id'];
$non_striker_id = $_POST['non_striker_id'];
$bowler_id = $_POST['bowler_id'];

if ($is_existing_match) {
    if ($innings_over) {
        // First innings has just finished. Reset the score for the second innings.
        $sql = "UPDATE live_score SET innings_no=2, target=?, runs=0, wickets=0, overs=0.0, batting_team_id=?, bowling_team_id=?, striker_id=NULL, non_striker_id=NULL, bowler_id=NULL WHERE match_id=?";
        $stmt = $conn->prepare($sql);
        // Note: Batting and bowling team IDs are swapped here.
        $stmt->bind_param("isssi", $current_score['target'], $bowling_team_id, $batting_team_id, $match_id); 
    } else {
        // Continue the current innings.
        $sql = "UPDATE live_score SET batting_team_id=?, bowling_team_id=?, runs=?, wickets=?, overs=?, striker_id=?, non_striker_id=?, bowler_id=? WHERE match_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiidiidi", $batting_team_id, $bowling_team_id, $current_score['runs'], $current_score['wickets'], $overs_decimal, $striker_id, $non_striker_id, $bowler_id, $match_id);
    }
} else { // This is the very first ball of the match.
    // **FIX**: The original `bind_param` string was incorrect. This is the corrected version.
    $sql = "INSERT INTO live_score (match_id, batting_team_id, bowling_team_id, runs, wickets, overs, striker_id, non_striker_id, bowler_id, innings_no, target) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1, 0)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issiidiis", $match_id, $batting_team_id, $bowling_team_id, $current_score['runs'], $current_score['wickets'], $overs_decimal, $striker_id, $non_striker_id, $bowler_id);
}

$stmt->execute();
$stmt->close();

// Add a signal to the JSON response so the frontend knows the innings is over.
$current_score['innings_over'] = $innings_over;
if ($innings_over) {
    // If the innings is over, reset the score in the response for the frontend display.
    $current_score['runs'] = 0;
    $current_score['wickets'] = 0;
    $current_score['balls'] = 0;
    $current_score['innings_no'] = 2;
}


echo json_encode($current_score);
?>