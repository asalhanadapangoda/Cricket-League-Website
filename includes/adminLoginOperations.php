<?php
session_start();

if (isset($_POST["submit"])) {
    $username = $_POST["uid"];
    $pwd = $_POST["pwd"];

    require_once 'db.php';

    if(emptyInputLogin($username, $pwd) !== false) { 
        echo "<script>alert('Please fill in all fields!'); window.location.href='../adminLogin.php';</script>";
        exit();
    }

    loginAdmin($conn, $username, $pwd);
    
} else {
    header('Location: ../adminLogin.php');
    exit();
} 

// Check emptyInputLogin
function emptyInputLogin($username, $pwd){
    // Initialize the variable with a default value
    $result = false;
    
    if(empty($username) || empty($pwd)){
        $result = true;
    }
    
    return $result;
}

// Check if admin exists
function adminExists($conn, $username) {
    $sql = "SELECT * FROM admin WHERE username = ?";
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

// Admin Login Function
function loginAdmin($conn, $username, $pwd) {
    $adminExists = adminExists($conn, $username); 

    if ($adminExists === false) {
        echo "<script>alert('Invalid admin username!'); window.location.href='../adminLogin.php';</script>";
        exit();
    }

    $dbPwd = $adminExists["password"];

    if ($pwd !== $dbPwd) {
        echo "<script>alert('Invalid password!'); window.location.href='../adminLogin.php';</script>";
        exit();
    } else {
        // Set admin session variables
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $adminExists["id"];
        $_SESSION['admin_username'] = $adminExists["username"];
        $_SESSION['admin_name'] = $adminExists["admin_name"];
        
        header("Location: ../adminDashboard.php");
        exit();
    }
}
?>