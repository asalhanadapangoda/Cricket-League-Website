<?php
header('Content-Type: application/json');
require_once 'includes/db.php';

// --- Input Data ---
$match_id = $_POST['match_id'];
$is_setup = filter_var($_POST['is_setup'] ?? 'false', FILTER_VALIDATE_BOOLEAN);

// --- Get current score from DB ---
$current_score = ['runs' => 0, 'wickets' => 0, 'balls' => 0];
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
    // **FIX**: Correctly calculate total balls from overs format (e.g., 5.5 -> 35 balls)
    $overs_parts = explode('.', (string)$row['overs']);
    $full_overs = (int)$overs_parts[0];
    $balls_in_over = isset($overs_parts[1]) ? (int)$overs_parts[1] : 0;
    $current_score['balls'] = ($full_overs * 6) + $balls_in_over;
}
$stmt->close();

// --- Process Ball and Calculate New Score (only if not a setup call) ---
if (!$is_setup) {
    $runs_scored = (int)($_POST['runs_scored'] ?? 0);
    $extras_type = $_POST['extras_type'] ?? 'none';
    $is_wicket = isset($_POST['is_wicket']);

    // Add runs based on type
    if ($extras_type === 'wide' || $extras_type === 'noball') {
        $current_score['runs'] += 1; // 1 run for the extra itself
    }
    $current_score['runs'] += $runs_scored; // Add runs hit by batsman

    // Increment balls only on legal deliveries
    if ($extras_type !== 'wide' && $extras_type !== 'noball') {
        $current_score['balls']++;
    }

    // Add wickets if one occurred
    if ($is_wicket) {
        $current_score['wickets']++;
    }
}

// Convert total balls back to overs format for DB storage and display
$overs_whole = floor($current_score['balls'] / 6);
$overs_balls = $current_score['balls'] % 6;
$overs_decimal = (float)($overs_whole . '.' . $overs_balls);

// --- Update Database ---
$batting_team_id = $_POST['batting_team_id'];
$bowling_team_id = $_POST['bowling_team_id'];
$striker_id = $_POST['striker_id'];
$non_striker_id = $_POST['non_striker_id'];
$bowler_id = $_POST['bowler_id'];

// **FIX**: Corrected the bind_param types to match the database schema
if ($is_existing_match) {
    $sql = "UPDATE live_score SET batting_team_id=?, bowling_team_id=?, runs=?, wickets=?, overs=?, striker_id=?, non_striker_id=?, bowler_id=? WHERE match_id=?";
    $stmt = $conn->prepare($sql);
    // Correct types: s, s, i, i, d, i, i, i, i
    $stmt->bind_param("ssiidiidi", $batting_team_id, $bowling_team_id, $current_score['runs'], $current_score['wickets'], $overs_decimal, $striker_id, $non_striker_id, $bowler_id, $match_id);
} else {
    $sql = "INSERT INTO live_score (match_id, batting_team_id, bowling_team_id, runs, wickets, overs, striker_id, non_striker_id, bowler_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    // Correct types: i, s, s, i, i, d, i, i, i
    $stmt->bind_param("issiidiidi", $match_id, $batting_team_id, $bowling_team_id, $current_score['runs'], $current_score['wickets'], $overs_decimal, $striker_id, $non_striker_id, $bowler_id);
}

$stmt->execute();
$stmt->close();

// --- Return the new, accurate score to the frontend ---
echo json_encode($current_score);
?>