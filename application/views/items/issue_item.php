<style type="text/css">
.controls {
margin: 75px;
}

select {
border-size: 2px;
border-color: #000066;
border-radius: 4px;
}

.controls a {
	border-radius: 4px;
	font-size: 15px;
	text-align: center;
	width: 100px;
}

.btn-large{
	height:60px;
	font-size: 20px;
	width: 150px;
}

</style>
<?php
$decodedTableData = json_decode($tableData,true);
$size = count($decodedTableData['itemNames']);
?>
	<form name="selection" method="post"  action="issue_item"> 
	<div class="row">

		<div class="input-field col s6 offset-s3">
			<select class="browser-default" name="selectedMess" value = "<?php echo isset($selectedMess) ? $selectedMess : "";?>" required>
				<option></option>
				<?php
					foreach($messTypes as $eachType)	
					{
					?>
					<option value='<?php echo $eachType;?>'><?php echo $eachType;?></option>
					<?php
					}	
				?>
			</select>
		</div>
	</div>

	<div class="row">
			<?php
				for($i=0;$i<count($decodedTableData['itemNames']);$i++)
				{
				?>
		<div class='row'>
		<div class = "input-field col s4">
		<p>
			<input type='checkbox' value='<?php echo $decodedTableData['itemNames'][$i];?>' 
				id='<?php echo $decodedTableData['itemNames'][$i];?>' 
					onclick="document.getElementById('<?php echo 'txt'.$decodedTableData['itemNames'][$i];?>').disabled=!this.checked;"
					 name='selectedItems[]'/>
					<label for='<?php echo $decodedTableData['itemNames'][$i];?>'><?php echo $decodedTableData['itemNames'][$i];?></label>
		</p>
		</div>
		<div class="input-field col s4">
                        <label class="blue-text text-darken-2"><?php echo $decodedTableData['quantityAvailable'][$i];?></label>
                        <input type="hidden" name='quantityAvailable[]' value='<?php echo $decodedTableData['quantityAvailable'][$i];?>'/>
                        <input type="hidden" name='latestRate[]' value='<?php echo $decodedTableData['latestRate'][$i];?>'/>
                </div>
                <div class="input-field col s4">
                        <input type="text" name="selectedQuantity[]" value="" id='<?php echo 'txt'.$decodedTableData['itemNames'][$i];?>' disabled/>
                        <label for="last_name">Enter Quantity</label>
                </div>
		
		</div>
				<?php
				}
			?>

	<div class="row">
		<div class="col s8 offset-s3">

			 <button class="btn waves-effect waves-light btn-large" 
					value="submit" type="submit" name="submit">
			 Submit
			    <i class="glyphicon glyphicon-chevron-right"></i>
			 </button>

			 <button class="btn waves-effect waves-light red darken-1 btn-large" 
					value="cancel" type="cancel" name="cancel">
			 Cancel
			    <i class="glyphicon glyphicon-remove"></i>
			</button>
		</div>
	</div>
	</form> 
</div>
