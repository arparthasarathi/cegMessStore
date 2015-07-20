<script>
function get_report(){
	
        var messName = encodeURIComponent($("#selectedMess").val());
	var from = encodeURIComponent($("#from").val());
	var to = encodeURIComponent($("#to").val())
	var report = $("#report");
	var itemName = $("#itemNames");
	var quantitySupplied = $("#quantitySupplied");
	var rate = $("#rate");
	var amount = $("#amount");
	var dataToPrint = "";
	$(report).html("");
         $.ajax({
            url : 'get_mess_return_report/'+messName+'/'+from+'/'+to,
            type : 'GET'  ,
	    dataType : 'json',
            success : function(data){
		$(data.itemNames).each(function(index){
			dataToPrint += '<div class="row">'+
							'<div class="col s2">'+
							data.returnedDate[index]+
							'</div>'+
							'<div class="col s2">'+
							data.itemNames[index]+
							'</div>'+
							'<div class="col s2">'+
								data.quantityReturned[index]+
							'</div>'+
							'<div class="col s2">'+
								data.rate[index]+
							'</div>'+
							'<div class="col s2">'+
								data.amount[index]+
							'</div></div>';
			console.log(data.itemNames[index]);
		});
		$("div#reports").html(dataToPrint);
            }
         }); 

    }

 function demoFromHTML() {
 	var options = {
		   pagesplit: true,
	};
html2canvas($("#reportArea"), {
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

$('#print-report').click(function () {
    var doc = new jsPDF();
    doc.addHTML(document.body,function() {
	doc.autoPrint();
	doc.save('test.pdf');
   });

  });

$("div#reportArea").hide();

$("#getButton").click(function(){

    $("#reportForMess").html($("#selectedMess").val());
    $("#reportFrom").html($("#from").val());
    $("#reportTo").html($("#to").val());
    $("div#reportArea").show();
});

$( "#from" ).pickadate();

$( "#to" ).pickadate();
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
<div class="container">
	<form name="selection" method="post"  action="mess_return"> 
	<div class="row">
		<div class="input-field col s6 offset-s3">
			<select class="browser-default" id="selectedMess" name="selectedMess" value = "<?php echo isset($selectedMess) ? $selectedMess : "";?>" required>
			<option></option>
			<?php	
			foreach($messTypes as $eachType)
			{
			?>
			<option value='<?php echo $eachType;?>'><?php echo $eachType;?></option>
			<?php
			}
			?>
			</select>
		</div>
	</div>

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
                  <a href="javascript:get_report();" class="btn waves-effect waves-light" id="getButton">
                   &gt;&gt;
                   </a>
                   </div>
	</div>

	<div id="reportArea" value='reportArea'>
	
		<div class="row">
			<div class="col s8 offset-s2">
			<span>MESS CONSUMPTION - <span id="reportForMess"></span></span>
			</div>
		</div>

		<div class ="row">
			<div class="col s8 offset-s2">
			<div class="col s6"><span>FROM:<span id="reportFrom"></span></span></div>
			<div class="col s6"><span>TO:<span id="reportTo"></span></span></div>
			</div>
		</div>

		<div class="row">
			<div class="col s12">
			<div class="col s2">
			<span class="blue-text">Returned Date</span>
			</div>

			<div class="col s2">
			<span class="blue-text">Item Name</span>
			</div>
			
			<div class="col s2">
			<span class="blue-text">Quantity Returned</span>
			</div>
			<div class="col s2">
			<span class="blue-text">Rate</span>
			</div>
			
			<div class="col s2">
			<span class="blue-text">Amount</span>
			</div>
			</div>
		</div>
	
		<div class="row">
			<div class="col s12">
			<div id="reports"></div>
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
	</form> 
</div>
