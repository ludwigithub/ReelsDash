<?php
include("config.php"); 


	$query = $conn->query("select * FROM dashinfo WHERE orderIndex IN (6, 3, 4, 9, 1, 30, 7, 20, 5, 40, 50, 60, 62, 51, 70)");
	$index= 0;
	Date_default_timezone_set("America/Chicago");
		
	while($row = $query->fetch_assoc()){
		//-------------------Define Condition --------------------------------
		$normalL1uptime = 50/100;
		$targetUptime = 60/100;
		$normalSpeed = [120, 130, 225, 90, 80, 160,200,13000,140,5000,65,720,720 ];
		$targetSpeed = 70;
		//$order = [6, 1, 30, 7, 3, 4, 9, 5, 20, 40, 50, 60, 62];
		$order = $row['orderIndex'];
		$hrsIntoShift = $row['hrsIntoShift'];
		$speedAvg = $row['SpeedAvg'];
		$rollAvg = $row['RollAvg'];
		$orderInfoTop_DIAM = $row['OrderInfoTop_DIAM'];
		$orderInfoTop_THICK = $row['OrderInfoTop_THICK'];
		$orderInfoBottom = $row['OrderInfoBottom'];
		$downTime = $row['downTime'];
		$totalDownTime = $row['totalDownTime'];
		$LDown = $row['LDown'];
		$units = $row['Units'];
		$totalDownTime = $row['totalDownTime'];
		$currentShift = $row['CurrentShift'];
		$lineLabels = $row['lineLabels'];
		$remaining = $row['qtyNeeded'] - $row['completedFlanges'];
		$flangesNeeded = $row['qtyNeeded'];
		$flangesCompleted = $row['completedFlanges'];
		$unpaidMins = $row['unpaidShiftMins'];
		$lossBy0 = $row['lossBySPS0'];
		$lossBy7 = $row['lossBySPS7'];
		$lossBy0 < $lossBy7 ? $lossBy0 = $lossBy7 : $lossBy7 = $lossBy0;
		$dataIntegrity0 = round($row['DataIntegrity0'], 1);

		//-----------------DETERMINE THE COLOR----------------------------------------
		$uptime = (number_format(($hrsIntoShift * 60) -(($downTime * 60)+$totalDownTime), 2)/($hrsIntoShift * 60)) *100;
		$uptime < 0 ? $uptime = 0 : $uptime = $uptime;
		$LDown >= 0 ? $activeColor = " colActive " : $activeColor = " ";
		$downTime*60 >= 3 ? $nameColor  = " bad " : ($downTime*60 >= 2 ?  $nameColor  = " okay " : $nameColor  = " good ");
		$remaining <= 25 && $remaining > 1? $remainingColor = " okay" : ($remaining == 0 ? $remainingColor = " good " : $remainingColor = "");
		$uptime >=71 ? $uptimeColor = " good" : ($uptime >= 50 ? $uptimeColor = " okay" : $uptimeColor = " bad");
		$rollAvg < 0.7 *$normalSpeed[$index] && $rollAvg >= 50 ? $rollColor = " okay" : ($rollAvg >=  0.7 *$normalSpeed[$index] ? $rollColor = " good" : $rollColor = " bad");
		$speedAvg < $normalSpeed[$index] * 0.7 && $speedAvg >= $normalSpeed[$index]*50 ? $avgSpeedColor = " okay" : ($speedAvg >= $normalSpeed[$index] * 0.7 ? $avgSpeedColor = " good" : $avgSpeedColor = " bad");
		$lossBy0 * 60 < 25 ? $dataIntegrity0 = " OK'> OK" : ($lossBy0 <= 0.25 && $lossBy7 > $unpaidMins/60*1.1 ? $dataColor = " colDataIssue " && $dataIntegrity0 = "'>  <font size='6'>Market Related:<br>" . number_format($lossBy7 *60,2) . " min</font>": $dataIntegrity0 = " colDataIssue ' > <font size='6'>Not Specified:<br>". number_format($lossBy0 * 60,2) ." min</font>");//>"

		//---------------------Add units--------------------------------
		switch($order){
			case 6:
				$units = $units . " Flgs";
				
				break;
			case 1:
				$units = $units . " Flgs";
				break;
			case 30:
				$units = $units . " Flgs";
				break;
			case 7:
				$units = $units . " Flgs";
				break;
			case 3:
				$units = $units . " Flgs";
				break;
			case 4:
				$units = $units . " Flgs";
				break;
			case 9:
				$units = $units . " Flgs";
				break;
			case 5: //Line 5
				$units = $units . " Flgs";
				break;
			case 20:
				$units = $units . " Flgs";
				break;
			case 40://Resaw
				$units = $units . " Ln Ft";
				break;
			case 50:
				$units = $units . " Bdls";
				break;
			case 60:
				$units = round((pow($orderInfoTop_DIAM, 2))/144 *$flangesCompleted, 2) . " Ln Ft";;
				//$units = round($units, 2) . " Sq. Ft";
				break;
			case 62:
				$units = round((pow(($orderInfoTop_DIAM), 2))/144 * $flangesCompleted, 2) . " Ln Ft";;
				//$units = round($units, 2) . " Sq. Ft";
				break;
			case 51:
				$units = number_format($units,2) . " Ln Ft";;
				//$units = round($units, 2) . " Sq. Ft";
				break;
		}
	echo "<tr> " .
					//-----Line Name-------. else{. " good " . } 
					"<td><div class = '$activeColor $nameColor dbCol colName'>$lineLabels </div><div class = '$nameColor postDowntime'>" . $downTime*60 ."mins</div></td> " .

					//-----Current Shift-------
					"<td><div class=' $activeColor dbCol colShift'> $currentShift </div></td> " .

					//-------Units------------
					"<td><div  class='$activeColor dbCol colUnits' id ='units" . $index . "'>" . $units  . " </div></td>" .

					//-------upTime------------$uptime = (/) *100
					"<td><div class='$activeColor $uptimeColor dbCol colUptime' title= '(". number_format(($hrsIntoShift * 60) -(($downTime * 60)+$totalDownTime),2) .  "mins of Uptime / " . ($hrsIntoShift * 60). "mins of Time into Shift ) * 100' >" . round(($uptime), 1) . " %</div></td>" .

					//-------SpeedAvg------------
					"<td><div class='$activeColor $avgSpeedColor colSpd'>" . number_format($speedAvg,1) ." </div></td> ".

					//-------RollAvg------------
					"<td><div class='$activeColor $rollColor dbCol colSpd'>$rollAvg</div></td> " .
					//-------Data Integrity------------
					"<td><div class='$activeColor dbCol colData " . $dataIntegrity0 . "</div></td> ".

					//-------ORder Info------------
					"<td><div class='$activeColor colOrder' id = 'orderInfo" . $index . "'> " . $orderInfoTop_DIAM ." x " .$orderInfoTop_THICK . "<br/>" . $flangesCompleted .  " of " . $flangesNeeded . "</div></td> " .

					//-------Needed------------
					"<td> <div class='$activeColor $remainingColor dbCol colRemaining'>" . $remaining ."</div></td>
					
			</tr>";
			$index +=1;

	}

		
		?>
}

