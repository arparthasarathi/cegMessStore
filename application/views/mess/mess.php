<script>
function addMess(){

	var mess_name = $("#add_mess_name").val();
	var mess_incharge = $("#add_mess_incharge").val();
	
	var contact = $("#add_contact").val();

	if(mess_name == '' || mess_incharge == '' || contact == '')
		alert('Kindly fill all the details');
	else{

	var toSend = {};
	toSend["mess_name"] = encodeURIComponent(mess_name);
	toSend["mess_incharge"] = encodeURIComponent(mess_incharge);
	
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
		$(data.vendorName).each(function(index){
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
							'</div>';
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

 function demoFromHTML() {
 	var options = {
		   pagesplit: true,
	};
html2canvas($("#messList"), {
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
<script>
$(document).ready(function() {
vendorsList();
$('#add_mess').hide();
$('#print-report').click(function () {
    var doc = new jsPDF();
    doc.addHTML(document.body,function() {
	doc.autoPrint();
	doc.save('test.pdf');
   });

  });

$('#add_button').click(function () {
  $('#add_mess').show();
});

});
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
			<div class="col s3">
			<span class="blue-text">Mess Name</span>
			</div>

			<div class="col s3">
			<span class="blue-text">Mess Incharge</span>
			</div>
			
			<div class="col s3">
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

	<div class="row">
                <div class="col s4 offset-s6">

                         <a href="javascript:demoFromHTML();" class="btn waves-effect waves-light btn-large" 
                                        value="print" name="print" id="print-report">
                         Print
                         </a>

                </div>
        </div>
</div>
