<?php
/*
Hartselle Nailwood Dashboard
2018.10.05
Nicholas West
*/
$autoRefresh=TRUE;
include 'header2p0.php';
?>
<a style='text-decoration:none;' href='dashboard2p0.php'><div class="brand">Sonoco Reels & Plugs</div></a>
<div class="brand timeStamp">
    <?php echo date('l h:i:s A') . "<br />" . date('jS \of F Y') . "<br />"; ?>
</div>
<?php

//   ****************    BUILD DATA SECTION   ****************    
//--------- GET EACH LINES DOWNTIME BY SPS CODE -------------------
//$order = [1,3,4,5,6,7,8,9,20,30,40,50,60];
$order = [1,3,4,5,6,7,9,20,30,40,50,51,60,62,70];
$q = "";
foreach ($order as $l) {
    for ($j=0; $j<=7; $j++){ $lossBySPS[$l][$j]=0.0; } // Initialize each loss by SPS for each line
}
foreach ($order as $l) {
    if (strlen($q)>1) { $q .= " union "; }
    $q .= "SELECT $l, sps_code, SUM(TIMESTAMPDIFF(SECOND,start,end))/60/60 AS DT_hrs "
            . "FROM " . $tblLoss[$l]['name'] . " "
            . "WHERE start>='" . $sStart[$l] . "' "
            . "GROUP BY sps_code";
    
}
// echo "<br /><br /> q  =  $q <br /> ";
$result = queryMysql($q);
if($result) { $num = mysqli_num_rows($result); }
if ($num==0) { 
    $error =    "<span class='error'>ERROR Connecting to database. (dashbaord - Loss Table)</span><br /><br />";
} else {
    for ($i=0; $i<$num; $i++) {
        $row = mysqli_fetch_row($result);
        $lossBySPS[$row[0]][$row[1]]=$row[2];
    }
}
//-----------  GET FLANGES PRODUCED THIS SHIFT and LAST 10 Mins---------------
//$order = [1,3,4,5,6,7,8,9,30,50];
$order = [1,3,4,5,6,7,9,30,50];
$q="";
// Define a union sql query string to retrieve each lines production count with union clause
foreach ($order as $l) {
    if (strlen($q)>1) { $q .= " union "; }
    $q .= " SELECT $l, COUNT(*) FROM " . $tblProd[$l]['name'] . " WHERE " 
            . $tblProd[$l]['ts'] . " >= '" . $sStart[$l] . "' and " 
            . $tblProd[$l]['cnt'] . ">0 ";
    $sQty[$l]=0; //Initially define each line's shift quanity to 0.
}
// echo "<br /><br />q= $q <br />";
$result = queryMysql($q);
if($result) { $num = mysqli_num_rows($result); }
if ($num==0) { 
    $error =    "<span class='error'>ERROR Connecting to database. (Shift Production)</span><br /><br />";
} else {
    for ($i=0; $i<$num; $i++) {
        $row = mysqli_fetch_row($result);
        $sQty[$row[0]]=$row[1];
    }
}

//-----------  GET Ft Cut  THIS SHIFT---------------
//$order = [1,3,4,5,6,7,8,9,30,50,70];
$order = [51];
$q="";
// Define a union sql query string to retrieve each lines production sum with union clause
foreach ($order as $l) {
    if (strlen($q)>1) { $q .= " union "; }
    $q .= " SELECT $l, SUM(qty) FROM " . $tblProd[$l]['name'] . " WHERE " 
            . $tblProd[$l]['ts'] . " >= '" . $sStart[$l] . "' and " 
            . $tblProd[$l]['cnt'] . ">0 ";
    $sQty[$l]=0; //Initially define each line's shift quanity to 0.
	//   ---------- UPTIME CALC ----------------
	$lID = $l;
	$tempVar = $lossBySPS[$lID][$spsNS] + $lossBySPS[$lID][$spsMS] + 
        $lossBySPS[$lID][$spsBD] + $lossBySPS[$lID][$spsCO] + $lossBySPS[$lID][$spsSuSd] + 
        $lossBySPS[$lID][$spsPM]; 
    // $upTime[$lID]=1 - ($tempVar + $idleTime[$lID]/60)/ ($sHrsIntoShift[$lID] - $lossBySPS[$lID][$spsMR] - $lossBySPS[$lID][$spsSPD]);
	//Don't have idletime calculation for the Opt SAW
	$upTime[$lID]=1 - ($tempVar )/ ($sHrsIntoShift[$lID] - $lossBySPS[$lID][$spsMR] - $lossBySPS[$lID][$spsSPD]);
    if ($upTime[$lID]<0) {$upTime[$lID]=0;}
	
}

