<script>
$(document).on("click",".remove_field", function(e){ //user click on remove text                 
                                e.preventDefault(); $(".card-panel").remove(); 
                                });
</script>
<div class="container">
<?php 
$msg= validation_errors(); 
if(isset($msg) && count($msg) > 0 && $msg != "")
{
$pre = '<div class="card-panel teal lighten-2">
	<div class="row" style="float:right;margin:4px" >
		<a href="#" class="remove_field" style="color:red">
		<span class="glyphicon glyphicon-remove">
		</span>
		</a>
	</div>';
if(is_array($msg))
{
foreach($msg as $each)
{
$main =	'<div class="row">
		<h6 class="black-text text-darken-2">
		'.$each.'
		</h6>
	</div>';
}
}
else
$main = '<div class="row">
                <h6 class="black-text text-darken-2">
                '.$msg.'
                </h6>
        </div>';

$post='</div>';
echo $pre.$main.$post;
}
if(isset($error))
{
foreach($error as $each)
?>
<div class="card-panel teal lighten-2"><?php echo $each;?></div>
<?php
}
?>
<style>
.btn-large{
        height:60px;
        font-size: 20px;
        width: 150px;
}

</style>

	<form method='post' action='issue_quantity'>
	<div class="row"></div>
	<div class="row">
		<div class="col s6">
			<span class="blue-text text-darken-2">Selected Mess</span>
		</div>
		<div class="col s6"> 
			<span class="blue-text text-darken-2"><?php echo $selectedMess;?></span>
		</div>
	</div>

	<input type="hidden" name='selectedMess' value='<?php echo $selectedMess;?>'/>	


	<div class="row"></div>	
	
	<div class="row">
		<div class="col s4">
			<span class="blue-text text-darken-2">Selected Items</span>
		</div>
		<div class="col s4">
			<span class="blue-text text-darken-2">Quantity Available</span>
		</div>
		<div class="col s4">
			<span class="blue-text text-darken-2">Selected Qunatity(Kg/L)</span>
		</div>
	</div>


	<div class="row"></div>	
	<?php
		if(isset($selectedItems) && count($selectedItems) >0)
		{
			for($i=0;$i<count($selectedItems);$i++)
			{
		?>
	<div class="row">
		<div class="input-field col s4">
			<span class="blue-text text-darken-2"><?php echo $selectedItems[$i];?></span>
			<input type="hidden" name='selectedItems[]' value='<?php echo $selectedItems[$i];?>'/>
		</div>
		<div class="input-field col s4">
			<span class="blue-text text-darken-2"><?php echo $quantityAvailable[$i];?></span>
			<input type="hidden" name='quantityAvailable[]' value='<?php echo $quantityAvailable[$i];?>'/>
			<input type="hidden" name='latestRate[]' value='<?php echo $latestRate[$i];?>'/>
		</div>
		<div class="input-field col s4">
			<input type="text" name="selectedQuantity[]" value=""/>
			<label for="last_name">Enter Quantity</label>
		</div>
	</div>
	

	<?php
			}

		}		
	?>
<!--
<button type="submit" name="submit">Submit</button>
<button type="cancel" name="cancel">Cancel</button>
-->
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
