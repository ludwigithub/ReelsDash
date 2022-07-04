<?php

$servername = "MXL1072KZ8";
$username = "ace";
$password = "Reels.Ace";

$connection = new mysqli($servername, $username, $password)

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
 echo "Connected successfully";
?>