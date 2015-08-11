<body>
   <script>
      function change_color(obj)
      {
            if($(obj).val().length !== 0) $(obj).css('background-color','#ffff00');
            else $(obj).css('background-color','#fff');
      }

      function printPDF(div,mess,from,to)
      {
            var htmlString = document.getElementById(div).innerHTML;


            var title = "<?php if(isset($title)) echo $title; else echo ""?>";
            console.log(title);
            $.ajax({
                  type: "POST",
                  url: "<?php echo base_url()."reports/printReport/";?>"+title+"/"+mess+"/"+from+"/"+to,
                  cache: false,
                  data: {'toSend': htmlString},
                  success: function (resp) {
                        console.log('Success');

                        window.open('/cegMessStore/reports/report.pdf');
                        // window.open('http://localhost/cegMessStore/reports/report.pdf');

                  },
                  async : false,
                  error: function(err) {
                        console.log(err);
                  }
            });  

      }

      function printAbstract(div,vendor,total,startDate,endDate)
      {
            var htmlString = document.getElementById(div).innerHTML;

            var title = "<?php if(isset($title)) echo $title; else echo ""?>";
            console.log(title);
            var date = new Date();
            var setDate = date.getDate();
            var setMonth = date.getMonth()+1;
            var setYear = date.getFullYear();
            console.log(setDate);
            if(setDate < 10)
            newDate = '0'+setDate.toString();
            if(setMonth < 10)
            newMonth = '0'+setMonth.toString();
            var newYear = setYear.toString();

            $.ajax({
                  type: "POST",
                  url: "<?php echo base_url()."reports/printAbstract/";?>"+title+"/"+vendor+"/"+total+"/"+startDate+"/"+endDate,
                  cache: false,
                  data: {'toSend': htmlString},
                  success: function (resp) {
                        var todayDate = newDate+"-"+newMonth+"-"+newYear;
                        if(title.indexOf("Vegetable") >= 0)
                        var file = "Vegetable Abstract"+"/"+vendor+"_"+startDate+"_"+endDate+"_"+todayDate;
                        else 
                        var file = "Items Abstract"+"/"+vendor+"_"+startDate+"_"+endDate+"_"+todayDate;
                        var loc = "http://localhost/cegMessStore/reports/"+file+".pdf";
                        console.log(resp);
                        window.open(loc);           
                        //window.open('/cegMessStore/reports/report.pdf');
                        // window.open('http://localhost/cegMessStore/reports/report.pdf');
                  },
                  async : false,
                  error: function(err) {
                        console.log(err);
                  }
            });  
      }

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
      var provison = false;
      var vegetable = false;
   </script>
   <style>
      .input-field{
            border-size: 2px;
            border-color: #000066;
            border-radius: 4px;
      }
   </style>

   <?php
      $admin = false;
      $provision =false;
      $vegetable = false;
      if(isset($group))
      if(in_array('admin',$group))
      {
         $admin =true;
      ?>
      <script>
         admin = true;
      </script>
      <?php
      }
      else if(in_array('vegetable',$group))
      {
         $vegetable =true;
      ?>
      <script>
         vegetable = true;
      </script>
      <?php
      }
      else if(in_array('provision',$group))
      {
         $provision = true;
      ?>
      <script>
         provision = true;
      </script>
      <?php
      }

   ?>

   <ul id="mess" class="dropdown-content">
      <?php 
         if(isset($username)) {
            if($provision || $admin)
            {
            ?>
            <li><a href='<?php echo base_url()."mess/mess_consumption";?>'>Mess Consumption</a></li>
            <li class="divider"></li>
            <li><a href='<?php echo base_url()."mess/mess_bill";?>'>Mess Bill</a></li>
            <li class="divider"></li>
            <li><a href='<?php echo base_url()."mess/mess_return";?>'>Mess Returns</a></li>
            <li class="divider"></li>
            <?php }
               if($vegetable || $admin) {
               ?>
               <li><a href='<?php echo base_url()."mess/mess_vegetable_consumption";?>'>Mess Vegetable Consumption</a></li>
               <li class="divider"></li>
               <li><a href='<?php echo base_url()."mess/mess_vegetable_bill";?>'>Mess Vegetable Bill</a></li>
               <li class="divider"></li>
               <?php }
               ?>
               <li><a href='<?php echo base_url()."mess/mess_details";?>'>Mess Details</a></li>
            </ul>

            <?php
               if($provision || $admin) {
               ?>
               <ul id="items" class="dropdown-content">
                  <li><a href="<?php echo base_url()."orders/order_receive";?>">Order Receival</a></li>
                  <li class="divider"></li>
                  <li><a href='<?php echo base_url()."orders/vendor_details";?>'>Vendor Details</a></li>
                  <!--
                  <li class="divider"></li>
                  <li><a href="<?php // echo base_url()."orders/payment_history";?>">Payment History</a></li>
                  <li class="divider"></li>
                  <li><a href="<?php //echo base_url()."orders/pending_payments";?>">Pending Payments</a></li>
                  <li class="divider"></li>
                  -->
                  <li><a href="<?php echo base_url()."orders/order_history";?>">Order History</a></li>
                  <li class="divider"></li>
                  <li><a href='<?php echo base_url()."items/issue_item";?>'>Item Issue</a></li>
                  <li class="divider"></li>
                  <li><a href='<?php echo base_url()."items/return_item";?>'>Item Return</a></li>
                  <li class="divider"></li>
                  <li><a href='<?php echo base_url()."items/items_in_stock";?>'>Items in Stock</a></li>
                  <li class="divider"></li>
                  <li><a href='<?php echo base_url()."items/add_item";?>'>Add New Items</a></li>
               </ul>
               <?php
               }
            ?>

            <?php 
               if($vegetable || $admin) 
               {
               ?>
               <ul id="vegetables" class="dropdown-content">
                  <li><a href="<?php echo base_url()."orders/vegetable_order";?>">Order Receival</a></li>
                  <li class="divider"></li>
                  <li><a href='<?php echo base_url()."orders/vendor_details";?>'>Vendor Details</a></li>
                  <li class="divider"></li>
                  <!--
                  <li><a href="<?php // echo base_url()."orders/vegetable_payment_history";?>">Payment History</a></li>
                  <li class="divider"></li>
                  <li><a href="<?php // echo base_url()."orders/vegetable_pending_payments";?>">Pending Payments</a></li>
                  <li class="divider"></li>
                  -->
                  <li><a href="<?php echo base_url()."orders/vegetable_order_history";?>">Order History</a></li>
                  <li class="divider"></li>
                  <li><a href='<?php echo base_url()."items/add_vegetable";?>'>Add New Vegetables</a></li>
               </ul>
               <?php
               }
            ?>


            <ul id="account" class="dropdown-content">
               <li><a href='<?php echo base_url()."auth/change_password";?>'>Change Password</a></li>
               <?php
                  if($admin)
                  {
                  ?>
                  <li><a href='<?php echo base_url()."auth/edit_existing_users";?>'>Edit Users</a></li>
                  <?php
                  }
               ?>
               <li class="divider"></li>
               <li><a href='<?php echo base_url()."auth/logout";?>'>Logout</a></li>
            </ul>

            <div class="navbar-fixed">
               <nav>
               <div class="nav-wrapper">
                  <!--<a href="#!" class="brand-logo">Logo</a>-->
                  <?php
                     if(isset($username))
                     {
                     ?>
                     <ul class="left">
                        <li>
                        <a class='dropdown-button' href='#'><?php echo $username;?></a>
                        </li>
                     </ul>
                     <?php
                     }
                  ?>
                  <ul class="right hide-on-med-and-down">

                     <!-- Dropdown Trigger -->
                     <?php
                        if($provision || $admin) 
                        {
                        ?>
                        <li>
                        <a class='dropdown-button' href='#' data-activates='items'>Items
                           <span class="glyphicon glyphicon-chevron-down"></span>
                        </a>
                        </li>
                        <?php
                        }
                     ?>
                     <?php 
                        if($vegetable || $admin)
                        {
                        ?>
                        <li>
                        <a class='dropdown-button' href='#' data-activates='vegetables'>Vegetables
                           <span class="glyphicon glyphicon-chevron-down"></span>
                        </a>
                        </li>
                        <?php
                        }
                     ?>
                     <li>
                     <a class='dropdown-button' href='#' data-activates='mess'>Mess
                        <span class="glyphicon glyphicon-chevron-down"></span>
                     </a>
                     </li>	


                     <li>
                     <a class='dropdown-button' href='#' data-activates='account'>Account
                        <span class="glyphicon glyphicon-chevron-down"></span>
                     </a>
                     </li>	
                     <?php }
                     ?>
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
                     $pre = '<div class="card-panel error red darken-3">
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
