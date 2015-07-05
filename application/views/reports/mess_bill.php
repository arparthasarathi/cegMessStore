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
	var totalAmount = 0;
	$(report).html("");
         $.ajax({
            url : 'get_mess_bill_report/'+messName+'/'+from+'/'+to,
            type : 'GET'  ,
	    dataType : 'json',
            success : function(data){
		$(data.suppliedDate).each(function(index){
			totalAmount += parseFloat(data.totalAmount[index],10);
			dataToPrint += '<div class="row">'+
							'<div class="col s3">'+
								data.suppliedDate[index]+
							'</div>'+
							'<div class="col s3">'+
								data.totalAmount[index]+
							'</div></div>';
			console.log(data.suppliedDate[index]);
		});
		dataToPrint += '<div class="row">'+
				 '<div class="col s3">'+
					'Total'+
                                 '</div>'+
                                 '<div class="col s3">'+
                                       totalAmount+
                                 '</div></div>';

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


$("div#reportArea").hide();

$("#print-report").hide();

$("#getButton").click(function(){

    $("#reportForMess").html($("#selectedMess").val());
    $("#reportFrom").html($("#from").val());
    $("#reportTo").html($("#to").val());
    $("div#reportArea").show();
    $("#print-report").show();
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
<?php
if(isset($msg)) 
print_r($msg); 

?>
<div class="container">
	<form name="selection" method="post"  action="mess_bill"> 
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
	<div class="row">
                <div class="col s4 offset-s6">

                         <a href="javascript:demoFromHTML();" class="btn waves-effect waves-light btn-large" 
                                        value="print" name="print" id="print-report">
                         Print
                         </a>

                </div>
	        </div>
	


	<div id="reportArea" value='reportArea'>
			<div class="row">
			<div class="col s8 offset-s3">
			<span>MESS BILL - <span id="reportForMess"></span></span>
			</div>
		</div>

		<div class ="row">
			<div class="col s8 offset-s3">
			<div class="col s6"><span>FROM:<span id="reportFrom"></span></span></div>
			<div class="col s6"><span>TO:<span id="reportTo"></span></span></div>
			</div>
		</div>

		<div class="row">
			<div class="col s8 offset-s4">
			<div class="col s3">
			<span class="blue-text">Date</span>
			</div>
			
			<div class="col s3">
			<span class="blue-text">Amount</span>
			</div>
			</div>
		</div>
	
		<div class="row">
			<div class="col s8 offset-s4">
			<div id="reports"></div>
			</div>
		</div>

	</div>
	</form> 
</div>
