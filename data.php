<?php
$server = "MXL1072KZ8";
$username = "ace";
$password = "Reels.Ace";
$dbname = "dashboard";

$conn = mysqli_connect($server, $username, $password, $dbname);

$lines = [1,3,4,5,6,7,9,20,30,40,50,60,62];
foreach ($lines as $l) 
{
    $sUnitsTgt[$l] = 0.0;
    $sUnitsL1[$l] = 0.0;
    $dUnitsTgt[$l] = 0.0;
    $dUnitsL1[$l] = 0.0;
    $spdTgt[$l] = 0.0;
	$spdL1[$l] = 0.0;
	$spdL1[$l] = 0.0;
	$sSpdPer[$l] = 0.0;
	$rollAvg10Per[$l] = 0.0;
}

$q = "SELECT p.idLine, shiftTarget, shiftL1, dailyTarget, dailyL1, uptimeTarget, uptimeL1, "
        . "(SELECT targetUnitsPerHr FROM target_speed WHERE target_Speed.idLine = p.idLine), "
		. "(SELECT visL1 FROM target_speed WHERE target_Speed.idLine = p.idLine), "
		. "(SELECT visL2 FROM target_speed WHERE target_Speed.idLine = p.idLine) "
        . "AS SpdTgt FROM target_production AS p";
$result = queryMysql($q);

if($result) { $num = mysqli_num_rows($result); }


if ($num==0) { 
    $error =    "<span class='error'>ERROR Connecting to database. (Nailer Lines - 10 min rolling avg.)</span><br /><br />";
} else {
    for ($i=0; $i<$num; $i++) {
        $row = mysqli_fetch_row($result);
        $sUnitsTgt[$row[0]]=$row[1];
        $sUnitsL1[$row[0]]=$row[2];
        $dUnitsTgt[$row[0]]=$row[3];
        $dUnitsL1[$row[0]]=$row[4];
        $uptimeTgt[$row[0]]=$row[5];
        $uptimeL1[$row[0]]=$row[6];
        $spdTgt[$row[0]] = $row[7];
		$spdL1[$row[0]] = $row[8];
		$spdL2[$row[0]] = $row[9];
    }
}
$q = "";

//   ****************    DISPLAY DASHBOARD   ****************    

//$order = [6,1,30,7,8,3,4,9,5,20,40,50,60];
$order = [6,1,30,7,3,4,9,5,20,40,50,60,62];

