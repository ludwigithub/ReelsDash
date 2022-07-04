<?php
$name = "David";

header('Content-Type: application/json');



$server = "MXL1072KZ8";
$username = "ace";
$password = "Reels.Ace";
$dbname = "dashboard";

//$conn = mysqli_connect($server, $username, $password, $dbname);
echo json_encode($name);
$conn = new mysqli($server, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
 }
   echo "Connected successfully";

echo json_encode($name);
 ?>