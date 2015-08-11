<script>
   $(document).ready(function() {




         $('#add_vendor').hide();
         $('#print-report').click(function () {
               var doc = new jsPDF();
               doc.addHTML(document.body,function() {
                     doc.autoPrint();
                     doc.save('test.pdf');
               });

         });

         get_pending_payments();
         $('#add_button').click(function () {
               $('#add_vendor').show();
         });

   });
</script>


<script>


   function get_pending_payments(){
         $.ajax({
               url : 'get_vegetable_pending_payments/',
               type: 'GET',
               dataType: 'json',
               success : function(data){
                     console.log(data);
                     var jsonObj = data;
                     console.log(data);
                     var htmlContents = "";	
                     for (i = 0; i < jsonObj.length; i++) {

                           var orderID = jsonObj[i].orderID;
                           var vendorName = jsonObj[i].vendorName;
                           var receivedDate = jsonObj[i].receivedDate;
                           var items = jsonObj[i].items;
                           htmlContents += 		  
                           '<div class= "row">'+
                              '<div class = "col s12">'+
                                 '<div class = "col s1">'+
                                    '<input type="checkbox" name="selectedOrders[]" id="'+orderID+'" value="'+orderID+'"/>'+
                                    '<label for="'+orderID+'"></label>'+
                                    '</div>'+
                                 '<div class = "col s3">'+
                                    '<span class="blue-text text-darken-2">'+
                                       orderID +
                                       '</span>'+
                                    '</div>'+
                                 '<div class = "col s3">'+
                                    '<span class="blue-text text-darken-2">'+
                                       vendorName +
                                       '</span>'+
                                    '</div>'+
                                 '<div class = "col s3">'+
                                    '<span class="blue-text text-darken-2">'+
                                       receivedDate +
                                       '</span>'+
                                    '</div>'+
                                 '</div>'+
                              '</div>';
                     }
                     $("div#vendorsList").html(htmlContents);
                     $('.collapsible').collapsible({
                           accordion : false // A setting that changes the collapsible behavior to expandable instead of the default accordion style
                     });

               }

         });
      }


   </script>
   <style type="text/css">
      .controls {
            margin: 75px;
         }

         select {
               border-size: 2px;
               border-color: #000066;
               border-radius: 4px;
            }

            .controls a {
                  border-radius: 4px;
                  font-size: 15px;
                  text-align: center;
                  width: 100px;
               }


            </style>

            <form action="generate_vegetable_abstract" method="post">

               <div id="reportArea" value='reportArea'>



                  <div class="row">
                     <div class="col s12">
                        <div class="col s3">
                           <span class="blue-text">Order ID</span>
                        </div>

                        <div class="col s3">
                           <span class="blue-text">Vendor Name</span>
                        </div>

                        <div class="col s3">
                           <span class="blue-text">Received Date</span>
                        </div>


                     </div>
                  </div>



                  <div class="row">
                     <div class="col s12">
                        <div id="vendorsList">
                        </div>
                     </div>
                  </div>

               </div>

               <div class="row">
                  <div class="col s4 offset-s6">

                     <a href="../reports/printReport" class="btn waves-effect waves-light btn-large" 
                        value="print" name="print" id="print-report">
                        Print
                     </a>

                  </div>

                  <div class="col s4 offset-s6">

                     <button class="btn waves-effect waves-light btn-large" type="submit" 
                        value="submit" name="submit" id="submit">
                        Generate Abstract
                     </button>

                  </div>

               </div>
            </form>
         </div>
