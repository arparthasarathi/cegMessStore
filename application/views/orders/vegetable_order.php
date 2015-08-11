<?php
$decodedTableData = json_decode($tableData,true);
$size = count($decodedTableData['itemNames']);
if(isset($selectedItems))
$misc = array_values(array_diff($selectedItems,$decodedTableData['itemNames']));
?>

<script>
   var selectedItems = null;
   selectedItems = <?php if(isset($selectedItems)) echo json_encode($selectedItems); else echo json_encode(NULL);?>;
   var selectedQuantity = <?php if(isset($selectedQuantity)) echo json_encode($selectedQuantity);
      else echo json_encode(NULL);?>;
   var latestRate = <?php if(isset($latestRate)) echo json_encode($latestRate);
         else echo json_encode(NULL);?>;
   var misc = <?php if(isset($misc)) echo json_encode($misc); else echo json_encode(NULL);?>

         $(document).ready(function() {
               $("#receivedDate").pickadate();
               var tabindex = 1;
               $('input,select,button,date').each(function() {
                     if (this.type != "hidden") {
                           var $input = $(this);
                           $input.attr("tabindex", tabindex);
                           tabindex++;
                     }
               });

               $('input[name="selectedQuantity[]"]').keyup(function() {
                     var id = (this.id).slice(3);
                     id = '#rate'+id;
                     console.log(id);
                     if (this.value == '') {
                           $(id).removeAttr('required');
                        } else {
                           $(id).attr('required','true');

                     }
               });

               $('input[name="latestRate[]"]').keyup(function() {
                     var id = (this.id).slice(4);
                     id = '#txt'+id;
                     console.log(id);
                     if (this.value == '') {
                           $(id).removeAttr('required');
                        } else {
                           $(id).attr('required','true');

                     }
               });

               if(selectedItems)
               {
                     var selectedVendor = <?php if(isset($selectedVendor)) echo json_encode($selectedVendor);
                        else echo json_encode(NULL);?>;
                        var selectedMess = <?php if(isset($selectedMess)) echo json_encode($selectedMess);
                                                else echo json_encode(NULL);?>;

                        var billNo = <?php if(isset($billNo)) echo json_encode($billNo);
                           else echo json_encode(NULL);?>;

                           var receivedDate = <?php if(isset($receivedDate)) echo json_encode($receivedDate);
                              else echo json_encode(NULL);?>;


                              $("select#selectedVendor").find('option[value="' + selectedVendor + '"]').prop('selected', true);
                              $("select#selectedMess").find('option[value="' + selectedMess + '"]').prop('selected', true);
                              $("#receivedDate").pickadate('picker').set('select',new Date(receivedDate));
                              $("#billNo").attr('value',billNo);
                              var count = 0;
                              for(var i=0;i<selectedItems.length;i++)
                              {
                                    var quantityID = "#txt"+selectedItems[i];
                                    var latestRateID = "#rate"+selectedItems[i];
                                    var quantity = selectedQuantity[i];
                                    var latestRateEach = latestRate[i];

                                    if(i >= (selectedItems.length - misc.length))
                                    {

                                        var miscID = "#MISCELLANEOUS"+count;
                                        var miscQuantityID = "#txtMISCELLANEOUS"+count;
                                        var miscLatestRateID = "#rateMISCELLANEOUS"+count;
                                        $(miscQuantityID).attr('value',quantity);
                                        $(miscQuantityID).css('background-color','#ffff00');
                                        $(miscLatestRateID).attr('value',latestRateEach);                                                                       $(miscLatestRateID).css('background-color','#ffff00');
                                        $(miscID).attr('value',misc[count]);
                                        count++;
                                  }
                                  else{
                                    $(quantityID).attr('value',quantity);
                                    $(quantityID).css('background-color','#ffff00');
                                    $(latestRateID).attr('value',latestRateEach);
                                    $(latestRateID).css('background-color','#ffff00');
                                }
                              }
                        }



                  });
               </script>
               <form name="selection" method="post"  action="vegetable_order">
                  <div class="row">

                     <div class="input-field col s6">
                        <select class="browser-default" name="selectedVendor" value = "<?php echo isset($selectedVendor) ? $selectedVendor : "";?>" id="selectedVendor" required>
                           <option value=''>Select Vendor</option>
                           <?php
                              foreach($vendors as $each)	
                              {
                              ?>
                              <option value='<?php echo $each;?>' id='<?php echo $each;?>'><?php echo $each;?></option>
                              <?php
                              }	
                           ?>
                        </select>
                     </div>

                     <div class="input-field col s6">
                        <select class="browser-default" name="selectedMess" value = "<?php echo isset($selectedMess) ? $selectedMess : "";?>" id="selectedMess" required>
                           <option value=''>Select Mess</option>
                           <?php
                              foreach($messTypes as $each)
                              {
                              ?>
                              <option value='<?php echo $each;?>' id='<?php echo $each;?>'><?php echo $each;?></option>
                              <?php
                              }
                           ?>
                        </select>
                     </div>
                  </div>
                  <div class='row'>
                     <div class="input-field col s6">
                        <input type='text'  name='billNo' id='billNo' required placeholder='Enter Bill Number'/>
                     </div>

                     <div class = "input-field col s6">
                        <input type="date" class="datepicker" id="receivedDate" name="receivedDate" required placeholder='Select Date'/>
                     </div>


                  </div>
                  <div class='row'>
                     <div class='col s4'>
                        <span class='blue-text text-darken-2'>Item Name</span>
                     </div>


                     <div class='col s4'>
                        <span class='blue-text text-darken-2'>Quantity Ordered(Kg/L)</span>
                     </div>


                     <div class='col s4'>
                        <span class='blue-text text-darken-2'>Latest Rate(Per Kg/L)</span>
                     </div>

                  </div>


                  <?php
                     for($i=0;$i<count($decodedTableData['itemNames']);$i++)
                     {
                     ?>
                     <div class="row margin_row">
                        <div class = "col s4">
                           <p>
                           <input type='text' value='<?php echo $decodedTableData['itemNames'][$i];?>' 
                           id='<?php echo $decodedTableData['itemNames'][$i];?>' 
                           readonly name='selectedItems[]'/>
                           </p>
                        </div>
                        <div class="col s4">
                           <input type="text" name="selectedQuantity[]" value="" id='<?php echo 'txt'.$decodedTableData['itemNames'][$i];?>' placeholder='Enter Quantity' onchange="javascript:change_color(this)"/>
                        </div>

                        <div class="col s4">
                           <input type="text" name="latestRate[]" value="" id='<?php echo 'rate'.$decodedTableData['itemNames'][$i];?>' placeholder='Enter Rate' onchange="javascript:change_color(this)"/>
                        </div>

                     </div>
                     <?php
                     }
                  ?>

                  <?php
                     for($i=0;$i<5;$i++)
                     {
                     ?>
                     <div class="row margin_row">
                        <div class = "col s4">
                           <p>
                           <input type='text' id= '<?php echo 'MISCELLANEOUS'.$i;?>'  name='selectedItems[]' placeholder='<?php echo 'MISCELLANEOUS '.($i+1)?>' onchange="javascript:change_color(this)"/>
                           </p>
                        </div>
                        <div class="col s4">
                           <input type="text" name="selectedQuantity[]" value="" id='<?php echo 'txtMISCELLANEOUS'.$i;?>' placeholder='Enter Quantity'/>
                        </div>

                        <div class="col s4">
                           <input type="text" name="latestRate[]" value="" id='<?php echo 'rateMISCELLANEOUS'.$i;?>' placeholder='Enter Rate'/>
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
                           value="cancel" type="cancel" name="cancel">
                           Cancel
                           <i class="glyphicon glyphicon-remove"></i>
                        </button>
                     </div>
                  </div>
               </form> 
            </div>
