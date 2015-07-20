<body>
<script>
$( document ).ready(function(){
		$(".dropdown-button").dropdown({
inDuration: 300,
outDuration: 225,
constrain_width: false, // Does not change width of dropdown to that of the activator
hover: true, // Activate on hover
gutter: 2, // Spacing from edge
belowOrigin: true // Displays dropdown below the button
});
});
var admin = false;
</script>

<ul id="reports" class="dropdown-content">
<li><a href='<?php echo base_url()."reports/mess_consumption";?>'>Mess Consumption</a></li>
<li class="divider"></li>
<li><a href='<?php echo base_url()."reports/mess_bill";?>'>Mess Bill</a></li>
<li class="divider"></li>
<li><a href='<?php echo base_url()."reports/mess_return";?>'>Mess Returns</a></li>

<li class="divider"></li>
<li><a href="#!">Payment History</a></li>
</ul>

<ul id="orders" class="dropdown-content">
<li><a href="#!">Place Order</a></li>
<li class="divider"></li>
<li><a href="#!">Receive Order</a></li>
</ul>

<ul id="items" class="dropdown-content">
<li><a href='<?php echo base_url()."items/issue_item";?>'>Item Issue</a></li>
 <li class="divider"></li>
<li><a href='<?php echo base_url()."items/return_item";?>'>Item Return</a></li>
<li class="divider"></li>
<li><a href='<?php echo base_url()."items/add_item";?>'>Add New Items</a></li>
</ul>

<ul id="account" class="dropdown-content">
<li><a href='<?php echo base_url()."auth/change_password";?>'>Change Password</a></li>
 <li class="divider"></li>
<li><a href='<?php echo base_url()."auth/logout";?>'>Logout</a></li>
</ul>




<?php
if(isset($group))
if(in_array('admin',$group))
{
?>
<script>
admin = true;
</script>
<ul id="edit" class="dropdown-content">
<li><a href='<?php echo base_url()."items/edit_issued_items";?>'>Issued Items</a></li>
<li class="divider"></li>
<li><a href='<?php echo base_url()."items/edit_items";?>'>Items in stock</a></li>
</ul>
<?php
}?>
<div class="navbar-fixed">
<nav>
<div class="nav-wrapper">
<!--<a href="#!" class="brand-logo">Logo</a>-->
<ul class="right hide-on-med-and-down">
<!-- Dropdown Trigger -->
<li>
<a class='dropdown-button' href='#' data-activates='items'>Items
<span class="glyphicon glyphicon-chevron-down"></span>
</a>
</li>

<li>
<a class='dropdown-button' href='#' data-activates='orders'>Orders
<span class="glyphicon glyphicon-chevron-down"></span>
</a>
</li>

<li>
<a class='dropdown-button' href='#' data-activates='reports'>Reports
<span class="glyphicon glyphicon-chevron-down"></span>
</a>
</li>	

<?php
if(isset($group))
if(in_array('admin',$group))
{
?>
<li>
<a class='dropdown-button' href='#' data-activates='edit'>Edit
<span class="glyphicon glyphicon-chevron-down"></span>
</a>
</li>
<?php
}
?>
<li>
<a class='dropdown-button' href='#' data-activates='account'>Account
<span class="glyphicon glyphicon-chevron-down"></span>
</a>
</li>	

</ul>
</div>
</nav>
</div>
<div class="container">
<?php 
$msg= validation_errors(); 
if(isset($message))
$msg = $message;
if(isset($msg))
{
	$pre = '<div class="card-panel msg teal lighten-2">
		<div class="row" style="float:right;margin:4px" >
		<a href="#" class="remove_field-1" style="color:red">
		<span class="glyphicon glyphicon-remove">
		</span>
		</a>
		</div>';
	$post='</div>';
	$main = "";
	if(is_array($msg) && count($msg)>0)
	{
		foreach($msg as $each)
		{
			$main .=	'<div class="row">
				<h6 class="black-text text-darken-2">
				'.$each.'
				</h6>
				</div>';
		}
	echo $pre.$main.$post;
	}
	else if($msg !=""){
		$main = '<div class="row">
			<h6 class="black-text text-darken-2">
			'.$msg.'
			</h6>
			</div>';

	echo $pre.$main.$post;
	}


}

if(isset($error))
{
	$pre = '<div class="card-panel error teal lighten-2">
		<div class="row" style="float:right;margin:4px" >
		<a href="#" class="remove_field-2" style="color:red">
		<span class="glyphicon glyphicon-remove">
		</span>
		</a>
		</div>';
	
	$post='</div>';

	$main = "";
	if(is_array($error) && count($error) > 0)
	{
		foreach($error as $each)
		{
			$main .=	'<div class="row">
				<h6 class="black-text text-darken-2">
				'.$each.'
				</h6>
				</div>';
		}

	echo $pre.$main.$post;
	}
	else if($error != ""){
	$main = '<div class="row">
		<h6 class="black-text text-darken-2">
		'.$error.'
		</h6>
		</div>';


	echo $pre.$main.$post;
	}
}

if(isset($lesser_items))
{
	$pre = '<div class="card-panel lesser-items red darken-3">
		<div class="row" style="float:right;margin:4px" >
		<a href="#" class="remove_field-3" style="color:black">
		<span class="glyphicon glyphicon-remove">
		</span>
		</a>
		</div>';
	
	$main = "";
	$post='</div>';
	if(is_array($lesser_items['itemNames']) && count($lesser_items['itemNames']) > 0)
	{
		for($i=0;$i<count($lesser_items['itemNames']);$i++)
		{
			$main .=	'<div class="row">
				<h6 class="white-text text-darken-2">
				'.$lesser_items['itemNames'][$i].' --> Only '.$lesser_items['quantityAvailable'][$i].'(kgs/l) is available!
				</h6>
				</div>';
		}

	echo $pre.$main.$post;
	}
}
?>
<script>

$(document).on("click",".remove_field-1", function(e){ //user click on remove text                 
                                e.preventDefault(); $(".msg").remove(); 
                                });

$(document).on("click",".remove_field-2", function(e){ //user click on remove text                 
                                e.preventDefault(); $(".error").remove(); 
                                });

$(document).on("click",".remove_field-3", function(e){ //user click on remove text                 
                                e.preventDefault(); $(".lesser-items").remove(); 
                                });

</script>
<?php
if(isset($title) && $title != "")
{
?>
	<div class="row">
	<div class="col s8 offset-s2">
		<h3 align='center'>	<span class="black-text text-darken-2">
		<?php
		echo $title;
		?>
		</h3></span>
	</div>
	</div>
<?php
}
?>
