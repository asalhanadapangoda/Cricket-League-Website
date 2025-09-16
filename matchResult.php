<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminLogin.php");
    exit;
}

require_once 'includes/db.php';

$errors = [];
$success_msg = "";

// Insert match result
if (isset($_POST['insert_match'])) {
    $home_team_id = $_POST['home_team_id'];
    $visit_team_id = $_POST['visit_team_id'];
    $home_runs = $_POST['home_team_runs'];
    $home_wickets = $_POST['home_team_wickets'];
    $home_overs = $_POST['home_team_overs'];
    $visit_runs = $_POST['visit_team_runs'];
    $visit_wickets = $_POST['visit_team_wickets'];
    $visit_overs = $_POST['visit_team_overs'];
    $match_date = $_POST['match_date'];

    if ($home_team_id == $visit_team_id) {
        $errors[] = "Home and Visiting teams cannot be the same.";
    }

    if (empty($errors)) {
        $home_team_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT team_name FROM team WHERE team_id='$home_team_id'"))['team_name'];
        $visit_team_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT team_name FROM team WHERE team_id='$visit_team_id'"))['team_name'];

        if ($home_runs > $visit_runs) {
            $final_result = "$home_team_name won by " . ($home_runs - $visit_runs) . " runs";
        } elseif ($visit_runs > $home_runs) {
            $wickets_remaining = 10 - $visit_wickets;
            $final_result = "$visit_team_name won by $wickets_remaining wickets";
        } else {
            $final_result = "Match tied";
        }

        $stmt = $conn->prepare("INSERT INTO recent_match 
            (home_team_id, visit_team_id, home_team_runs, home_team_wickets, home_team_overs,
             visit_team_runs, visit_team_wickets, visit_team_overs, final_result, date)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "ssiiddiiss",
            $home_team_id,
            $visit_team_id,
            $home_runs,
            $home_wickets,
            $home_overs,
            $visit_runs,
            $visit_wickets,
            $visit_overs,
            $final_result,
            $match_date
        );

        if ($stmt->execute()) {
            $stmt->close();
            $_SESSION['success_msg'] = "Match result inserted successfully!";
            header("Location: matchResult.php");
            exit;
        } else {
            $errors[] = "Error inserting match result: " . $stmt->error;
        }
    }
}

// Delete match result
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    mysqli_query($conn, "DELETE FROM recent_match WHERE match_id = '$delete_id'");
    $_SESSION['success_msg'] = "Match result deleted successfully!";
    header("Location: matchResult.php");
    exit;
}

// Fetch all matches
$matches = mysqli_query($conn, "SELECT rm.*, 
    t1.team_name AS home_team_name, 
    t2.team_name AS visit_team_name 
    FROM recent_match rm
    JOIN team t1 ON rm.home_team_id = t1.team_id
    JOIN team t2 ON rm.visit_team_id = t2.team_id
    ORDER BY rm.date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Match Results</title>
<link rel="stylesheet" href="CSS_File/matchResult.css">
<link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Sidebar Navigation -->
    <?php include 'adminDashboardNav.php'; ?>

    <!-- Body content -->
    <div class="main-content">
        <h1>Match Results Dashboard</h1>

        <!-- Display Messages -->
        <?php 
        if (!empty($errors)) { 
            foreach ($errors as $err) { echo "<div class='error-msg'>$err</div>"; } 
        } 
        if (!empty($_SESSION['success_msg'])) { 
            echo "<div class='success-msg'>".$_SESSION['success_msg']."</div>"; 
            unset($_SESSION['success_msg']); 
        ?> 
            <script>
                // Auto refresh page after 2 seconds
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            </script>
        <?php } ?>

        <!-- Insert Form -->
        <form method="POST">
            <select name="home_team_id" id="home_team" required onchange="updateVisitTeams()">
                <option value="">Select Team 1</option>
                <?php
                $teams = mysqli_query($conn, "SELECT * FROM team");
                while ($team = mysqli_fetch_assoc($teams)) {
                    echo "<option value='{$team['team_id']}'>{$team['team_name']}</option>";
                }
                ?>
            </select>

            <select name="visit_team_id" id="visit_team" required>
                <option value="">Select Team 2</option>
                <?php
                $teams = mysqli_query($conn, "SELECT * FROM team");
                while ($team = mysqli_fetch_assoc($teams)) {
                    echo "<option value='{$team['team_id']}'>{$team['team_name']}</option>";
                }
                ?>
            </select>

            <input type="number" name="home_team_runs" placeholder="Team 1 Runs" required>
            <input type="number" name="home_team_wickets" placeholder="Team 1 Wickets" required>
            <input type="number" step="0.1" name="home_team_overs" placeholder="Team 1 Overs" required>

            <input type="number" name="visit_team_runs" placeholder="Team 2 Runs" required>
            <input type="number" name="visit_team_wickets" placeholder="Team 2 Wickets" required>
            <input type="number" step="0.1" name="visit_team_overs" placeholder="Team 2 Overs" required>

            <input type="date" name="match_date" required>
            <button type="submit" name="insert_match" class="btn btn-insert">Insert Match</button>
        </form>

        <!-- Show all matches -->
        <h2>All Match Results</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Home Team</th>
                    <th>Visiting Team</th>
                    <th>Home Runs</th>
                    <th>Home Wickets</th>
                    <th>Home Overs</th>
                    <th>Visiting Runs</th>
                    <th>Visiting Wickets</th>
                    <th>Visiting Overs</th>
                    <th>Final Result</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($match = mysqli_fetch_assoc($matches)) { ?>
                    <tr>
                        <td><?php echo $match['match_id']; ?></td>
                        <td><?php echo $match['home_team_name']; ?></td>
                        <td><?php echo $match['visit_team_name']; ?></td>
                        <td><?php echo $match['home_team_runs']; ?></td>
                        <td><?php echo $match['home_team_wickets']; ?></td>
                        <td><?php echo $match['home_team_overs']; ?></td>
                        <td><?php echo $match['visit_team_runs']; ?></td>
                        <td><?php echo $match['visit_team_wickets']; ?></td>
                        <td><?php echo $match['visit_team_overs']; ?></td>
                        <td><?php echo $match['final_result']; ?></td>
                        <td><?php echo $match['date']; ?></td>
                        <td>
                            <a href="?delete_id=<?php echo $match['match_id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this match?')">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

<script>
function updateVisitTeams() {
    const homeSelect = document.getElementById('home_team');
    const visitSelect = document.getElementById('visit_team');
    const selectedHome = homeSelect.value;

    for (let i = 0; i < visitSelect.options.length; i++) {
        visitSelect.options[i].disabled = visitSelect.options[i].value === selectedHome;
    }

    if (visitSelect.value === selectedHome) {
        visitSelect.value = "";
    }
}
</script>

</body>
</html>
