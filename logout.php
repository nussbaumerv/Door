<?php

setcookie("pwd", '', time()-7000000, '/');
setcookie("email", '', time()-7000000, '/');

header("Location: login.php");