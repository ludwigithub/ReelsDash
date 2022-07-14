<?php
include("config.php"); 


	$query = $conn->query("select * FROM dashinfo WHERE orderIndex IN (6, 3, 4, 9, 1, 30, 7, 20, 5, 40, 50, 60, 62) ORDER BY lineOrder");
	$index= 0;
	while($row = $query->fetch_assoc()){
		//-------------------Define Condition --------------------------------
		$normalL1uptime = 50/100;
		$targetUptime = 60/100;
		$normalSpeed = [120, 130, 225, 90, 80, 160,200,13000,140,5000,65,720,720 ];
		$targetSpeed = 70;
		$order = [6, 1, 30, 7, 3, 4, 9, 5, 20, 40, 50, 60, 62 ];


		$uptime = $row['upTime'];
		$speedAvg = $row['SpeedAvg'];
		$rollAvg = $row['RollAvg'];
		$orderInfoTop = $row['OrderInfoTop'];
		$orderInfoBottom = $row['OrderInfoBottom'];
		$downTime = $row['downTime'];
		$LDown = $row['LDown'];
		$units = $row['Units'];
		$currentShift = $row['CurrentShift'];
		$lineLabels = $row['lineLabels'];
		$remaining = $row['qtyNeeded'] - $row['completedFlanges'];
		$unpaidMins = $row['unpaidShiftMins'];
		$lossBy0 = $row['lossBySPS0'];
		$lossBy7 = $row['lossBySPS7'];
		$dataIntegrity0 = round($row['DataIntegrity0'], 1);

		//-----------------DETERMINE THE COLOR----------------------------------------
		$uptime < 0 ? $uptime = 100 : $uptime = $uptime;
		$downTime == 0 ? $nameColor = " good " : $nameColor  = " bad ";
		$LDown != 0 ? $activeColor = " colActive " : $activeColor = " ";
		$remaining <= 25 && $remaining >0? $remainingColor = " okay" : ($remaining <= 0 ? $remainingColor = " good " : $remainingColor = "");
		$uptime >= $normalL1uptime && $uptime < $targetUptime ? $uptimeColor = " okay" : ($uptime >= $normalL1uptime ? $uptimeColor = " good" : $uptimeColor = " bad");
		$rollAvg < 0.7 *$normalSpeed[$index] && $rollAvg >= 50 ? $rollColor = " okay" : ($rollAvg >=  0.7 *$normalSpeed[$index] ? $rollColor = " good" : $rollColor = " bad");
		$speedAvg < $normalSpeed[$index] * 0.7 && $speedAvg >= $normalSpeed[$index]*50 ? $avgSpeedColor = " okay" : ($speedAvg >= $normalSpeed[$index] * 0.7 ? $avgSpeedColor = " good" : $avgSpeedColor = " bad"); //"
		$lossBy0 <= 0.25 && $lossBy7 <=$unpaidMins/60*1.1 ? $dataColor = " OK '>": ($lossBy0 <= 0.25 && $lossBy7 > $unpaidMins/60*1.1 ? $dataColor = " colDataIssue " && $dataIntegrity0 = "'>  <font size='6'>Market Related:<br>" . number_format($lossBy7,2) . " min</font>": $dataColor = " colDataIssue " && $dataIntegrity0 = "'> <font size='6'>Not Specified:<br>". number_format($lossBy0,2) ." min</font>");//>"

	echo "<tr> " .
					//-----Line Name-------. else{. " good " . } 
					"<td><div class = '$activeColor $nameColor dbCol colName'>$lineLabels </div><div class = '$nameColor postDowntime'>" . $downTime*60 ."mins</div></td> " .

					//-----Current Shift-------
					"<td><div class=' $activeColor dbCol colShift'> $currentShift </div></td> " .

					//-------Units------------
					"<td><div class='$activeColor dbCol colUnits'> " . $units  . "</div></td>" .

					//-------upTime------------
					"<td><div class='$activeColor $uptimeColor dbCol colUptime' >" . round($uptime, 1) . "</div></td>" .

					//-------SpeedAvg------------
					"<td><div class='$activeColor $avgSpeedColor colSpd'>" . number_format($speedAvg,1) ." </div></td> ".

					//-------RollAvg------------
					"<td><div class='$activeColor $rollColor dbCol colSpd'>$rollAvg</div></td> " .
					//-------Data Integrity------------
					"<td><div class='$activeColor dbCol colData $dataColor" . $dataIntegrity0 . "</div></td> ".

					//-------ORder Info------------
					"<td><div class='$activeColor colOrder'> " .$orderInfoTop  . "<br/>" . $orderInfoBottom . "</div></td> " .

					//-------Needed------------
					"<td> <div class='$activeColor $remainingColor dbCol colRemaining'>" . $remaining ."</div></td>
			</tr>";

	}
		$index +=1;
			?>
}
?>
