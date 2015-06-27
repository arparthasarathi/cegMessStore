<script type = 'text/javascript'>
$(document).ready(function() {
		var max_fields      = 10; //maximum input boxes allowed
		var wrapper         = $(".input_fields_wrap"); //Fields wrapper
		var add_button      = $(".add_field_button"); //Add button ID

		var x = 1; //initlal text box count
		$(add_button).click(function(e){ //on add input button click
			e.preventDefault();
			if(x < max_fields){ //max input box allowed
			x++; //text box increment
			$(wrapper).append(
				'<div>'+
				'<span class="label label-default">Item Name</span>'+
				'<input type="text" name="itemName[]"/>'+
				'<span class="label label-default">Item Rate</span>'+
				'<input type="text" name="itemRate[]"/>'+
				'<span class="label label-default">Quantity Available</span>'+
				'<input type="text" name="quantityAvailable[]" />'+
				'<a href="#" class="remove_field">'+
				'<span class="glyphicon glyphicon-remove" aria-hidden="true">'+
				'</span>'+
				'</a>'+
				'</div>'); //add input box
			}
			});

		$(wrapper).on("click",".remove_field", function(e){ //user click on remove text
			
				e.preventDefault(); $(this).parent('div').remove(); x--;
				})
});
</script>
<?php echo $title;?>
<?php echo validation_errors(); ?>
<?php 
if(isset($msg))
print_r($msg);
?>

<?php echo form_open('items/add_item') ?>
<div>
<div class="input_fields_wrap">

<?php 
if(isset($itemName) && (count($itemName) > 0))
{
for($i=0;$i<count($itemName);$i++)
{
?>
<div>
<span class="label label-default">Item Name</span>
<input type="text" name="itemName[]" value='<?php echo $itemName[$i];?>'/>
<span class="label label-default">Item Rate</span>
<input type="text" name="itemRate[]" value='<?php echo $itemRate[$i];?>'/>
<span class="label label-default">Quantity Available</span>
<input type="text" name="quantityAvailable[]" value='<?php echo $quantityAvailable[$i];?>'/>
<?php if($i > 0) { ?>
<a href="#" class="remove_field">
<span class="glyphicon glyphicon-remove" aria-hidden="true">
</span>
</a>
</div>
<br/>
<?php
}
}
}
else
{
?>
<span class="label label-default">Item Name</span>
<input type="text" name="itemName[]"/>
<span class="label label-default">Item Rate</span>
<input type="text" name="itemRate[]"/>
<span class="label label-default">Quantity Available</span>
<input type="text" name="quantityAvailable[]"/>
<?php
}
?>
</div>
<br/><br/>	
<button class="add_field_button">Add More Items</button>
<br/><br/>
<button type="submit" name="submit">Submit</button>
<button type="cancel" name="cancel">Cancel</button>
</div>
</form>
