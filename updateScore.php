<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminLogin.php");
    exit;
}

header('Content-Type: application/json');
require_once 'includes/db.php';

$match_id = (int)($_POST['match_id'] ?? 0);
$is_setup = filter_var($_POST['is_setup'] ?? 'false', FILTER_VALIDATE_BOOLEAN);
$batting_team_id = $_POST['batting_team_id'] ?? null;
$bowling_team_id = $_POST['bowling_team_id'] ?? null;
$striker_id = isset($_POST['striker_id']) && !empty($_POST['striker_id']) ? (int)$_POST['striker_id'] : null;
$non_striker_id = isset($_POST['non_striker_id']) && !empty($_POST['non_striker_id']) ? (int)$_POST['non_striker_id'] : null;
$bowler_id = isset($_POST['bowler_id']) && !empty($_POST['bowler_id']) ? (int)$_POST['bowler_id'] : null;

$latest_innings_sql = "SELECT * FROM live_score WHERE match_id = ? ORDER BY id DESC LIMIT 1";
$stmt = $conn->prepare($latest_innings_sql);
$stmt->bind_param("i", $match_id);
$stmt->execute();
$result = $stmt->get_result();
$latest_innings_row = $result->fetch_assoc();
$stmt->close();

if ($is_setup) {
    if ($latest_innings_row && $latest_innings_row['innings_no'] == 1) { 
        $target = $latest_innings_row['target'];
        $sql = "INSERT INTO live_score (match_id, batting_team_id, bowling_team_id, runs, wickets, overs, striker_id, non_striker_id, bowler_id, innings_no, target) VALUES (?, ?, ?, 0, 0, 0.0, ?, ?, ?, 2, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issiiii", $match_id, $batting_team_id, $bowling_team_id, $striker_id, $non_striker_id, $bowler_id, $target);
    } else { 
        $sql = "INSERT INTO live_score (match_id, batting_team_id, bowling_team_id, runs, wickets, overs, striker_id, non_striker_id, bowler_id, innings_no, target) VALUES (?, ?, ?, 0, 0, 0.0, ?, ?, ?, 1, 0)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issiii", $match_id, $batting_team_id, $bowling_team_id, $striker_id, $non_striker_id, $bowler_id);
    }
    $stmt->execute();
    $stmt->close();
    echo json_encode(['runs' => 0, 'wickets' => 0, 'balls' => 0, 'innings_over' => false]);
    exit;
}

if (!$latest_innings_row) {
    echo json_encode(['error' => 'Match not set up yet.']);
    exit;
}

$current_score = [
    'runs' => (int)$latest_innings_row['runs'],
    'wickets' => (int)$latest_innings_row['wickets'],
    'innings_no' => (int)$latest_innings_row['innings_no'],
    'target' => (int)$latest_innings_row['target']
];
$overs_parts = explode('.', (string)$latest_innings_row['overs']);
$current_score['balls'] = ((int)$overs_parts[0] * 6) + ((int)($overs_parts[1] ?? 0));

$innings_over = false;

$runs_scored = (int)($_POST['runs_scored'] ?? 0);
$extras_type = $_POST['extras_type'] ?? 'none';
$is_wicket = isset($_POST['is_wicket']) && $_POST['is_wicket'] === 'true';

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

if ($current_score['innings_no'] == 1 && ($current_score['balls'] >= 120 || $current_score['wickets'] >= 10)) {
    $innings_over = true;
    if ($current_score['target'] == 0) { 
        $current_score['target'] = $current_score['runs'] + 1;
    }
}

$overs_whole = floor($current_score['balls'] / 6);
$overs_balls = $current_score['balls'] % 6;
$overs_decimal = (float)($overs_whole . '.' . $overs_balls);

$sql = "UPDATE live_score SET runs=?, wickets=?, overs=?, striker_id=?, non_striker_id=?, bowler_id=?, target=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iidiiisi", $current_score['runs'], $current_score['wickets'], $overs_decimal, $striker_id, $non_striker_id, $bowler_id, $current_score['target'], $latest_innings_row['id']);
$stmt->execute();
$stmt->close();

$current_score['innings_over'] = $innings_over;
echo json_encode($current_score);
?>
