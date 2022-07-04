<?php
$name = "David";

header('Content-Type: application/json');





//$conn = mysqli_connect($server, $username, $password, $dbname);
$username = "ace";
$password = "Reels.Ace";
$server = "MXL1072KZ8";
// Opens a connection to a MySQL server



$link = mysql_connect("MXL1072KZ8", "ace", "Reels.Ace");
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
echo 'Connected successfully';

mysql_close($link);

echo json_encode($name);
//if you want to suppress the error message, substitute the connection line for:
//$connection = @mysql_connect($server, $username, $password) or die('try again in some minutes, please');


 ?>