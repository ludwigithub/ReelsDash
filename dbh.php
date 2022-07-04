<?php
$name = "David";

header('Content-Type: application/json');





//$conn = mysqli_connect($server, $username, $password, $dbname);
$username = "ace";
$password = "Reels.Ace";
$server = "MXL1072KZ8";
// Opens a connection to a MySQL server


echo json_encode('Connected successfully');

$pdo = new PDO("MXL1072KZ8", "ace", "Reels.Ace", "dashboard");
if (!$pdo) {
    echo json_encode("Error");
}
echo json_encode($name);

$sql = "SELECT orderindex from dashinfo;";
        $result = mysqli_query($pdo, $sql);
        $check = mysqli_num_rows($result);
        if($check > 0){
            while($row = mysqli_fetch_assoc($result)){
                echo json_encode(1);

            }
        }

//if you want to suppress the error message, substitute the connection line for:
//$connection = @mysql_connect($server, $username, $password) or die('try again in some minutes, please');


 ?>