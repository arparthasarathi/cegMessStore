<script type = 'text/javascript'>
$(document).ready(function() {
		var max_fields      = 10; //maximum input boxes allowed
		var wrapper         = $(".input_fields_wrap"); //Fields wrapper
		var add_button      = $(".add_field_button"); //Add button ID

		var x = 1; //initlal text box count
		$(add_button).click(function(e){ //on add input button click
			e.preventDefault();
			var num = $("#numberToAdd").val();
			while(num > 0) {
			$(wrapper).append(

				'<div class="row">'+
				'<div class="col s2">'+
				'<span class="label label-default">Item Name</span>'+
				'<input type="text" name="itemName[]"/>'+
				'</div>'+
				'<div class="col s2">'+
				'<span class="label label-default">Item Rate</span>'+
				'<input type="text" name="itemRate[]"/>'+
				'</div>'+
				'<div class="col s2">'+
				'<span class="label label-default">Quantity Available</span>'+
				'<input type="text" name="quantityAvailable[]" />'+
				'</div>'+
				'<div class="col s2">'+
				'<span class="label label-default">Minimum Quantity</span>'+
				'<input type="text" name="minimumQuantity[]"/>'+
				'</div>'+
				'<div class="col s2">'+
				'<a href="#" class="remove_field">'+
				'<span class="glyphicon glyphicon-remove" aria-hidden="true">'+
				'</span>'+
				'</a>'+
				'</div>'+
				'</div>'); //add input box

			num--;
			}
			});

		$(wrapper).on("click",".remove_field", function(e){ //user click on remove text

				e.preventDefault(); $(this).parent('div').parent('div').remove(); x--;
				})
});
</script>
<div class="row"></div>
<?php echo form_open('items/add_item') ?>
<div class="row">
	<div class="col s12 offset-s2">	
		<div class="input_fields_wrap">

			<?php 
			if(isset($itemName) && (count($itemName) > 0))
			{
				for($i=0;$i<count($itemName);$i++)
				{
				?>
				
				<div class="row">
				<div class="col s2">
					<span class="label label-default">Item Name</span>
					<input type="text" name="itemName[]" value='<?php echo $itemName[$i];?>'/>
				</div>
				<div class="col s2">
					<span class="label label-default">Item Rate</span>
					<input type="text" name="itemRate[]" value='<?php echo $itemRate[$i];?>'/>
				</div>
				<div class="col s2">
					<span class="label label-default">Quantity Available</span>
					<input type="text" name="quantityAvailable[]" value='<?php echo $quantityAvailable[$i];?>'/>
				</div>		
				<div class="col s2">
					<span class="label label-default">Minimum Quantity</span>
					<input type="text" name="minimumQuantity[]" value='<?php echo $minimumQuantity[$i];?>'/>
				</div>		
	
		<?php if($i > 0) { ?>
				<div class="col s2">
					<a href="#" class="remove_field">
					<span class="glyphicon glyphicon-remove" aria-hidden="true">
					</span>
					</a>
				</div>
				</div>
			<?php
				}
				}
			}	
			else
			{
			?>
			<div class="row">
				<div class="col s2">	
					<span class="label label-default">Item Name</span>
					<input type="text" name="itemName[]"/>
				</div>
				<div class="col s2">
					<span class="label label-default">Item Rate</span>
					<input type="text" name="itemRate[]"/>
				</div>	
			<div class="col s2">
					<span class="label label-default">Quantity Available</span>
					<input type="text" name="quantityAvailable[]"/>
				</div>
					<div class="col s2">
					<span class="label label-default">Minimum Quantity</span>
					<input type="text" name="minimumQuantity[]"/>
				</div>
	
		</div>
			<div class="row">
				<div class="col s2">	
					<span class="label label-default">Item Name</span>
					<input type="text" name="itemName[]"/>
				</div>
				<div class="col s2">
					<span class="label label-default">Item Rate</span>
					<input type="text" name="itemRate[]"/>
				</div>
				<div class="col s2">
					<span class="label label-default">Quantity Available</span>
					<input type="text" name="quantityAvailable[]"/>
				</div>
					<div class="col s2">
					<span class="label label-default">Minimum Quantity</span>
					<input type="text" name="minimumQuantity[]"/>
				</div>
	
				<div class="col s2">
                                <a href="#" class="remove_field">
                                <span class="glyphicon glyphicon-remove" aria-hidden="true">
                                </span>
                                </a>
                                </div>
		
			</div>
			<div class="row">
				<div class="col s2">	
					<span class="label label-default">Item Name</span>
					<input type="text" name="itemName[]"/>
				</div>
				<div class="col s2">
					<span class="label label-default">Item Rate</span>
					<input type="text" name="itemRate[]"/>
				</div>
				<div class="col s2">
					<span class="label label-default">Quantity Available</span>
					<input type="text" name="quantityAvailable[]"/>
				</div>
					<div class="col s2">
					<span class="label label-default">Minimum Quantity</span>
					<input type="text" name="minimumQuantity[]"/>
				</div>
	
				<div class="col s2">
                                <a href="#" class="remove_field">
                                <span class="glyphicon glyphicon-remove" aria-hidden="true">
                                </span>
                                </a>
                                </div>
		
			</div>
			<div class="row">
				<div class="col s2">	
					<span class="label label-default">Item Name</span>
					<input type="text" name="itemName[]"/>
				</div>
				<div class="col s2">
					<span class="label label-default">Item Rate</span>
					<input type="text" name="itemRate[]"/>
				</div>
				<div class="col s2">
					<span class="label label-default">Quantity Available</span>
					<input type="text" name="quantityAvailable[]"/>
				</div>	
				<div class="col s2">
					<span class="label label-default">Minimum Quantity</span>
					<input type="text" name="minimumQuantity[]"/>
				</div>
	
				<div class="col s2">
                                <a href="#" class="remove_field">
                                <span class="glyphicon glyphicon-remove" aria-hidden="true">
                                </span>
                                </a>
                                </div>
		
			</div>
			<?php
			}
			?>
			
		</div>
	</div>
</div>
<div class="row">
	<div class="col s3">
	 <a class="btn waves-effect waves-light btn-large add_field_button" 
					value="submit" type="submit" name="add_field_button">
	Add More Items
	</a>
		</div>
	<div class="col s3">
		<input type = "text" name="numberToAdd" id="numberToAdd"/>
		<label for="numberToAdd">Number of fields</label>
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
