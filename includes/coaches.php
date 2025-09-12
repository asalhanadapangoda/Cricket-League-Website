<?php 
include __DIR__ . '/db.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Coaches</title>
    <link rel="stylesheet" href="/Cricket-League-Website/CSS_File/coachesStyle.css">
</head>
<body>

<?php 
include __DIR__ . '/db.php'; 
?>

<div class="container">
    <h2 class="text-center">Manage Coaches</h2>

    <div class="button-row">
        <button class="btn-add" onclick="openAddModal()">Add New Coach</button>
    </div>

    <table class="coaches-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Role</th>
                <th>Team ID</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM coach ORDER BY coach_id ASC";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $counter = 1;
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$counter}</td>
                        <td>{$row['first_name']}</td>
                        <td>{$row['last_name']}</td>
                        <td>{$row['role']}</td>
                        <td>{$row['team_id']}</td>
                        <td>
                            <a href='/Cricket-League-Website/includes/coachesDelete.php?id={$row['coach_id']}' 
                               class='btn-delete' 
                               onclick=\"return confirm('Are you sure you want to delete this coach?');\">
                               Delete
                            </a>
                        </td>
                    </tr>";
                    $counter++;
                }
            } else {
                echo "<tr><td colspan='6'>No coaches found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Keep your Add Coach Modal + JS here -->


<!-- Add Coach Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeAddModal()">&times;</span>
        <h3>Add New Coach</h3>

        <form action="includes/coachesAdd.php" method="POST">
            <label>First Name</label>
            <input type="text" name="first_name" required>
            <br><br>
            <label>Last Name</label>
            <input type="text" name="last_name" required>
            <br><br>
            <label>Role</label>
            <input type="text" name="role" required>
            <br><br>
            <label>Team ID</label>
            <input type="text" name="team_id" required>
            <br><br>
            <button type="submit" class="btn-submit">Add Coach</button>
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


window.onclick = function(event) {
    if (event.target == document.getElementById('addModal')) closeAddModal();
    
}
</script>

</body>
</html>
