<?php
/*
View downtime event video
2019.10.06
Jared Callahan
Sonoco Products Co

*/
$error="0";
$autoRefresh=FALSE;
include 'header2p0.php';
include 'buildVideoArray.php';
?>
<style>
	.videoBox {
		position: relative;
		top:100px;
	}
	.videoButton {
		display: inline-block;
		position: relative;
		font-family: 'Helvetica';
		font-size: 16pt;
		text-align: center;
		color: tan;
		font-weight: bold;
		padding: 10px;
		width: 150px;
		height: 30px;
		border: 10px solid #006;
		box-shadow: 15px 15px 10px #333333;
		background: darkblue;
		background: radial-gradient(center,#3232a2,#000072);
		background:-moz-radial-gradient(center,#3232a2,#000072);
		background:-webkit-radial-gradient(center,#3232a2,#000072);
		background:-o-radial-gradient(center,#3232a2,#000072);
		overflow-y: auto }
</style>
<a style='text-decoration:none;' href='dashboard2p0.php'><div class="brand">Sonoco Reels & Plugs</div></a>
<div class="brand timeStamp timeStampNarrow">
    <?php echo date('l h:i:s A') . "<br />" . date('jS \of F Y') . "<br />"; ?>
</div>
<?php
//find video to play
if (isset($_GET['ts']) and isset($_GET['line'])) {
	$line=$_GET['line'];
	$offset=$_GET['offs'];
	$eventTime=$_GET['ts'];
	$nextFileAvail=false;
	$prevFileAvail=false;
	
	for ($camera=0; $camera<count($videoFileNames[$line]); $camera++) { //loop through all cameras for line
		for ($file=0; $file<count($videoFileNames[$line][$camera]); $file++) { //loop through all files for camera
			if ($videoFileNames[$line][$camera][$file]['ts']>$eventTime){
				break;
			} else {
				$fileNum = $file + $offset;
				if ($fileNum < 0) {$fileNum = 0;} //make sure file index is not out of bounds
				if ($fileNum > count($videoFileNames[$line][$camera])-1) {$fileNum = count($videoFileNames[$line][$camera])-1;} //make sure file index is not out of bounds
				$fileToPlay[$camera]=$videoFileNames[$line][$camera][$fileNum]['filePath'].$videoFileNames[$line][$camera][$fileNum]['fileName'];
				$startTime[$camera]=$eventTime-$videoFileNames[$line][$camera][$fileNum]['ts'];
			}
		}
		if ($fileNum<count($videoFileNames[$line][$camera])-1) {$nextFileAvail=true;} //check if there is a next file
		if ($fileNum>0) {$prevFileAvail=true;} //check if there is a prev file
	}
	
	for ($camera=0; $camera<count($videoFileNames[$line]); $camera++) {
		if (isset($fileToPlay[$camera])){
			echo '<div class="videoBox">';
			echo '<video id="vid'.$camera.'" width="1000" height="600" controls autoplay>'
				.'<source src="'.$fileToPlay[$camera].'" type="video/mp4">'
				.'Your browser does not support the video tag.'
				.'</video><br>';
			if ($offset == 0) {
				echo "<script>"//script to start video playback at precise downtime event second
					."document.getElementById('vid".$camera."').addEventListener('loadedmetadata', function() {"
					.  "this.currentTime = ".$startTime[$camera].";"
					."}, false);"
					."</script>";
			}
		}
	}
	if ($prevFileAvail){
		echo "<a style='text-decoration:none;' href='DTEVideo.php?ts=".$eventTime."&line=".$line."&offs=".($offset-1)."'><div class='videoButton'>PREV</div></a> ";
	}
	if ($nextFileAvail){
		echo "<a style='text-decoration:none;' href='DTEVideo.php?ts=".$eventTime."&line=".$line."&offs=".($offset+1)."'><div class='videoButton'>NEXT</div></a> ";
	}
}
?>
    </body>
</html>