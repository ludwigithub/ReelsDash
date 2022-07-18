<?php
/*
 * Update Downtime Event Equipment column
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
        isset($_GET['r']) && isset($_GET['s']) && isset($_GET['srt'])
         && isset($_GET['end']) && isset($_GET['dt']) && isset($_GET['o']) )) {
    echo "<div class='box b1'>NO VALUES passed</div>";
} else {
    $workCell = $_GET['c'];
    $id = $_GET['id'];
    $equipID = $_GET['equip'];
    $dtCodeID = $_GET['r'];
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

    $query = "SELECT id, dt_reason FROM dt_code WHERE deleted=0 AND equip=" . $equipID;
    $result = queryMysql($query);
    if($result) { $num = mysqli_num_rows($result); }
    echo '<div class="dtEvents reas">';
    if ($num==0) { 
        $error =    "<span class='error'>Error Retrieving DT Codes</span><br /><br />";
        echo $error;
    } else {
        echo '<p>UPDATE DOWNTIME REASON</p><p>';
        echo '<table><col width="250"><col width="250"><col width="200"><tr>'
            . '<td>Start</td><td>End</td><td>Duration (mins)</td></tr>'
            . '<tr><td>' . $start . '</td><td>' . $end . '</td><td>' . $dtMins 
            . '</td></tr></table></p>';
        echo '<p>SELECT REASON:</p>';
        echo '<form action="DTEventsUpdateSPS.php">';
        echo '<input type="hidden" name="id" value="'. $id .'">';
        echo '<input type="hidden" name="c" value="' . $workCell .'">';
        echo '<input type="hidden" name="equip" value="' . $equipID .'">'; 
        echo '<input type="hidden" name="s" value="' . $spsCodeID .'">';
        echo '<input type="hidden" name="srt" value="' . $start . '">';
        echo '<input type="hidden" name="end" value="' . $end . '">';
        echo '<input type="hidden" name="dt" value="' . $dtMins . '">';
        echo '<input type="hidden" name="ns" value="' . $ns . '">';
        echo '<input type="hidden" name="o" value="' . $opt . '">';
            
        echo '<select name="reason" font-size="20px"><optgroup>';
        for ($i=0; $i<$num; $i++) {
            $row = mysqli_fetch_row($result);
            echo "<option value='" . $row[0] . "'";
            if ($row[0] == $dtCodeID) { echo " selected='selected'"; }
            echo ">". $row[1] . "</option>";
        }
        echo '</optgroup></select><br /><br /><input type="submit" value="NEXT"></form>';
    }
    echo '</div>';
        
    mysqli_close($myLink);
}
?>
    </body>
</html>