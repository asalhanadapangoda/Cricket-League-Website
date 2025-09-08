<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM upcoming_match WHERE match_id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: fixtures.php");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
