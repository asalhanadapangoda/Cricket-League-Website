<?php 
include __DIR__ . '/db.php'; // DB connection

// Fetch teams for the dropdown
$teams_query = "SELECT team_id, team_name FROM team";
$teams_result = $conn->query($teams_query);
$teams = [];
if ($teams_result->num_rows > 0) {
    while($row = $teams_result->fetch_assoc()) {
        $teams[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/Cricket-League-Website/CSS_File/fixturesStyle.css">
</head>
<body>
<?php include 'adminDashboardNav.php'; ?>
<div class="container">

    <div class="button-row">
        <button class="btn-add" onclick="openAddModal()">Add New Match</button>
    </div>

    <table class="fixtures-table">
        <thead>
            <tr>
                
                <th>Home Team</th>
                <th>Visiting Team</th>
                <th>Match Date</th>
                <th>Match Time</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT f.*, ht.team_name as home_team_name, vt.team_name as visit_team_name FROM upcoming_match f JOIN team ht ON f.home_team_id = ht.team_id JOIN team vt ON f.visit_team_id = vt.team_id ORDER BY date ASC, time ASC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $counter = 1;
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                        
                        <td>{$row['home_team_name']}</td>
                        <td>{$row['visit_team_name']}</td>
                        <td>{$row['date']}</td>
                        <td>{$row['time']}</td>
                        <td>
                            <button class='btn-edit' onclick=\"openEditModal('{$row['match_id']}', '{$row['home_team_id']}', '{$row['visit_team_id']}', '{$row['date']}', '{$row['time']}')\">Edit</button>
                            <a href='/Cricket-League-Website/includes/fixturesDelete.php?id={$row['match_id']}' class='btn-delete' onclick=\"return confirm('Are you sure you want to delete this match?');\">Delete</a>
                        </td>
                    </tr>";
                    $counter++;
                }
            } else {
                echo "<tr><td colspan='6'>No matches found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>



<div id="addModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeAddModal()">&times;</span>
        <h3>Add New Match</h3>

    <form action="includes/fixturesAdd.php" method="POST" onsubmit="return validateTeams(this)">

            <label>Home Team</label>
            <select name="team1" id="add_team1" required onchange="syncTeamDropdowns('add_team1','add_team2')">
                <option value="">Select Home Team</option>
                <?php foreach ($teams as $team): ?>
                    <option value="<?php echo htmlspecialchars($team['team_id']); ?>"><?php echo htmlspecialchars($team['team_name']); ?></option>
                <?php endforeach; ?>
            </select>
            <br><br>
            <label>Visiting Team</label>
            <select name="team2" id="add_team2" required onchange="syncTeamDropdowns('add_team2','add_team1')">
                <option value="">Select Visiting Team</option>
                <?php foreach ($teams as $team): ?>
                    <option value="<?php echo htmlspecialchars($team['team_id']); ?>"><?php echo htmlspecialchars($team['team_name']); ?></option>
                <?php endforeach; ?>
            </select>
            <br><br>
            <label>Match Date</label>
            <input type="date" name="match_date" required>
            <br><br>
            <label>Match Time</label>
            <input type="time" name="match_time" required>
            <br><br>
            <button type="submit" class="btn-submit">Add Match</button>
        </form>
    </div>
</div>

<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h3>Edit Match</h3>
    <form action="/Cricket-League-Website/includes/fixturesUpdate.php" method="POST" onsubmit="return validateTeams(this)">
            <input type="hidden" name="match_id" id="edit_id">
            <label>Home Team</label>
            <select name="team1" id="edit_team1" required onchange="syncTeamDropdowns('edit_team1','edit_team2')">
                <option value="">Select Home Team</option>
                <?php foreach ($teams as $team): ?>
                    <option value="<?php echo htmlspecialchars($team['team_id']); ?>"><?php echo htmlspecialchars($team['team_name']); ?></option>
                <?php endforeach; ?>
            </select>
            <br><br>
            <label>Visiting Team</label>
            <select name="team2" id="edit_team2" required onchange="syncTeamDropdowns('edit_team2','edit_team1')">
                <option value="">Select Visiting Team</option>
                <?php foreach ($teams as $team): ?>
                    <option value="<?php echo htmlspecialchars($team['team_id']); ?>"><?php echo htmlspecialchars($team['team_name']); ?></option>
                <?php endforeach; ?>
            </select>
            <br><br>
            <label>Match Date</label>
            <input type="date" name="match_date" id="edit_date" required>
            <br><br>
            <label>Match Time</label>
            <input type="time" name="match_time" id="edit_time" required>
            <br><br>
            <button type="submit" class="btn-submit">Update Match</button>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('addModal').style.display = 'block';
}
function closeAddModal() {
    document.getElementById('addModal').style.display = 'none';
}
function openEditModal(id, home_id, visit_id, date, time) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_team1').value = home_id;
    document.getElementById('edit_team2').value = visit_id;
    document.getElementById('edit_date').value = date;
    document.getElementById('edit_time').value = time;
    document.getElementById('editModal').style.display = 'block';
}
function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

window.onclick = function(event) {
    if (event.target == document.getElementById('addModal')) closeAddModal();
    if (event.target == document.getElementById('editModal')) closeEditModal();
}

// Disable selected team in the other dropdown
function syncTeamDropdowns(changedId, otherId) {
    var changed = document.getElementById(changedId);
    var other = document.getElementById(otherId);
    var selectedValue = changed.value;
    for (var i = 0; i < other.options.length; i++) {
        other.options[i].disabled = false;
        if (other.options[i].value && other.options[i].value === selectedValue) {
            other.options[i].disabled = true;
        }
    }
}

// Initialize disables when modals open
function openAddModal() {
    document.getElementById('addModal').style.display = 'block';
    syncTeamDropdowns('add_team1','add_team2');
    syncTeamDropdowns('add_team2','add_team1');
}
function openEditModal(id, home_id, visit_id, date, time) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_team1').value = home_id;
    document.getElementById('edit_team2').value = visit_id;
    document.getElementById('edit_date').value = date;
    document.getElementById('edit_time').value = time;
    document.getElementById('editModal').style.display = 'block';
    syncTeamDropdowns('edit_team1','edit_team2');
    syncTeamDropdowns('edit_team2','edit_team1');
}
</script>

</body>
</html>