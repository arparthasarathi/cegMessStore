<script>

function deleteMess(mess_name){
         var recipient = mess_name;// Extract info from data-* attributes
	if(confirm('Do you really want to delete this Mess ? This cannot be undone' )) {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url()."mess/delete_mess";?>",
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

}


function addMess(){

	var mess_name = $("#add_mess_name").val();
	var mess_incharge = $("#add_mess_incharge").val();
	
	var contact = $("#add_contact").val();

	if(mess_name == '' || mess_incharge == '' || contact == '')
		alert('Kindly fill all the details');
	else{

	var toSend = {};
	toSend["messName"] = encodeURIComponent(mess_name);
	toSend["messIncharge"] = encodeURIComponent(mess_incharge);
	toSend["contact"] = encodeURIComponent(contact);
	var toSendJson = JSON.stringify(toSend);
	console.log(toSendJson);
	$.ajax({
            url : "<?php echo base_url().'mess/add_mess';?>",
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

function messList(){
	
	var report = $("#report");
	var itemName = $("#itemNames");
	var quantitySupplied = $("#quantitySupplied");
	var rate = $("#rate");
	var amount = $("#amount");
	var dataToPrint = "";
	$(report).html("");
         $.ajax({
            url : '../mess/get_mess_details/',
            type : 'GET'  ,
		    dataType : 'json',
            success : function(data){
				console.log(data);
				console.log(data.messName.length);
				if(data.messName.length==0)
				dataToPrint += '<div class="row">'+
					'<div class="col s8 offset-s2">'+
					'<span class="blue-text text-darken-2">No mess. Add new.</span>'+
					'</div></div>';
				else{
					$(data.messName).each(function(index){
					var editData = {};
					editData["messName"] = encodeURIComponent(data.messName[index]);
					editData["messIncharge"] = encodeURIComponent(data.messIncharge[index]);
					editData["contact"] = (data.contact[index]);

					var jsonEdit = JSON.stringify(editData);
					console.log(jsonEdit);
					
					var editID = "edit_"+data.messName[index]+'_'+data.messIncharge[index]+'_'+data.contact[index];
					dataToPrint += '<div class="row">'+
							'<div class="col s2">'+
							data.messName[index]+
							'</div>'+
							'<div class="col s2">'+
							data.messIncharge[index]+
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
								'<a href = "javascript:deleteMess(\''+editData['messName']+'\');" class="btn btn-small btn-primary" >'+
							'Delete'+
					                '</a>'+
							'</div></div>';
					console.log(data.messName[index]);
		});
		}
		$("div#messList").html(dataToPrint);
            },
	    error: function(xhr, status, error) {
 			 var err = eval("(" + xhr.responseText + ")");
			 alert(err.Message);
		}
         }); 

    }

$(document).ready(function() {
messList();
$('#add_mess').hide();
$('#print-report').click(function () {
    var doc = new jsPDF();
    doc.addHTML(document.body,function() {
	doc.autoPrint();
	doc.save('test.pdf');
   });

  });

$('#add_button').hide();
if(admin) $('#add_button').show();
var toggle = 0;
$('#add_button').click(function () {
     toggle = !toggle;
     if(toggle){
           $('#add_button').text('Close');
           $('#add_mess').show();
     }
     else{
	   $('#add_button').text('Add Mess');
           $('#add_mess').hide();
     }
   });




});
</script>


	<div class="row">
                <div class="col s4">

                         <a class="btn waves-effect waves-light btn-large" 
                                        value="add_button" name="add_button" id="add_button">
                         Add Mess
                         </a>

                </div>
        </div>

	<div id="add_mess">
	 <div class="row">
               <div class = "input-field col s3">
		<input type="text" name="add_mess_name" id="add_mess_name"/>
		<label for="add_mess_name">Mess Name</label>
               </div>
		<div class = "input-field col s3">
                <input type="text" name="add_mess_incharge" id="add_mess_incharge"/>
                <label for="add_mess_incharge">Mess Incharge</label>
               </div>
		<div class = "input-field col s3">
                <input type="text" name="add_contact" id="add_contact"/>
                <label for="add_contact">Contact</label>
               </div>
	
		

        </div>
	
	<div class="row">
                <div class="col s4 offset-s6">

                         <a href="javascript:addMess();" class="btn waves-effect waves-light btn-large" 
                                        value="add_mess" name="add_mess" id="add_mess">
                         Submit
                         </a>

                </div>
        </div>
	</div>



	<div id="reportArea" value='reportArea'>
	
		<div class="row">
			<div class="col s8 offset-s2">
			<span>MESS LIST</span>
			</div>
		</div>


		<div class="row">
			<div class="col s12">
			<div class="col s2">
			<span class="blue-text">Mess Name</span>
			</div>

			<div class="col s2">
			<span class="blue-text">Mess Incharge</span>
			</div>
			
			<div class="col s2">
			<span class="blue-text">Contact</span>
			</div>
			
			</div>
		</div>
		
		
	
		<div class="row">
			<div class="col s12">
			<div id="messList"></div>
			</div>
		</div>

	</div>

</div>
<div class="modal fade in" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="false">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="memberModalLabel">Edit Mess Detail</h4>
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
                url: "<?php echo base_url()."mess/edit_mess_form";?>",
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

