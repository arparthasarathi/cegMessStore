<?php echo validation_errors(); ?>

<?php echo form_open('items/add_confirmation') ?>
<div>
<div>
<table>
<?php 
for($i=0;$i<count($itemName);$i++)
{
?>
<tr>
<td>
<h4><input type='hidden' name='itemName[]' value='<?php echo $itemName[$i];?>'/>
<?php echo $itemName[$i];?>
</h4>
</td>
<td>
<h4><input type='hidden' name='itemRate[]' value='<?php echo $itemRate[$i];?>'/>
<?php echo "Rs.".$itemRate[$i]."/-";?>
</h4>
</td>
<td>
<h4><input type='hidden' name='quantityAvailable[]' value='<?php echo $quantityAvailable[$i];?>'/>
<?php echo $quantityAvailable[$i];?>
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
