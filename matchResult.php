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

// -------------------- Insert Match --------------------
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

// -------------------- Delete Match --------------------
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']); // sanitize input

    if ($delete_id > 0) {
        $delete_query = "DELETE FROM recent_match WHERE match_id = $delete_id";
        if (mysqli_query($conn, $delete_query)) {
            $_SESSION['success_msg'] = "Match result deleted successfully!";
        } else {
            $_SESSION['success_msg'] = "Error deleting match: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['success_msg'] = "Invalid match ID.";
    }

    header("Location: matchResult.php");
    exit;
}

// -------------------- Fetch Matches --------------------
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
<style>
/* Quick inline CSS fixes */
.main-content { margin-left: 250px; padding: 20px; }
table { width: 100%; border-collapse: collapse; margin-top: 20px; }
table, th, td { border: 1px solid #ccc; }
th, td { padding: 8px; text-align: center; }
.error-msg { color: red; margin: 10px 0; }
.success-msg { color: green; margin: 10px 0; }
.btn-delete { background-color: #e74c3c; color: #fff; padding: 5px 10px; text-decoration: none; border-radius: 5px; }
.btn-delete:hover { background-color: #c0392b; }
.btn-insert { padding: 8px 15px; background-color: #3498db; color: #fff; border: none; border-radius: 5px; cursor: pointer; }
.btn-insert:hover { background-color: #2980b9; }
</style>
</head>
<body>
    <!-- Sidebar Navigation -->
    <?php include 'adminDashboardNav.php'; ?>

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
        } 
        ?>

        <!-- Insert Form -->
        <form method="POST">
            <select name="home_team_id" id="home_team" required onchange="updateVisitTeams()">
                <option value="">Select Home Team</option>
                <?php
                $teams = mysqli_query($conn, "SELECT * FROM team");
                while ($team = mysqli_fetch_assoc($teams)) {
                    echo "<option value='{$team['team_id']}'>{$team['team_name']}</option>";
                }
                ?>
            </select>

            <select name="visit_team_id" id="visit_team" required>
                <option value="">Select Visiting Team</option>
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
            <button type="submit" name="insert_match" class="btn-insert">Insert Match</button>
        </form>

        <!-- Match Table -->
        <h2>All Match Results</h2>
        <table>
            <thead>
                <tr>
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
                            <a href="matchResult.php?delete_id=<?php echo $match['match_id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this match?')">Delete</a>
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
