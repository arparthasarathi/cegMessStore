<?php echo form_open('orders/payment_confirmation') ?>
<div class="row">
   <div class="col s6"> 
      <span class="blue-text txt-darken-2">Order Number:</span>
   </div>
   <div class="col s6">
      <span class="blue-text text-darken-2"><?php echo $orderID;?></span>
      <input type='hidden' name='orderID' id = 'orderID'value='<?php echo $orderID;?>'/>
   </div>

</div>
<div class="row">
   <div class="col s6">
      <span class="blue-text text-darken-2">Payment Date</span>
   </div>
   <div class="col s6">
      <span class="blue-text text-darken-2"><?php echo $paymentDate;?></span>
      <input type='hidden' name='paymentDate' value='<?php echo $paymentDate;?>'/>
   </div>
</div>
<div class="row">
   <div class="col s6">
      <span class="blue-text text-darken-2">Payment Mode</span>
   </div>
   <div class="col s6">
      <span class="blue-text text-darken-2"><?php echo $paymentMode;?></span>
      <input type='hidden' name='paymentMode' value='<?php echo $paymentMode;?>'/>
   </div>
</div>
<div class="row">
   <div class="col s6">
      <span class="blue-text text-darken-2">Payment Number</span>
   </div>
   <div class="col s6">
      <span class="blue-text text-darken-2"><?php echo $paymentNumber;?></span>
      <input type='hidden' name='paymentNumber' value='<?php echo $paymentNumber;?>'/>
   </div>
</div>
<div class="row">
   <div class="col s6">
      <span class="blue-text text-darken-2">Bank Name</span>
   </div>
   <div class="col s6">
      <span class="blue-text text-darken-2"><?php echo $bankName;?></span>
      <input type='hidden' name='bankName' value='<?php echo $bankName;?>'/>
   </div>
</div>
<div class="row">
   <div class="col s6">
      <span class="blue-text text-darken-2">In Favour Of</span>
   </div>
   <div class="col s6">
      <span class="blue-text text-darken-2"><?php echo $inFavourOf;?></span>
      <input type='hidden' name='inFavourOf' value='<?php echo $inFavourOf;?>'/>
   </div>
</div>




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

