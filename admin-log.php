<?php
include("connect.php");
include("login.php");

login();

if (!requireAdmin()) {
    header("location: log.php");
} 



$sql = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($connect, $sql);
$row = mysqli_fetch_assoc($result);

$user_id = $row['id'];
$user_type = $row['user_type'];


$sql = "SELECT * FROM accsess_log ORDER BY id DESC";
$result_access = mysqli_query($connect, $sql);

$sql = "SELECT * FROM user_log ORDER BY id DESC";
$result_user = mysqli_query($connect, $sql);


?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="https://avatars.githubusercontent.com/u/83828188?v=4" type="img/vnd.microsoft.icon" />
    <title>Admin Log</title>
    <style>
        body {
            margin: 0px;
            font-family: arial;
            text-align: center;
        }

        td {
            background-color: lightgrey;
            padding: 10px;
            width: 100vw;
        }

        th {
            background-color: grey;
            padding: 10px;
        }

        #user {
            display: none;
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
    </style>
    <script>
        function change(before, after) {
            document.getElementById(before).style.display = "none";
            document.getElementById(after).style.display = "block";
        }
    </script>
</head>

<body>
    <?php include("menu.html"); ?>
    <br>
    <div id="accsess">
        <h1>Zugriffsprotokoll</h1>
        <button class="button" onclick="change('accsess', 'user')">Userprotokoll</button>
        <br><br>
        <?php
        echo "<table>
    <tr>
        <th>
            User
        </th>
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
        while ($row = mysqli_fetch_assoc($result_access)) {
            if ($row['response'] == '{"statusCode":100,"body":{},"message":"success"}') {
                $status = "Erfolgreich";
            } else {
                $status = "Fehler";
            }

            echo "        
        <tr>
             <td>
            " . $row['user_id'] . "  
            </td>
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
    </div>


    <div id="user">
        <h1>Userprotokoll</h1>
        <button class="button" onclick="change('user', 'accsess')">Zugriffsprotokoll</button>
        <br><br>
        <?php
        echo "<table>
    <tr>
        <th>
            User
        </th>
        <th>
            Type
        </th>
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
        while ($row = mysqli_fetch_assoc($result_user)) {


            echo "        
        <tr>
            <td>
            " . $row['user_id'] . "  
            </td>
            <td>
            " . $row['type'] . "  
            </td>
            <td>
            " . $row['date'] . "  
            </td>
            <td>
            " . $row['time'] . " 
            </td>
            <td>
            " . $row['status'] . " 
            </td>
            <td>
            <a href='https://whatismyipaddress.com/ip/" . $row['ip'] . "'>" . $row['ip'] . "</a>
            </td>
        </tr>";
        }
        echo "</table>";
        ?>
    </div>
</body>

</html>