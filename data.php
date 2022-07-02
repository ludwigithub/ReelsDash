<?php
function error($msg){
    $response = array("success" => false, "message" => $msg);
    return json_encode($response);
}
$name = $_POST['name'];

if($name ==''){
    die(error("Error: No name"));
}

$name = 'Asa';
$messaage = "Created" . $name;
$response =array();
$response["success"] = true;
$response["message"] = $message;
?>