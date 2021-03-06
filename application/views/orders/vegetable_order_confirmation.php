<?php echo form_open('orders/vegetable_order_confirmation') ?>
<div class="row">
   <div class="col s6"> 
      <span class="blue-text txt-darken-2">Order Number:</span>
   </div>
   <div class="col s6">
      <span class="blue-text text-darken-2 order_number"></span>
      <input type='hidden' name='orderNo' id = 'orderNo'/>
   </div>

</div>
<div class="row">
   <div class="col s6">
      <span class="blue-text text-darken-2">Selected Vendor</span>
   </div>
   <div class="col s6">
      <span class="blue-text text-darken-2"><?php echo $selectedVendor;?></span>
      <input type='hidden' name='selectedVendor' value='<?php echo $selectedVendor;?>'/>
   </div>
</div>
<div class="row">
   <div class="col s6">
      <span class="blue-text text-darken-2">Selected Mess</span>
   </div>
   <div class="col s6">
      <span class="blue-text text-darken-2"><?php echo $selectedMess;?></span>
      <input type='hidden' name='selectedMess' value='<?php echo $selectedMess;?>'/>
   </div>
</div>
<div class="row">
   <div class="col s3">
      <span class="blue-text text-darken-2">Bill Number</span>
   </div>
   <div class="col s3">
      <span class="blue-text text-darken-2"><?php echo $billNo;?></span>
      <input type='hidden' name='billNo' value='<?php echo $billNo;?>'/>
   </div>
   <div class="col s3">
      <span class="blue-text text-darken-2">Received Date</span>
   </div>
   <div class="col s3">
      <span class="blue-text text-darken-2"><?php echo $receivedDate;?></span>
      <input type='hidden' name='receivedDate' value='<?php echo $receivedDate;?>'/>
   </div>

</div>


<div class="row">
   <div class="col s4">
      <span class="blue-text text-darken-2">Selected Items</span>
   </div>
   <div class="col s4">
      <span class="blue-text text-darken-2">Quantity Received(Kg/L)</span>
   </div>
   <div class="col s4">
      <span class="blue-text text-darken-2">Rate(Per Kg/L)</span>
   </div>
</div>


<?php 
   for($i=0;$i<count($selectedItems);$i++)
   {				
   ?>
   <div class="row">
      <div class="blue-text text-darken-2 col s4">
         <input type='hidden' name='selectedItems[]' value='<?php echo $selectedItems[$i];?>'/>
         <?php echo $selectedItems[$i];?>
      </div>


      <div class="blue-text text-darken-2 col s4">
         <input type='hidden' name='selectedQuantity[]'
         value='<?php echo $selectedQuantity[$i];?>'/>
         <input type='hidden' name='quantityAvailable[]'
         value='<?php echo $quantityAvailable[$i];?>'/>

         <?php echo $selectedQuantity[$i];?>
      </div>

      <div class="blue-text text-darken-2 col s4">
         <input type='hidden' name='latestRate[]'
         value='<?php echo $latestRate[$i];?>'/>
         <?php echo $latestRate[$i];?>
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
<script type='text/javascript'>
   var date = new Date();
   var components = [
   date.getFullYear(),
   date.getMonth()+1,
   date.getDate(),
   date.getHours(),
   date.getMinutes(),
   date.getSeconds(),
   ];

   var id = components.join("-");
   id = 'VEG'+id;
   $('.order_number').html(id);
   $('#orderNo').val(id);
</script>

