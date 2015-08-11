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

get_vegetable_pending_payments();
$('#add_button').click(function () {
  $('#add_vendor').show();
});

});
</script>


<script>
function deleteVendor(vendor_name){
         var recipient = vendor_name;// Extract info from data-* attributes
            $.ajax({
                type: "POST",
                url: "<?php echo base_url()."orders/delete_vendor";?>",
                cache: false,
                data: {'data' : recipient},
                dataType: 'html',

                success: function (resp) {
                    console.log(resp);
                    alert(resp);
                    location.reload(true);
                },
                error: function(err) {
                    console.log(err);
                }
            });  

}




function addVendor(){

	var vendor_name = $("#add_vendor_name").val();
	var owner_name = $("#add_owner_name").val();
	var address = $("#add_address").val();
	var contact = $("#add_contact").val();

	if(vendor_name == '' || owner_name == '' || address == '' || contact == '')
		alert('Kindly fill all the details');
	else{

	var toSend = {};
	toSend["vendorName"] = encodeURIComponent(vendor_name);
	toSend["ownerName"] = encodeURIComponent(owner_name);
	toSend["address"] = encodeURIComponent(address);
	toSend["contact"] = encodeURIComponent(contact);
	var toSendJson = JSON.stringify(toSend);
	console.log(toSendJson);
	$.ajax({
            url : "<?php echo base_url().'orders/add_vendor';?>",
            type : "POST",
	    data: {'data' :toSendJson},
	    cache: false,
            dataType : "html",
            success : function(resp){

		console.log(resp);
		alert(resp);

		location.reload(true);
            },
	    error: function(xhr, status, error) {
 		 var err = eval("(" + xhr.responseText + ")");
		 alert(err.Message);
	    }
	
         }); 
	}
}


