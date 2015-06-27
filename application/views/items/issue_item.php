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
<script>
function moveAll(from, to) {
	$('#'+from+' option').remove().appendTo('#'+to); 
}

function moveSelected(from, to) {
	$('#'+from+' option:selected').remove().appendTo('#'+to); 
}
function selectAll() {
	$("#to option").attr("selected","selected");
}
</script>
<?php
if(isset($msg)) 
print_r($msg); 

$decodedTableData = json_decode($tableData,true);
$size = count($decodedTableData['itemNames']);
?>
<div class="container">
	<form name="selection" method="post"  action="issue_item" onsubmit="return selectAll()"> 
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

		<div class = "input-field col s4">
			<select class="browser-default" multiple size=20 id="from">
			<?php
				foreach($decodedTableData['itemNames'] as $each)
				{
				?>
					<option value='<?php echo $each;?>'><?php echo $each;?></option>
				<?php
				}
			?>

			</select>
		</div>
	
			
		<div class = "input-field col s4">
			<div class="controls">
				<div class="row">
				<a href="javascript:moveAll('from', 'to')" class="btn waves-effect waves-light">
				&gt;&gt;
				</a> 
				</div>
				<div class="row">
				<a href="javascript:moveSelected('from', 'to')" class="btn waves-effect waves-light">
				&gt;
				</a> 
				</div>
				<div class="row">
				<a href="javascript:moveSelected('to', 'from')" class="btn waves-effect waves-light">
				&lt;
				</a> 
				</div>
				<div class="row">
				<a href="javascript:moveAll('to', 'from')" href="#" 
							class="btn waves-effect waves-light">
				&lt;&lt;
				</a>
				</div>
			 </div>
		</div>
			
		<div class = "input-field col s4">
			<select class="browser-default" multiple id="to" size=20 name="selectedItems[]">
			</select>
		</div>
	</div>
	
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
