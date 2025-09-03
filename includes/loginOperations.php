<?php
if (isset($_POST["submit"])) {
    $username = $_POST["uid"];
    $pwd = $_POST["pwd"];

    require_once 'dbh.inc.php';

    if(emptyInputLogin($username, $pwd) !== false) { 
        exit();
    }

    loginUser($conn, $username, $pwd);
    
} else {
    header('Location: ../login.php');
    exit();
} 

// Cheack emptyInputLogin

function emptyInputLogin($username, $pwd){
        $result;
        if(empty($username) || empty($pwd)){
            $result=true;
        }else{
            $result=false;
        }
        return $result;
}

// LoginUser we have to add location link

function LoginUser($conn, $username, $pwd) {
    $uidExists = uidExists($conn, $username); 

    if ($uidExists === false) {
        echo "<script>alert('Invalid username!');</script>";
        exit();
    }

    $dbPwd = $uidExists["usersPwd"];

    if ($pwd !== $dbPwd) {
        echo "<script>alert('Invalid password!');</script>";
        exit();
    } else {
        header("Location: ../index.php");
        exit();
    }
}

// Cheack uidExists

function uidExists($conn, $username) {
    $sql = "SELECT * FROM users WHERE usersUid = ?";
    $stmt = mysqli_stmt_init($conn);
    
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        return false;
    }

    mysqli_stmt_close($stmt);
}

?>