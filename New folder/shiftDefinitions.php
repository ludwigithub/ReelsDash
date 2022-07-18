<?php
/*
 * shiftDefinitions.php
 * Retrieve shift schedule from databse and define shift parameters for each line.
 * Written By: Nicholas West
 * Date: 9/19/2018
 * 
 */


//Temp for development
//include 'functions.php';

//-------------  Retrieve Shift Schedule -------------
$q = "SELECT idLine, s1Start, s1End, s2Start, s2End, s3Start,s3End, unpaidShiftMins FROM shiftschedule";
$result = queryMysql($q);
if($result) { $num = mysqli_num_rows($result); }
if ($num==0) { 
    $error =    "<span class='error'>ERROR Connecting to database. (shiftSchedule)</span><br /><br />";
} else {
    for ($i=0; $i<$num; $i++) {
        $row = mysqli_fetch_row($result);
        $sSchedule[$row[0]][1]['start']=$row[1];
        $sScheduleSecs[$row[0]][1]['startSecs']=secsAfterMidnight($row[1]);
        $sSchedule[$row[0]][1]['end']=$row[2];
        $sScheduleSecs[$row[0]][1]['endSecs']=secsAfterMidnight($row[2]);
        $sSchedule[$row[0]][2]['start']=$row[3];
        $sScheduleSecs[$row[0]][2]['startSecs']=secsAfterMidnight($row[3]);
        $sSchedule[$row[0]][2]['end']=$row[4];
        $sScheduleSecs[$row[0]][2]['endSecs']=secsAfterMidnight($row[4]);
        $sSchedule[$row[0]][3]['start']=$row[5];
        $sScheduleSecs[$row[0]][3]['startSecs']=secsAfterMidnight($row[5]);
        $sSchedule[$row[0]][3]['end']=$row[6];
        $sScheduleSecs[$row[0]][3]['endSecs']=secsAfterMidnight($row[6]);
        $sSchedule[$row[0]]['unpaidShiftMins']=$row[7];
    }
}

// **************  WORKING ON SHIFT LENGTH ********* UNPAID shift mins has to be subtracted from each shift that has a shift length>0 to accurately get shift 0 len...Or removed in calculation when it i sued.
// -------- Calculate shift length for each shift -------
foreach ($sScheduleSecs as $lID => $sSched) {
    for ($i=1; $i<=3; $i++) {
        if ($sSched[$i]['endSecs']>=$sSched[$i]['startSecs']) {
            $sLen[$lID][$i] = ($sSched[$i]['endSecs'] - $sSched[$i]['startSecs'])/60/60 - $sSchedule[$lID]['unpaidShiftMins']/60;
        } else {
            $sLen[$lID][$i] = (24*60*60 - $sSched[$i]['startSecs'] + $sSched[$i]['endSecs'])/60/60 - $sSchedule[$lID]['unpaidShiftMins']/60;
        }
        if ($sLen[$lID][$i]<0) {$sLen[$lID][$i]=0;}
    }
    $sLen[$lID][0] = 24 - $sLen[$lID][1] - $sLen[$lID][2] - $sLen[$lID][3];
    if ($sLen[$lID][0]>24 OR $sLen[$lID][0]<0) { $sLen[$lID][0]=0; }
    $sLen[$lID][4]=24;
}
/*
echo "<table>";
echo "<tr><td>LineID</td><td>S1 Start</td><td>S1 End</td><td>S2 Start</td><td>S2 End</td><td>S3 Start</td><td>S3 End</td><td> Unpaid Shift Mins </td></tr>";
foreach ($sSchedule as $key => $lShift) {
    echo "<tr><td>$key</td><td>" . $lShift[1]['start'] . "</td><td>" . $lShift[1]['end'] . "</td><td>" . $lShift[2]['start'] 
            . "</td><td>" . $lShift[2]['end'] . "</td><td>" . $lShift[3]['start'] . "</td><td>" . $lShift[3]['end'] 
            . "</td><td>" . $lShift['unpaidShiftMins'] . "</td></tr>";
}
echo "</table>";
*/

//-------------  Get Current Shift -------------
$q = "SELECT idLine, IF(s1End>s1Start,If(TIME(NOW())>=s1Start and TIME(NOW())<s1End,1,0),IF(TIME(NOW())<s1End OR TIME(NOW())>=s1Start,1,0) ) +
    IF(s2End>s2Start,If(TIME(NOW())>=s2Start and TIME(NOW())<s2End,2,0),IF(TIME(NOW())<s2End OR TIME(NOW())>=s2Start,2,0) ) +
    IF(s3End>s3Start,If(TIME(NOW())>=s3Start and TIME(NOW())<s3End,3,0),IF(TIME(NOW())<s3End OR TIME(NOW())>=s3Start,3,0) ) As CurrentShift FROM shiftschedule";