// echo "<br /><br />q= $q <br />";
$result = queryMysql($q);
if($result) { $num = mysqli_num_rows($result); }
if ($num==0) { 
    $error =    "<span class='error'>ERROR Connecting to database. (Shift Production)</span><br /><br />";
} else {
    for ($i=0; $i<$num; $i++) {
        $row = mysqli_fetch_row($result);
        $sQty[$row[0]]=$row[1]/2.54/12;
    }
}
// BOLT LINE 1  
$order = [70];
$boltDiam = 0.0;
$boltLen = 0.0;
foreach ($order as $i) {
    $lID = $i;
	$boltDiam = 0.0;
	$boltLen = 0.0;
    list ($lDown[$lID], $oQty[$lID], $oNeeded[$lID], $boltDiam, $boltLen, $idleTime[$lID])
            = shiftStatusBolts($lID);
    $oID[$lID] = $boltDiam . ' X ' . $boltLen;
	$sSpdAvg[$lID] = 0.0;
//    echo "<br /><br />Line = $lID<br/>Non-MR Loss = $tempVar<br />Idle Time = $idleTime[$lID]<br/>Qty=$sQty[$lID]";
//    echo "<br/>Spd Loss = " . $lossBySPS[$lID][$spsSPD] . "; MR Loss = " . $lossBySPS[$lID][$spsMR];
//    echo "<br/>Hrs Into Shift = $sHrsIntoShift[$lID]";
}

