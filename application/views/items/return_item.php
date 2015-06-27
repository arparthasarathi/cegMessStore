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
		$(data).each(function(index){
			$(providedItems).append(
			$('<option></option>').val(data[index]).html(data[index])
			);
		});
            }
         }); 

    });
}) 
</script>
<style type="text/css">
select {
width: 200px;
float: left;
}
.controls {
width: 40px;
float: left;
margin: 10px;
}
.controls a {
	background-color: #222222;
	border-radius: 4px;
border: 2px solid #000;
color: #ffffff;
padding: 2px;
	 font-size: 14px;
	 text-decoration: none;
display: inline-block;
	 text-align: center;
margin: 5px;
width: 20px;
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

?>
<form name="selection" method="post"  action="return_item" onsubmit="return selectAll()"> 
<select id="selectedMess" name="selectedMess" value = "<?php echo isset($selectedMess) ? $selectedMess : "";?>" required>
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
<select multiple size="25" name="issuedItems[]" id="issuedItems"></select>
<div class="controls"> 
<a href="javascript:moveAll('issuedItems', 'to')">&gt;&gt;</a> 
<a href="javascript:moveSelected('issuedItems', 'to')">&gt;</a> 
<a href="javascript:moveSelected('to', 'issuedItems')">&lt;</a> 
<a href="javascript:moveAll('to', 'issuedItems')" href="#">&lt;&lt;</a> </div>
<select multiple id="to" size=25 name="selectedItems[]"></select>
<br><br>
<button type="submit" name="submit">Submit</button>
<button type="cancel" name="cancel">Cancel</button>
</form> 
