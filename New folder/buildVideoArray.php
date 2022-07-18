<?php
/*
Build video array
2019.10.06
Jared Callahan
Sonoco Products Co

*/

//scan directory where video files are stored and create an array of all video file names
$fileroot = $_SERVER['DOCUMENT_ROOT'];
$videoFileNames = array();
$directory = $fileroot.'/dteventvideos';
$lineFolders = array_diff(scandir($directory), array('..', '.'));
foreach ($lineFolders as $line){
	$lineCameras = array();
	$videoFileNames[$line] = array();
	$directory = $fileroot.'/dteventvideos/'.$line;
	$yearFolders = array_diff(scandir($directory), array('..', '.'));
	foreach ($yearFolders as $year){
		$directory = $fileroot.'/dteventvideos/'.$line.'/'.$year;
		$monthFolders = array_diff(scandir($directory), array('..', '.'));
		foreach ($monthFolders as $month){
			$directory = $fileroot.'/dteventvideos/'.$line.'/'.$year.'/'.$month;
			$dayFolders = array_diff(scandir($directory), array('..', '.'));
			foreach ($dayFolders as $day){
				$directory = $fileroot.'/dteventvideos/'.$line.'/'.$year.'/'.$month.'/'.$day;
				$files = array_diff(scandir($directory), array('..', '.'));
				foreach ($files as $key => $item){
					$filetype = substr($item, -3);
					if ($filetype == 'mp4'){
						$itemCamera = substr($item,0,(strlen($item)-18));
						if (!(in_array($itemCamera,$lineCameras))){
							array_push($lineCameras,$itemCamera);
							$itemCamNum = count($lineCameras)-1;
						} else {
							$itemCamNum = array_search($itemCamera,$lineCameras);
						}
							$fileYr = substr($item, -18, 4);
							$fileMo = substr($item, -14, 2);
							$fileDay = substr($item, -12, 2);
							$fileHr = substr($item, -10, 2);
							$fileMin = substr($item, -8, 2);
							$fileSec = substr($item, -6, 2);
							$fileTS = strtotime($fileYr.'/'.$fileMo.'/'.$fileDay.' '.$fileHr.':'.$fileMin.':'.$fileSec);
						$file['fileName'] = $item;
						$file['filePath'] = 'dteventvideos/'.$line.'/'.$fileYr.'/'.$fileMo.'/'.$fileDay.'/';
						$file['ts'] = $fileTS;
						//echo $file['fileName'].'<br>';
						//echo $file['ts'].'<br><br>';
						if (!isset($videoFileNames[$line][$itemCamNum])) $videoFileNames[$line][$itemCamNum] = array();
						array_push($videoFileNames[$line][$itemCamNum],$file);
					}
				}
			}
		}
	}
}

?>