//-----------  GET Bolts Produced THIS SHIFT---------------
//$order = [1,3,4,5,6,7,8,9,30,50,70];
$order = [70];
$q="";
// Define a union sql query string to retrieve each lines production sum with union clause
foreach ($order as $l) {
    if (strlen($q)>1) { $q .= " union "; }
    $q .= " SELECT $l, SUM(qty) FROM " . $tblProd[$l]['name'] . " WHERE " 
            . $tblProd[$l]['ts'] . " >= '" . $sStart[$l] . "'";
	//echo "<br /><br /><br /><br /><br /><br /> $q <br /><br />";
    $sQty[$l]=0; //Initially define each line's shift quanity to 0.
	$sSpdAvg[$l] = 0;
	
}
$result = queryMysql($q);
if($result) { $num = mysqli_num_rows($result); }
if ($num==0) { 
    $error =    "<span class='error'>ERROR Connecting to database. (Shift Production)</span><br /><br />";
} else {
    for ($i=0; $i<$num; $i++) {
        $row = mysqli_fetch_row($result);
        $sQty[$row[0]]=$row[1];
		//   ---------- UPTIME & Speed CALC ----------------
		$lID = $row[0];
		$tempVar = $lossBySPS[$lID][$spsNS] + $lossBySPS[$lID][$spsMS] + 
			$lossBySPS[$lID][$spsBD] + $lossBySPS[$lID][$spsCO] + $lossBySPS[$lID][$spsSuSd] + 
			$lossBySPS[$lID][$spsPM]; 
		$upTime[$lID]=1 - ($tempVar + $idleTime[$lID]/60)/ ($sHrsIntoShift[$lID] - $lossBySPS[$lID][$spsMR] - $lossBySPS[$lID][$spsSPD]);
		if ($upTime[$lID]<0) {$upTime[$lID]=0;}
		$sSpdAvg[$lID] = $sQty[$lID]/($sHrsIntoShift[$lID] - $tempVar + $lossBySPS[$lID][$spsSPD] + $idleTime[$lID]/60);
    }
}
$order = [51,70];
$q="";
/// -----------------  OPT SAW & BOLT 10 min Speed 
foreach ($order as $l) {
    if (strlen($q)>1) { $q .= " union "; }
    $q .= " SELECT $l, SUM(qty) FROM " . $tblProd[$l]['name'] . " WHERE " 
            . $tblProd[$l]['ts'] . " >= DATE_SUB(NOW(),INTERVAL 10 MINUTE)";
	//echo "<br /><br /><br /><br /><br /><br /><br /> . $q . <br />";
	$rollAvg10[$lID] = 0;
}
// echo "<br /><br />q= $q <br />";
$result = queryMysql($q);
if($result) { $num = mysqli_num_rows($result); }
if ($num==0) { 
    $error =    "<span class='error'>ERROR Connecting to database. (Shift Production)</span><br /><br />";
} else {
    for ($i=0; $i<$num; $i++) {
        $row = mysqli_fetch_row($result);
        if ($row[0]==51) {
			$rollAvg10[$row[0]]=$row[1]/2.54/12;
		} else {
			$rollAvg10[$row[0]]=$row[1];
		}
    }
	if ($rollAvg10[$row[0]]>0) {
		$rollAvg10[$row[0]] *= 6;
	} else {
		$rollAvg10[$row[0]]=0;
	}
}
// ---------- Line 1 Nailer  & CAPE LINE ---------- 
// These lines don't have rolling averages being store in the PLC.  Will need to caluclate current speed 
// w/ query getting # of flanges in the last 10 minutes.  More individual queries are needed as well.
$order = [6,5];
$q="";
foreach ($order as $i) {
    $lID = $i;
    $SpdAvg[$lID] = 0;
    $Spd10Min[$lID] = 0;
    list ($flangeDiam[$lID], $flangeThick[$lID]) = flangeSize($tblSetup[$lID]['name'],$tblSetup[$lID]['id']);
    list ($oQty[$lID], $oNeeded[$lID], $idleTime[$lID]) = 
            runProgress($tblProd[$lID]['name'],$tblProd[$lID]['id'],$tblProd[$lID]['ts'],$sStart[$lID]);
    // $sQty[$lID] = shiftProgress($tblProd[$lID]['name'],$tblProd[$lID]['ts'],$s[$lID]);
    // $sDT[$lID] = totalDT($tblLoss[$lID]['name'],$s[$lID],$sStart[$lID]);
    
    $oID[$lID] = $flangeDiam[$lID] . '" X ' . $flangeThick[$lID];
    if ($idleTime[$lID] >= 120) { $lDown[$lID] = 1; } else { $lDown[$lID] = 0; }
 //   echo " <br /><br /><br /> -------<br /> idletime for $lID = $idleTime[$lID]";
    $idleTime[$lID] /= 60;
 //   echo " <br /> -------<br /> idletime for $lID = $idleTime[$lID]";
    $tempVar = $lossBySPS[$lID][$spsNS] + $lossBySPS[$lID][$spsMS] + 
        $lossBySPS[$lID][$spsBD] + $lossBySPS[$lID][$spsCO] + $lossBySPS[$lID][$spsSuSd] + 
        $lossBySPS[$lID][$spsPM]; 
//    echo "<br /><br />Line = $lID<br/>Non-MR Loss = $tempVar<br />Idle Time = $idleTime[$lID]";
//    echo "<br/>Spd Loss = " . $lossBySPS[$lID][$spsSPD] . "; MR Loss = " . $lossBySPS[$lID][$spsMR];
//    echo "<br/>Hrs Into Shift = $sHrsIntoShift[$lID]";
    $upTime[$lID]=1 - ($tempVar + $idleTime[$lID]/60)/ ($sHrsIntoShift[$lID] - $lossBySPS[$lID][$spsMR] - $lossBySPS[$lID][$spsSPD]);
    if ($upTime[$lID]<0) {$upTime[$lID]=0;}
    $sSpdAvg[$lID] = $sQty[$lID]/($tempVar + $lossBySPS[$lID][$spsSPD] + $idleTime[$lID]/60);
    $rollAvg10[$lID]=0;  //Initially define each line's last 10 mins flanges to 0.
    if (strlen($q)>1) { $q .= " union "; }
    $q .= " SELECT $lID, COUNT(*) FROM " . $tblProd[$lID]['name'] . " WHERE " 
            . $tblProd[$lID]['ts'] . " >= DATE_SUB(NOW(),INTERVAL 10 MINUTE) and " 
            . $tblProd[$lID]['cnt'] . ">0 ";
}   
//----------Get the last 10 min rolling average
$result = queryMysql($q);
if($result) { $num = mysqli_num_rows($result); }
if ($num==0) { 
    $error =    "<span class='error'>ERROR Connecting to database. (Nailer Lines - 10 min rolling avg.)</span><br /><br />";
} else {
    for ($i=0; $i<$num; $i++) {
        $row = mysqli_fetch_row($result);
        $rollAvg10[$row[0]]=$row[1] * 60 / 10;
    }
}

