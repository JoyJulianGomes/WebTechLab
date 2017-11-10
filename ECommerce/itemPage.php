<?php 
  session_start();
  if(!isset($_SESSION['cartItemsAndQuantity'])) $_SESSION['cartItemsAndQuantity'] = array();
?>
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
			padding: 5px;
		}
		/*flex-item for ItemContainer*/
		.Item{
			width:22%;
			height: 150px;
			border: 1px #5555ff solid;
			margin:15px;
			padding: 5px;
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
		table tr td{
			border: 1px solid black;
		}
	</style>
    
</head>
<body>

	<?php 
		echo "Hello ".$_SESSION['username'];
		$xmlItemlList = simplexml_load_file("itemList.xml") or die("Error: Cannot create item object");
		//print_r($xmlItemlList);
	?>
	<div class="SuperContainer">
		<form method='POST' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">				
			<div class="ItemContainer">
				<?php 
					foreach($xmlItemlList as $it)
					{
						echo '<div class="Item">';
							echo "Name: "; echo $it->Name; echo "<br>";
							echo "Price: "; echo $it->Price; echo "<br>";
							$itemID = $it->attributes()->ID;
							echo 'ID-'.$itemID.'<br>';
							echo '<input type ="checkbox" name="selected[]" value="'.$itemID.'">';
							echo '<input type="number" name="quantity['.$itemID.']" min="1" max="25" value="1">';
						echo '</div>';
					}
				?>
			</div>
			<input type="hidden" name="action" value="add">
			<input id="AddToCartButton" type ="submit" value="Add to Cart">
		</form>
		
		<div class="CartContainer">
			<?php
				print_r($_SESSION['cartItemsAndQuantity']);
				echo "<br>";
				if($_SERVER['REQUEST_METHOD']=='POST')
				{
					if($_POST['action']=="add")
					{
						if(isset($_POST['selected']) && isset($_POST['quantity']))
						{	
							foreach($_POST['selected'] as $itemsSelected)
							{
								if(!isset(($_SESSION['cartItemsAndQuantity'][$itemsSelected])))
								{
									$_SESSION['cartItemsAndQuantity'] += [$itemsSelected=>$_POST['quantity'][$itemsSelected]];
								}
								else
								{
									$_SESSION['cartItemsAndQuantity'][$itemsSelected] += $_POST['quantity'][$itemsSelected];
								}
							}
						}
					}
					if($_POST['action']=="rem")
					{
						//echo "inside removing block";
						//echo "rm".$_POST['remID'];
						unset($_SESSION['cartItemsAndQuantity'][$_POST['remID']]);
						/*if(!isset($_SESSION['cartItemsAndQuantity'][$_POST['remID']]))
						{
							echo "Removed";
						}
						else{
							echo "Not Removed";
						}
						*/	
					}	
				}
				print_r($_SESSION['cartItemsAndQuantity']);			
				echo "<br>";
				$total = 0;
				echo "<table><tr><th>ID</th><th>Name</th><th>Quantity</th><th>Unit Price</th><th>Total Price</th><tr>";
				$pageurl = htmlspecialchars($_SERVER["PHP_SELF"]);
				foreach($_SESSION['cartItemsAndQuantity'] as $IDS=>$Quant)
				{
					$result = $xmlItemlList->xpath("/ItemList/Item[@ID='".$IDS."']");
					$itemDetails = $result[0];
					$total += $itemDetails->Price*$Quant;
					echo "<tr>
							<td>".$IDS."</td>
							<td>".$itemDetails->Name."</td>
							<td>".$Quant."</td>
							<td>".$itemDetails->Price."</td>
							<td>".$itemDetails->Price*$Quant."</td>
							<td>".
							'<form method="POST" action="'.$pageurl.'">				
										<input type="hidden" name="action" value="rem">
										<input type="hidden" name="remID" value="'.$IDS.'">
										<input type ="submit" value="Remove '.$IDS.'">
							</form>'."
							</td>
							</tr>";
					//echo "ID:".$itemsSelected." Name: ".$_POST['name'][$itemsSelected]." Selected ".$_POST['quantity'][$itemsSelected]."Times Price: ".$_POST['price'][$itemsSelected]."<br>";	
				}
				echo "<tr>
						<td>Total</td>
						<td></td>
						<td></td>
						<td></td>
						<td>".$total."</td>
					</tr>";
				echo "</table>";
					
			?>
		</div>
	</div>
	
	<a href="logout.php">logout</a>
</body>

</html>