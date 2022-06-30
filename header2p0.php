<?php // Header 2p0; Nick West; 2018.10.05
session_start();
echo "<!DOCTYPE html>";
include 'functions.php';
?>

<html>
    <head>
        <title>Hartselle Nailwood</title>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <meta http-equiv="X-UA-Compatible" content="IE=9" />
		<link rel="icon" href="reel.png">
        <?php if ($autoRefresh) { echo '<meta http-equiv="refresh" content="60"/>'; } ?>
        <style>
            .brand {
                position: absolute;
                top: 0px;
                left: 5px;
                font-family: 'Helvetica';
                font-size: 50pt;
                text-align: left;
                color: #4db8ff;
                background-color: #00111a;
                padding: 2px;
                width: 700px;
                height: 80px;
            }
            .timeStamp {
                left: 1320px;
                width: 475px;
                font-size: 30pt;
                text-align: right;
                color: #4db8ff;
            }
            .timeStampNarrow { width: 400px; left: 870px; font-size: 25pt;}
            .dashboard {
                position: absolute;
                top: 90px;
                left: 15px;
                width: 100%
            }
			.dbPageHead {
				padding: 5px;
                position: fixed;
                top: 0px;
                left: 0px;
                width: 100%;
				height: 200;
				background-color: #00111a;
				color: blue;
				z-index:1000;
            }
            .dbTitle {
                font-size: 15pt;
                color: #4db8ff;
                text-align: right;
                background-color: #00111a; //Light Blue
                font-family: 'Helvetica';
            }
            .dbCol {
                color: black;
                height: 100px;
                text-align: center;
                border: lightgray;
                background-color: darkgrey;
                font-size: 57pt;
                font-family: 'Helvetica';
                border-radius: 5px; 
                vertical-align:  middle;
            }
            .colName { color: black; font-size: 40pt; width: 210px; padding-top: 2%; line-height: 85%;}
            .colShift { width: 70px; }
            .colUptime { width: 160px; }
            .colSpd { width: 240px; }
            .colUnits { width: 280px; }
            .colOrder { font-size: 30pt; width: 240px; line-height: 120%; letter-spacing: -2px; }
            .colData { width: 240px; font-size: 45pt; line-height: 150%; }
            .colRemaining { width: 170px; }
            .colActive { color: black; background: #d1e2ff; }
            .colDataIssue { background: #f4b042; line-height: 65% }
            

            .good { color: black; background: #6ee804; }
            .bad { color: white; background: #ff4300; }
            .okay { color: black; background: #bee804; }
            
            .lossBtn {
                height: 200px;
                width: 200px;
                font-family: 'Helvetica';
                font-size: 20pt;
                border-radius: 10px;
                box-shadow: 10px 10px 5px #888;
            }
            .lossBtn:active { box-shadow: none; }
            
            .box {
                position: absolute;
                top: 0px;
                font-family: 'Helvetica';
                font-size: 16pt;
                text-align: center;
                color: tan;
                font-weight: bold;
                padding: 10px;
                width: 440px;
                height: 65px;
                border: 10px solid #006;
                box-shadow: 15px 15px 10px #333333;
                background: darkblue;
                background: radial-gradient(center,#3232a2,#000072);
                background:-moz-radial-gradient(center,#3232a2,#000072);
                background:-webkit-radial-gradient(center,#3232a2,#000072);
                background:-o-radial-gradient(center,#3232a2,#000072);
                overflow-y: auto }
            .b1 {
                font-size: 12pt;
                border: 5px solid #006;
                transform: rotate(0deg);
                margin-top: 110px;
                left: 5px;
                width: 160px;
                height: 40px;
                border-top-left-radius: 10px 10px;
                border-top-right-radius: 10px 10px;
                border-bottom-left-radius: 10px 10px;
                border-bottom-right-radius: 10px 10px; }
            .b1:active { box-shadow: none; }
            .b3 {
                margin-top: 190px;
                border-radius: 40px; 
                width: 700px;
                height: 500px;
                border: 10px solid #CC0099;
                color: black;
                background: #993399;
                background: radial-gradient(center,#CC0099,#993399);
                background:-moz-radial-gradient(center,#CC0099,#993399);
                background:-webkit-radial-gradient(center,#CC0099,#993399);
                background:-o-radial-gradient(center,#CC0099,#993399);
                overflow-y: auto }
            .b3:active {
                box-shadow: none;
                background: #993399;
                background: radial-gradient(center,#CC0099,#663366);
                background:-moz-radial-gradient(center,#CC0099,#663366);
                background:-webkit-radial-gradient(center,#CC0099,#663366);
                background:-o-radial-gradient(center,#CC0099,#663366); }
            .posButton2 {
                position: absolute;
                top: 0px;
                margin-left: 380px; }
            .posButton3 {
                position: absolute;
                top: 0px;
                left: 200px; }
            .posButton4 {
                position: absolute;
                top: 0px;
                left: 400px; }
            .posButton5 {
                position: absolute;
                top: 0px;
                left: 600px; }
            .posButton6 {
                position: absolute;
                top: 0px;
                left: 800px; }
            .posButton7 {
                position: absolute;
                top: 0px;
                left: 1000px; }
            .selected {
                color: black;
                background: #CC0099;
                background: radial-gradient(center,#CC0099,#993399);
                background:-moz-radial-gradient(center,#CC0099,#993399);
                background:-webkit-radial-gradient(center,#CC0099,#993399);
                background:-o-radial-gradient(center,#CC0099,#993399);}
            .dtEvents {
                position: absolute;
                line-height: 150%;
                margin-top: 190px;
                font-family: 'Arial';
                font-size: 12pt;
                text-align: left;
                color: black;
                padding: 10px;
                width: 1300px;
                height:500px;
                border: 2px solid #006;
                background: white;
                overflow-y: auto; }
            .report {
                position: absolute;
                top: 50px;
                left: 700px;
                width: 100px;
                height: 20px;
                margin-top: 0px;

            }
          .s2 {
                color:blue;
                font-size: 14pt;
                font-style: 'italic';}
            
            .lnk:hover { background-color: lightblue; }
            .ns { background-color: #ff99ff;  }
            select {
                font-size: 30px;
                width: 525px;
                height: 50px;}
            input[type=submit]{
                font-size: 25px;
                width: 325px;
                height: 50px;}
            input[type=text]{
                font-size: 18px;
                width: 50px;
                height: 30px;}
            .reas { background: palegoldenrod; }
            .sps { background: palegreen; }
            optgroup {
                font-size: 18px;
                color:black;
                }
            .update { background-color: #F5A9A9;}
            .breakUpBorder { border: 8px solid red; }
            .error {
                position: relative;
                color: red;
                background-color: #ddd;
                width: 580px;
                height: 75px;
                overflow-y: auto;
                text-align: left;
                border: 2px solid #006;
                box-shadow: 5px 5px 3px #888;
                font-family: 'Helvetica';
                font-size: 16pt; 
          }
          .translate {
                position: absolute;
                top: 2px;
                left: 710px;
				z-index:1001;
            }
          a { text-decoration: none; }
          a:visited { text-decoration: none; }
          a:hover { text-decoration: none; }
          a:focus { text-decoration: none; }
          a:hover, a:active { text-decoration: none; }

            
        </style>
    </head>
    <body style="background-color:#00111a">
<?php
include 'shiftDefinitions.php';
include 'lineDefinitions.php';
?>
<script type="text/javascript">
	
    /*function googleTranslateElementInit() {
        new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
    }*/
    </script>
<?php 
$client = gethostbyaddr($_SERVER['REMOTE_ADDR']);
if ($client != "a820spsstatus02.sonoco.com") {
	/*echo "<script type='text/javascript' src='//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit'></script>
    <div id='google_translate_element' class='translate'></div>";*/
}
?>

