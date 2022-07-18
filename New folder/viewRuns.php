<?php
/*
view Runs this week
2015.11.09
Nicholas West
Engineering Projects Manager
Sonoco Products Co

*/
$autoRefresh=TRUE;
include 'header2p0.php';
?>
<a style='text-decoration:none;' href='dashboard2p0.php'><div class="brand">Sonoco Reels & Plugs</div></a>
<div class="brand timeStamp timeStampNarrow">
    <?php echo date('l h:i:s A') . "<br />" . date('jS \of F Y') . "<br />"; ?>
</div>
<?php
$error = "";
if (!(isset($_GET['c']))) {
    echo "<div class='box b1'>NO Work Cell (Line) passed</div>";
} else {
    $workCell = $_GET['c'];
    include 'lineDefinitions.php';
    echo "<a style='text-decoration:none;' href='dashboard2p0.php?c=" . $workCell 
            . "'><div class='box b1'>" . $lTitle[$workCell] . '</div></a>';
	echo "<br /><br />Workcell= $workCell <br />";
	echo "<br /><br />lType= " . $lType[$workCell]. " <br />";
	// @@@@@@@@@@@@@@@@@@@   Flanges - Nailer, Layup Table, Final Flange @@@@@@@@@@@@@@@@@@@@@@
	if ($lType[$workCell]=="final" || $lType[$workCell]=="nailer") {
		//----------LOCATE INFO FROM RUN THAT STARTED LAST WEEK-----------
		$query =  "SELECT DATE(ts), TIME(ts), FlangeDiam, FlangeThick, WoodUnits 
			FROM ".$tblSetup[$workCell]['name']." WHERE WEEK(ts)=WEEK(NOW())-1 ORDER BY "
			.$tblSetup[$workCell]['id']." DESC limit 1";
		$result = queryMysql($query);
		if($result) { $num = mysqli_num_rows($result); }
		if ($num==0) { 
			$error =    "<span class='error'>Could not the last order from last week"
					. "</span><br /><br />";
			$tableWk[0]=['date' => "",'time' => "",'fd' => "",'ft' => "", 'wu' => "", 'cnt' => ""];
		} else {
			$row = mysqli_fetch_row($result);
			$tableWk[0]=['date' => $row[0],'time' => $row[1],'fd' => $row[2],
				'ft' => $row[3], 'wu' => $row[4], 'cnt' => 0];
		}
		//----------GET RUNS THAT STARTED THIS WEEK-----------
		$query = "SELECT DATE(fs1.ts), TIME(fs1.ts), fs1.FlangeDiam, "
			. "fs1.FlangeThick, fs1.WoodUnits FROM ".$tblSetup[$workCell]['name']." AS fs1 "
			. "INNER JOIN ".$tblSetup[$workCell]['name']." AS fs2 ON fs2."
			.$tblSetup[$workCell]['id']."=fs1.".$tblSetup[$workCell]['id']."-1 "
			. "AND (abs(fs2.flangeDiam-fs1.flangeDiam) >= 0.3 OR "
			. "ABS(fs2.FlangeThick-fs1.FlangeThick) >= 0.2) "
			. "WHERE YEAR(fs1.ts)=YEAR(NOW()) AND WEEK(fs1.ts)=WEEK(NOW())";
		$result = queryMysql($query);
		if($result) { $num = mysqli_num_rows($result); }
		$iLast=0;
		if ($num==0) { 
			$error .=  "<span class='error'>No completed orders that were started this week."
					. "</span><br /><br />";
		} else {
			for ($i=1; $i<=$num; $i++) {
				$row = mysqli_fetch_row($result);
				$tableWk[$i]=['date' => $row[0],'time' => $row[1],'fd' => $row[2],
					'ft' => $row[3], 'wu' => $row[4], 'cnt' => 0];
			}
			$iLast = $num;
		}
		if (!($tableWk[0]['date']=="")) {
					//--------------GET TIMESTAMP OF ALL FLANGES THAT WERE PRODUCED---------
			$query = "SELECT ".$tblProd[$workCell]['ts']." FROM ".$tblProd[$workCell]['name']." WHERE YEAR(".$tblProd[$workCell]['ts'].")=YEAR(NOW()) "
				. "AND WEEK(".$tblProd[$workCell]['ts'].")=WEEK(NOW()) AND ".$tblProd[$workCell]['cnt'].">0";
			$result = queryMysql($query);
			if($result) { $num = mysqli_num_rows($result); }
			if ($num==0) { 
				$error .=  "<span class='error'>No Flanges Returned from Production "
					. "Table.</span><br /><br />";
			} else {
				$cnt=0;
				$k=0;
				for ($i=0; $i<$num; $i++) {
					$row = mysqli_fetch_row($result);
					If ($k<$iLast) {
						$chk = ($tableWk[$k+1]['date'] . " " .$tableWk[$k+1]['time']);
						If ($row[0]<$chk) {
							$cnt++;
						} else {
							$tableWk[$k]['cnt']=$cnt;
							$cnt=1;
							$k++;
						}
					} else { $cnt++; }       
				}
				$tableWk[$iLast]['cnt'] = $cnt;
			}
							
			// Build Table
			echo "<div class='box b3'>RUNS THIS WEEK<br /><table class='info'>"
				. "<col width='170'><col width='150'><col width='100'><col width='135'><tr>"
				. "<td>Date</td><td>Size</td><td>Flanges</td><td>1st Flange</td><tr>"
				. "<tr><td>------</td><td>------</td><td>------</td><td>------</td></tr>";
			for ($k=0; $k<=$iLast; $k++) {
				echo '<tr><td>';
				if ($k==0) { 
					echo "<span style='color: #3333CC'>". $tableWk[$k]['date'] . "</span>";
				} else { echo $tableWk[$k]['date']; }
				echo '</td><td>' . $tableWk[$k]['fd'] . ' x ' . $tableWk[$k]['ft'] 
					. '</td><td>' . $tableWk[$k]['cnt'] . '</td><td>' 
					. $tableWk[$k]['time'] . '</td></tr>';
			}
			echo "<tr><td>------</td><td>------</td><td>------</td><td>------</td></tr></table></div>";

		}
		echo "<a href='reports/opReport.php?c=$workCell'><div class='box b1 report'>Reports</div></a>";
	}
	// @@@@@@@@@@@@@@@@@@@   Report for Bolt Machine @@@@@@@@@@@@@@@@@@@@@@
	if ($lType[$workCell]=="bolts") {
		$query =  "SELECT prodDate, shift, OrderID, MIN(ts), SUM(qty) "
			. "FROM ".$tblProd[$workCell]['name']
			. " WHERE prodDate>=DATE(DATE_SUB(NOW(),INTERVAL 10 DAY)) AND qty>0 "
			. "GROUP BY prodDate, shift, OrderID "
			. "ORDER BY ts DESC";
		// echo "<br /><br /><br />$query<br /><br />";
		$result = queryMysql($query);
		if($result) { $num = mysqli_num_rows($result); }
		if ($num==0) { 
			$error =    "<span class='error'>Could not find production in the past 10 days"
					. "</span><br /><br />";
			$tableWk[0]=['date' => "",'shift' => "",'size' => "",'start' => "", 'cnt' => ""];
		} else {
			echo "<br /><br /><br />num=$num<br /><br />";
			for ($i=0; $i<$num; $i++) {
				$row = mysqli_fetch_row($result);
				$tableWk[$i]=['date' => $row[0],'shift' => $row[1],'size' => $row[2],
				'start' => $row[3], 'cnt' => $row[4]];
			}
			
			// Build Table
			echo "<div class='box b3'>RUNS PAST 10 Days<br /><table class='info'>"
				. "<col width='120'><col width='60'><col width='160'><col width='225'><col width='100'><tr>"
				. "<td>Date</td><td>Shift</td><td>Size</td><td>Started</td><td>Qty</td><tr>"
				. "<tr><td>------</td><td>------</td><td>------</td><td>------</td><td>------</td></tr>";
			for ($k=0; $k<$num; $k++) {
				echo '<tr><td>';
				if ($k==0) { 
					echo "<span style='color: #3333CC'>". $tableWk[$k]['date'] . "</span>";
				} else { echo $tableWk[$k]['date']; }
				echo '</td><td>' . $tableWk[$k]['shift']  
					. '</td><td>' . $tableWk[$k]['size'] . '</td><td>' 
					. $tableWk[$k]['start'] . '</td><td>' . $tableWk[$k]['cnt'] . '</td></tr>';
			}
			echo "<tr><td>------</td><td>------</td><td>------</td><td>------</td><td>------</td></tr></table></div>";
		}
	}	
	// @@@@@@@@@@@@@@@@@@@   Report for Optimization Saw @@@@@@@@@@@@@@@@@@@@@@
	if ($lType[$workCell]=="saw" && $workCell==51) {
		$query = "SELECT ts, ROUND(ROUND(thick/25.4*8,0)/8,2) AS `thk`, ROUND(ROUND(width/25.4*4,0)/4,2) AS `wide`, " 
		. "ROUND(ROUND(length/25.4*4,0)/4,2) AS `len` , SUM(qty) "
		. "FROM indusoft.board_summary_os1 "
		. "WHERE waste=0 AND qty>0 AND ts>=DATE_SUB(NOW(),INTERVAL 10 DAY)  "
		. "GROUP BY ts, thick, width, `len` "
		. "ORDER BY id_bs_os1 DESC";
	
		$result = queryMysql($query);
		if($result) { $num = mysqli_num_rows($result); }
		if ($num==0) { 
			$error =    "<span class='error'>Could not find production in the past 10 days"
					. "</span><br /><br />";
			$tableWk[0]=['date' => "",'thick' => "",'width' => "",'len' => "", 'cnt' => ""];
		} else {
			echo "<br /><br /><br />num=$num<br /><br />";
			for ($i=0; $i<$num; $i++) {
				$row = mysqli_fetch_row($result);
				$tableWk[$i]=['date' => $row[0],'thick' => $row[1],'width' => $row[2],
				'len' => $row[3], 'cnt' => $row[4]];
			}
			
			// Build Table
			echo "<div class='box b3'>CUTS PAST 10 Days (excl waste & current shift) <br /><table class='info'>"
				. "<col width='275'><col width='125'><col width='125'><col width='125'><col width='100'><tr>"
				. "<td>Shift End</td><td>Thick</td><td>Width</td><td>Length</td><td>Qty</td><tr>"
				. "<tr><td>------</td><td>------</td><td>------</td><td>------</td><td>------</td></tr>";
			for ($k=0; $k<$num; $k++) {
				echo '<tr><td>';
				echo $tableWk[$k]['date']; 
				echo '</td><td>' . $tableWk[$k]['thick']  
					. '</td><td>' . $tableWk[$k]['width'] . '</td><td>' 
					. $tableWk[$k]['len'] . '</td><td>' . $tableWk[$k]['cnt'] . '</td></tr>';
			}
			echo "<tr><td>------</td><td>------</td><td>------</td><td>------</td><td>------</td></tr></table></div>";
		}
	}
    mysqli_close($myLink);
} 


?>