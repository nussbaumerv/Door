<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include("connect.php");
    include("premissionAlert.php");
    include("auth.php");

    login();


    if ($_GET['excute'] == "true") {
        //$url = "https://api.switch-bot.com/v1.0/devices/DE80505B0E1C/commands/";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Content-Type: application/json",
            //"Authorization: Bearer f41dfb8101f23c4969b72dc8aaad33a77b7e16bf3a015b50b7990de22f96ca5947ba78827dd376522dc3fc6ec4a405c4",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        //$data = '{"command" : "press"}';

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);

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

        $sql = "INSERT INTO accsess_log (user_id, date, time, ip, response) VALUES ('$user_id', '$date', '$time', '$ip', '$resp')";
        $result = mysqli_query($connect, $sql);

        if ($resp == '{"statusCode":100,"body":{},"message":"success"}' && $result) {
            echo "ok</br>";
            header("Location: ?excute=done");
        } else {
            echo "Something went wrong";
            header("Location: ?excute=false");
        }
    }

    ?>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="128x128" href="https://avatars.githubusercontent.com/u/83828188?v=4">
    <link rel="shortcut icon" href="https://avatars.githubusercontent.com/u/83828188?v=4" type="img/vnd.microsoft.icon" />
<link rel="manifest" href="manifest.json" />
    <title>V-DOOR</title>
    <style>
        body {
            text-align: center;
            margin: 0px;
            overflow-x: hidden;
        }

        .container {
            min-height: 100vh;
            display: flex;
        }

        .content {
            margin: auto;
        }

        #image {
            cursor: pointer;
            width: 40vw;
        }

        @media only screen and (min-width: 600px) {
            #image {
                width: 30vw;
            }
        }

        @media only screen and (min-width: 800px) {
            #image {
                width: 20vw;
            }
        }
    </style>
    <script>
        function set_sucsess() {
            document.getElementById("image").src = "https://upload.wikimedia.org/wikipedia/commons/thumb/8/82/Check_mark_9x9.svg/800px-Check_mark_9x9.svg.png";
            setTimeout(set_default, 2000);
        }

        function set_error() {
            document.getElementById("image").src = "https://media.istockphoto.com/vectors/check-marks-red-cross-icon-simple-vector-vector-id1131230925?k=20&m=1131230925&s=612x612&w=0&h=dAwxwLvBxMDmgOnEIs2MMv2KQpgHo9jHibghE39Ryl8=";
            setTimeout(set_default, 2000);
        }

        function set_default() {
            document.getElementById("image").src = "https://media.istockphoto.com/vectors/lock-icon-vector-id936681148?k=20&m=936681148&s=612x612&w=0&h=j6fxNWrJ09iE7khUsDWetKn_PwWydgIS0yFJBEonGow=";
        }

        function set_loading() {
            document.getElementById("image").src = "https://i.pinimg.com/originals/d7/34/49/d73449313ecedb997822efecd1ee3eac.gif";

        }
    </script>
</head>

<body>
    <?php include("menu.html"); ?>
    <div class="container">
        <div class="content">

            <a onclick="approve_opening()">
                <img src="https://media.istockphoto.com/vectors/lock-icon-vector-id936681148?k=20&m=936681148&s=612x612&w=0&h=j6fxNWrJ09iE7khUsDWetKn_PwWydgIS0yFJBEonGow=" id="image">
            </a><br>
        </div>
    </div>
    <?php
    if ($_GET['excute'] == "done") {
        echo '<script>set_sucsess();</script>';
    }
    if ($_GET['excute'] == "false") {
        echo '<script>set_error();</script>';
    }
    ?>
</body>

</html>