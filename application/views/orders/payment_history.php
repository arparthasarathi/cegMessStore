<script>
   $(document).ready(function() {
         console.log(admin);
         $('#print-report').click(function () {
               var doc = new jsPDF();
               doc.addHTML(document.body,function() {
                     doc.autoPrint();
                     doc.save('test.pdf');
               });
         });

         $("#printDiv").hide();
         $("div#reportArea").hide();

         $("#getButton").click(function(){
               $("#reportForMess").html($("#selectedMess").val());
               $("#reportFrom").html($("#from").val());
               $("#reportTo").html($("#to").val());
               $("#printDiv").show();
               $("div#reportArea").show();  
         });

         $( "#from" ).pickadate();
         $( "#to" ).pickadate();
   });

   function submit_update(){
         var messName = encodeURIComponent($('[name="modalMessName"]').val());
         var itemName = encodeURIComponent($('[name="modalItemName"]'));
         var quantitySupplied = $('[name="modalQuantitySupplied"]');
         var rate = $('[name="modalRate"]');
         var dataToPrint = "";
         $.ajax({
               url : this.action,
               type : this.method,
               dataType : 'html',
               success : function(data){
                     alert('Data updated succesfully ');
               },
               error : function(data) {
                     alert('Error');
               }
         });

      }


      function get_payment_history(){

            var from = encodeURIComponent($("#from").val());
            var to = encodeURIComponent($("#to").val())
            var dataToPrint = "";
            $("#reports").html("");
            $.ajax({
                  url : '../reports/get_payment_history/'+from+'/'+to,
                  type : 'GET'  ,
                  dataType : 'json',
                  success : function(data){
                        console.log(data);
                        for(var i=0;i<data.length;i++){
                              var editData = {};
                              editData["paymentID"] = data[i].paymentID;
                              editData["paymentDate"] = data[i].paymentDate;
                              editData["bankName"] = data[i].bankName;
                              editData["inFavourOf"] = data[i].inFavourOf;
                              editData["paymentNumber"] = data[i].paymentNumber;
                              editData["paymentMode"] = data[i].paymentMode;

                              var jsonEdit = JSON.stringify(editData);
                              console.log(jsonEdit);
                              var editID = "edit_"+data[i].paymentID;
                              dataToPrint += '<div class="row">'+
                                 '<div class="col s2">'+
                                    data[i].paymentID+
                                    '</div>'+
                                 '<div class="col s2">'+
                                    data[i].paymentDate+
                                    '</div>'+
                                 '<div class="col s2">'+
                                    data[i].inFavourOf+
                                    '</div>'+
                                 '<div class="col s2">'+
                                    data[i].paymentNumber+
                                    '</div>'+
                                 '<div class="col s2">'+
                                    data[i].paymentMode+
                                    '</div>';
                                 if(admin)
                                 {
                                       dataToPrint +=	'<div class="col s2">'+
                                          '<a class="btn btn-small btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever='+jsonEdit+' data-keyboard="true">'+
                                             'Edit'+
                                             '</a>'+
                                          '</div>';
                                 }
                                 dataToPrint += '</div>';

                        }

                        $("div#reports").html(dataToPrint);
                  }   
            }); 

         }


      </script>
      <style type="text/css">

         select {
               border-size: 2px;
               border-color: #000066;
               border-radius: 4px;
            }


         </style>
         <form name="selection" method="post"  action="payment_history"> 

            <div class="row">
               <div class = "col s6">
                  <input type="date" class="datepicker" id="from"/>
                  <label for="from">From date</label> 
               </div>
               <div class = "col s6">
                  <input type="date" class="datepicker" id="to"/>
                  <label for="to">To date</label>
               </div>  
            </div>

            <div class="row">
               <div class = 'col s6'>
                  <a href="javascript:get_payment_history();" class="btn waves-effect waves-light" id="getButton">
                     &gt;&gt;
                  </a>
               </div>

               <div class="col s6 offset-s6" id="printDiv">     
                  <a href="javascript:printPDF()" class="btn waves-effect waves-light" value="print" name="print" id="print-report">
                     Print
                  </a>
               </div>
            </div>


            <div id="reportArea" value='reportArea'>
               <div class="row">
                  <div class="col s12 offset-s2">
                     <span>PAYMENT HISTORY - <span id="reportForMess"></span></span>
                  </div>
               </div>

               <div class ="row">
                  <div class="col s12 offset-s2">
                     <div class="col s6"><span>FROM:<span id="reportFrom"></span></span></div>
                     <div class="col s6"><span>TO:<span id="reportTo"></span></span></div>
                  </div>
               </div>  

               <div class="row">
                  <div class="col s12">
                     <div class="col s2">
                        <span class="blue-text">Payment ID</span>
                     </div>

                     <div class="col s2">
                        <span class="blue-text">Payment Date</span>
                     </div>



                     <div class="col s2">
                        <span class="blue-text">In Favour Of</span>
                     </div>

                     <div class="col s2">
                        <span class="blue-text">Payment Number</span>
                     </div>	

                     <div class="col s2">
                        <span class="blue-text">Payment Mode</span>
                     </div>	
                  </div>
               </div>  

               <div class="row">
                  <div class="col s12">
                     <div id="reports"></div>
                  </div>
               </div>

            </div>

         </form> 
      </div>
      <div class="modal fade in" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="false">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
               <h4 class="modal-title" id="memberModalLabel">Edit Member Detail</h4>
            </div>
            <div class="modal-body">
            </div>
         </div>
      </div>

      <script>
         $('#exampleModal').on('show.bs.modal', function (event) {
               $('body').css("margin-left", "0px");
               var button = $(event.relatedTarget) // Button that triggered the modal
               var recipient = button.data('whatever') // Extract info from data-* attributes
               var modal = $(this);
               var dataString = 'id=' + recipient;
               console.log(recipient);
               $.ajax({
                     type: "POST",
                     url: "<?php echo base_url()."items/edit_row_form";?>",
                     cache: false,
                     data: recipient,
                     dataType: 'html',

                     success: function (resp) {
                           console.log(resp);
                           modal.find('.modal-body').html(resp);
                     },
                     error: function(err) {
                           console.log(err);
                     }
               });  
         })