foreach ($order as $i) {
    // ------------ LINE NAME -------------------
    echo "<tr><td><a style='text-decoration:none;' href='DTEvents.php?c=$i'><div class='dbCol colName "; 
    if ($lDown[$i]==0) { echo "good'>".$lLabel[$i]."</div></a></td>"; } 
    elseif ($lDown[$i]==1 && $s[$i]>0){
		if ($idleTime[$i]<=999){
			echo "bad'>".$lLabel[$i]."<br/> "
                . number_format($idleTime[$i],0) . " min</div></a></td>"; 
		} else {
			echo "bad'>".$lLabel[$i]."<br/> "
                . number_format(($idleTime[$i]/60),0) . " hr</div></a></td>"; 
		}
    } else { echo "'>" . $lLabel[$i] . "</div></a></td>"; }
    // ------------ SHIFT -------------------
    echo "<td><a style='text-decoration:none;' href='DTEvents.php?c=$i'>"
        . "<div class='dbCol colShift "; if ($s[$i]>0) {echo "colActive";} echo "'>".$s[$i]."</div></a></td>";
    // ------------ UNITS -------------------
    echo "<td><a style='text-decoration:none;' href='DTEvents.php?c=$i'>"
        . "<div class='dbCol colUnits "; if ($s[$i]>0) {echo "colActive";} echo "'>". number_format($sQty[$i],0) . "</div></a></td>";
    // ------------ UPTIME -------------------
    echo "<td><a style='text-decoration:none;' href='DTEvents.php?c=$i'><div class='dbCol colUptime "; 
    if ($s[$i]>0 && $upTime[$i]>=$uptimeL1[$i]/100 && $upTime[$i]<$uptimeTgt[$i]/100) { echo " okay"; }
    if ($s[$i]>0 && $upTime[$i]>=$uptimeTgt[$i]/100) { echo " good"; }
    if ($s[$i]>0 && $upTime[$i]<$uptimeL1[$i]/100) { echo " bad"; }
    echo "'>".number_format($upTime[$i]*100,0)."</div></a></td>";
    // ------------ AVERAGE SPEED -------------------
	$sSpdPer[$i]=$sSpdAvg[$i]/$spdTgt[$i]*100;
    echo "<td><a style='text-decoration:none;' href='DTEvents.php?c=$i'><div class='dbCol colSpd ";
    if ($s[$i]>0 && $sSpdPer[$i]<$spdL2[$i] && $sSpdPer[$i]>=$spdL1[$i]) { echo " okay"; }
    if ($s[$i]>0 && $sSpdPer[$i]>=$spdL2[$i] ) { echo " good"; }
    if ($s[$i]>0 && $sSpdPer[$i]<$spdL1[$i] ) { echo " bad"; }
    echo "'>".number_format($sSpdPer[$i],0)."</div></a></td>";
    // ------------ CURRENT SPEED -------------------
	$rollAvg10Per[$i]=$rollAvg10[$i]/$spdTgt[$i]*100;
    echo "<td><a style='text-decoration:none;' href='DTEvents.php?c=$i'><div class='dbCol colSpd";
    if ($s[$i]>0 && $rollAvg10Per[$i]<$spdL2[$i] && $rollAvg10Per[$i]>=$spdL1[$i]) { echo " okay"; }
    if ($s[$i]>0 && $rollAvg10Per[$i]>=$spdL2[$i] ) { echo " good"; }
    if ($s[$i]>0 && $rollAvg10Per[$i]<$spdL1[$i] ) { echo " bad"; }
    echo "'>".number_format($rollAvg10Per[$i],0);
    echo "</div></a></td>";
    // ------------ Data Integrity (Not Specified) -------------------
    echo "<td><a style='text-decoration:none;' href='DTEvents.php?c=$i'>"
        . "<div class='dbCol colData "; if ($s[$i]>0) {echo "colActive";}   
    if ($lossBySPS[$i][$spsNS]<=.25 && $lossBySPS[$i][$spsMR]<= $sSchedule[$i]['unpaidShiftMins']/60*1.1 ) { echo "'>OK"; } 
    if ($lossBySPS[$i][$spsNS]<=.25 && $lossBySPS[$i][$spsMR] > $sSchedule[$i]['unpaidShiftMins']/60*1.1) {
        echo " colDataIssue'><font size='6'>Market Related:<br></font><font size='8'>".number_format($lossBySPS[$i][$spsMR]*60)." min</font>";
    }if ($lossBySPS[$i][$spsNS]>.25 ) {
        echo " colDataIssue'><font size='6'>Not Specified:<br></font><font size='8'>".number_format($lossBySPS[$i][$spsNS]*60)." min</font>";
    }
    echo "</div></a></td>";
    // ------------ Order Info -------------------
    if($i!=8 && $i!=50 && $i != 9){
		echo "<td><a style='text-decoration:none;' href='viewRuns.php?c=$i'>"
			. "<div class='dbCol colOrder "; if ($s[$i]>0) {echo "colActive";} echo "'>".$oID[$i]."<br/>"
			. $oQty[$i] . ' of ' . $oNeeded[$i] ."</div></a></td>";
    } else {
		echo "<td><a style='text-decoration:none;' href='viewRuns.php?c=$i'>"
        . "<div class='dbCol colOrder "; if ($s[$i]>0) {echo "colActive";} echo "'>".$oID[$i]."<br/>"
        . "? of ?</div></a></td>";
	}
    // ------------ Order Remaining (Need) -------------------
    echo "<td><a style='text-decoration:none;' href='DTEvents.php?c=$i'>"
        . "<div class='dbCol colRemaining "; if ($s[$i]>0) {echo "colActive";}
    if ($i != 20 && $i != 40 && $i != 8 && $i != 50 && $i != 9) {
        $remaining[$i] = $oNeeded[$i]-$oQty[$i];
        if ($remaining[$i]<=25 and $remaining[$i]>0) {
            echo " okay";
        }
        if ($remaining[$i]<=0 ) {
            echo " good";
        }
    } else {$remaining[$i]="";}
    echo "'>$remaining[$i]</div></a></td>";

    
    echo "</tr>";
}
echo "</table></div>";
/*
echo "<table>";
$order = [1,3,4,5,6,30,20];
foreach($order as $l) {
foreach ($lossBySPS[$l] as $key => $value) {
    echo "<tr><td>Hrs=$sHrsIntoShift[$l]</td><td>$idleTime[$l]</td><td>Line=$l</td><td>Key=$key</td><td>Value=$value</td></tr>";
}
}
echo "</table>";
 * 
 */

mysqli_close($myLink);
?>
?>