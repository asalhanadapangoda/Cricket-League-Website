<?php include __DIR__ . '/includes/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Fixtures</title>
    <!-- Link CSS file -->
    <link rel="stylesheet" href="CSS/fixturesStyle.css">
</head>
<body>

<div class="container">
    <h2 class="text-center">Manage Fixtures</h2>

    <div class="text-right mb-3">
        <button class="btn-add" onclick="openAddModal()">Add New Match</button>
    </div>

    <table class="fixtures-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Home Team</th>
                <th>Visiting Team</th>
                <th>Match Date</th>
                <th>Match Time</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- No rows shown -->
        </tbody>
    </table>
</div>

<!-- Add Fixture Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeAddModal()">&times;</span>
        <h3>Add New Match</h3>
        <form action="fixtures_add.php" method="POST">
            <label>Home Team</label>
            <input type="text" name="team1" required>
            <br><br>
            <label>Visiting Team</label>
            <input type="text" name="team2" required>
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

<!-- Edit Fixture Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h3>Edit Match</h3>
        <form action="fixtures_update.php" method="POST">
            <input type="hidden" name="match_id" id="edit_id">
            <label>Home Team</label>
            <input type="text" name="team1" id="edit_team1" required>
            <br><br>
            <label>Visiting Team</label>
            <input type="text" name="team2" id="edit_team2" required>
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
function openEditModal(id, home, visit, date, time) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_team1').value = home;
    document.getElementById('edit_team2').value = visit;
    document.getElementById('edit_date').value = date;
    document.getElementById('edit_time').value = time;
    document.getElementById('editModal').style.display = 'block';
}
function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Close modals when clicking outside
window.onclick = function(event) {
    if (event.target == document.getElementById('addModal')) {
        closeAddModal();
    }
    if (event.target == document.getElementById('editModal')) {
        closeEditModal();
    }
}
</script>

</body>
</html>
