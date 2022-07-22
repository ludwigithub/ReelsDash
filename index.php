
<?php
include("config.php"); 
$query = $conn->query("select * FROM dashinfo");
?>
<html>
	<head>
	<title>Hartselle Nailwood</title>
	<style>
<?php include "colorpage.css"; ?>
	</style>
	</head>
	<body style="background-color:#00111a">
	<a style="text-decoration:none;" href="dashboard2p0.php"><div class="brand">Sonoco Reels &amp; Plugs</div></a>
	<div id= "tester" class = "holder"> </div>
	<div id= "time" class="brand timeStamp"> </div>
		<table class = "table">
			<tr>
				<td class = "dbTitle Line">Line</td>
				<td class = "dbTitle shift">Shift</td>
				<td class = "dbTitle Units">Units Produced</td>
				<td class = "dbTitle upTime">Total Uptime(mins)</td>
				<td class = "dbTitle Speed">Current Speed</td>
				<td class = "dbTitle Speed10min">Speed(10min)</td>
				<td class = "dbTitle Integrity">Data Integrity</td>
				<td class = "dbTitle orderInfo">Order Info</td>
				<td class = "dbTitle Needed">Need</td>
			</tr>
		</table>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"> </script>
		<! –– when the document is ready(has loaded), run a function, use a selector to select the button, when button is clicked, run another function, that loads comments from the database ––>
			<div id="comments">
				
			</div>
		<table id= "populatetable">

			</table>
			<script>
			//when the page has finished loading, call the function

			//when called, update the data from the server
			$reload = function(){
			$.ajax({
					type: "GET",
					url: "load-table.php",
					dataType: "html",
					success: function(data){
						$("#populatetable").html(data);
						var now = new Date();
						var date = now.getFullYear() + "-"+now.getMonth() + "-" + now.getDate();
						var time = now.getHours() + ":" + now.getMinutes() + ":" + now.getSeconds();
						$("#time").text("Updated: "+date +" "+time);
						

					},
					complete: function(data){
						setTimeout($reload,120*1000);
					}
				});
			}

			$(document).ready(function(){
				$reload();
				
			});

		</script>
		</body>
	</html>
