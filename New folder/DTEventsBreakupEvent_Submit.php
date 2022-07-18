<?php
/*
 * Break-up a Downtime Event - Submit Page.
 */
$autoRefresh=FALSE;
include 'header2p0.php';
?>
<a style='text-decoration:none;' href='dashboard2p0.php'><div class="brand">Sonoco Reels & Plugs</div></a>
<div class="brand timeStamp timeStampNarrow">
    <?php echo date('l h:i:s A') . "<br />" . date('jS \of F Y') . "<br />"; ?>
</div>
<?php
if (!(isset($_GET['id']) && isset($_GET['c']) && isset($_GET['e']) && 
        isset($_GET['r']) && isset($_GET['s']) && isset($_GET['srt'])
         && isset($_GET['end']) && isset($_GET['dt']) && isset($_GET['o'])
         && isset($_GET['eventLen']))) {
    echo "<div class='box b1'>NO VALUES passed</div>";
} else {
    $workCell = $_GET['c'];
    $id = $_GET['id'];
    $equipID = $_GET['e'];
    $dtCodeID = $_GET['r'];
    $spsCodeID = $_GET['s'];
    $start = $_GET['srt'];
    $end = $_GET['end'];
    $dtMins = $_GET['dt'];
    $opt =  $_GET['o'];
    $newEventLen = $_GET['eventLen'];
    
    $ns=0;
    if (isset($_GET['ns'])) { $ns=$_GET['ns']; }
    
    include "lineDefinitions.php";
    echo "<a style='text-decoration:none;' href='DTEvents.php?c=" . $workCell 
            . "&ns=" . $ns . "&o=" . $opt
            . "'><div class='box b1'>" . $lTitle[$workCell] . '</div></a>';
    echo '<div class="dtEvents breakUpBorder">';
    if (is_numeric($newEventLen)) {
        if ($newEventLen>=2 && $newEventLen <= ($dtMins-2)) {
            $query = "SELECT shift, crew FROM ".$tblLoss[$workCell]['name']." WHERE ".$tblLoss[$workCell]['id']."=$id";
            $result = queryMysql($query);
            if($result) { $num = mysqli_num_rows($result); }
            if ($num==0) { 
                $error =    "<span class='error'>Error Retrieving Crew and Shift</span><br /><br />";
                echo $error;
            } else {
                $row = mysqli_fetch_row($result);
                $shift=$row[0];
                $crew=$row[1];
            
                
                if (isset($_GET['before'])) {
                    $query = "UPDATE ".$tblLoss[$workCell]['name']." SET start="
                            . "DATE_ADD(start,INTERVAL $newEventLen MINUTE) "
                            . "WHERE ".$tblLoss[$workCell]['id']."=$id";
                    echo "Before,update query=$query<br/>";
                    $result = queryMysql($query);
                    if($result) {
                        $query = "INSERT INTO ".$tblLoss[$workCell]['name']." SET equipment=$equipNS[$workCell], "
                            . "dt_code=$dtcNS[$workCell], sps_code=0, start='$start', "
                            . "end=DATE_ADD('$start',INTERVAL $newEventLen MINUTE), "
                            . "shift=$shift, crew='$crew'";
                        echo "Before,insert query=$query<br/>";
                        $result = queryMysql($query);
                        if ($result) {
                            echo 'Original Event was successfully updated, and '
                            . 'new event inserted';
                        } else {
                            echo 'An error occured. Original Event was updated, '
                            . 'but NEW EVENT INSERT FAILED!';
                        }
                    } else {
                        echo 'An error occured. The Original Event was not '
                        . 'updated, and Insert of new Event was not attempted.';
                    }
                }
                if (isset($_GET['after'])) {
                    echo "After,update query=$query<br/>";
                    $query = "UPDATE ".$tblLoss[$workCell]['name']." SET end="
                            . "DATE_SUB(end,INTERVAL $newEventLen MINUTE) "
                            . "WHERE ".$tblLoss[$workCell]['id']."=$id";
                    $result = queryMysql($query);
                    if($result) {
                        $query = "INSERT INTO ".$tblLoss[$workCell]['name']." SET equipment=$equipNS[$workCell], "
                            . "dt_code=$dtcNS[$workCell], sps_code=0, "
                            . "start=DATE_SUB('$end',INTERVAL $newEventLen MINUTE)"
                            . ", end='$end', "
                            . "shift=$shift, crew='$crew'";
                        echo "After,insert query=$query<br/>";
                        echo '<br />';
                        $result = queryMysql($query);
                        if ($result) {
                            echo 'Original Event was successfully updated, and '
                            . 'new event inserted';
                        } else {
                            echo 'An error occured. Original Event was updated, '
                            . 'but NEW EVENT INSERT FAILED!';
                        }
                    } else {
                        echo 'An error occured. The Original Event was not '
                        . 'updated, and Insert of new Event was not attempted.';
                    }
                }
            }
            
            mysqli_close($myLink);
        } else { 
            echo '<span id="spanWarn">Duration must be greater than 2 mins '
            . 'and less than Original event minus 2 mins</span>';
        }
    } else {
        echo '<span id="spanWarn">Duration must be a numeric value.</span>';
    }
    echo '</div>';

}