// Flange production lines with data being stored and captured in the PLC.
//$order = [1,3,4,7,8,9,30,50,60];
$order = [1,3,4,7,9,30,50,60,62];
foreach ($order as $i) {
    $lID = $i;
    $SpdAvg[$lID] = 0;
    $Spd10Min[$lID] = 0;
    list ($lDown[$lID], $sQtyNotUsed[$lID], $oQty[$lID], $oNeeded[$lID], 
            $flangeThick[$lID], $flangeDiam[$lID], $idleTime[$lID], $sDT[$lID], 
            $rollAvg10[$lID], $rollAvg30[$lID], $shift_temp[$lID], $shiftHrs_catch, $fstPass[$lID], $rework[$lID])
            = shiftStatusff($lID);
	if ($lType[$lID]=="plywood") {
		$sQty[$lID]=$sQtyNotUsed[$lID];
	}
	$oID[$lID] = round($flangeDiam[$lID],2) . '" X ' . round($flangeThick[$lID],2);
    $tempVar = $lossBySPS[$lID][$spsNS] + $lossBySPS[$lID][$spsMS] + 
        $lossBySPS[$lID][$spsBD] + $lossBySPS[$lID][$spsCO] + $lossBySPS[$lID][$spsSuSd] + 
        $lossBySPS[$lID][$spsPM]; 
    $upTime[$lID]=1 - ($tempVar + $idleTime[$lID]/60)/ ($sHrsIntoShift[$lID] - $lossBySPS[$lID][$spsMR] - $lossBySPS[$lID][$spsSPD]);
    if ($upTime[$lID]<0) {$upTime[$lID]=0;}
    $sSpdAvg[$lID] = $sQty[$lID]/($sHrsIntoShift[$lID] - $tempVar + $lossBySPS[$lID][$spsSPD] + $idleTime[$lID]/60);
}

