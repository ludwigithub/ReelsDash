<?php
$name = "David";

header('Content-Type: application/json');
echo json_encode($name);




//$conn = mysqli_connect($server, $username, $password, $dbname);
$username = "ace";
$password = "Reels.Ace";
$server = "MXL1072KZ8";
// Opens a connection to a MySQL server



$conn = mysqli_connect("MXL1072KZ8", "ace", "Reels.Ace", "dashboard");
if (!$conn) {
    die('Could not connect: ' . mysql_error());
}
echo 'Connected successfully';



//if you want to suppress the error message, substitute the connection line for:
//$connection = @mysql_connect($server, $username, $password) or die('try again in some minutes, please');


 ?>