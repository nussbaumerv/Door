<?php
$GLOBALS['userType'] = "";

if (!isset($_COOKIE["email"])) {
    header("location: login.php");
}

function login()
{
    include("connect.php");

    $email = $_COOKIE['email'];
    $password = $_COOKIE['pwd'];


    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($connect, $sql);
    $row = mysqli_fetch_assoc($result);

 
    if (password_verify($row['password'], $password) && $email == $row['email']) {
        $GLOBALS['userType'] = $row['user_type'];
    } else {
        header("location: logout.php");
    }
}

function requireAdmin(){
    $valid = false;
    if($GLOBALS['userType'] == "admin" || $GLOBALS['userType'] == "super_admin"){
        $valid = true;
    }
    return $valid;
}