<?php
// player.php
session_start();

// Access control
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminLogin.php");
    exit;
}

require_once 'includes/db.php';

$errors = [];
$success = ""; // for success message
$old = [
    'first_name'=>'',
    'last_name'=>'',
    'type'=>'Batsman',
    'number_of_match'=>'0',
    'runs'=>'0',
    'wickets'=>'0',
    'team_id'=>''
];

// Fetch teams for dropdown
$teams = [];
$teamRes = $conn->query("SELECT team_id, team_name FROM team ORDER BY team_name");
while ($r = $teamRes->fetch_assoc()) $teams[] = $r;

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['first_name'] = trim($_POST['first_name'] ?? '');
    $old['last_name'] = trim($_POST['last_name'] ?? '');
    $old['type'] = $_POST['type'] ?? '';
    $old['number_of_match'] = $_POST['number_of_match'] ?? '0';
    $old['runs'] = $_POST['runs'] ?? '0';
    $old['wickets'] = $_POST['wickets'] ?? '0';
    $old['team_id'] = $_POST['team_id'] ?? '';

    // validate
    if ($old['first_name'] === '' || $old['last_name'] === '') $errors[] = "First name and last name are required.";

    $valid_types = ['Batsman','Bowler','All-Rounder','Wicket-Keeper'];
    if (!in_array($old['type'], $valid_types, true)) $errors[] = "Invalid player type.";

    if (!ctype_digit((string)$old['number_of_match']) || (int)$old['number_of_match'] < 0) $errors[] = "Number of matches must be non-negative.";
    if (!ctype_digit((string)$old['runs']) || (int)$old['runs'] < 0) $errors[] = "Runs must be non-negative.";
    if (!ctype_digit((string)$old['wickets']) || (int)$old['wickets'] < 0) $errors[] = "Wickets must be non-negative.";

    // ensure team exists
    $stmt = $conn->prepare("SELECT team_id FROM team WHERE team_id = ?");
    $stmt->bind_param("s", $old['team_id']);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows === 0) $errors[] = "Selected team does not exist.";
    $stmt->close();

    // insert if no errors
    if (empty($errors)) {
        $sql = "INSERT INTO player (first_name, last_name, type, number_of_match, runs, wickets, team_id)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $nm = (int)$old['number_of_match'];
        $runs = (int)$old['runs'];
        $wickets = (int)$old['wickets'];
        $stmt->bind_param("sssiiis",
            $old['first_name'],
            $old['last_name'],
            $old['type'],
            $nm,
            $runs,
            $wickets,
            $old['team_id']
        );

        if ($stmt->execute()) {
            $success = "Player added successfully.";
            $old = ['first_name'=>'','last_name'=>'','type'=>'Batsman','number_of_match'=>'0','runs'=>'0','wickets'=>'0','team_id'=>''];
        } else {
            $errors[] = "Database error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Add Player - Admin</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>
    body{font-family:Arial,Helvetica,sans-serif;padding:0;margin:0}
    form{max-width:720px;margin:auto}
    .error{color:#b00020;margin-bottom:10px}
    .success{color:green;margin-bottom:10px;font-weight:bold}
    label{display:block;margin-top:10px}
    input[type="text"], input[type="number"], select {width:100%;padding:8px;margin-top:4px}
    .row{display:flex;gap:10px}
    .row > div{flex:1}
    .btn{margin-top:12px;padding:10px 16px;border:0;background:#1976d2;color:#fff;border-radius:4px;cursor:pointer}
    .btn.secondary{background:#6c757d}
    .main-content{margin-left:250px;padding:20px} /* Adjust margin if sidebar width changes */
  </style>
</head>
<body>
  <!-- Sidebar Navigation -->
  <?php include 'adminDashboardNav.php'; ?>

  <div class="main-content">
    <h2>Add Player</h2>

    <?php if ($success): ?>
      <div class="success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    <?php if (!empty($errors)): ?>
      <div class="error"><?php echo implode('<br>', array_map('htmlspecialchars', $errors)); ?></div>
    <?php endif; ?>

    <form method="post" action="player.php" novalidate>
      <label>First name
        <input type="text" name="first_name" value="<?php echo htmlspecialchars($old['first_name']); ?>" required>
      </label>

      <label>Last name
        <input type="text" name="last_name" value="<?php echo htmlspecialchars($old['last_name']); ?>" required>
      </label>

      <label>Type
        <select name="type" required>
          <?php foreach (['Batsman','Bowler','All-Rounder','Wicket-Keeper'] as $t):
              $sel = ($old['type']===$t)?'selected':'';
              echo "<option value=\"" . htmlspecialchars($t) . "\" $sel>" . htmlspecialchars($t) . "</option>";
          endforeach; ?>
        </select>
      </label>

      <div class="row">
        <div>
          <label>Matches
            <input type="number" min="0" name="number_of_match" value="<?php echo htmlspecialchars($old['number_of_match']); ?>">
          </label>
        </div>
        <div>
          <label>Runs
            <input type="number" min="0" name="runs" value="<?php echo htmlspecialchars($old['runs']); ?>">
          </label>
        </div>
        <div>
          <label>Wickets
            <input type="number" min="0" name="wickets" value="<?php echo htmlspecialchars($old['wickets']); ?>">
          </label>
        </div>
      </div>

      <label>Team
        <select name="team_id" required>
          <option value="">-- Select team --</option>
          <?php foreach ($teams as $t):
            $sel = ($old['team_id']===$t['team_id'])?'selected':'';
            echo '<option value="'.htmlspecialchars($t['team_id']).'" '.$sel.'>'.htmlspecialchars($t['team_name']).'</option>';
          endforeach; ?>
        </select>
      </label>

      <button class="btn" type="submit">Add Player</button>
      <button type="button" onclick="location.href='managePlayers.php'" class="btn secondary">Show Players</button>
      <button type="button" onclick="location.href='adminDashboard.php'" class="btn secondary">Cancel</button>
    </form>
  </div>
</body>
</html>