function get_vegetable_pending_payments(){
	$.ajax({
	  url : 'get_vegetable_pending_payments/',
	  type: 'GET',
	  dataType: 'json',
	  success : function(data){
		console.log(data);
		var jsonObj = data;
		console.log(data);
		var htmlContents = "";	
   	        htmlContents += '<ul class="collapsible" data-collapsible="accordion">';
		for (i = 0; i < jsonObj.length; i++) {

		   var orderID = jsonObj[i].orderID;
		   var vendorName = jsonObj[i].vendorName;
		   var receivedDate = jsonObj[i].receivedDate;
		   var items = jsonObj[i].items;
		   htmlContents += '<li>'+		  
				   '<div class = "collapsible-header">'+
					   '<div class= "row">'+
						   '<div class = "col s12">'+
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
							   '<div class = "col s3">'+
								   '<a href="enter_payment_details/'+orderID+'" class="btn btn-primary">Update</a>'+
							    '</div>'+

		                                   '</div>'+
                	                   '</div>'+
                                   '</div>';
		   htmlContents +=  '<div class = "collapsible-body">'+
					 '<div class= "row">'+
                                                    '<div class = "col s12 offset-s1">'+
                                                           '<div class = "col s4">'+
                                                                   '<span class="black-text text-darken-2">'+
                                                                       'Item Name'    +
                                                                   '</span>'+
                                                           '</div>'+
                                                           '<div class = "col s4">'+
                                                                   '<span class="black-text text-darken-2">'+
                                                                           'Quantity Received' +
                                                                   '</span>'+
                                                           '</div>'+
                                                           '<div class = "col s4">'+
                                                                   '<span class="black-text text-darken-2">'+
                                                                           'Rate' +
                                                                   '</span>'+
                                                           '</div>'+
                                                   '</div>'+
                                           '</div>';

		   for(j=0;j<items.length;j++){
			 htmlContents +=  
                        	         '<div class= "row">'+
                               			    '<div class = "col s12 offset-s1">'+
			                                   '<div class = "col s4">'+
                        				           '<span class="black-text text-darken-2">'+
					                                   items[j].itemName +
				                                   '</span>'+
                                			   '</div>'+
			                                   '<div class = "col s4">'+
				                                   '<span class="black-text text-darken-2">'+
                                					   items[j].quantityReceived +
				                                   '</span>'+
                                			   '</div>'+
			                                   '<div class = "col s4">'+
                        				           '<span class="black-text text-darken-2">'+
					                                   items[j].rate +
				                                   '</span>'+
                                			   '</div>'+
		                                   '</div>'+
                	                   '</div>';
                                   

		   }
		}
		htmlContents += "</li></div></ul>";
		$("div#vendorsList").html(htmlContents);
		$('.collapsible').collapsible({
      accordion : false // A setting that changes the collapsible behavior to expandable instead of the default accordion style
    });

		/*
			console.log(items[j].itemName);
		   console.log(orderID); //applicationDetail's First Object
		   console.log(vendorName); //productSubGrupDetail1's First Object
		   <ul class="collapsible" data-collapsible="accordion">
   		   <li>
		      <div class="collapsible-header"><i class="material-icons">filter_drama</i>First</div>
		      <div class="collapsible-body"><p>Lorem ipsum dolor sit amet.</p></div>
		   </li>
		   <li>
		      <div class="collapsible-header"><i class="material-icons">place</i>Second</div>
		      <div class="collapsible-body"><p>Lorem ipsum dolor sit amet.</p></div>
		   </li>
		   <li>
		      <div class="collapsible-header"><i class="material-icons">whatshot</i>Third</div>
		      <div class="collapsible-body"><p>Lorem ipsum dolor sit amet.</p></div>
		   </li>
		  </ul>
*/
		}

	});
}
function vendorsList(){
	
	var report = $("#report");
	var itemName = $("#itemNames");
	var quantitySupplied = $("#quantitySupplied");
	var rate = $("#rate");
	var amount = $("#amount");
	var dataToPrint = "";
	$(report).html("");
         $.ajax({
            url : 'get_vendors_list/',
            type : 'GET'  ,
	    dataType : 'json',
            success : function(data){
		console.log(data);
		console.log(data.vendorName.length);
		if(data.vendorName.length==0)
			dataToPrint += '<div class="row">'+
					'<div class="col s8 offset-s2">'+
					'<span class="blue-text text-darken-2">No vendors. Add new.</span>'+
					'</div></div>';
		else{
		$(data.vendorName).each(function(index){
		 var editData = {};

                 editData["vendorName"] = encodeURIComponent(data.vendorName[index]);
                 editData["ownerName"] = encodeURIComponent(data.ownerName[index]);
                 editData["address"] = encodeURIComponent(data.address[index]);
                 editData["contact"] = (data.contact[index]);

                 var jsonEdit = JSON.stringify(editData);
                 console.log(jsonEdit);
                                  
                 var editID = "edit_"+data.vendorName[index]+'_'+data.ownerName[index]+'_'+data.address[index]+'_'+data.contact[index];

			dataToPrint += '<div class="row">'+
							'<div class="col s2">'+
							data.vendorName[index]+
							'</div>'+
							'<div class="col s2">'+
							data.ownerName[index]+
							'</div>'+
							'<div class="col s2">'+
								data.address[index]+
							'</div>'+
							'<div class="col s2">'+
								data.contact[index]+
							'</div>'+
 '<div class="col s2">'+
                                                                '<a class="btn btn-small btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever='+jsonEdit+' data-keyboard="true">'+
                                                        'Edit'+
                                                        '</a>'+
                                                        '</div>'+
                                                        '<div class="col s2">'+
                                                                '<a href = "javascript:deleteVendor(\''+editData['vendorName']+'\');" class="btn btn-small btn-primary" >'+
                                                        'Delete'+
                                                        '</a>'+

							'</div>';
			console.log(data.vendorName[index]);
		});
		}

		$("div#vendorsList").html(dataToPrint);
            },
	    error: function(xhr, status, error) {
 			 var err = eval("(" + xhr.responseText + ")");
			 alert(err.Message);
		}
         }); 

    }

 function demoFromHTML(id) {
 	var options = {
		   pagesplit: true,
	};
	
	var specialElementHandlers = {
             'a': function(element, renderer) {
                 return true;
             }
         };
	
	html2canvas($(id), {
            onrendered: function(canvas) {         
                var imgData = canvas.toDataURL(
                    'image/png');              
                var doc = new jsPDF('l', 'pt','a4');
                doc.addImage(imgData, 'PNG', 10, 10);
                doc.save('sample-file.pdf');
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

.btn-large{
        height:60px;
        font-size: 20px;
        width: 150px;
}

</style>


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
                url: "<?php echo base_url()."orders/edit_vendor_form";?>",
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
</script>
