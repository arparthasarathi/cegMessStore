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
</script>

<ul id="reports" class="dropdown-content">
	<li><a href="#!">Mess Consumption</a></li>
	<li class="divider"></li>
	<li><a href="#!">Order History</a></li>
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
</ul>

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
				<li><a href="#1">Logout</a></li>
			</ul>
		</div>
	</nav>
</div>
