<?php
/*
 * Break-up a Downtime Event.
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
         && isset($_GET['end']) && isset($_GET['dt']) && isset($_GET['o']))) {
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
    $ns=0;
    if (isset($_GET['ns'])) { $ns=$_GET['ns']; }
    
    include "lineDefinitions.php";
    echo "<a style='text-decoration:none;' href='DTEvents.php?c=" . $workCell 
            . "&ns=" . $ns . "&o=" . $opt
            . "'><div class='box b1'>" . $lTitle[$workCell] . '</div></a>';
    
    echo '<div class="dtEvents breakUpBorder">';
    echo '<p>BREAK-UP EVENT</p><p>';
    echo '<table><col width="250"><col width="250"><col width="200"><tr>'
        . '<td>Start</td><td>End</td><td>Duration (mins)</td></tr>'
        . '<tr><td>' . $start . '</td><td>' . $end . '</td><td>' . $dtMins 
        . '</td></tr></table></p>';
    echo 'New Event Duration:';
    echo '<form action="DTEventsBreakupEvent_Submit.php">';
    echo '<input type="hidden" name="id" value="'. $id .'">';
    echo '<input type="hidden" name="c" value="' . $workCell .'">';
    echo '<input type="hidden" name="r" value="' . $dtCodeID .'">'; 
    echo '<input type="hidden" name="s" value="' . $spsCodeID .'">';
    echo '<input type="hidden" name="srt" value="' . $start . '">';
    echo '<input type="hidden" name="end" value="' . $end . '">';
    echo '<input type="hidden" name="dt" value="' . $dtMins . '">';
    echo '<input type="hidden" name="ns" value="' . $ns . '">';
    echo '<input type="hidden" name="o" value="' . $opt . '">';
    echo '<input type="hidden" name="e" value="' . $equipID . '">';
    echo '<input type="text" name="eventLen">(mins)';
    echo "<span id='spanWarn'><br />CAUTION: CAN'T BE UNDONE</span><br />";
    echo '<input name="before" class="update" type="submit" value="INSERT AT BEGINNING"><br />';
    echo '<input name="after" class="update" type="submit" value="INSERT AT END">';
    echo '</form>';
}

?>