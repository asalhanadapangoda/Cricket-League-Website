<?php
    $serverName="localhost";
    $dbUserName="root";
    $dbPassword="";
    $dbname="lpl";

    $conn = mysqli_connect($serverName,$dbUserName,$dbPassword,$dbname);

    // Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
    ?>