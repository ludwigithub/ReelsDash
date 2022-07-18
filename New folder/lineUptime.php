<?php
/*
 * 
Line Uptime Screen
2016.07.30
Nicholas West

 */
$autoRefresh=FALSE;
include 'header.php';
$error = "";
if (!(isset($_GET['c']))) {
    echo "<div class='box b1'>NO Work Cell (Line) passed</div>";
} else {
    $workCell = $_GET['c'];
    if (isset($_GET['wk'])) { 
        if ($_GET['wk']=="l") {
            $week = "DATE_SUB(NOW(),INTERVAL 7 DAY)"; 
            $lastWeek=TRUE;
            $qryWkStart = date_format($prevWkStart, "Y-m-d H:i:s");
            $qryWkEnd = date_format($prevWkEnd, "Y-m-d H:i:s");
        } Else {
            $week = "NOW()";
            $lastWeek=FALSE;
            $qryWkStart = date_format($wkStart, "Y-m-d H:i:s");
            $qryWkEnd = date_format($wkEnd, "Y-m-d H:i:s");
        }
    } else {
        $week = "NOW()";
        $lastWeek=FALSE;
        $qryWkStart = date_format($wkStart, "Y-m-d H:i:s");
        $qryWkEnd = date_format($wkEnd, "Y-m-d H:i:s");
    }
    if (isset($_GET['s'])) { $byShift = TRUE; } else { $byShift = FALSE; }
    include 'LineVariables.php';
// ------------------- Define Array --------------------------
    //$masterTbl[0]=['day'=>"Sunday",'shift'=>0,'dt'=>0,'sHrs'=>0,'ut'=>0,'ms'=>0,'flgs'=>0,'coM'=>0,'coE'=>0];
    $masterTbl[0]=['day'=>"Sunday",'shift'=>0,'dt'=>0,'sHrs'=>0,'ut'=>0,'ms'=>0,'flgs'=>0,'coM'=>0,'coE'=>0];
    $masterTbl[1]=['day'=>"Sunday",'shift'=>1,'dt'=>0,'sHrs'=>0,'ut'=>0,'ms'=>0,'flgs'=>0,'coM'=>0,'coE'=>0];
    $masterTbl[2]=['day'=>"Sunday",'shift'=>2,'dt'=>0,'sHrs'=>0,'ut'=>0,'ms'=>0,'flgs'=>0,'coM'=>0,'coE'=>0];
    $masterTbl[3]=['day'=>"Sunday",'shift'=>3,'dt'=>0,'sHrs'=>0,'ut'=>0,'ms'=>0,'flgs'=>0,'coM'=>0,'coE'=>0];
    $masterTbl[4]=['day'=>"Monday",'shift'=>0,'dt'=>0,'sHrs'=>0,'ut'=>0,'ms'=>0,'flgs'=>0,'coM'=>0,'coE'=>0];
    $masterTbl[5]=['day'=>"Monday",'shift'=>1,'dt'=>0,'sHrs'=>0,'ut'=>0,'ms'=>0,'flgs'=>0,'coM'=>0,'coE'=>0];
    $masterTbl[6]=['day'=>"Monday",'shift'=>2,'dt'=>0,'sHrs'=>0,'ut'=>0,'ms'=>0,'flgs'=>0,'coM'=>0,'coE'=>0];
    $masterTbl[7]=['day'=>"Monday",'shift'=>3,'dt'=>0,'sHrs'=>0,'ut'=>0,'ms'=>0,'flgs'=>0,'coM'=>0,'coE'=>0];
    $masterTbl[8]=['day'=>"Tuesday",'shift'=>0,'dt'=>0,'sHrs'=>0,'ut'=>0,'ms'=>0,'flgs'=>0,'coM'=>0,'coE'=>0];
    $masterTbl[9]=['day'=>"Tuesday",'shift'=>1,'dt'=>0,'sHrs'=>0,'ut'=>0,'ms'=>0,'flgs'=>0,'coM'=>0,'coE'=>0];
    $masterTbl[10]=['day'=>"Tuesday",'shift'=>2,'dt'=>0,'sHrs'=>0,'ut'=>0,'ms'=>0,'flgs'=>0,'coM'=>0,'coE'=>0];
    $masterTbl[11]=['day'=>"Tuesday",'shift'=>3,'dt'=>0,'sHrs'=>0,'ut'=>0,'ms'=>0,'flgs'=>0,'coM'=>0,'coE'=>0];
    $masterTbl[12]=['day'=>"Wednesday",'shift'=>0,'dt'=>0,'sHrs'=>0,'ut'=>0,'ms'=>0,'flgs'=>0,'coM'=>0,'coE'=>0];
    $masterTbl[13]=['day'=>"Wednesday",'shift'=>1,'dt'=>0,'sHrs'=>0,'ut'=>0,'ms'=>0,'flgs'=>0,'coM'=>0,'coE'=>0];
    $masterTbl[14]=['day'=>"Wednesday",'shift'=>2,'dt'=>0,'sHrs'=>0,'ut'=>0,'ms'=>0,'flgs'=>0,'coM'=>0,'coE'=>0];
    $masterTbl[15]=['day'=>"Wednesday",'shift'=>3,'dt'=>0,'sHrs'=>0,'ut'=>0,'ms'=>0,'flgs'=>0,'coM'=>0,'coE'=>0];
    $masterTbl[16]=['day'=>"Thursday",'shift'=>0,'dt'=>0,'sHrs'=>0,'ut'=>0,'ms'=>0,'flgs'=>0,'coM'=>0,'coE'=>0];
    $masterTbl[17]=['day'=>"Thursday",'shift'=>1,'dt'=>0,'sHrs'=>0,'ut'=>0,'ms'=>0,'flgs'=>0,'coM'=>0,'coE'=>0];
    $masterTbl[18]=['day'=>"Thursday",'shift'=>2,'dt'=>0,'sHrs'=>0,'ut'=>0,'ms'=>0,'flgs'=>0,'coM'=>0,'coE'=>0];
    $masterTbl[19]=['day'=>"Thursday",'shift'=>3,'dt'=>0,'sHrs'=>0,'ut'=>0,'ms'=>0,'flgs'=>0,'coM'=>0,'coE'=>0];
    $masterTbl[20]=['day'=>"Friday",'shift'=>0,'dt'=>0,'sHrs'=>0,'ut'=>0,'ms'=>0,'flgs'=>0,'coM'=>0,'coE'=>0];
    $masterTbl[21]=['day'=>"Friday",'shift'=>1,'dt'=>0,'sHrs'=>0,'ut'=>0,'ms'=>0,'flgs'=>0,'coM'=>0,'coE'=>0];
    $masterTbl[22]=['day'=>"Friday",'shift'=>2,'dt'=>0,'sHrs'=>0,'ut'=>0,'ms'=>0,'flgs'=>0,'coM'=>0,'coE'=>0];
    $masterTbl[23]=['day'=>"Friday",'shift'=>3,'dt'=>0,'sHrs'=>0,'ut'=>0,'ms'=>0,'flgs'=>0,'coM'=>0,'coE'=>0];
    $masterTbl[24]=['day'=>"Saturday",'shift'=>0,'dt'=>0,'sHrs'=>0,'ut'=>0,'ms'=>0,'flgs'=>0,'coM'=>0,'coE'=>0];
    $masterTbl[25]=['day'=>"Saturday",'shift'=>1,'dt'=>0,'sHrs'=>0,'ut'=>0,'ms'=>0,'flgs'=>0,'coM'=>0,'coE'=>0];
    $masterTbl[26]=['day'=>"Saturday",'shift'=>2,'dt'=>0,'sHrs'=>0,'ut'=>0,'ms'=>0,'flgs'=>0,'coM'=>0,'coE'=>0];
    $masterTbl[27]=['day'=>"Saturday",'shift'=>3,'dt'=>0,'sHrs'=>0,'ut'=>0,'ms'=>0,'flgs'=>0,'coM'=>0,'coE'=>0];
    
//----------------DRAW HEADERS--------------
    echo '<div class="box b1'; if (!$lastWeek) {echo ' selected';} 
    echo '" onClick="location.href=' . "'lineUptime.php?c=" 
            . $workCell . "'". '">Current Week</div>';    
    echo '<div class="box b1 posB2boxb7'; if ($byShift) {echo ' selected';} 
    echo '" onClick="location.href=' . "'lineUptime.php?c=" 
            . $workCell; if (!$byShift) {echo "&s=y";} if ($lastWeek) {echo "&wk=l";} 
    echo "'" . '">By Shift</div>';
    echo '<div class="box b1 posB3boxb7'; if ($lastWeek) {echo ' selected';} 
    echo '" onClick="location.href=' . "'lineUptime.php?c=" 
            . $workCell; if ($byShift) {echo "&s=y";} echo "&wk=l'". '">Last Week</div>';

// ------------------- GET Total Downtime and Run time for each shift --------------------------
    if ($sOverLapDay == 0) {
        $qryOverLapDayAdj = "IF(shift = 0 and Hour(start) > 22,DATE_ADD(start,interval 5 hour),start)";
    }
    if ($sOverLapDay == 2) {
        $qryOverLapDayAdj = "IF(shift = 2 and Hour(start) < 4, DATE_SUB(start,interval 5 hour),start)";
    }
    if ($sOverLapDay == 3) {
        $qryOverLapDayAdj = "IF(shift = 3 and Hour(start) >= 22, DATE_ADD(start,interval 3 hour),start)";
    }
    $query = "SELECT DATE_format($qryOverLapDayAdj, '%W') AS 'Day', shift, 
	SUM(TIMESTAMPDIFF(SECOND, start, end))/60/60 AS 'DT_Hrs', If(shift=1,$s1LenHrs,IF(shift=2,$s2LenHrs,$s3LenHrs)) AS 'Running hrs'
        FROM $eventsTable
        WHERE sps_code <> 7 AND start >= '$qryWkStart' and start < '$qryWkEnd'  
        GROUP BY DATE($qryOverLapDayAdj), shift";

    $result = queryMysql($query);
    if($result) { $num = mysqli_num_rows($result); }
    if ($num==0) { 
        $error .=  "<span class='error'>No loss events found"
                . "</span><br /><br />";
    } else {
        for ($i=0; $i<$num; $i++) {
            $row = mysqli_fetch_row($result);
            for ($test=0;$test<=27;$test++) {
                if ($masterTbl[$test]['day']==$row[0] & $masterTbl[$test]['shift']==$row[1]) {
                    $masterTbl[$test]['dt']=$row[2];
                    $masterTbl[$test]['sHrs']=$row[3];
                }
            }
        }
    }
    // ------------------- Correct Runtime by removing MR DT ----------------------------------
    $query = "SELECT DATE_format($qryOverLapDayAdj, '%W') AS 'Day', shift, 
	SUM(TIMESTAMPDIFF(SECOND, start, end))/60/60 AS 'DT_Hrs', If(shift=1,$s1LenHrs,IF(shift=2,$s2LenHrs,$s3LenHrs)) AS 'Running hrs'
        FROM $eventsTable
        WHERE sps_code = 7 AND start >= '$qryWkStart' and start < '$qryWkEnd'  
        GROUP BY DATE($qryOverLapDayAdj), shift";
    $result = queryMysql($query);
    if($result) { $num = mysqli_num_rows($result); }
    if ($num==0) { 
        $error .=  "<span class='error'>No MR events found"
                . "</span><br /><br />";
    } else {
        for ($i=0; $i<$num; $i++) {
            $row = mysqli_fetch_row($result);
            for ($test=0;$test<=27;$test++) {
                if ($masterTbl[$test]['day']==$row[0] & $masterTbl[$test]['shift']==$row[1] 
                        & ($masterTbl[$test]['sHrs'] - $row[2]) > 0 & 
                        $masterTbl[$test]['dt'] <= ($masterTbl[$test]['sHrs'] - $row[2])) {
                    $masterTbl[$test]['sHrs']-=$row[2];
                }
            }
        }
    }

// ------------------- GET Minor Stop Summary for each shift --------------------------
    $query = "SELECT DATE_format($qryOverLapDayAdj, '%W') AS 'Day', shift, 
	SUM(TIMESTAMPDIFF(SECOND, start, end))/60/60 AS 'DT_Hrs'
        FROM $eventsTable
        WHERE sps_code = 1 AND start >= '$qryWkStart' and start < '$qryWkEnd'  
        GROUP BY DATE($qryOverLapDayAdj), shift";

    $result = queryMysql($query);
    if($result) { $num = mysqli_num_rows($result); }
    if ($num==0) { 
        $error .=  "<span class='error'>No loss events found"
                . "</span><br /><br />";
    } else {
        for ($i=0; $i<$num; $i++) {
            $row = mysqli_fetch_row($result);
            for ($test=0;$test<=27;$test++) {
                if ($masterTbl[$test]['day']==$row[0] & $masterTbl[$test]['shift']==$row[1]) {
                    $masterTbl[$test]['ms']=$row[2];
                }
            }
        }
    }
// ------------------- GET Change Over Summary for each shift --------------------------
    $query = "SELECT DATE_format($qryOverLapDayAdj, '%W') AS 'Day', shift, 
	SUM(TIMESTAMPDIFF(SECOND, start, end))/60 AS 'CO_Min', 
        COUNT($EventsId) AS CO_Events 
        FROM $eventsTable 
        WHERE sps_code = 3 AND start >= '$qryWkStart' and start < '$qryWkEnd'  
        GROUP BY DATE($qryOverLapDayAdj), shift";
    $result = queryMysql($query);
    if($result) { $num = mysqli_num_rows($result); }
    if ($num==0) { 
        $error .=  "<span class='error'>No loss events found"
                . "</span><br /><br />";
    } else {
        for ($i=0; $i<$num; $i++) {
            $row = mysqli_fetch_row($result);
            for ($test=0;$test<=27;$test++) {
                if ($masterTbl[$test]['day']==$row[0] & $masterTbl[$test]['shift']==$row[1]) {
                    $masterTbl[$test]['coM']=$row[2];
                    $masterTbl[$test]['coE']=$row[3];
                }
            }
        }
    }
// ------------------- GET Total Downtime and Run time for each shift --------------------------
    if ($sOverLapDay == 0) {
        $qryOverLapDayAdj = "IF(p1.shift = 0 and Hour(p1.$ts) > 22,DATE_ADD(p1.$ts,interval 5 hour),p1.$ts)";
    }
    if ($sOverLapDay == 2) {
        $qryOverLapDayAdj = "IF(p1.shift = 2 and Hour(p1.$ts) < 4, DATE_SUB(p1.$ts,interval 5 hour),p1.$ts)";
    }
    if ($sOverLapDay == 3) {
        $qryOverLapDayAdj = "IF(p1.shift = 3 and Hour(p1.$ts) >= 22, DATE_ADD(p1.$ts,interval 3 hour),p1.$ts)";
    }
    If ($lineType == "nailer" || $lineType == "final" || $lineType == "layup") {
    // ------------------- GET Flange Count for each shift --------------------------
       $query = "SELECT DATE_FORMAT($qryOverLapDayAdj,'%W') AS 'Day', "
                    . "COUNT(p1.$productionId) AS 'Flanges', p1.Shift, " 
                    . "MIN(p1.$ts) AS 'First Flange', MAX(p1.$ts) AS 'Last Flange' "
                . "FROM $productionTable AS p1 "
                . "INNER JOIN $productionTable AS p2 ON p2.$productionId = p1.$productionId-1 "
                . "WHERE  WEEK($qryOverLapDayAdj)=WEEK($week) AND "
                    . "p1.$ts >= '$qryWkStart' AND p1.$ts < '$qryWkEnd' AND "
                    . "p1.$productionQty>0 AND p1.$productionQty>p2.$productionQty "
                . "GROUP BY dayofweek($qryOverLapDayAdj), p1.Shift";
    }
    If ($lineType =="staves") {
    // ------------------- GET Linear Footage Count for each shift --------------------------
       $query = "SELECT DATE_FORMAT($qryOverLapDayAdj,'%W') AS 'Day', "
            . "SUM(LF), Shift FROM indusoft.$productionTable As p1 "
            . "WHERE p1.$ts >= '$qryWkStart' AND p1.$ts < '$qryWkEnd' "
            . "GROUP BY dayofweek($qryOverLapDayAdj), Shift";   
    }
    $result = queryMysql($query);
    if($result) { $num = mysqli_num_rows($result); }
    if ($num==0) { 
        $error .=  "<span class='error'>No flanges found."
                . "</span><br /><br />";
    } else {
        for ($i=0; $i<$num; $i++) {
            $row = mysqli_fetch_row($result);
            for ($test=0;$test<=27;$test++) {
                if ($masterTbl[$test]['day']==$row[0] & $masterTbl[$test]['shift']==$row[2]) {
                    $masterTbl[$test]['flgs']=$row[1];
                }
            }
        }
    }
//------------------------------------------------------
//----------------------DRAW PAGE-----------------------
//------------------------------------------------------
    echo "<div class='box b7'>" . $title ." - Weekly Summary<br /><table class='info'>"
            . "<col width='120'><col width='95'><col width='95'><col width='95'>"
            . "<col width='95'><col width='95'><col width='95'><tr>"
            . "<td>Day</td><td>"; if ($byShift) { echo "Shift"; } 
            echo "</td><td>DT<br />(hrs)</td><td>MS<br />(hrs)</td><td>Mins<br />per CO</td>"
            . "<td>";
    If ($lineType == "nailer" || $lineType == "final" || $lineType == "layup") { echo "Flngs"; }
    If ($lineType == "staves") { echo "LF";}
    echo "</td><td>Up<br />Time</td><tr>"
            . "<tr><td>------</td><td>"; if ($byShift) { echo "------"; } 
            echo "</td><td>------</td><td>------</td><td>------</td>"
            . "<td>------</td><td>------</td></tr>"; 
    $dtHrs = 0;
    $sHrs = 0;
    $flanges = 0;
    $msHrs = 0;
    $coMins = 0;
    $coEvents = 0;
//--------GROUP BY DAY AND SHIFT -------------
    if ($byShift) {
        for ($k=0; $k<=27; $k++) {
            if (($masterTbl[$k]['sHrs']>0 & $masterTbl[$k]['sHrs']> $masterTbl[$k]['dt']) | $masterTbl[$k]['flgs']>0) {
                $dtHrs += $masterTbl[$k]['dt'];
                $sHrs += $masterTbl[$k]['sHrs'];
                $flanges += $masterTbl[$k]['flgs'];
                $msHrs += $masterTbl[$k]['ms'];
                $coMins += $masterTbl[$k]['coM'];
                $coEvents += $masterTbl[$k]['coE'];
                echo "<tr><td>" . $masterTbl[$k]['day']
                    . "</td><td>" . $masterTbl[$k]['shift']
                    . "</td><td>" . number_format($masterTbl[$k]['dt'],1) 
                    . "</td><td>" . number_format($masterTbl[$k]['ms'],1)
                    . "</td><td>"; 
                if ($masterTbl[$k]['coE']>0) { 
                    echo number_format($masterTbl[$k]['coM']/$masterTbl[$k]['coE'],0);
                } else { echo 0; }
                echo '</td><td>' . number_format($masterTbl[$k]['flgs'],0) . "</td><td>";
                If ($masterTbl[$k]['dt']>=$masterTbl[$k]['sHrs']) {
                    echo 0;
                } else { 
                    if (!$lastWeek & date("l")==$masterTbl[$k]['day'] & $shift == $masterTbl[$k]['shift']) { echo ""; }
                    else { echo number_format(($masterTbl[$k]['sHrs']-$masterTbl[$k]['dt'])/$masterTbl[$k]['sHrs']*100,1); }
                    echo "%";
                }
                echo "</td></tr>";       
            }
        }
    } else { //--------GROUP BY DAY (not shift) -------------
        for ($k=0; $k<=6; $k++) {
            $dayDT = 0;
            $dayHrs = 0;
            $dayFlngs = 0;
            $dayMS = 0;
            $dayCOMins = 0;
            $dayCOEvents = 0;
            for ($s=0; $s<=3; $s++) {
                if (($masterTbl[$k*4+$s]['sHrs']>0 & $masterTbl[$k*4+$s]['sHrs']> $masterTbl[$k*4+$s]['dt']) | $masterTbl[$k*4+$s]['flgs']>0) {
                    $dayDT += $masterTbl[$k*4+$s]['dt'];
                    $dayHrs += $masterTbl[$k*4+$s]['sHrs'];
                    $dayFlngs += $masterTbl[$k*4+$s]['flgs'];
                    $dayMS += $masterTbl[$k*4+$s]['ms'];
                    $dayCOMins += $masterTbl[$k*4+$s]['coM'];
                    $dayCOEvents += $masterTbl[$k*4+$s]['coE'];
                }
            }
            if (($dayHrs>0 & $dayHrs > $dayDT) | $dayFlngs > 0 ) {
                    $dtHrs += $dayDT;
                    $sHrs += $dayHrs;
                    $flanges += $dayFlngs;
                    $msHrs += $dayMS;
                    $coMins += $dayCOMins;
                    $coEvents += $dayCOEvents;
                echo "<tr><td>" . $masterTbl[$k*4]['day']
                    . "</td><td>" //. $masterTbl[$k*2]['shift']
                    . "</td><td>" . number_format($dayDT,1) 
                    . "</td><td>" . number_format($dayMS,1)
                    . "</td><td>"; 
                if ($dayCOEvents>0) { echo number_format($dayCOMins/$dayCOEvents,0);}
                else {echo 0;}
                    echo '</td><td>' . number_format($dayFlngs,0) . "</td><td>";
                If ($dayDT>=$dayHrs) {
                    echo 0;
                } else { 
                    echo number_format(($dayHrs-$dayDT)/$dayHrs*100,1);
                    echo "%";
                }
                echo "</td></tr>";       
            }
        }
    }
//--------------SHOW WEEKLY SUMMARY ON THE BOTTOM---------------------
    echo "<tr><td>------</td><td>"; if ($byShift) { echo "------"; } echo "</td>"
        . "<td>------</td><td>------</td><td>------</td><td>------</td>"
        . "<td>------</td></tr>"
        . "<tr><td>Summary</td><td></td><td>" . number_format($dtHrs,1) 
        . "</td><td>" . number_format($msHrs,1) 
        . "</td><td>";
    if ($coEvents>0) { echo number_format($coMins/$coEvents,0); }
    else {echo 0;}
    echo "</td><td>" . number_format($flanges,0) . "</td><td>" 
        . number_format(($sHrs - $dtHrs)/$sHrs*100,1) . "%</td></tr>";
            
    echo "</table>";
    if (!$lastWeek) {echo "NOTE: Current shift Uptime not calculated.";}
    echo "<br />"
        . "</div>";
    echo "<a href='reports/opReport.php?c=$workCell'><div class='box b1 report'>Reports</div></a>";

}
mysqli_close($myLink);    

?>
    </body>
</html>