$result = queryMysql($q);
if($result) { $num = mysqli_num_rows($result); }
if ($num==0) { 
    $error =    "<span class='error'>ERROR Connecting to database. (shiftSchedule)</span><br /><br />";
} else {
    for ($i=0; $i<$num; $i++) {
        $row = mysqli_fetch_row($result);
        $s[$row[0]]=$row[1];
    }
}
/*
echo "<table>";
echo "<tr><td>LineID</td><td>Current Shift</td></tr>";
foreach ($s as $key => $value) {
    echo "<tr><td>$key</td><td>$value</td></tr>";
}
echo "</table>";
*/
//-------------  Get Current Shift -------------
/*echo "<table>";
echo "<tr><td>LineID</td><td>Shfit Start Time</td><td>Shfit End Time</td></tr>";
foreach ($sSchedule as $key => $value) {
    if ($s[$key]<>0) { echo "<tr><td>$key</td><td>" . $value[$s[$key]]['start'] . "</td><td>" . $value[$s[$key]]['end'] . "</td></tr>"; }
}
echo "</table>";
*/
// ------  DEFINE WEEK OF RANGE and LAST WEEK OF RANGE --------
$tempDate = new DateTime("now");
$date = $tempDate->format('Y-m-d');
$tempOffset = $tempDate->getOffset();
// ---- get current date at midnight in DateTimeInterface format ---
$wkOf = new DateTime($date . " 00:00:00");
$dayOfWk = gmdate("w", time() + $tempOffset);
$wkOf->sub(new DateInterval("P" . $dayOfWk . "D"));


// --- Get Current date and time in formats that can be used later on
$lt=getdate();
// - create current date and time string --
$timeStr = $lt['hours'] . ":" . $lt['minutes'] . ':' . $lt['seconds'];
$DTstr = $lt['year'] . '-' . $lt['mon'] . '-' . 
        $lt['mday'] . ' ' . $timeStr;
// ---- get current date and time in DateTimeInterface format ---
$yd = $currentDTI = new DateTime($DTstr);
// Subtract 1 day to turn yd into a DateTimeInterface for yesterday
$yd->sub(new DateInterval('P1D'));

/*
echo "<table>";
echo "<tr><td>Line ID</td><td>Current time (secs from Midnight)</td><td>Shift Start (secs from midnight)</td></tr>";
 */
