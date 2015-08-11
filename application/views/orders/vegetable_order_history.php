<script>
   function get_order_history(){
         var from = encodeURIComponent($("#from").val());
         var to = encodeURIComponent($("#to").val())
         $.ajax({
               url : 'get_vegetable_order_history/'+from+'/'+to,
               type: 'GET',
               dataType: 'json',
               success : function(data){
                     console.log(data);
                     var jsonObj = data;
                     console.log(data);
                     var htmlContents = '<form name="abstract" action="generate_vegetable_abstract" method="post">';	
                        htmlContents += '<ul class="collapsible" data-collapsible="accordion">';
                           for (i = 0; i < jsonObj.length; i++) {

                                 var orderID = jsonObj[i].orderID;
                                 var vendorName = jsonObj[i].vendorName;

                                 var receivedDate = jsonObj[i].receivedDate;
                                 var billNo = jsonObj[i].billNo;
                                 var items = jsonObj[i].items;
                                 htmlContents += '<li>'+		  
                                 '<div class = "collapsible-header">'+
                                    '<div class= "row margin_row">'+
                                       '<table>'+
                                          '<tr>'+
                                             '<th>'+
                                                '<input type="checkbox" name="selectedOrders[]" id="'+orderID+'" value="'+orderID+'"/>'+
                                                '<label for="'+orderID+'"></label>'+

                                                '</th>'+
/*                                             '<th>'+
                                                '<span class="blue-text text-darken-2">'+
                                                   orderID +
                                                   '</span>'+
                                                '</th>'+
*/
                                             '<th>'+
                                                '<span class="blue-text text-darken-2">'+
                                                   vendorName +
                                                   '</span>'+
                                                '</th>'+
                                             '<th>'+
                                                '<span class="blue-text text-darken-2">'+
                                                   receivedDate +
                                                   '</span>'+
                                                '</th>'+
                                             '<th>'+
                                                '<span class="blue-text text-darken-2">'+
                                                   billNo +
                                                   '</span>'+
                                                '</th>'+

                                             '<th>'+
                                                '<span class="blue-text text-darken-2">'+
                                                   'View' +
                                                   '</span>'+
                                                '</th>'+
                                             '</tr>'+
                                          '</table>'+
                                       '</div>'+
                                    '</div>';
                                 htmlContents +=  '<div class = "collapsible-body"><table>'+
                                       '<tr>'+
                                          '<th>'+
                                             '<span class="black-text text-darken-2">'+
                                                'Item Name'    +
                                                '</span>'+
                                             '</th>'+
                                          '<th>'+
                                             '<span class="black-text text-darken-2">'+
                                                'Quantity Received' +
                                                '</span>'+
                                             '</th>'+
                                          '<th>'+
                                             '<span class="black-text text-darken-2">'+
                                                'Proposed Rate' +
                                                '</span>'+
                                             '</th>'+

                                          '<th>'+
                                             '<span class="black-text text-darken-2">'+
                                                'Actual Rate' +
                                                '</span>'+
                                             '</th>'+
                                          '<th>'+
                                             '<span class="black-text text-darken-2">'+
                                                'Amount' +
                                                '</span>'+
                                             '</th>'+
                                          '</tr>';

                                       for(j=0;j<items.length;j++){
                                             htmlContents +=  
                                             '<tr>'+
                                                '<th>'+
                                                   '<span class="black-text text-darken-2">'+
                                                      items[j].itemName +
                                                      '</span>'+
                                                   '</th>'+
                                                '<th>'+
                                                   '<span class="black-text text-darken-2">'+
                                                      items[j].quantityReceived +
                                                      '</span>'+
                                                   '</th>'+
                                                '<th>'+
                                                   '<span class="black-text text-darken-2">'+
                                                      items[j].proposedRate +
                                                      '</span>'+
                                                   '</th>'+

                                                '<th>'+
                                                   '<span class="black-text text-darken-2">'+
                                                      items[j].rate +
                                                      '</span>'+
                                                   '</th>'+
                                                '<th>'+
                                                   '<span class="black-text text-darken-2">'+
                                                      items[j].amount +
                                                      '</span>'+
                                                   '</th>'+
                                                '</tr>';


                                       }
                                       htmlContents += '</table>';
                              }
                              htmlContents += "</li></div></ul>";
                        htmlContents +=  '<button class="btn waves-effect waves-light btn-large" type="submit"'+ 
                           'value="submit" name="submit" id="submit">'+
                           ' Generate Abstract'+
                           ' </button>';
                        htmlContents += '</form>';

                     $("div#vendorsList").html(htmlContents);
                     $('.collapsible').collapsible({
                           accordion : false // A setting that changes the collapsible behavior to expandable instead of the default accordion style
                     });

               }

         });
      }
      $(document).ready(function() {


            $("div#reportArea").hide();

            $("#printDiv").hide();

            $("#getButton").click(function(){
                  if($("#from").val() == '') alert('Select start date for the range');
                  else if($("#to").val() == '') alert('Select end date for the range');
                  else {
                        $("#reportFrom").html($("#from").val());
                        $("#reportTo").html($("#to").val());
                        $("div#reportArea").show();
                        $("#printDiv").show();
                  }
            });

            $( "#from" ).pickadate();

            $( "#to" ).pickadate();
      });
   </script>
   <div class="row">
      <div class = "col s6">
         <label for="from">From date</label>
         <input type="date" class="datepicker" id="from"/>

      </div>
      <div class = "col s6">
         <label for="to">To date</label>
         <input type="date" class="datepicker" id="to"/>

      </div>

   </div>

   <div class="row">
      <div class='col s6'>
         <a href="javascript:get_order_history();" class="btn waves-effect waves-light" id="getButton">
            Get Report
         </a>
      </div>
      <div class="col s6 offset-s6" id="printDiv">

         <a href="javascript:printPDF('reportArea');" class="btn waves-effect waves-light" 
            value="print" name="print" id="print-report">
            Print
         </a>




      </div>
   </div>

   <div id="reportArea" value='reportArea'>



      <div class="row">
         <table>
            <tr>
               <th>

                  <span class="blue-text">Select</span>
               </th>
               <th>

                  <span class="blue-text">Order ID</span>
               </th>

               <th>
                  <span class="blue-text">Vendor Name</span>
               </th>

               <th>
                  <span class="blue-text">Received Date</span>
               </th>

               <th>
                  <span class="blue-text">Bill No</span>
               </th>
               <th>
                  <span class="blue-text">Action</span>
               </th>
            </tr>
            <table>
            </div>



            <div id="vendorsList">
            </div>

         </div>


      </div>



