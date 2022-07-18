<?php
/*
 * Update Downtime Event SPS Loss column
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
        isset($_GET['reason']) && isset($_GET['s']) && isset($_GET['srt'])
         && isset($_GET['end']) && isset($_GET['dt']) && isset($_GET['o']) )) {
    echo "<div class='box b1'>NO VALUES passed</div>";
} else {
    $workCell = $_GET['c'];
    $id = $_GET['id'];
    $equipID = $_GET['equip'];
    $dtCodeID = $_GET['reason'];
    $spsCodeID = $_GET['s'];
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

    $query = "SELECT id, Metric FROM sps_downtime_codes";
    $result = queryMysql($query);
    if($result) { $num = mysqli_num_rows($result); }
    echo '<div class="dtEvents sps">';
    if ($num==0) { 
        $error =    "<span class='error'>Error Retrieving SPS Loss Codes</span><br /><br />";
        echo $error;
    } else {
        echo '<p>UPDATE DOWNTIME EVENT SPS LOSS CATEGORY</p><p>';
        echo '<table><col width="250"><col width="250"><col width="200"><tr>'
            . '<td>Start</td><td>End</td><td>Duration (mins)</td></tr>'
            . '<tr><td>' . $start . '</td><td>' . $end . '</td><td>' . $dtMins 
            . '</td></tr></table></p>';
        echo '<p>SELECT SPS LOSS CATEGORY:</p>';
        echo '<form action="DTEventsUpdateSubmit.php">';
        echo '<input type="hidden" name="id" value="'. $id .'">';
        echo '<input type="hidden" name="c" value="' . $workCell .'">';
        echo '<input type="hidden" name="equip" value="' . $equipID .'">'; 
        echo '<input type="hidden" name="reason" value="' . $dtCodeID .'">';
        echo '<input type="hidden" name="srt" value="' . $start . '">';
        echo '<input type="hidden" name="end" value="' . $end . '">';
        echo '<input type="hidden" name="dt" value="' . $dtMins . '">';
        echo '<input type="hidden" name="ns" value="' . $ns . '">';
        echo '<input type="hidden" name="o" value="' . $opt . '">';
        
        echo '<select name="sps" font-size="20px"><optgroup>';
        for ($i=0; $i<$num; $i++) {
            $row = mysqli_fetch_row($result);
            echo "<option value='" . $row[0] . "'";
            if ($row[0] == $spsCodeID) { echo " selected='selected'"; }
            echo ">". $row[1] . "</option>";
        }
        echo '</optgroup></select><br /><br /><input  class="update" type="submit" value="UPDATE RECORD"></form>';
    }
    echo '</div>';
        
    mysqli_close($myLink);
}
?>
    </body>
</html>