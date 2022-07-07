<?php
$db_host = "f81aeb52@us-cdbr-east-06.cleardb.net";
$db_user = "be3e1e29137712";
$db_pass = "f81aeb52";
$db_name = "heroku_671f81d6999c808";

$connect = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die("database connection error");

echo "Success";
