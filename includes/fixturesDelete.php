<?php
include __DIR__ . '/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM upcoming_match WHERE match_id=$id";

    if ($conn->query($sql) === TRUE) {
        // Redirect back to admin dashboard with fixtures page loaded
        header("Location: /Cricket-League-Website/adminDashboard.php?page=fixtures");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
