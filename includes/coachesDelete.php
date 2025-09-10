<?php
include __DIR__ . '/db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "DELETE FROM coach WHERE coach_id = $id";

    if ($conn->query($sql) === TRUE) {
        // Redirect back to coaches page
        header("Location: /Cricket-League-Website/adminDashboard.php?page=coaches");
        exit;
    } else {
        echo "Error deleting coach: " . $conn->error;
    }
} else {
    echo "No coach ID specified.";
}
?>
