<?php
/*
View downtime events
2015.11.05
Nicholas West
Engineering Projects Manager
Sonoco Products Co

*/
$error="0";
$autoRefresh=TRUE;
include 'header2p0.php';
include 'buildVideoArray.php';
?>
<a style='text-decoration:none;' href='dashboard2p0.php'><div class="brand">Sonoco Reels & Plugs</div></a>
<div class="brand timeStamp timeStampNarrow">
    <?php echo date('l h:i:s A') . "<br />" . date('jS \of F Y') . "<br />"; ?>
</div>
<?php
$ns = FALSE;
if (!(isset($_GET['c']))) {
    echo "<div class='box b1'>NO Work Cell (Line) passed</div>";
} else {
    $workCell = $_GET['c'];
    
    $End='NOW()';
    $opt=1;
    $where = "WHERE dte.start>='". $sStart[$workCell] ."' AND dte.start<=NOW() ";
    
    if (isset($_GET['o'])) {
        $opt = $_GET['o'];
        if ($opt==2) { $where = "WHERE DATE(dte.start)=DATE(NOW()) ";}
        if ($opt==3) { $where = "WHERE DATE(dte.start)=DATE(DATE_SUB(NOW(),INTERVAL 1 DAY)) ";}
        if ($opt==4) { $where = "WHERE dte.start>='$wkOfSQL[$workCell]' AND dte.start<='$wkEndSQL[$workCell]' ";}
        if ($opt==5) { $where = "WHERE dte.start>='$lastWkOfSQL[$workCell]' AND dte.start<='$wkOfSQL[$workCell]' ";}
    }
    if (isset($_GET['ns'])) { 
        if ($_GET['ns']==1) {
            $where .= "AND dte.sps_code=0 AND dte.equipment=$equipNS[$workCell] AND dte.dt_code=$dtcNS[$workCell] "; 
            $ns=TRUE;
        }
    }
    $query="SELECT dte." . $tblLoss[$workCell]['id'] . ", "
            . "dte.equipment As EquipID, equipment.equipment, dte.dt_code AS DTCodeID, "
            . "dtc.dt_reason, dte.sps_code AS SPSMetricID, spc.Metric, dte.start, dte.end, "
            . "TIMESTAMPDIFF(SECOND,dte.start,dte.end) As 'DownTime (s)', "
            . "TIMESTAMPDIFF(SECOND,dte.start,dte.end)/60 As 'DownTime (min)' FROM "  
            . $tblLoss[$workCell]['name'] . " AS dte "
            . "INNER JOIN equipment ON equipment.id = dte.equipment "
            . "INNER JOIN dt_code AS dtc ON dtc.id = dte.dt_code "
            . "INNER JOIN sps_downtime_codes AS spc ON spc.id = dte.sps_code "
            . $where 
            . "ORDER BY dte.start DESC LIMIT 5000";
    $result = queryMysql($query);
    $totalLoss = 0;
    echo "<a style='text-decoration:none;' href='DTEvents.php?c=$workCell";
    if ($ns) {echo "&ns=1";}
    echo "'><div class='box b1"; if ($opt==1) {echo ' selected';} echo "'>$lTitle[$workCell]</div></a>";
    echo "<a style='text-decoration:none;' href='DTEvents.php?c=$workCell&o=2"; 
    if ($ns) {echo "&ns=1";}
    echo "'><div class='box b1 posButton3"; if ($opt==2) {echo ' selected';} echo "'>Today</div></a>";
    echo "<a style='text-decoration:none;' href='DTEvents.php?c=$workCell&o=3";
    if ($ns) {echo "&ns=1";}
    echo "'><div class='box b1 posButton4"; if ($opt==3) {echo ' selected';} echo "'>Yesterday</div></a>";
    echo "<a style='text-decoration:none;' href='DTEvents.php?c=$workCell&o=4";
    if ($ns) {echo "&ns=1";}
    echo "'><div class='box b1 posButton5"; if ($opt==4) {echo ' selected';} echo "'>This Week</div></a>";
    echo "<a style='text-decoration:none;' href='DTEvents.php?c=$workCell&o=5";
    if ($ns) {echo "&ns=1";}
    echo "'><div class='box b1 posButton6"; if ($opt==5) {echo ' selected';} echo "'>Last Week</div></a>";
    echo "<a style='text-decoration:none;' href='DTEvents.php?c=$workCell&o=$opt";
    if ($ns) { echo "'><div class='box b1 posButton7 selected'>Not Specified</div></a>"; }
    else { echo "&ns=1'><div class='box b1 posButton7'>Not Specified</div></a>"; }

    if($result) { $num = mysqli_num_rows($result); }
    if ($num==0) { 
        $error =    "<span class='error'>No Events Returned</span><br /><br />";
    } else {
        echo '<table class="dtEvents"><col width="75"><col width="175"><col width="350"><col width="200">'
            . '<col width="200"><col width="200"><col width="75"><col width="50">';
        echo '<tr class="s2"><td>id</td><td>Equipment</td><td>Reason</td><td>SPS Loss</td>'
        . '<td>Started</td><td>Ended</td><td>DT(mins)</td><td></td></tr>';

         for ($i=0; $i<$num; $i++) {
            $row = mysqli_fetch_row($result);
            echo '<tr class="lnk';
            if ($row[5]==0) { echo ' ns'; }
            echo '" onclick="location.href=' . "'DTEventsUpdateEquip.php?id=" 
                    . $row[0] ."&c=" . $workCell . "&e=" . $row[1] ."&r=". $row[3] 
                    . "&s=" . $row[5] . "&srt=" . $row[7] . "&end=" . $row[8] 
                    . "&dt=" . round($row[10],2) . "&ns=" . $ns  
                    . "&o=" . $opt  . "'" .'"><td>' . $row[0] 
                    . '</td><td>' . $row[2]
                    . '</td><td>' . $row[4] . '</td><td>' . $row[6] 
                    . '</td><td>' . $row[7]. '</td><td>' . $row[8]
                    . '</td><td>' . number_format($row[10],2) . '</td>';
					//----logic for displaying video icon-----------
					$dtVidFound = false;
					if (isset($videoFileNames[$lLabel[$workCell]])){//check to see if any videos are available at all for the line
						for ($cam=0; $cam<count($videoFileNames[$lLabel[$workCell]]); $cam++) { //loop through all cameras for line
							if ($videoFileNames[$lLabel[$workCell]][$cam][0]['ts']<strtotime($row[7])) {//check if first video time is less than dt event time
								$dtVidFound = true;
								break;
							}
						}
					}
					if ($dtVidFound) {
						echo '<td><a style="text-decoration:none;" href="DTEVideo.php?ts='.strtotime($row[7]).'&line='.$lLabel[$workCell].'&offs=0'
							. '"><img src="cam.png"></a></td>';
					}
					//----end of logic for displaying video icon----
					echo '</tr></a>';
            $totalLoss += $row[10];

         }
         echo '<tr class="s2"><td></td><td></td><td></td><td></td><td></td><td>Period Total:</td><td> ' 
            . number_format($totalLoss,2) . '</td></tr></table>';
    }
    echo '</div>';
    echo "<a href='reports/opReport.php?c=$workCell'><div class='box b1 report'>Reports</div></a>";
    mysqli_close($myLink);
}
?>
    </body>
</html>