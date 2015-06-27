<?php echo validation_errors(); ?>
<?php
if(isset($msg)) echo $msg;
if(isset($error))
{
foreach($error as $each)
echo $each;
} 
?>

<?php echo form_open('items/return_confirmation') ?>
<div>
<div>
<table>
<tr>
<td>
<h4><input type='hidden' name='selectedMess' value='<?php echo $selectedMess;?>'/>
<?php echo $selectedMess;?>
</h4>
</td>
</tr>
<?php 
for($i=0;$i<count($selectedItems);$i++)
{
?>
<tr>
<td>
<h4><input type='hidden' name='selectedItems[]' value='<?php echo $selectedItems[$i];?>'/>
<?php echo $selectedItems[$i];?>
</h4>
</td>
<input type="hidden" name='quantitySupplied[]' value='<?php echo $quantitySupplied[$i];?>'/>

<input type="hidden" name='quantityAvailable[]' value='<?php echo $quantityAvailable[$i];?>'/>
<input type="hidden" name='latestRate[]' value='<?php echo $latestRate[$i];?>'/>
<td>
<h4><input type='hidden' name='selectedQuantity[]' value='<?php echo $selectedQuantity[$i];?>'/>
<?php echo $selectedQuantity[$i]." kg/l";?>
</h4>
</td>
</tr>
<?php
}
?>
</table>
<br/><br/>	
<br/><br/>
<button type="submit" name="submit">Confirm</button>
<button type="cancel" name="cancel" onclick="window.history.back()">Back</button>
</div>
</div>
</form>