// STAVES LINE 1 & Re-Saw Line 1 
$order = [20,40];
foreach ($order as $i) {
    $lID = $i;
    list ($lDown[$lID], $sQty[$lID], $idleTime[$lID], $sDT[$lID], 
            $LFpM_Eff_s1, $LFpM_Run_s1, $rollAvg5_s1, $rollAvg10[$lID], $rollAvg30[$lID], 
            $shift_s1, $shiftHrs_s1 ) = stavesShiftProgress($lID);
    $oID[$lID] = "";
    $tempVar = $lossBySPS[$lID][$spsNS] + $lossBySPS[$lID][$spsMS] + 
        $lossBySPS[$lID][$spsBD] + $lossBySPS[$lID][$spsCO] + $lossBySPS[$lID][$spsSuSd] + 
        $lossBySPS[$lID][$spsPM]; 
//    echo "<br /><br />Line = $lID<br/>Non-MR Loss = $tempVar<br />Idle Time = $idleTime[$lID]<br/>Qty=$sQty[$lID]";
//    echo "<br/>Spd Loss = " . $lossBySPS[$lID][$spsSPD] . "; MR Loss = " . $lossBySPS[$lID][$spsMR];
//    echo "<br/>Hrs Into Shift = $sHrsIntoShift[$lID]";
    $upTime[$lID]=1 - ($tempVar + $idleTime[$lID]/60)/ ($sHrsIntoShift[$lID] - $lossBySPS[$lID][$spsMR] - $lossBySPS[$lID][$spsSPD]);
    if ($upTime[$lID]<0) {$upTime[$lID]=0;}
    $sSpdAvg[$lID] = $sQty[$lID]/($sHrsIntoShift[$lID] - $tempVar + $lossBySPS[$lID][$spsSPD] + $idleTime[$lID]/60);
    $oQty[$lID] = "0";
    $oNeeded[$lID] = "?";
}


//   ****************    GET THRESHOLDS (VISUAL INDICATOR LEVELS)   ****************
//$lines = [1,3,4,5,6,7,8,9,20,30,40,50,60];
$lines = [1,3,4,5,6,7,9,20,30,40,50,51,60,62,70];
foreach ($lines as $l) {
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
$order = [6,1,30,7,3,4,9,5,20,40,50,60,62,51,70];
echo "<div class='dashboard'><table>";
echo "<tr>"
. "<td><div class='dbTitle'>Line</div></td><td><div class='dbTitle'>Shift</div></td>"
. "<td><div class='dbTitle'>Units Produced</div></td><td><div class='dbTitle'>Uptime</div></td>"
. "<td><div class='dbTitle'>Avg Speed</div></td><td><div class='dbTitle'>Speed (10min)</div></td>"
. "<td><div class='dbTitle'>Data Integrity</div><td><div class='dbTitle'>Order Info</div></td></td>"
. "<td><div class='dbTitle'>Need</div></td></tr>";

foreach ($order as $i) {
    echo "{$rollAvg10Per[$i]}< {$spdL1[$i]} <br />";
    
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
    if($i!=8 && $i!=50 && $i != 9 && $i !=51){
		echo "<td><a style='text-decoration:none;' href='viewRuns.php?c=$i'>"
			. "<div class='dbCol colOrder "; if ($s[$i]>0) {echo "colActive";} echo "'>".substr($oID[$i],0,12)."<br/>"
			. $oQty[$i] . ' of ' . $oNeeded[$i] ."</div></a></td>";
    } else {
		echo "<td><a style='text-decoration:none;' href='viewRuns.php?c=$i'>"
        . "<div class='dbCol colOrder "; if ($s[$i]>0) {echo "colActive";} echo "'>".$oID[$i]."<br/>"
        . "? of ?</div></a></td>";
	}
    // ------------ Order Remaining (Need) -------------------
    echo "<td><a style='text-decoration:none;' href='DTEvents.php?c=$i'>"
        . "<div class='dbCol colRemaining "; if ($s[$i]>0) {echo "colActive";}
    if ($i != 20 && $i != 40 && $i != 8 && $i != 50 && $i != 9 && $i !=51) {
        $remaining[$i] = $oNeeded[$i]-$oQty[$i];
        if ($remaining[$i]<=25 and $remaining[$i]>0) {
            echo " okay";
        }
        if ($remaining[$i]<=0 ) {
            echo " good";
        }
    } else {$remaining[$i]="";}
	if ($remaining[$i]=="") {echo "' > </div></a></td>";}
    else {
		if ($remaining[$i]>5000) {
			echo "' >" . Round($remaining[$i]/1000,0) . "K</div></a></td>";
		} else {
			echo "' >" . $remaining[$i] . "</div></a></td>";
		}
	}
	

    
    echo "</tr>";
    */
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

</body>
</html>