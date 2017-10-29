<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sign up</title>
        <style>
		.Head{
			margin: 1% auto;
			width : 20%;
			background-color: #83db93;
			border: 1px solid yellow;

		}
		.cont{
			margin: 1% auto;
			border: 1px solid red;
		}
		.OutputBox{
			float:left;
			width : 20%;
			height: 400px;
		margin: 10% 1% 1% 10%;
			border: 1px solid yellow;
		}
		#_1{
			background-color: #83dbca;
		}
		#_2{
			background-color: #43c6ad;
		}
		#_3{
			background-color: #28bfa2;
		}
        </style>
    </head>
    <body>
	<center>
        <div class="Head">
			<?php 
				date_default_timezone_set("Asia/Dhaka");
				echo "Current time in Dhaka: ". date('d/m/Y H:i:s');
			?>
		</div>
		
		<div class="cont">
			<div class="OutputBox" id="_1">		
			<?php
				$NewYear=date_create_from_format("d/m/Y","1/1/2018");
				$Current=date_create_from_format("d/m/Y",date('d/m/Y'));;
				$diff=date_diff($NewYear,$Current);
				echo $diff->days." Days left for New Year!!";
			?>
			</div>
			<div class="OutputBox" id="_2">
				Web Tech Class Schedule:
				<?php
					$currentSundayRAW = strtotime("next Sunday");
					for($i=0; $i<10; $i++)
					{
						$currentSundayFORMATED = date("d/m/Y", $currentSundayRAW);
						echo $currentSundayFORMATED."<br>";	
						$currentSundayRAW = strtotime("+1 week", $currentSundayRAW);
					}
				?>
			</div>
			<div class="OutputBox" id="_3">
				Semester Break Will Start After:
				<?php
					$Current=strtotime("now");
					$Plus2Month = strtotime("+2 month", $Current);
					echo date("d/m/Y", $Plus2Month);
				?>
			</div>
		</div>
	</center>
    </body>
</html>
