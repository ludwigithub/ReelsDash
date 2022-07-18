<?php
/*
 * 
 * Update a Downtime Event with the parameters the user selected on the previous
 * page.
 * 2015.11.06
 * Nicholas West
 * Engineering Projects Manager
 * Sonoco Products Co
 * 
*/
$autoRefresh=FALSE;
include 'header2p0.php';
?>
<a style='text-decoration:none;' href='dashboard2p0.php'><div class="brand">Sonoco Reels & Plugs</div></a>
<div class="brand timeStamp timeStampNarrow">
    <?php echo date('l h:i:s A') . "<br />" . date('jS \of F Y') . "<br />"; ?>
</div>
<?php
if (!(isset($_GET['id']) && isset($_GET['c']) && isset($_GET['equip']) && 
        isset($_GET['reason']) && isset($_GET['sps']) && isset($_GET['srt'])
         && isset($_GET['end']) && isset($_GET['dt']) && isset($_GET['o']) )) {
    echo "<div class='box b1'>NO VALUES passed</div>";
} else {
    $workCell = $_GET['c'];
    $id = $_GET['id'];
    $equipID = $_GET['equip'];
    $dtCodeID = $_GET['reason'];
    $spsCodeID = $_GET['sps'];
    $start = $_GET['srt'];
    $end = $_GET['end'];
    $dtMins = $_GET['dt'];
    $opt =  $_GET['o'];
    $ns=0;
    if (isset($_GET['ns'])) { $ns=$_GET['ns']; }
    include "lineDefinitions.php";

    echo "<a style='text-decoration:none;' href='DTEvents.php?c=" . $workCell
            . "&ns=" . $ns . "&o=" . $opt
            . "'><div class='box b1'>" . $lTitle[$workCell] . '</div></a>';

    $query = "UPDATE " . $tblLoss[$workCell]['name'] 
            . " SET equipment=" . $equipID 
            . ",dt_code=" . $dtCodeID 
            . ",sps_code=". $spsCodeID 
            . " WHERE " . $tblLoss[$workCell]['id']  . "=" . $id;
    $result = queryMysql($query);
    
    if($result) {
        echo '<div class="dtEvents">Event was successfully updated.</div>';
    } else {
        echo '<div class="dtEvents">An error occured. The event was not updated.</div>';
    }
    mysqli_close($myLink);
}

?>
    </body>
</html>