$timeSecondsAfterMidnight = secsAfterMidnight($timeStr);
foreach ($sSchedule as $lID => $sSched) {
    //  ----  If shift > 0 then shift start and end are defined for the given shift.
    if ($s[$lID]>0) {
        $sStartSecAMid[$lID] = $sScheduleSecs[$lID][$s[$lID]]['startSecs'];
        $sEndSecAMid[$lID] = $sScheduleSecs[$lID][$s[$lID]]['endSecs'];
//        echo "<tr><td>$lID</td><td>$timeSecondsAfterMidnight</td><td>" . $sStartSecAMid[$lID] . "</td></tr>";
        //Check to see if the current time has rolled over into the morning.
        if ($timeSecondsAfterMidnight>=$sStartSecAMid[$lID]){
            $sStart[$lID] = $lt['year'] . '-' . $lt['mon'] . '-' . $lt['mday'] . " " . $sSched[$s[$lID]]['start'];
            $sHrsIntoShift[$lID] = ($timeSecondsAfterMidnight - $sStartSecAMid[$lID])/60/60;
        } else {
            //$sStart[$lID] = $yd['year'] . '-' . $yd['mon'] . '-' . $yd['mday'] . " " . $sSched[$s[$lID]]['start'];
            $sStart[$lID] = $yd->format('Y-m-d') . " " . $sSched[$s[$lID]]['start'];
            $sHrsIntoShift[$lID] = (24*60*60 - $sStartSecAMid[$lID] + $timeSecondsAfterMidnight)/60/60;
        }
    } else { // if shift = 0 the following computations are used to determine when the last and next shift will be to determine how long shift 0 will be active.
        for ($i=1; $i<=3; $i++) {
            $timeSinceShift[$i] = 24*60*60;
            $timeNextShift[$i] = 24*60*60;
            if ($sScheduleSecs[$lID][$i]['endSecs']>0 ) {
                if ($sScheduleSecs[$lID][$i]['endSecs']<$timeSecondsAfterMidnight) {
                    $timeSinceShift[$i] = $timeSecondsAfterMidnight - $sScheduleSecs[$lID][$i]['endSecs'];
                } else {
                    $timeSinceShift[$i] = 24*60*60 - $sScheduleSecs[$lID][$i]['endSecs'] + $timeSecondsAfterMidnight;
                }
            }
            if ($sScheduleSecs[$lID][$i]['startSecs']>0) {
                if ($timeSecondsAfterMidnight<=$sScheduleSecs[$lID][$i]['startSecs']) {
                    $timeNextShift[$i] = $sScheduleSecs[$lID][$i]['startSecs'] - $timeSecondsAfterMidnight;
                } else {
                    $timeNextShift[$i] = 24*60*60 - $timeSecondsAfterMidnight + $sScheduleSecs[$lID][$i]['startSecs'];
                }
            }
        }
        $sLast = array_keys($timeSinceShift, min($timeSinceShift));
        $sNext = array_keys($timeNextShift, min($timeNextShift));
//        echo "<tr><td>$lID</td><td>Last Shift = $sLast[0]</td><td>Next Shift = $sNext[0]</tr>";
        $sStartSecAMid[$lID] = $sScheduleSecs[$lID][$sLast[0]]['endSecs'];
        $sEndSecAMid[$lID] = $sScheduleSecs[$lID][$sNext[0]]['startSecs'];
//        echo "<tr><td>$lID</td><td>$timeSecondsAfterMidnight</td><td>" . $sStartSecAMid[$lID] . "</td></tr>";
        if ($timeSecondsAfterMidnight>=$sStartSecAMid[$lID]){
            $sStart[$lID] = $lt['year'] . '-' . $lt['mon'] . '-' . $lt['mday'] . " " . $sSched[$sLast[0]]['end'];
            $sHrsIntoShift[$lID] = ($timeSecondsAfterMidnight - $sStartSecAMid[$lID])/60/60;
        } else {
            $sStart[$lID] = $yd->format('Y-m-d') . " " . $sSched[$sLast[0]]['end'];
            //$sStart[$lID] = $yd['year'] . '-' . $yd['mon'] . '-' . $yd['mday'] . " " . $sSched[$sLast[0]]['end'];
            $sHrsIntoShift[$lID] = (24*60*60 - $sStartSecAMid[$lID] + $timeSecondsAfterMidnight)/60/60;
        }
    }
    
    //-------------  Define array of shift overlapping midnight -------------
    $sOverlappingMid[$lID]=0;
    for ($i=1; $i<=3; $i++) {
        if ($sScheduleSecs[$lID][$i]['startSecs']>$sScheduleSecs[$lID][$i]['endSecs']) {
            $sOverlappingMid[$lID] = $i;
            $sOverlapping_ShiftEnd[$lID] = $sSchedule[$lID][$i]['end'];
            $sOverlapping_ShiftStart[$lID] = $sSchedule[$lID][$i]['start'];
        }
    }
    //  --- Define WeekOf SQL Tags for each line ---
    If ($sOverlappingMid[$lID]==0) {
        $wkOfSQL[$lID] = $wkOf->format('Y-m-d')." 00:00:00";
        $tempDate = new DateTime($wkOf->format('Y-m-d') . " 00:00:00");
        $tempDate->add(new DateInterval("P7D"));
        $wkEndSQL[$lID] = $tempDate->format('Y-m-d') . " 00:00:00";
        $tempDate->sub(new DateInterval("P14D"));
        $lastWkOfSQL[$lID] =  $tempDate->format('Y-m-d')." 00:00:00";
    } 
    If ($sOverlappingMid[$lID]>=1 && $sOverlappingMid[$lID]<=2) {
        $tempDate = new DateTime($wkOf->format('Y-m-d') . " 00:00:00");
        $wkOfSQL[$lID] = $wkOf->format('Y-m-d')." ".$sOverlapping_ShiftEnd[$lID];
        $tempDate->add(new DateInterval("P7D"));
        $wkEndSQL[$lID] = $tempDate->format('Y-m-d')." ".$sOverlapping_ShiftEnd[$lID];
        $tempDate->sub(new DateInterval("P14D"));
        $lastWkOfSQL[$lID] =  $tempDate->format('Y-m-d')." ".$sOverlapping_ShiftEnd[$lID];        
    }
    If ($sOverlappingMid[$lID]==3) {
        $tempDate = new DateTime($wkOf->format('Y-m-d') . " 00:00:00");
        $tempDate->sub(new DateInterval("P1D"));
        $wkOfSQL[$lID] = $tempDate->format('Y-m-d')." ".$sOverlapping_ShiftStart[$lID];
        $tempDate->add(new DateInterval("P7D"));
        $wkEndSQL[$lID] = $tempDate->format('Y-m-d')." ".$sOverlapping_ShiftStart[$lID];
        $tempDate->sub(new DateInterval("P14D"));
        $lastWkOfSQL[$lID] = $tempDate->format('Y-m-d')." ".$sOverlapping_ShiftStart[$lID];
    }
 //   echo "Line=$lID";
 //   echo "<br/>PrevousWk: Start = $lastWkOfSQL[$lID]; End = $wkOfSQL[$lID]<br/>";
 //   echo "CurrentWk: Start = $wkOfSQL[$lID]; End = $wkEndSQL[$lID]<br/><br/>";
}
// echo "</table>";


/*
echo "<table>";
echo "<tr><td> Line ID </td><td> Shift Start </td><td> Hrs Into Shift </td><td>Shift</td><td> Shift Length </td></tr>";
foreach ($sStart as $key => $value) {
    for ($i=0;$i<=4;$i++) {
        echo "<tr><td>$key</td><td>$value</td><td>$sHrsIntoShift[$key]</td><td>$i</td><td>".$sLen[$key][$i]."</td></tr>";
    }
}
echo "</table>";
*/
//-------------  Define shift arrays -------------
//
//shiftStart

?>
