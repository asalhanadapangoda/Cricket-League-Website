<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminLogin.php");
    exit;
}

require_once "includes/db.php";

$message = "";

// ADD TEAM
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $teamId   = trim($_POST['team_id'] ?? '');
        $teamName = trim($_POST['team_name'] ?? '');

        if (!$teamId || !$teamName) {
            $message = "Team ID and Name are required.";
        } elseif (empty($_FILES['team_logo']['name']) || $_FILES['team_logo']['error'] !== 0) {
            $message = "Valid team logo is required.";
        } else {
            $safeId   = mysqli_real_escape_string($conn, $teamId);
            $safeName = mysqli_real_escape_string($conn, $teamName);

            // check if exists
            $exists = mysqli_query($conn, "SELECT 1 FROM team WHERE team_id='$safeId' LIMIT 1");
            if (mysqli_num_rows($exists)) {
                $message = "Team ID already exists.";
            } else {
                $ext = strtolower(pathinfo($_FILES['team_logo']['name'], PATHINFO_EXTENSION));
                if (!in_array($ext, ['png', 'jpg', 'jpeg', 'webp'])) {
                    $message = "Logo must be png, jpg, jpeg, or webp.";
                } else {
                    $uploadDir = __DIR__ . "/Pictures/";
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                    $fileName = "team_" . preg_replace("/[^a-zA-Z0-9_-]/", "_", $teamId) . ".$ext";
                    $filePath = $uploadDir . $fileName;

                    if (move_uploaded_file($_FILES['team_logo']['tmp_name'], $filePath)) {
                        $relPath = "Pictures/$fileName";
                        $sql = "INSERT INTO team (team_id, team_name, logo) 
                                VALUES ('$safeId', '$safeName', '" . mysqli_real_escape_string($conn, $relPath) . "')";
                        if (mysqli_query($conn, $sql)) {
                            mysqli_query($conn, "INSERT INTO point_table (team_id) VALUES ('$safeId')");
                            header("Location: addTeam.php?success=1");
                            exit;
                        } else {
                            unlink($filePath);
                            $message = "Failed to add team.";
                        }
                    } else $message = "Failed to upload logo.";
                }
            }
        }
    }

    // DELETE TEAM
    if ($action === 'delete') {
        $teamId = trim($_POST['team_id'] ?? '');
        if ($teamId) {
            $teamId = mysqli_real_escape_string($conn, $teamId);
            $logo   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT logo FROM team WHERE team_id='$teamId'"))['logo'] ?? '';

            mysqli_begin_transaction($conn);
            $ok = mysqli_query($conn, "DELETE FROM coach WHERE team_id='$teamId'")
               && mysqli_query($conn, "DELETE FROM point_table WHERE team_id='$teamId'")
               && mysqli_query($conn, "DELETE FROM team WHERE team_id='$teamId'");

            if ($ok) {
                mysqli_commit($conn);
                if ($logo) @unlink(__DIR__ . "/$logo");
                header("Location: addTeam.php?deleted=1");
                exit;
            } else {
                mysqli_rollback($conn);
                $message = "Unable to delete team. Remove related data first.";
            }
        }
    }
}

// FETCH TEAMS
$teams = mysqli_query($conn, "SELECT team_id, team_name, logo FROM team ORDER BY team_name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="CSS_File/addTeam.css">
</head>
<body>
<?php include 'adminDashboardNav.php'; ?>
<div class="page-container">

  <div class="add-btn">
    <button id="btnShowForm" class="btn" type="button">Add Team</button>
  </div>

  <?php if(isset($_GET['success'])) {
    echo "<div class='msg' style='color:green'>Team added successfully.</div>";
  } ?>
  <?php if(isset($_GET['deleted'])) {
    echo "<div class='msg' style='color:green'>Team deleted successfully.</div>"; 
  }?>
  <?php if($message) {
    echo "<div class='msg'>".htmlspecialchars($message)."</div>";
  } ?>

  <!-- Modal Add Form -->
  <div id="addForm" class="modal">
    <div class="form-card">
      <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="add">
        <label>Team ID <input type="text" name="team_id" required></label><br><br>
        <label>Team Name <input type="text" name="team_name" required></label><br><br>
        <label>Logo <input type="file" name="team_logo" accept="image/*" required></label><br><br>
        <button type="button" id="btnCancel" class="btn secondary">Cancel</button>
        <button type="submit" class="btn">Submit</button>
      </form>
    </div>
  </div>

  <table>
    <thead><tr><th>Logo</th><th>Name</th><th>ID</th><th>Actions</th></tr></thead>
    <tbody>
    <?php if(mysqli_num_rows($teams)==0): ?>
      <tr><td colspan="4">No teams found.</td></tr>
    <?php else: while($t=mysqli_fetch_assoc($teams)): ?>
      <tr>
        <td><img class="logo-img" src="<?=htmlspecialchars($t['logo'])?>" alt=""></td>
        <td><?=htmlspecialchars($t['team_name'])?></td>
        <td><?=htmlspecialchars($t['team_id'])?></td>
        <td>
          <form method="post" onsubmit="return confirm('Delete this team?');">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="team_id" value="<?=$t['team_id']?>">
            <button type="submit" class="btn secondary">Delete</button>
          </form>
        </td>
      </tr>
    <?php endwhile; endif; ?>
    </tbody>
  </table>
</div>

<script>
const modal=document.getElementById('addForm');
document.getElementById('btnShowForm').onclick=()=>modal.classList.add('open');
document.getElementById('btnCancel').onclick=()=>modal.classList.remove('open');
modal.onclick=e=>{if(e.target===modal) modal.classList.remove('open')};
</script>
</body>
</html>
<?php mysqli_close($conn); ?>
