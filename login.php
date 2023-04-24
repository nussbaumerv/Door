<?php

include("connect.php");
include("PHPMailer/mail.php");
//include("auth.php");

if ($_POST['submit']) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($connect, $sql);
    $row = mysqli_fetch_assoc($result);

    $user_id = $row['id'];

    if ($password == $row['password'] && $email == $row['email']) {

        date_default_timezone_set("Europe/Zurich");

        $date = date("d/m/Y");
        $time = date("H:i:s");
        
        
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        $type = "Login";
        $status = "OK";

        $sql = "INSERT INTO user_log (user_id, type, status, date, time, ip) VALUES ('$user_id', '$type', '$status', '$date', '$time', '$ip')";
        $result = mysqli_query($connect, $sql);

        $to = $email;
        $subject = "Neues Login";
        $message = "Am " . $date . " um " . $time . " loggte sich ein User mit der IP Adresse ".$ip." erfolgreich in Ihren Account ein. <br>
        Fals Sie dies nicht waren löschen Sie bitte sofort Ihren V-DOOR Account in den <a href='https://home.valentin-nussbaumer.com/settings.php'>einstellungen</a>.";

        send_mail($to, $subject, $message);

        $hash = password_hash($row['password'], PASSWORD_DEFAULT);

        setcookie("email", $email, time() + (86400 * 300), "/");
        setcookie("pwd", $hash, time() + (86400 * 300), "/");

        header("Location: index.php");

    } else {
        echo "Falsches Passwort";
    }

    
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="https://avatars.githubusercontent.com/u/83828188?v=4" type="img/vnd.microsoft.icon" />
    <title>Login</title>
    <style>
        body {
            text-align: center;
            font-family: Arial;
            margin: 0px;
        }

        input {
            padding: 10px 20px;
            width: 300px;
            background-color: rgb(255, 255, 255);
            border: solid;
            border-color: #c72f2f;
            font-size: 16px;
            -webkit-appearance: none;
        }

        input:focus {
            outline: none;
            background-color: #e0e0e0;
        }

        .button {
            padding: 5px 10px;
            color: rgb(0, 0, 0);
            background-color: rgb(226, 225, 225);
            border: solid;
            font-size: 16px;
            border-color: #c72f2f;
            cursor: pointer;
            transition: opacity .2s;
        }

        .button:hover {
            opacity: 0.7;
            transition: opacity .2s;
        }

        #container {
            min-height: 100vh;
            display: flex;
        }

        #content {
            margin: auto;
        }

        titel {
            font-size: 50px;
        }
        a{
            color:black;
            text-decoration:none;
        }
    </style>
</head>

<body>

    <div id="container">
        <div id="content">
            <titel>Login</titel>
            <form method="post" action="login.php"><br>
                <input type="email" placeholder="Email" name="email"><br><br>
                <input type="password" placeholder="Passwort" name="password"><br><br>
                <input class="button" name="submit" type="submit">
            </form>
            <br>
            ––– OR –––
            <br><br>
            <a class="button" href="register.php">Registrieren</a>
        </div>
    </div>

</body>

</html>