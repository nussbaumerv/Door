<?php
include("connect.php");
include("PHPMailer/mail.php");

if (isset($_COOKIE["email"])) {
    header("location: index.php");
}

if ($_POST['submit']) {
    if ($_POST['key'] == "dFEKR0gYEWduuKXJeAV0PADH3b0L05yH") {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($connect, $sql);
        $row = mysqli_fetch_assoc($result);

        if ($row['email'] == $email) {
            echo "Email existiert bereits!";
            exit;
        }

        $sql = "INSERT INTO users (email, password) VALUES ('$email', '$password')";
        $result = mysqli_query($connect, $sql);

        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($connect, $sql);
        $row = mysqli_fetch_assoc($result);

        $user_id = $row['id'];

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        date_default_timezone_set("Europe/Zurich");

        $date = date("d/m/Y");
        $time = date("H:i:s");

        $type = "Registration";

        if ($result) {

            $status = "OK";

            $sql = "INSERT INTO user_log (user_id, type, status, date, time, ip) VALUES ('$user_id', '$type', '$status', '$date', '$time', '$ip')";
            $result = mysqli_query($connect, $sql);

            setcookie("email", $email, time() + (86400 * 30), "/");
            setcookie("pwd", $password, time() + (86400 * 30), "/");


            $to = "nussbaumerv9@gmail.com";
            $subject = $email . " hat sich registriert";
            $message = $email . " hat sich am " . $date . " um " . $time . " registriert.<br> Die IP adresse lautet " . $ip;

            send_mail($to, $subject, $message);

            $to = $email;
            $subject = "Registration V-DOOR";
            $message = "Vielen dank, dass Sie sich bei V-DOOR registriert haben.";

            send_mail($to, $subject, $message);

            header("Location: index.php");
        } else {
            $status = "ERROR";

            $sql = "INSERT INTO user_log (user_email, type, status, date, time, ip) VALUES ('$email', '$type', '$status', '$date', '$time', '$ip')";
            $result = mysqli_query($connect, $sql);

            $to = "nussbaumerv9@gmail.com";
            $subject = $email . " hat sich versucht zu registrieren";
            $message = $email . " hat sich am " . $date . " um " . $time . " versucht zu registriert.<br> Die IP adresse lautet " . $ip;

            send_mail($to, $subject, $message);

            echo "Es ist etwas schief gegangen";
        }
    } else {
        echo "Falscher Key";
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

        a {
            color: black;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div id="container">
        <div id="content">
            <titel>Login</titel>
            <form method="post" action=""><br>
                <input type="email" placeholder="Email" name="email"><br><br>
                <input type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Ihr Passwort muss mindistens 8 Zeichen lang sein, mindestens ein eine Zahl, einen Grossen und Kleinen Buchstaben beinhalten." placeholder="Passwort" name="password"><br><br>
                <input type="text" placeholder="Secret-Key" name="key"><br><br>
                <input class="button" name="submit" type="submit">
            </form>
            <br>
            ––– OR –––
            <br><br>
            <a class="button" href="login.php">Login</a>
        </div>
    </div>

</body>

</html>