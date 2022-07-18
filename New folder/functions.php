<?php 
//$dbhost  = 'a820spsdata';    // Unlikely to require changing
$dbhost  = 'a820spsdata';    // Unlikely to require changing
$dbname  = 'indusoft';       // Modify these...
$dbuser  = 'sps';   // ...variables according
$dbpass  = 'Reels.SPS';   // ...to your installation
$appname = "Hartselle Nailwood"; // ...and preference
$myLink = mysqli_connect($dbhost, $dbuser, $dbpass) or die(mysqli_error($myLink));

mysqli_select_db($myLink,$dbname) or die(mysqli_error($myLink));


function queryMysql($query)
{
    global $myLink;
    $result = mysqli_query($myLink, $query) or die(mysqli_error($myLink));
	 return $result;
}

function destroySession()
{
    $_SESSION=array();
    
    if (session_id() != "" || isset($_COOKIE[session_name()]))
        setcookie(session_name(), '', time()-2592000, '/');

    session_destroy();
}

function sanitizeString($var)
{
    global $myLink;
    $var = strip_tags($var);
    $var = htmlentities($var);
    $var = stripslashes($var);
    return mysqli_real_escape_string($myLink, $var);
}


//------------FUNCTIONS FOR DASHBOARD DATA-----------------
function flangeSize($setupTable, $id)
{
    $query="SELECT FlangeDiam, FlangeThick FROM " . $setupTable . " ORDER BY " . 
            $id . " DESC LIMIT 1";
    $result = queryMysql($query);
    if($result) $num    = mysqli_num_rows($result);
    if ($num==0) {
        echo "<span class='error'>No current week production data returned</span><br /><br />";
    }
    else    { $row = mysqli_fetch_row($result); }
    
    return array ($row[0], $row[1]);
}
function runProgress($table,$id,$ts,$shiftStart)
{
    $query="SELECT NailerQty, QtyNeeded, TIMESTAMPDIFF(SECOND,GREATEST(" . $ts . 
            ",'" . $shiftStart . "'),NOW()) FROM " . $table . " ORDER BY " . 
            $id . " DESC LIMIT 1";
    $result = queryMysql($query);
    if($result) $num    = mysqli_num_rows($result);
    if ($num==0) {
        echo "<span class='error'>No current week production data returned</span><br /><br />";
    }
    else    { $row = mysqli_fetch_row($result); }
    
    return array ($row[0], $row[1], $row[2]);
}
function runProgressff($table,$id,$ts,$shiftStart)
{
    $query="SELECT flanges, QtyNeeded, TIMESTAMPDIFF(SECOND,GREATEST(" . $ts . 
            ",'" . $shiftStart . "'),NOW()) FROM " . $table . " ORDER BY " . 
            $id . " DESC LIMIT 1";
    $result = queryMysql($query);
    if($result) $num    = mysqli_num_rows($result);
    if ($num==0) {
        echo "<span class='error'>No current week production data returned</span><br /><br />";
    }
    else    { $row = mysqli_fetch_row($result); }
    
    return array ($row[0], $row[1], $row[2]);
}
function shiftProgress($table,$ts,$shift)
{
    $query="SELECT count(*), sum(WoodUnits) FROM " . $table . " WHERE " . $ts .
            ">=DATE_SUB(NOW(),INTERVAL 12 HOUR) AND shift = " . $shift;
    $result = queryMysql($query);
    if($result) $num    = mysqli_num_rows($result);
    if ($num==0) {
        echo "<span class='error'>No current week production data returned</span><br /><br />";
    }
    else    { $row = mysqli_fetch_row($result); }
    
    return $row[0];
}

