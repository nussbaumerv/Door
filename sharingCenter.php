<?php
include("connect.php");

if (!isset($_COOKIE["email"])) {
    header("location: login.php");
}

$email = $_COOKIE['email'];
$password = $_COOKIE['pwd'];


$sql = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($connect, $sql);
$row = mysqli_fetch_assoc($result);

$user_id = $row['id'];
$user_type = $row['user_type'];

if ($password == $row['password'] && $email == $row['email']) {
} else {
    header("location: login.php");
}

if($user_type != 'admin') {
    header("location: admin-log.php");
}


$sql = "SELECT * FROM accsess_log WHERE user_id = '$user_id' ORDER BY id DESC";
$result = mysqli_query($connect, $sql);


?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="https://avatars.githubusercontent.com/u/83828188?v=4" type="img/vnd.microsoft.icon" />
    <title>Log</title>
    <style> 
    body{
        margin:0px;
        font-family: arial;
        text-align:center;
    }
    
    td{
        background-color: lightgrey;
        padding:10px;
        width:100vw;
    }
    th{
        background-color: grey;
        padding:10px;
    }
    </style>
</head>

<body>
    <?php include("menu.html");?>
    <br>
    <h1>Zugriffsprotokoll</h1>
    <p>User type</p>
    <form method="post">
        <input pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Ihr Passwort muss mindistens 8 Zeichen lang sein, mindestens ein eine Zahl, einen Grossen und Kleinen Buchstaben beinhalten."  value=<?php echo $row['password'] ?> type="password" name="password"> <br><br>
        <input class="button" name="submit_password" value="Ändern" type="submit">
    </form>
    <br>
    <p>Email ändern</p>
    <form method="post">
        <input value=<?php echo $row['email'] ?> type="email" name="email"> <br><br>
        <input class="button" name="submit_email" value="Ändern" type="submit">
    </form>
    <br><br><br><br><br><br>
    <form method="post">
        <input class="button" name="submit_delete" value="Account Löschen" type="submit">
    </form>

   
</body>

</html>