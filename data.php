<?php

function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

debug_to_console("Test");

$servername = "MXL1072KZ8";
$username = "ace";
$password = "Reels.Ace";

$connection = new mysqli($servername, $username, $password)

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
 echo "Connected successfully";


 


?>