function shiftWUff($table,$ts,$shift)
{
    $query="SELECT sum(WU) FROM " . $table . " WHERE " . $ts .
            ">=DATE_SUB(NOW(),INTERVAL 12 HOUR) AND shift = " . $shift;
    $result = queryMysql($query);
    if($result) $num    = mysqli_num_rows($result);
    if ($num==0) {
        echo "<span class='error'>No current week production data returned</span><br /><br />";
    }
    else    { $row = mysqli_fetch_row($result); }
    
    return $row[0];
}
function totalDT($table,$shift,$shiftStart)
{
    $query="SELECT SUM(TIMESTAMPDIFF(SECOND,start,end))/60/60 FROM " . $table . 
            " WHERE start>='". $shiftStart ."' AND shift=" . $shift;
    $result = queryMysql($query);
    if($result) { $num = mysqli_num_rows($result); }
    if ($num==0) {
        echo "<span class='error'>No current week production data returned</span><br /><br />";
    }
    else    { $row = mysqli_fetch_row($result); }
    
    return $row[0];
}

function getMetricLoss($table,$spsMetricID,$idLbl,$shiftStart) {
    $query = "SELECT COUNT(". $idLbl ."), sum(TIMESTAMPDIFF(SECOND,start,end))/60 FROM "
            . $table . " WHERE start>='" . $shiftStart . "' AND sps_code=" . $spsMetricID;
    $result = queryMysql($query);
    if ($result) $num = mysqli_num_rows($result);
    if ($num==0) $error =    "<span class='error'>No Loss Data Returned</span><br /><br />";
    else 
    {
        $row = mysqli_fetch_row($result);
        if ($row[0]<=0) $row[0]=0;
        if ($row[1]<=0) $row[1]=0;
        return array ($row[0], $row[1]);
    }
}

function getValue($r, $metric)
{
    foreach ($r as $chk) {
        if ($chk[0]==$metric) {
            return $chk[1];
        }
    }
    return 0;
}
function stavesShiftProgress($idL) {
    $query = "SELECT lineDown, linearFeet, dtMins_CurrentEvent, dtHrs_CurrentShift, LFperMin_Effective, "
            . "LFperMIn_Running, 5minRollAvg, 10minRollAvg, 30minRollAvg, shift, hrs_intoShift "
            . "FROM indusoft.status_staves WHERE idLine=$idL;";
    $result = queryMysql($query);
    if($result) $num    = mysqli_num_rows($result);
    if ($num==0) {
        echo "<span class='error'>No Status Report for Line = $idL</span><br /><br />";
    }
    else    { $row = mysqli_fetch_row($result); }
    
    return array ($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], 
        $row[8], $row[9], $row[10]);
}
function shiftStatusff($idL) {
    $query = "SELECT lineDown, flanges, flanges_order, flanges_tgt, thick, diam, dtMins_CurrentEvent, dtHrs_CurrentShift, "
            . "10minRollAvg, 30minRollAvg, shift, hrs_intoShift, 1stPass, rework "
            . "FROM indusoft.status_flanges WHERE idLine=$idL;";
    $result = queryMysql($query);
    if($result) $num    = mysqli_num_rows($result);
    if ($num==0) {
        echo "<span class='error'>No Status Report for Line = $idL</span><br /><br />";
    }
    else    { $row = mysqli_fetch_row($result); }
    
    return array ($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], 
        $row[8], $row[9], $row[10], $row[11], $row[12], $row[13]);
}
function shiftStatusBolts($idL) {
	$query = "SELECT lineDown, bolts_order, bolts_tgt, diam, len, dtMins_CurrentEvent " 
		. "FROM indusoft.status_bolts WHERE idLine=$idL;";
	$result = queryMysql($query);
    if($result) $num    = mysqli_num_rows($result);
    if ($num==0) {
        echo "<span class='error'>No Bolts Status Report for Line = $idL</span><br /><br />";
    }
    else    { $row = mysqli_fetch_row($result); }
    
    return array ($row[0], $row[1], $row[2], $row[3], $row[4], $row[5]);
}
function secsAfterMidnight($t) {
    if (strlen($t)>1) {
        $components = explode(":",$t);
        $val = $components[0]*60*60 + $components[1]*60 + $components[2];
    } else { $val = 0; }
    return $val;
}
?>
