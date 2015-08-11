<?php
   defined('BASEPATH') OR exit('No direct script access allowed');

   class Orders extends CI_Controller {

      /**
      * Index Page for this controller.
      *
      * Maps to the following URL
      * 		http://example.com/index.php/welcome
      *	- or -
      * 		http://example.com/index.php/welcome/index
      *	- or -
      * Since this controller is set as the default controller in
      * config/routes.php, it's displayed at http://example.com/
      *
      * So any other public methods not prefixed with an underscore will
      * map to /index.php/welcome/<method_name>
      * @see http://codeigniter.com/user_guide/general/urls.html
      */

      private $messTypes= array("JUNIOR MESS","SENIOR VEG MESS","SENIOR NON VEG MESS","GIRLS MESS");

      public function __construct()
      {
         parent::__construct();
         $this->load->model('items_model');

         $this->load->model('mess_model');
         $this->load->model('orders_model');
         $this->load->helper('form');
         $this->load->helper('url');
         $this->load->library('session');

         $this->load->library('form_validation');
         $this->load->helper('date');
         $this->load->library('ion_auth');

      }

      public function get_mess_types()
      {

         if(!$this->ion_auth->logged_in())
         redirect('auth/login','refresh');
         else
         {
            $data['username'] = $this->ion_auth->user()->row()->username;

            $data['group'] = $this->ion_auth->get_logged_in_user_group_names();

            $jsonMessTypes = ($this->mess_model->get_mess_types_model());

            $messTypes = json_decode($jsonMessTypes,true);

            return $messTypes['messName'];
         }

      }


      public function get_vendors_list()
      {
         if(!$this->ion_auth->logged_in())
         redirect('auth/login','refresh');
         else
         {
            $data['username'] = $this->ion_auth->user()->row()->username;

            $data['group'] = $this->ion_auth->get_logged_in_user_group_names();

            $vendorsList = ($this->orders_model->get_vendors());
            echo ($vendorsList);
         }

      }


      public function vendor_details()
      {
         if(!$this->ion_auth->logged_in())
         redirect('auth/login','refresh');
         else {
            $data['username'] = $this->ion_auth->user()->row()->username;

            $data['group'] = $this->ion_auth->get_logged_in_user_group_names();
            $data['title'] = " Vendors Details";
            $this->load->view('templates/header');
            $this->load->view('templates/body',$data);
            $this->load->view('orders/vendor_details',$data);
         }
      }

      public function add_vendor()
      {

         if(!$this->ion_auth->logged_in())
         redirect('auth/login','refresh');
         else {
            $post_data = $_POST['data'];
            $data['username'] = $this->ion_auth->user()->row()->username;
            $data['group'] = $this->ion_auth->get_logged_in_user_group_names();
            $vendor = json_decode($post_data,true);
            $return = $this->orders_model->add_vendor($vendor);
            if($return == 1)
            echo 'Vendor added succesfully';
            else
            echo $return;
         }

      }

      public function edit_vendor_form()
      {


         $vendorName= $this->input->post('vendorName');

         $ownerName = $this->input->post('ownerName');
         $address= $this->input->post('address');

         $contact = $this->input->post('contact');

         $form = "
         <form name = 'edit_row' action = 'update_vendor_details' method = 'post'>
            <div class='input-field'>
               <span class='blue-text text-darken-2'>Vendor Name</span>
               <input type='text' value='".urldecode($vendorName)."' id= '".$vendorName."Disabled' name='modalVendorNameDisabled' disabled/>        
               <input type='hidden' value='".urldecode($vendorName)."' id= '".$vendorName."' name='modalVendorName'/>        

            </div>
         </div>
         <div class = 'row'>
            <div class='input-field'>
               <span class='blue-text text-darken-2'>Owner Name</span>
               <input type='text' value='".urldecode($ownerName)."' id='".$ownerName."' name='modalOwnerName'/>
            </div>
         </div>
         <div class = 'row'>
            <div class='input-field'>
               <span class='blue-text text-darken-2'>Address</span>
               <input type='text' value='".urldecode($address)."' id='".$address."' name='modalAddress'/>
            </div>
         </div>

         <div class = 'row'>
            <div class='input-field'>
               <span class='blue-text text-darken-2'>Contact</span>
               <input type='text' value='".$contact."'  id='".$contact."' name='modalContact'/>

            </div>
         </div>
         <div class='row'>
            <div class='col s8 offset-s3'>

               <!--    <a href='javascript:submit_update();' class='btn waves-effect waves-light btn-large' value='submit' type='submit' name='submit'>-->
                  <button class='btn waves-effect waves-light btn-large' value='submit' type='submit' name='submit'>
                     Submit
                     <i class='glyphicon glyphicon-chevron-right'></i>       
                  </button>

                  <button class='btn waves-effect waves-light red darken-1 btn-large' value='reset' type='reset' name='cancel'>
                     Cancel
                     <i class='glyphicon glyphicon-remove'></i>
                  </button>
               </div>
            </div>

         </form>";
         echo $form;
      }




      public function order_receive($data="")
      {


         if(!$this->ion_auth->logged_in())
         redirect('auth/login','refresh');
         else{
            $this->load->view('templates/header');
            $reload =  $this->session->flashdata('data');
            $data = $reload;
            $data['username'] = $this->ion_auth->user()->row()->username;
            $data['group'] = $this->ion_auth->get_logged_in_user_group_names();
            $data['title'] = 'Order Receival';
            $data['lesser_items'] = $this->items_model->get_lesser_items();
            $tableData = $this->items_model->get_items();
            $data['tableData'] = $tableData;
            $vendorsList = json_decode($this->orders_model->get_vendors(),true);
            $data['vendors'] = $vendorsList['vendorName'];

            if(isset($_POST['submit'])){
               $quantityAvailable = $this->input->post('quantityAvailable[]');

               $data['billNo'] = $this->input->post('billNo');
               $data['receivedDate'] = date('d-m-Y',strtotime($this->input->post('receivedDate')));
               $data['selectedVendor'] = $this->input->post('selectedVendor');
               $data['orderNo'] = $this->input->post('orderNo');


               $selectedItems = $this->input->post('selectedItems[]');

               $quantityAvailable = $this->input->post('quantityAvailable[]');

               $latestRate = $this->input->post('latestRate[]');
               $selectedQuantity = ($this->input->post('selectedQuantity[]'));

               $data['quantityAvailable'] = array();
               $data['latestRate'] = array();
               $data['selectedQuantity'] = array();
               $data['selectedItems'] = array();
               for($i=0;$i<count($selectedItems);$i++)
               {
                  if($selectedQuantity[$i] == '' && $latestRate[$i] == '')
                  continue;
                  array_push($data['selectedItems'],$selectedItems[$i]);
                  array_push($data['selectedQuantity'],$selectedQuantity[$i]);
                  array_push($data['latestRate'],$latestRate[$i]);
                  array_push($data['quantityAvailable'], $quantityAvailable[$i]);
               }
               if(count($data['selectedItems']) == 0)
               {
                  $data['message'] = "No values entered. Empty fields.";
                  $this->session->set_flashdata('data',$data);
                  redirect('orders/order_receive');
               }

               $this->session->set_flashdata('data',$data);
               redirect('orders/order_confirmation');
            }
            else
            {
               if(isset($reload) && $reload !== null)
               {

                  $this->load->view('templates/body',$data); 
                  $this->load->view('orders/order_receive',$data);

               }
               else{
                  $this->load->view('templates/body',$data); 
                  $this->load->view('orders/order_receive',$data);
               }
            }

            $this->load->view('templates/footer');
         }
      }



      public function order_confirmation()
      {


         if(!$this->ion_auth->logged_in())
         redirect('auth/login','refresh');
         else {
            $data = $this->session->flashdata('data');

            $this->load->view('templates/header');
            $data['title']= 'Order Confirmation';
            $data['username'] = $this->ion_auth->user()->row()->username;
            $data['group'] = $this->ion_auth->get_logged_in_user_group_names();
            $this->load->view('templates/body',$data); 

            $this->load->view('orders/order_confirmation',$data);


            $data['selectedItems'] = $this->input->post('selectedItems[]');
            $data['selectedQuantity'] = $this->input->post('selectedQuantity[]');
            $data['selectedVendor'] = $this->input->post('selectedVendor');
            $data['orderNo'] = $this->input->post('orderNo');
            $data['latestRate'] = $this->input->post('latestRate[]');
            $data['quantityAvailable'] = $this->input->post('quantityAvailable[]');
            $data['billNo'] = $this->input->post('billNo');
            $data['receivedDate'] = $this->input->post('receivedDate');

            if(isset($_POST['cancel']))
            {
               $data['title'] = 'Create a news item';
               $this->session->set_flashdata('data',$data);
               redirect('orders/order_receive');

            }
            else if(isset($_POST['submit']))
            {
               $return = $this->orders_model->order_receive_model($data);
               if($return == 1)
               {

                  $data['error'] = "Data Inserted Successfully";

                  $this->session->set_flashdata('data',$data);
                  redirect('orders/order_receive');
               }
               else
               {
                  $data['error'] = $return;
                  $this->session->set_flashdata('data',$data);
                  redirect('orders/order_receive');
               }

            }

            $this->load->view('templates/footer');
         }

      }



      public function vegetable_order($data="")
      {


         if(!$this->ion_auth->logged_in())
         redirect('auth/login','refresh');
         else{
            $this->load->view('templates/header');
            $reload =  $this->session->flashdata('data');
            $data = $reload;
            $data['username'] = $this->ion_auth->user()->row()->username;
            $data['group'] = $this->ion_auth->get_logged_in_user_group_names();
            $data['title'] = 'Vegetable Orders';
            $tableData = $this->items_model->get_vegetables();
            $data['tableData'] = $tableData;
            $vendorsList = json_decode($this->orders_model->get_vendors(),true);
            $data['vendors'] = $vendorsList['vendorName'];
            $data['messTypes'] = $this->get_mess_types();

            if(isset($_POST['submit'])){

               $quantityAvailable = $this->input->post('quantityAvailable[]');

               $data['billNo'] = $this->input->post('billNo');
               $data['receivedDate'] = date('d-m-Y',strtotime($this->input->post('receivedDate')));
               $data['selectedVendor'] = $this->input->post('selectedVendor');
               $data['orderNo'] = $this->input->post('orderNo');
               $data['selectedMess'] = $this->input->post('selectedMess');

               $selectedItems = $this->input->post('selectedItems[]');
               $quantityAvailable = $this->input->post('quantityAvailable[]');
               $latestRate = $this->input->post('latestRate[]');
               $selectedQuantity = ($this->input->post('selectedQuantity[]'));

               $data['quantityAvailable'] = array();
               $data['latestRate'] = array();
               $data['selectedQuantity'] = array();
               $data['selectedItems'] = array();
               for($i=0;$i<count($selectedItems);$i++)
               {
                  if($selectedQuantity[$i] == '' && $latestRate[$i] == '')
                  continue;
                  array_push($data['selectedItems'],$selectedItems[$i]);
                  array_push($data['selectedQuantity'],$selectedQuantity[$i]);
                  array_push($data['latestRate'],$latestRate[$i]);
                  array_push($data['quantityAvailable'], $quantityAvailable[$i]);
               }
               if(count($data['selectedItems']) == 0)
               {
                  $data['message'] = "No values entered. Empty fields.";
                  $this->session->set_flashdata('data',$data);
                  redirect('orders/vegetable_order');
               }

               $this->session->set_flashdata('data',$data);
               redirect('orders/vegetable_order_confirmation');
            }
            else
            {
               if(isset($reload) && $reload !== null)
               {

                  $this->load->view('templates/body',$data); 
                  $this->load->view('orders/vegetable_order',$data);

               }
               else{
                  $this->load->view('templates/body',$data); 
                  $this->load->view('orders/vegetable_order',$data);
               }
            }

            $this->load->view('templates/footer');
         }
      }


      public function vegetable_order_confirmation()
      {


         if(!$this->ion_auth->logged_in())
         redirect('auth/login','refresh');
         else {
            $data = $this->session->flashdata('data');

            $this->load->view('templates/header');
            $data['title']= 'Vegetable Order Confirmation';
            $data['username'] = $this->ion_auth->user()->row()->username;
            $data['group'] = $this->ion_auth->get_logged_in_user_group_names();
            $this->load->view('templates/body',$data); 

            $this->load->view('orders/vegetable_order_confirmation',$data);


            $data['selectedItems'] = $this->input->post('selectedItems[]');
            $data['selectedQuantity'] = $this->input->post('selectedQuantity[]');
            $data['selectedVendor'] = $this->input->post('selectedVendor');
            $data['orderNo'] = $this->input->post('orderNo');
            $data['latestRate'] = $this->input->post('latestRate[]');
            $data['billNo'] = $this->input->post('billNo');
            $data['receivedDate'] = $this->input->post('receivedDate');

            $data['selectedMess'] = $this->input->post('selectedMess');
            if(isset($_POST['cancel']))
            {
               $this->session->set_flashdata('data',$data);
               redirect('orders/vegetable_order');

            }
            else if(isset($_POST['submit']))
            {
               $return = $this->orders_model->vegetable_order_receive_model($data);
               if($return == 1)
               {

                  $data['error'] = "Data Inserted Successfully";

                  $this->session->set_flashdata('data',$data);
                  redirect('orders/vegetable_order');
               }
               else
               {
                  $data['error'] = $return;
                  $this->session->set_flashdata('data',$data);
                  redirect('orders/vegetable_order');
               }

            }

            $this->load->view('templates/footer');
         }

      }



      public function update_vendor_details($data="")
      {
         if(!$this->ion_auth->logged_in())
         redirect('auth/login','refresh');
         else {
            $data = $this->session->flashdata('data');
            $data['username'] = $this->ion_auth->user()->row()->username;
            $data['group'] = $this->ion_auth->get_logged_in_user_group_names();

            $data['ownerName'] = urldecode($this->input->post('modalOwnerName'));
            $data['address'] = urldecode($this->input->post('modalAddress'));
            $data['contact'] = $this->input->post('modalContact');
            $data['vendorName'] = urldecode($this->input->post('modalVendorName'));
            $return = $this->orders_model->update_vendor_details($data);
            if($return == 1)

            redirect('orders/vendor_details',$data);
            else
            {
               $data['error'] = $return;
               redirect('orders/vendor_details',$data);
            }

         }

      }


      public function delete_vendor()
      {
         if(!$this->ion_auth->logged_in())
         redirect('auth/login','refresh');
         else {
            $post_data = $_POST['data'];
            $data['username'] = $this->ion_auth->user()->row()->username;
            $data['group'] = $this->ion_auth->get_logged_in_user_group_names();
            $return = $this->orders_model->delete_vendor($post_data);
            if($return == 1)
            echo 'Vendor deleted succesfully';
            else
            echo $return;
         }

      }

      public function get_pending_payments()
      {
         if(!$this->ion_auth->logged_in())
         redirect('auth/login','refresh');
         else
         {
            $data['username'] = $this->ion_auth->user()->row()->username;

            $data['group'] = $this->ion_auth->get_logged_in_user_group_names();

            $pendingPayments = ($this->orders_model->get_pending_payments());
            echo ($pendingPayments);
         }

      }

      public function get_vegetable_pending_payments()
      {
         if(!$this->ion_auth->logged_in())
         redirect('auth/login','refresh');
         else
         {
            $data['username'] = $this->ion_auth->user()->row()->username;

            $data['group'] = $this->ion_auth->get_logged_in_user_group_names();

            $pendingPayments = ($this->orders_model->get_vegetable_pending_payments());
            echo ($pendingPayments);
         }

      }

      public function pending_payments()
      {

         if(!$this->ion_auth->logged_in())
         redirect('auth/login','refresh');
         else {
            $data['username'] = $this->ion_auth->user()->row()->username;

            $data['group'] = $this->ion_auth->get_logged_in_user_group_names();
            $data['title'] = "Pending Payments";
            $reload = $this->session->flashdata('data');
            if($reload != null && $reload != ""){
               $data = $data + $reload;
            }
            $this->load->view('templates/header');
            $this->load->view('templates/body',$data);
            $this->load->view('orders/pending_payments',$data);
         }
      }


      public function vegetable_pending_payments()
      {

         if(!$this->ion_auth->logged_in())
         redirect('auth/login','refresh');
         else {
            $data['username'] = $this->ion_auth->user()->row()->username;

            $data['group'] = $this->ion_auth->get_logged_in_user_group_names();
            $data['title'] = "Pending Payments";
            $reload = $this->session->flashdata('data');
            if($reload != null && $reload != ""){
               $data = $data + $reload;
            }
            $this->load->view('templates/header');
            $this->load->view('templates/body',$data);
            $this->load->view('orders/vegetable_pending_payments',$data);
         }
      }


      public function enter_payment_details($orderID="")
      {
         if(!$this->ion_auth->logged_in())
         redirect('auth/login','refresh');
         else{
            $data['username'] = $this->ion_auth->user()->row()->username;

            $data['group'] = $this->ion_auth->get_logged_in_user_group_names();
            $data['title'] = "Update Payment Details";
            $this->load->view('templates/header');
            $reload = $this->session->flashdata('data');
            if($reload != null && $reload != ""){
               $data = $data + $reload;
            }
            $data['orderID'] = $orderID;
            $this->load->view('templates/body',$data);
            $orderDetails = $this->orders_model->get_order_details($orderID);
            $data = ($data + $orderDetails);
            $this->load->view('orders/enter_payment_details',$data);
         }
      }

      public function update_payment_details()
      {
         if(!$this->ion_auth->logged_in())
         redirect('auth/login','refresh');
         else{
            $data['username'] = $this->ion_auth->user()->row()->username;

            $data['group'] = $this->ion_auth->get_logged_in_user_group_names();
            $data['title'] = "Update Payment Details";
            $this->load->view('templates/header');


            $reload =  $this->session->flashdata('data');
            if($reload != null && $reload != "")
            $data = $data + $reload;

            $this->load->view('templates/body',$data);
            $this->form_validation->set_rules('paymentDate', 'Payment Date', 'required');
            $this->form_validation->set_rules('paymentMode', 'Payment Mode', 'required');

            $this->form_validation->set_rules('paymentNumber', 'Cheque/DD Number', 'required');
            $this->form_validation->set_rules('bankName', 'Bank Name', 'required');
            $this->form_validation->set_rules('inFavourOf', 'In Favour Of', 'required');

            if(isset($_POST['submit']) && ($this->form_validation->run() === TRUE)){

               $data['paymentDate'] = $this->input->post('paymentDate');
               $data['paymentMode'] = $this->input->post('paymentMode');

               $data['paymentNumber'] = $this->input->post('paymentNumber');
               $data['bankName'] = $this->input->post('bankName');
               $data['inFavourOf'] = $this->input->post('inFavourOf');

               $data['orderID'] = ($this->input->post('orderID'));
               $this->session->set_flashdata('data',$data);
               redirect('orders/payment_confirmation');
            }
            else
            {
               $data['message'] = validation_errors();
               $orderID = $this->input->post('orderID');
               $this->session->set_flashdata('data',$data);
               redirect('orders/enter_payment_details/'.$orderID);

            }
         }
      }


      public function generate_abstract()
      {
         if(!$this->ion_auth->logged_in())
         redirect('auth/login','refresh');
         else{
            $data['username'] = $this->ion_auth->user()->row()->username;

            $data['group'] = $this->ion_auth->get_logged_in_user_group_names();
            $data['title'] = "Generate Abstract";
            $this->load->view('templates/header');


            $reload =  $this->session->flashdata('data');
            if($reload != null && $reload != "")
            $data = $data + $reload;

            $selectedOrders = $this->input->post('selectedOrders[]');

            $orderIDs = array();
            $vendorNames = array();
            $totalAmount = array();
            $receivedDates = array();
            $billNos = array();
            if(count($selectedOrders) == 0)
            {
               $data['error'] = "Select atleast one items";
               $this->session->set_flashdata('data',$data);
               redirect('orders/order_history');
            }
            foreach($selectedOrders as $order)
            {
               $orderDetails = $this->orders_model->get_order_details($order);
               array_push($orderIDs,$order);
               array_push($vendorNames,$orderDetails['vendorName'][0]);
               $total = 0;
               foreach($orderDetails['amount'] as $amount)
               $total += $amount;
               array_push($totalAmount,$total);
               array_push($receivedDates,$orderDetails['receivedDate'][0]);
               array_push($billNos,$orderDetails['billNo'][0]);
            }
            $data['orderIDs'] = $orderIDs;
            $data['vendorName'] = $vendorNames[0];
            $data['totalAmount'] = $totalAmount;
            $data['receivedDates'] = $receivedDates;
            $data['billNos'] = $billNos;
            $this->load->view('templates/body',$data);


            if(isset($_POST['submit'])){
               if(count(array_unique($vendorNames)) == 1)
               {

                  $this->load->view('orders/generate_abstract',$data);
               }

               else
               {
                  $data['error'] = "You have selected different vendors";
                  $this->session->set_flashdata('data',$data);
                  redirect('orders/order_history');
               }
            }
         }
      }

      public function generate_vegetable_abstract()
      {
         if(!$this->ion_auth->logged_in())
         redirect('auth/login','refresh');
         else{
            $data['username'] = $this->ion_auth->user()->row()->username;

            $data['group'] = $this->ion_auth->get_logged_in_user_group_names();
            $data['title'] = "Generate  Vegetable Bill Abstract";
            $this->load->view('templates/header');


            $reload =  $this->session->flashdata('data');
            if($reload != null && $reload != "")
            $data = $data + $reload;

            $selectedOrders = $this->input->post('selectedOrders[]');

            $orderIDs = array();
            $vendorNames = array();
            $totalAmount = array();
            $receivedDates = array();
            $billNos = array();
            if(count($selectedOrders) == 0)
            {
               $data['error'] = "Select atleast one items";
               $this->session->set_flashdata('data',$data);
               redirect('orders/vegetable_order_history');
            }
            foreach($selectedOrders as $order)
            {
               $orderDetails = $this->orders_model->get_vegetable_order_details($order);
               array_push($orderIDs,$order);
               array_push($vendorNames,$orderDetails['vendorName'][0]);
               $total = 0;
               foreach($orderDetails['amount'] as $amount)
               $total += $amount;
               array_push($totalAmount,$total);
               array_push($receivedDates,$orderDetails['receivedDate'][0]);
               array_push($billNos,$orderDetails['billNo'][0]);
            }
            $data['orderIDs'] = $orderIDs;
            $data['vendorName'] = $vendorNames[0];
            $data['totalAmount'] = $totalAmount;
            $data['receivedDates'] = $receivedDates;
            $data['billNos'] = $billNos;
            $this->load->view('templates/body',$data);


            if(isset($_POST['submit'])){
               if(count(array_unique($vendorNames)) == 1)
               {

                  $this->load->view('orders/generate_vegetable_abstract',$data);
               }

               else
               {
                  $data['error'] = "You have selected different vendors";
                  $this->session->set_flashdata('data',$data);
                  redirect('orders/vegetable_order_history');
               }
            }
         }
      }


      public function payment_confirmation()
      {


         if(!$this->ion_auth->logged_in())
         redirect('auth/login','refresh');
         else {
            $data = $this->session->flashdata('data');

            $this->load->view('templates/header');
            $data['title']= 'Payment Confirmation';
            $data['username'] = $this->ion_auth->user()->row()->username;
            $data['group'] = $this->ion_auth->get_logged_in_user_group_names();
            $this->load->view('templates/body',$data); 
            $this->load->view('orders/payment_confirmation',$data);


            $data['paymentDate'] = $this->input->post('paymentDate');
            $data['paymentMode'] = $this->input->post('paymentMode');

            $data['paymentNumber'] = $this->input->post('paymentNumber');
            $data['bankName'] = $this->input->post('bankName');
            $data['inFavourOf'] = $this->input->post('inFavourOf');

            $data['orderID'] = ($this->input->post('orderID'));



            if(isset($_POST['cancel']))
            {
               $orderID = $this->input->post('orderID');
               $this->session->set_flashdata('data',$data);
               redirect('orders/enter_payment_details/'.$orderID);

            }
            else if(isset($_POST['submit']))
            {
               $return = $this->orders_model->payments_update_model($data);
               if($return == 1)
               {

                  $data['error'] = "Data Inserted Successfully";

                  $this->session->set_flashdata('data',$data);
                  redirect('orders/pending_payments',$data);
               }
               else
               {
                  $data['error'] = $return;
                  $this->session->set_flashdata('data',$data);
                  redirect('orders/enter_payment_details/'.$data['orderID']);
               }

            }

            $this->load->view('templates/footer');
         }

      }


      public function get_order_history($from,$to)
      {
         if(!$this->ion_auth->logged_in())
         redirect('auth/login','refresh');
         else
         {
            $data['username'] = $this->ion_auth->user()->row()->username;

            $data['group'] = $this->ion_auth->get_logged_in_user_group_names();


            $from = urldecode($from);
            $to= urldecode($to);
            $from = date('d-m-Y',strtotime($from));
            $to = date('d-m-Y',strtotime($to));
            $orderHistory = ($this->orders_model->generate_order_history($from,$to));
            echo json_encode($orderHistory);
         }


      }

      public function order_history()
      {

         if(!$this->ion_auth->logged_in())
         redirect('auth/login','refresh');
         else{
            $data = $this->session->flashdata('data');
            $data['title'] = "Orders History";
            $data['username'] = $this->ion_auth->user()->row()->username;


            $data['group'] = $this->ion_auth->get_logged_in_user_group_names();


            $this->load->view('templates/header');
            $this->load->view('templates/body',$data);
            $this->load->view('orders/order_history',$data);
         }
      }

      public function get_vegetable_order_history($from,$to)
      {
         if(!$this->ion_auth->logged_in())
         redirect('auth/login','refresh');
         else
         {
            $data['username'] = $this->ion_auth->user()->row()->username;

            $data['group'] = $this->ion_auth->get_logged_in_user_group_names();


            $from = urldecode($from);
            $to= urldecode($to);
            $from = date('d-m-Y',strtotime($from));
            $to = date('d-m-Y',strtotime($to));
            $orderHistory = ($this->orders_model->generate_vegetable_order_history($from,$to));
            echo json_encode($orderHistory);
         }


      }


      public function vegetable_order_history()
      {

         if(!$this->ion_auth->logged_in())
         redirect('auth/login','refresh');
         else{
            $data = $this->session->flashdata('data');
            $data['title'] = "Vegetable Orders History";
            $data['username'] = $this->ion_auth->user()->row()->username;


            $data['group'] = $this->ion_auth->get_logged_in_user_group_names();

            $this->load->view('templates/header');
            $this->load->view('templates/body',$data);
            $this->load->view('orders/vegetable_order_history',$data);
         }
      }



      public function get_payment_history($from,$to)
      {
         if(!$this->ion_auth->logged_in())
         redirect('auth/login','refresh');
         else
         {
            $data['username'] = $this->ion_auth->user()->row()->username;

            $data['group'] = $this->ion_auth->get_logged_in_user_group_names();


            $from = urldecode($from);
            $to= urldecode($to);
            $from = date('d-m-Y',strtotime($from));
            $to = date('d-m-Y',strtotime($to));
            $paymentHistory = ($this->orders_model->generate_payment_history($from,$to));
            echo json_encode($paymentHistory);
         }


      }

      public function payment_history()
      {

         if(!$this->ion_auth->logged_in())
         redirect('auth/login','refresh');
         else{
            $data['title'] = "Payment History";
            $data['username'] = $this->ion_auth->user()->row()->username;


            $data['group'] = $this->ion_auth->get_logged_in_user_group_names();

            $this->load->view('templates/header');
            $this->load->view('templates/body',$data);


            $this->load->view('orders/payment_history',$data);
         }
      }


   }

