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
echo json_encode('Connected successfully');

$sql = "SELECT orderindex from dashinfo;";
        $result = mysqli_query($conn, $sql);
        $check = mysqli_num_rows($result);
        if($check > 0){
            while($row = mysqli_fetch_assoc($result)){
                echo json_encode(1);

            }
        }

//if you want to suppress the error message, substitute the connection line for:
//$connection = @mysql_connect($server, $username, $password) or die('try again in some minutes, please');


 ?>