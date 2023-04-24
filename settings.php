<?php
include("connect.php");
include("auth.php");

login();

if ($_GET["status"] == "success") {
    echo "<script> alert('Erfolgreich geändert'); </script>";
}
if ($_GET["status"] == "error") {
    echo "<script> alert('Es ist ein Fehler aufgeten'); </script>";
}
if (!isset($_COOKIE["email"])) {
    header("location: login.php");
}

$email = $_COOKIE['email'];
$password = $_COOKIE['pwd'];

$email = $_COOKIE['email'];
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($connect, $sql);
$row = mysqli_fetch_array($result);

if (isset($_POST['submit_password'])) {
    $password = $_POST['password'];
    $sql = "UPDATE users SET password='$password' WHERE email = '$email'";

    setcookie("pwd", $password, time() + (86400 * 30), "/");

    $result = mysqli_query($connect, $sql);

    if ($result) {
        header("Location: settings.php?status=success");
    } else {
        header("Location: settings.php?status=error");
    }
}
if (isset($_POST['submit_email'])) {
    $email_new = $_POST['email'];
    $sql = "UPDATE users SET email='$email_new' WHERE email = '$email'";

    setcookie("email", $email_new, time() + (86400 * 30), "/");

    $result = mysqli_query($connect, $sql);

    if ($result) {
        header("Location: settings.php?status=success");
    } else {
        header("Location: settings.php?status=error");
    }
}

if (isset($_POST['submit_delete'])) {

    $sql = "DELETE FROM users WHERE email = '$email'";

    $result = mysqli_query($connect, $sql);

    if ($result) {
        echo "<script> alert('Account wurde erfolgreich gelöscht');</script>";
        header("Location: logout.php");
    } else {
        echo "<script> alert('Es ist etwas schiefgelaufen');</script>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="128x128" href="https://avatars.githubusercontent.com/u/83828188?v=4">
    <link rel="shortcut icon" href="https://avatars.githubusercontent.com/u/83828188?v=4" type="img/vnd.microsoft.icon" />
    <title>V-DOOR</title>
    <style>
        body {
            text-align: center;
            font-family: arial;
            
        }

        input {
            -webkit-appearance: none;
            padding: 10px 20px;
            width: 200px;
            background-color: rgb(255, 255, 255);
            border: solid;
            border-color: #c72f2f;
            font-size: 16px;
        }

        input:focus {
            outline: none;
            background-color: #e0e0e0;
        }

        .button {
            padding: 5px 5px;
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

        P {
            font-size: 20px;
        }
    </style>
</head>

<body>
    <?php include("menu.html");?>
    <br>
    <h1>Einstellungen</h1>
    <p>Passwort ändern</p>
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