<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<title>Items</title>
	<style>
		/*flex-container*/
		.SuperContainer{
			width:100%;
			display:flex;
		}
		/*flex-item for SuperContainer*/
		/*flex-container*/
		.ItemContainer{
			display:flex;
			flex-wrap: wrap;
			width:100%;
			border: 1px solid red;
		}
		/*flex-item*/
		.CartContainer{
			width:30%;
			border: 1px solid green;
		}
		/*flex-item for ItemContainer*/
		.Item{
			width:22%;
			height: 150px;
			border: 1px #5555ff solid;
			margin:15px;
			/*background-image: linear-gradient(to bottom, #bbaaaa, #995005);*/
			/*background-image: linear-Gradient(angle , colorStop1, colorStop2, ..);*/
			/*background-image: radial-Gradient(#333333 10%, #999999 20%, white 50%);*/
			/*background-image: linear-Gradient(20deg , red,orange,yellow,green,blue,indigo,violet);*/
			box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
		}
		#AddToCartButton{
			width:22%;
			height:40px;
			margin:15px;
			background: green;
			box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
		}
	</style>
    
</head>
<body>

	<?php 
		echo "Hello ".$_SESSION['username'];
		$xml = simplexml_load_file("itemList.xml") or die("Error: Cannot create object");
		//print_r($xml);
	?>
	<div class="SuperContainer">
		<form method='POST' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">				
			<div class="ItemContainer">
				<?php 
					foreach($xml as $it)
					{
						echo '<div class="Item">';
							echo "Name: "; echo $it->Name; echo "<br>";
							echo "Price: "; echo $it->Price; echo "<br>";
							echo 'ID-'.$it->Id.'<br>';
							echo '<input type ="checkbox" name="selected[]">';
							echo '<input type="number" name="quantity[]" min="1" max="25">';
						echo '</div>';
					}
				?>
			</div>
			<input id="AddToCartButton" type ="submit" value="Add to Cart">
		</form>
		
		<div class="CartContainer">
		</div>
	</div>
	<?php
		if($_SERVER['REQUEST_METHOD']=='POST')
        {

		}
	?>
	<a href="logout.php">logout</a>
</body>

</html>