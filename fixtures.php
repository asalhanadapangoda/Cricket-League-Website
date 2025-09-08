<?php include 'header.php'; ?>
<?php include 'db.php'; ?>

<div class="container mt-4">
    <h2>Manage Fixtures</h2>
    <button class="btn btn-primary float-end mb-3" data-bs-toggle="modal" data-bs-target="#addFixtureModal">Add New Match</button>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Team 1</th>
                <th>Team 2</th>
                <th>Match Date</th>
                <th>Match Time</th>
                <th>Venue</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM fixtures ORDER BY match_date ASC, match_time ASC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['match_id']}</td>
                            <td>{$row['team1']}</td>
                            <td>{$row['team2']}</td>
                            <td>{$row['match_date']}</td>
                            <td>{$row['match_time']}</td>
                            <td>{$row['venue']}</td>
                            <td>{$row['status']}</td>
                            <td>
                                <button class='btn btn-warning btn-sm editBtn' data-id='{$row['match_id']}'>✏</button>
                                <a href='fixtures_delete.php?id={$row['match_id']}' class='btn btn-danger btn-sm'>🗑</a>
                            </td>
                        </tr>";
                }
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Add Fixture Modal -->
<div class="modal fade" id="addFixtureModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="fixtures_add.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title">Add New Match</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="text" name="team1" class="form-control mb-2" placeholder="Team 1" required>
          <input type="text" name="team2" class="form-control mb-2" placeholder="Team 2" required>
          <input type="date" name="match_date" class="form-control mb-2" required>
          <input type="time" name="match_time" class="form-control mb-2" required>
          <input type="text" name="venue" class="form-control mb-2" placeholder="Venue" required>
          <select name="status" class="form-control mb-2">
            <option value="Upcoming">Upcoming</option>
            <option value="Completed">Completed</option>
            <option value="Live">Live</option>
          </select>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Add Match</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Fixture Modal -->
<div class="modal fade" id="editFixtureModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="fixtures_update.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title">Update Match</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="match_id" id="edit_id">
          <input type="text" name="team1" id="edit_team1" class="form-control mb-2" required>
          <input type="text" name="team2" id="edit_team2" class="form-control mb-2" required>
          <input type="date" name="match_date" id="edit_date" class="form-control mb-2" required>
          <input type="time" name="match_time" id="edit_time" class="form-control mb-2" required>
          <input type="text" name="venue" id="edit_venue" class="form-control mb-2" required>
          <select name="status" id="edit_status" class="form-control mb-2">
            <option value="Upcoming">Upcoming</option>
            <option value="Completed">Completed</option>
            <option value="Live">Live</option>
          </select>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update Match</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.querySelectorAll(".editBtn").forEach(button => {
    button.addEventListener("click", function() {
        let row = this.closest("tr").children;
        document.getElementById("edit_id").value = this.dataset.id;
        document.getElementById("edit_team1").value = row[1].innerText;
        document.getElementById("edit_team2").value = row[2].innerText;
        document.getElementById("edit_date").value = row[3].innerText;
        document.getElementById("edit_time").value = row[4].innerText;
        document.getElementById("edit_venue").value = row[5].innerText;
        document.getElementById("edit_status").value = row[6].innerText;
        new bootstrap.Modal(document.getElementById("editFixtureModal")).show();
    });
});
</script>
