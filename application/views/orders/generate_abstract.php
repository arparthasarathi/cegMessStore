<style>

   tr,th{
         border: 1px solid black;
   } 
</style>
<script>
   $(document).ready(function() {


         $( "#paymentDate" ).pickadate();
   });

</script>

<div class='col s12 offset-s2'>
   <form>
      <div id='abstract'>
         <div class='row'>
            <div class='col s6'>
               <h5><span class='black-text text-darken-2'>
                     <?php echo $vendorName; ?>
                  </span>
               </h5>
            </div>
         </div>
         <table>
            <?php
               for($i=0;$i<count($orderIDs);$i++){ 
               ?>
               <tr>
                  <th>
                     <span class='black-text text-darken-2'>
                        <?php echo $i+1; ?>
                     </span>
                  </th>
                  <th>
                     <span class='black-text text-darken-2'>
                        <?php echo "Provision & Vegetable"; ?>
                     </span>
                  </th>
                  <th>
                     <span class='black-text text-darken-2'>
                        <?php echo $receivedDates[$i]; ?>
                     </span>
                  </th>
                  <th>
                     <span class='black-text text-darken-2'>
                        <?php echo $billNos[$i]; ?>
                     </span>
                  </th>
                  <th>
                     <span class='black-text text-darken-2'>
                        <?php echo $totalAmount[$i]; ?>
                     </span>
                  </th>
               </tr>

               <?php
               }
            ?>
         </table>
      </div>
      <div class="row"></div>
      <div class="row">
         <div class="col s8 offset-s3">

            <a class="btn waves-effect waves-light btn-large" href="javascript:printPDF('abstract');"
               value="submit" type="submit" name="submit">
               Print
               <i class="glyphicon glyphicon-chevron-right"></i>
            </a>

            <a class="btn waves-effect waves-light red darken-1 btn-large" href="order_history"
               value="cancel" type="reset" name="cancel">
               Cancel
               <i class="glyphicon glyphicon-remove"></i>
            </a>
         </div>
      </div>

   </form>
</div>
</div>

