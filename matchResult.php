<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminLogin.php");
    exit;
}

require_once 'includes/db.php';

$notification = ""; // Notification message

// -------------------- Delete Match --------------------
if (isset($_POST['delete']) && isset($_POST['match_id'])) {
    $match_id = (int)$_POST['match_id'];
    $conn->query("DELETE FROM recent_match WHERE match_id = $match_id");
    $notification = "Match deleted successfully!";
}

// -------------------- Insert Match --------------------
if (isset($_POST['insert_match'])) {
    $home_team_id = $_POST['home_team_id'];
    $visit_team_id = $_POST['visit_team_id'];
    $home_runs = (int)$_POST['home_team_runs'];
    $home_wickets = (int)$_POST['home_team_wickets'];
    $home_overs = $_POST['home_team_overs'];
    $visit_runs = (int)$_POST['visit_team_runs'];
    $visit_wickets = (int)$_POST['visit_team_wickets'];
    $visit_overs = $_POST['visit_team_overs'];
    $date = $_POST['date'];

    $home_team_name = $conn->query("SELECT team_name FROM team WHERE team_id='$home_team_id'")->fetch_assoc()['team_name'];
    $visit_team_name = $conn->query("SELECT team_name FROM team WHERE team_id='$visit_team_id'")->fetch_assoc()['team_name'];

    if ($home_runs > $visit_runs) {
        $run_diff = $home_runs - $visit_runs;
        $final_result = "$home_team_name won by $run_diff runs";
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
    $stmt->bind_param("ssiidiisss",
        $home_team_id, 
        $visit_team_id,
        $home_runs, 
        $home_wickets, 
        $home_overs, 
        $visit_runs, 
        $visit_wickets, 
        $visit_overs, 
        $final_result, 
        $date
    );
    $stmt->execute();
    $stmt->close();

    $notification = "Match added successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="CSS_File/matchResult.css">
    <script>
        function openModal() { document.getElementById("modal").style.display = "block"; }
        function closeModal() { document.getElementById("modal").style.display = "none"; }

        // Prevent selecting the same team
        function updateVisitOptions() {
            let homeTeam = document.querySelector("select[name='home_team_id']").value;
            let visitSelect = document.querySelector("select[name='visit_team_id']");
            for (let opt of visitSelect.options) {
                opt.disabled = (opt.value && opt.value === homeTeam);
            }
        }
        function updateHomeOptions() {
            let visitTeam = document.querySelector("select[name='visit_team_id']").value;
            let homeSelect = document.querySelector("select[name='home_team_id']");
            for (let opt of homeSelect.options) {
                opt.disabled = (opt.value && opt.value === visitTeam);
            }
        }

        // Show top-right notification
        function showNotification(message) {
            const notif = document.getElementById('notification');
            notif.textContent = message;
            notif.style.display = 'block';
            setTimeout(() => { notif.style.display = 'none'; }, 3000);
        }

        // Show PHP notification if exists
        <?php if($notification): ?>
            window.onload = function() {
                showNotification("<?= $notification ?>");
                closeModal(); // Close modal automatically if open
            };
        <?php endif; ?>
    </script>
</head>
<body>

<div id="notification"></div>

<h2>Recent Matches</h2>

<button class="add-btn" onclick="openModal()">Add Match Result</button>

<!-- Modal Form -->
<div id="modal" class="modal">
    <div class="modal-content">
        <span style="float:right; cursor:pointer;" onclick="closeModal()">❌</span>
        <h3>Add Match</h3>
        <form method="POST">
            <label>Home Team:</label><br>
            <select name="home_team_id" required onchange="updateVisitOptions()">
                <option value="">Select</option>
                <?php
                $teams = $conn->query("SELECT * FROM team");
                while ($t = $teams->fetch_assoc()) {
                    $selected = (isset($_POST['home_team_id']) && $_POST['home_team_id'] == $t['team_id']) ? "selected" : "";
                    echo "<option value='{$t['team_id']}' $selected>{$t['team_name']}</option>";
                }
                ?>
            </select><br><br>

            <label>Visit Team:</label><br>
            <select name="visit_team_id" required onchange="updateHomeOptions()">
                <option value="">Select</option>
                <?php
                $teams2 = $conn->query("SELECT * FROM team");
                while ($t2 = $teams2->fetch_assoc()) {
                    $selected = (isset($_POST['visit_team_id']) && $_POST['visit_team_id'] == $t2['team_id']) ? "selected" : "";
                    echo "<option value='{$t2['team_id']}' $selected>{$t2['team_name']}</option>";
                }
                ?>
            </select><br><br>

            <label>Home Runs:</label><br>
            <input type="number" name="home_team_runs" required value="<?= htmlspecialchars($_POST['home_team_runs'] ?? '') ?>"><br><br>
            <label>Home Wickets:</label><br>
            <input type="number" name="home_team_wickets" required value="<?= htmlspecialchars($_POST['home_team_wickets'] ?? '') ?>"><br><br>
            <label>Home Overs:</label><br>
            <input type="text" name="home_team_overs" required value="<?= htmlspecialchars($_POST['home_team_overs'] ?? '') ?>"><br><br>

            <label>Visit Runs:</label><br>
            <input type="number" name="visit_team_runs" required value="<?= htmlspecialchars($_POST['visit_team_runs'] ?? '') ?>"><br><br>
            <label>Visit Wickets:</label><br>
            <input type="number" name="visit_team_wickets" required value="<?= htmlspecialchars($_POST['visit_team_wickets'] ?? '') ?>"><br><br>
            <label>Visit Overs:</label><br>
            <input type="text" name="visit_team_overs" required value="<?= htmlspecialchars($_POST['visit_team_overs'] ?? '') ?>"><br><br>

            <label>Date:</label><br>
            <input type="date" name="date" required value="<?= htmlspecialchars($_POST['date'] ?? '') ?>"><br><br>

            <button type="submit" name="insert_match">Save</button>
        </form>
    </div>
</div>

<!-- Matches Table -->
<table>
    <tr>
        <th>Home Team</th>
        <th>Visit Team</th>
        <th>Result</th>
        <th>Date</th>
        <th>Action</th>
    </tr>
    <?php
    $matches = $conn->query("SELECT m.*, 
                h.team_name AS home_name, 
                v.team_name AS visit_name
                FROM recent_match m
                JOIN team h ON m.home_team_id=h.team_id
                JOIN team v ON m.visit_team_id=v.team_id
                ORDER BY m.date DESC");

    while ($row = $matches->fetch_assoc()) {
        echo "<tr>
            <td>{$row['home_name']} ({$row['home_team_runs']}/{$row['home_team_wickets']} in {$row['home_team_overs']} ov)</td>
            <td>{$row['visit_name']} ({$row['visit_team_runs']}/{$row['visit_team_wickets']} in {$row['visit_team_overs']} ov)</td>
            <td>{$row['final_result']}</td>
            <td>{$row['date']}</td>
            <td>
                <form method='POST' onsubmit=\"return confirm('Delete this match?');\">
                    <input type='hidden' name='match_id' value='{$row['match_id']}'>
                    <button type='submit' name='delete'>Delete</button>
                </form>
            </td>
        </tr>";
    }
    ?>
</table>

</body>
</html>
