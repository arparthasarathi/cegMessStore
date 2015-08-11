<script>
   $(document).ready(function() {


         $( "#paymentDate" ).pickadate();
   });
</script>

<div class='col s12 offset-s2'>
   <form name='update_payment_details' action='<?php echo base_url()."orders/update_payment_details";?>' method='post'>
      <div class='row'>
         <div class='col s12'>
            <h5><span class='black-text text-darken-2'>
                  <input type='hidden' name='orderID' value='<?php echo $orderID[0];?>'/>
                  <?php echo $orderID[0]; ?>
               </span>
            </h5>
         </div>
      </div>
      <div class='row'>
         <div class='col s6'>
            <h5><span class='black-text text-darken-2'>
                  <?php echo $vendorName[0]; ?>
               </span>
            </h5>
         </div>
      </div>
      <?php
         { 
         ?>
         <div class='row'>
            <div class='input-field col s8'>
               <input type='date' name='paymentDate' id='paymentDate' class='datepicker'/>
               <label for='paymentDate'>Payment Date</label>
            </div>
         </div>
         <div class='row'>
            <div class='input-field col s8'>
               <input type='radio' name='paymentMode' id='paymentMode1' value='cheque'/>
               <label for='paymentMode1'>Cheque</label>
               <input type='radio' name='paymentMode' id='paymentMode2' value='dd'/>
               <label for='paymentMode2'>DD</label>
            </div>
         </div>

         <div class='row'>
            <div class='input-field col s8'>
               <input type='text' name='paymentNumber' id='paymentNumber'/>
               <label for='paymentNumber'>Cheque/DD Number</label>
            </div>
         </div>
         <div class='row'>
            <div class='input-field col s8'>
               <input type='text' name='bankName' id='bankName'/>
               <label for='bankName'>Bank Name</label>
            </div>
         </div>
         <div class='row'>
            <div class='input-field col s8'>
               <input type='text' name='inFavourOf' id='inFavourOf'/>
               <label for='inFavourOf'>In Favour Of</label>
            </div>

         </div>
      </div>
      <?php
      }
   ?>
   <div class="row">
      <div class="col s8 offset-s3">

         <button class="btn waves-effect waves-light btn-large" 
            value="submit" type="submit" name="submit">
            Submit
            <i class="glyphicon glyphicon-chevron-right"></i>
         </button>

         <button class="btn waves-effect waves-light red darken-1 btn-large" 
            value="cancel" type="reset" name="cancel">
            Cancel
            <i class="glyphicon glyphicon-remove"></i>
         </button>
      </div>
   </div>

</form>
</div>
</div>

