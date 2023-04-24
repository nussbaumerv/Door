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
    <?php
    echo "<table>
    <tr>
        <th>
            Datum
        </th>
        <th>
            Zeit
        </th>
        <th>
            Status
        </th>
        <th>
            IP
        </th>
    </tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['response'] == '{"statusCode":100,"body":{},"message":"success"}') {
            $status = "Erfolgreich";
        } else {
            $status = "Fehler";
        }

        echo "        
        <tr>
            <td>
            " . $row['date'] . "  
            </td>
            <td>
            " . $row['time'] . " 
            </td>
            <td>
            " . $status . " 
            </td>
            <td>
            <a href='https://whatismyipaddress.com/ip/" . $row['ip'] . "'>" . $row['ip'] . "</a>
            </td>
        </tr>";
    }
    echo "</table>";
    ?>
</body>

</html>