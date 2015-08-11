</form>
<?php echo form_open('items/add_vegetable_confirmation') ?>
<div class="row"></div>
<div class="row">
   <div class="col s6">
      <span class="blue-text text-darken-2">Item Name</span>
   </div>
</div>


<?php 
   for($i=0;$i<count($itemName);$i++)
   {				
   ?>
   <div class="row">
      <div class="blue-text text-darken-2 col s6">
         <input type='hidden' name='itemName[]' value='<?php echo $itemName[$i];?>'/>
         <?php echo $itemName[$i];?>
      </div>

   </div>
   <?php
   }
?>
<div class="row">
   <div class="col s8 offset-s3">

      <button class="btn waves-effect waves-light btn-large" 
         value="submit" type="submit" name="submit">
         Confirm
         <i class="glyphicon glyphicon-chevron-right"></i>
      </button>

      <button class="btn waves-effect waves-light red darken-1 btn-large" 
         value="cancel" type="cancel" name="cancel">
         Back
         <i class="glyphicon glyphicon-remove"></i>
      </button>
   </div>
</div>



</form>
</div>
