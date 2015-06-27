<?php echo validation_errors(); 
?>
<?php 
if(isset($msg)) echo $msg;
if(isset($error))
{
foreach($error as $each)
echo $each;
}
?>
<form method='post' action='return_quantity'>

<span class="label label-default">Selected Mess</span>
<span class="label label-default"><?php echo $selectedMess;?></span>
<input type="hidden" name='selectedMess' value='<?php echo $selectedMess;?>'/>

<span class="label label-default">Selected Items</span>
<span class="label label-default">Quantity Supplied</span>
<span class="label label-default">Selected Qunatity(Kg/L)</span>
<br/>
<?php
if(isset($selectedItems) && count($selectedItems) >0)
{
for($i=0;$i<count($selectedItems);$i++)
{
?>
<span class="label label-default"><?php echo $selectedItems[$i];?></span>
<input type="hidden" name='selectedItems[]' value='<?php echo $selectedItems[$i];?>'/>

<span class="label label-default"><?php echo $quantitySupplied[$i];?></span>

<input type="hidden" name='quantitySupplied[]' value='<?php echo $quantitySupplied[$i];?>'/>


<input type="hidden" name='quantityAvailable[]' value='<?php echo $quantityAvailable[$i];?>'/>
<input type="hidden" name='latestRate[]' value='<?php echo $latestRate[$i];?>'/>

<span><input type="text" name="selectedQuantity[]" required 
value="<?php if(isset($selectedQuantity[$i]) && $selectedQuantity[$i] != "") echo $selectedQuantity[$i];?>"/></span>
<br/>
<?php
}

}
?>
<button type="submit" name="submit">Submit</button>
<button type="cancel" name="cancel">Cancel</button>
</form>

