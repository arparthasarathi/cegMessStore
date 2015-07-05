<script>
$(function(){
    $('#selectedMess').change(function(){
	
         var messName = encodeURIComponent($(this).val());
	 var providedItems = $("#issuedItems");
	$(providedItems).html("");
         $.ajax({
            url : 'getMessConsumptionForToday/'+messName,
            type : 'GET'  ,
	    dataType : 'json',
            success : function(data){
		console.log(data);
		$(data.itemNames).each(function(index){

		var divRow = $(document.createElement('div')).attr({
					class: 'row'
				});

		var divSelected = $(document.createElement('div')).attr({
					class: 'input-field col s4'
					});

		$(divRow).append(divSelected);

		$(divSelected).append(
			$(document.createElement('input')).attr({
         			  id:    data.itemNames[index]
			          ,name:  'selectedItems[]'
			          ,value: data.itemNames[index]
			          ,type:  'checkbox'
      				 })
			);

		$(divSelected).append(
			$(document.createElement('label')).attr({
         			  for:    data.itemNames[index]
			          }).html(data.itemNames[index])
			);


		
		var divSelected1 = $(document.createElement('div')).attr({
					class: 'input-field col s4'
					});
	
		




		$(divRow).append(divSelected1);

                $(divSelected1).append(
                        $(document.createElement('label')).attr({
				class: 'blue-text text-darken-2'
                                 }).html(data.quantitySupplied[index])
                        );

		 $(divSelected1).append(
                        $(document.createElement('input')).attr({
                                  id:    data.quantitySupplied[index]
                                  ,name:  'quantitySupplied[]'
                                  ,value: data.quantitySupplied[index]
                                  ,type:  'hidden'
                                 })
                        );
		
		 $(divSelected1).append(
                        $(document.createElement('input')).attr({
                                  id:    data.latestRate[index]
                                  ,name:  'latestRate[]'
                                  ,value: data.latestRate[index]
                                  ,type:  'hidden'
                                 })
                        );

		var divSelected2 = $(document.createElement('div')).attr({
					class: 'input-field col s4'
					});
	


		$(divRow).append(divSelected2);

                $(divSelected2).append(
                        $(document.createElement('input')).attr({
				  id : 'txt'+data.itemNames[index]
                                  ,name:  'selectedQuantity[]'
                                  ,type:  'text'
                                 })
                        );
		$(divSelected2).append(
                        $(document.createElement('label')).attr({
                                  for:   'txt'+data.itemNames[index]
                                  }).html('Enter Quantity')
                                 
                        );



		$(providedItems).append(divRow);

		var txtBox = document.getElementById('txt'+data.itemNames[index]);
		txtBox.disabled = true;
		var chkBox = document.getElementById(data.itemNames[index]);
		chkBox.onchange = function() {
			txtBox.disabled=!chkBox.checked;
			}

		});
            }
         }); 

    });
}) 
</script>
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
	<form name="selection" method="post"  action="return_item"> 
	<div class="row">
		<div class="input-field col s6 offset-s3">
			<select class="browser-default" id="selectedMess" name="selectedMess" value = "<?php echo isset($selectedMess) ? $selectedMess : "";?>" required>
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
			<div id="issuedItems">
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
