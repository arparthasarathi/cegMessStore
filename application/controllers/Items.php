<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Items extends CI_Controller {

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
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->helper('date');
		$this->load->library('ion_auth');

	}

	public function getMessTypes()
	{
		return $this->messTypes;
	}

	public function check_for_lesser_items()
	{
		$items = $this->items_model->get_lesser_items();
		return $items;
	}


	public function return_item($data="")
	{



		if(!$this->ion_auth->logged_in())
			redirect('auth/login','refresh');
		else {
			$this->load->view('templates/header');
			$reload =  $this->session->flashdata('data');
			$data = $reload;
			$data['username'] = $this->ion_auth->user()->row()->username;
			$data['group'] = $this->ion_auth->get_logged_in_user_group_names();
			$data['messTypes'] = $this->get_mess_types();
			$data['title'] = 'Return Items to Store';
			$this->form_validation->set_rules('selectedItems[]', 'Atleast select one item', 'required');
			$this->form_validation->set_rules('selectedQuantity[]', 'Quantity', 'required');
			if(isset($_POST['submit']) && ($this->form_validation->run() === TRUE)){

				$data['selectedItems'] = $this->input->post('selectedItems[]');
				$data['selectedMess'] = $this->input->post('selectedMess');
				$data['latestRate'] = $this->input->post('latestRate[]');
				$data['quantitySupplied'] = $this->input->post('quantitySupplied[]');

				$data['selectedQuantity'] = $this->input->post('selectedQuantity[]');
				$quantityAvailable = json_decode($this->items_model->get_items($data['selectedItems']),true);
				$data['quantityAvailable'] = $quantityAvailable['quantityAvailable'];
				$this->session->set_flashdata('data',$data);
				redirect('items/return_confirmation');
			}
			else
			{
				if(isset($reload) && $reload !== null)
				{

					$this->load->view('templates/body',$data); 
					$this->load->view('items/return_item',$data);
				}

				else
				{

					$this->load->view('templates/body',$data); 
					$this->load->view('items/return_item',$data);
				}
			}
			$this->load->view('templates/footer');
		}
	}


	public function getMessConsumptionForToday($messName = null)
	{

		$date = date('Y-m-d');	
		$messName = urldecode($messName);
		$consumedItems = ($this->items_model->get_consumed_items($messName,$date)); 
		echo json_encode($consumedItems);
	}

	public function edit_row_form()
	{
		$suppliedDate = $this->input->post('suppliedDate');
		$messName= $this->input->post('messName');
		$itemName = $this->input->post('itemName');
		$quantitySupplied = $this->input->post('quantitySupplied');
		$latestRate = $this->input->post('latestRate');
		$form = "
			<form name = 'edit_row' action = '../items/update_issued_items' method = 'post'>
			<div class = 'row'>
			<div class='input-field'>
			<span class='blue-text text-darken-2'>Supplied Date</span>
			<input type='text' value='".$suppliedDate."' id = '".$suppliedDate."Disabled' name='suppliedDateDisabled' disabled/>
			<input type='hidden' value='".$suppliedDate."' id = '".$suppliedDate."' name='modalSuppliedDate'/>
			</div>
			</div>
			<div class = 'row'>
			<div class='input-field'>
			<span class='blue-text text-darken-2'>Item Name</span>
			<input type='hidden' value='".urldecode($itemName)."' id= '".$itemName."' name='modalItemName'/>	
			<input type='text' value='".urldecode($itemName)."' id= '".$itemName."Disabled' name='itemNameDisabled' disabled/>
			</div>
			</div>
			<div class = 'row'>
			<div class='input-field'>
			<span class='blue-text text-darken-2'>Quantity SUpplied</span>
			<input type='text' value='".$quantitySupplied."' id='".$quantitySupplied."' name='modalQuantitySupplied'/>
			</div>
			</div>
			<div class = 'row'>
			<div class='input-field'>
			<span class='blue-text text-darken-2'>Latest Rate</span>
			<input type='text' value='".$latestRate."'  id='".$latestRate."' name='modalRate'/>
			<input type='hidden' value='".urldecode($messName)."' name='modalMessName'/>
			</div>
			</div>
			<div class='row'>
			<div class='col s8 offset-s3'>
		<!--	<a href='javascript:submit_update();' class='btn waves-effect waves-light btn-large' value='submit' type='submit' name='submit'>-->
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

	public function return_confirmation()
	{


		if(!$this->ion_auth->logged_in())
			redirect('auth/login','refresh');
		else{	
			$data = $this->session->flashdata('data');

			$this->load->view('templates/header');

			$data['title'] = 'Return Confirmation';
			$data['username'] = $this->ion_auth->user()->row()->username;
			$data['group'] = $this->ion_auth->get_logged_in_user_group_names();
			$this->load->view('templates/body',$data); 

			$this->load->view('items/return_confirmation',$data);


			$data['selectedItems'] = $this->input->post('selectedItems[]');
			$data['quantitySupplied'] = $this->input->post('quantitySupplied[]');
			$data['quantityAvailable'] = $this->input->post('quantityAvailable[]');
			$data['selectedQuantity'] = $this->input->post('selectedQuantity[]');
			$data['latestRate'] = $this->input->post('latestRate[]');
			$data['selectedMess'] = $this->input->post('selectedMess');

			if(isset($_POST['cancel']))
			{
				$data['title'] = 'Create a news item';
				$this->session->set_flashdata('data',$data);
				redirect('items/return_item');

			}
			else if(isset($_POST['submit']))
			{
				$return = $this->items_model->return_item_model($data);
				if($return == 1)
				{

					$data['error'] = "Data Inserted Successfully";
					$data['title']="Inserted";

					$this->session->set_flashdata('data',$data);
					redirect('items/return_item');
				}
				else
				{
					$data['error'] = $return;
					$data['title'] = "Create news item";
					$this->session->set_flashdata('data',$data);
					redirect('items/return_item');
				}

			}
			$this->load->view('templates/footer');
		}

	}

	public function issue_item($data="")
	{


		if(!$this->ion_auth->logged_in())
			redirect('auth/login','refresh');
		else{
			$this->load->view('templates/header');
			$reload =  $this->session->flashdata('data');
			$data = $reload;
			$data['username'] = $this->ion_auth->user()->row()->username;
			$data['group'] = $this->ion_auth->get_logged_in_user_group_names();
			$data['title'] = 'Issue items to mess';
			$data['lesser_items'] = $this->check_for_lesser_items();
			$tableData = $this->items_model->get_items();
			$data['tableData'] = $tableData;
			$data['messTypes'] = $this->get_mess_types();



			$this->form_validation->set_rules('selectedItems[]', 'Atleast select one item', 'required');
			$this->form_validation->set_rules('selectedQuantity[]', 'Quantity', 'required');

			if(isset($_POST['submit']) && ($this->form_validation->run() === TRUE)){
				$data['selectedItems'] = $this->input->post('selectedItems[]');

				$data['selectedMess'] = $this->input->post('selectedMess');
				$data['quantityAvailable'] = $this->input->post('quantityAvailable[]');

				$data['latestRate'] = $this->input->post('latestRate[]');
				$data['selectedQuantity'] = ($this->input->post('selectedQuantity[]'));
				$this->session->set_flashdata('data',$data);
				redirect('items/issue_confirmation');
			}
			else
			{
				if(isset($reload) && $reload !== null)
				{

					$this->load->view('templates/body',$data); 
					$this->load->view('items/issue_item',$data);

				}
				else{
					$this->load->view('templates/body',$data); 
					$this->load->view('items/issue_item',$data);
				}
			}

			$this->load->view('templates/footer');
		}
	}



	public function issue_confirmation()
	{


		if(!$this->ion_auth->logged_in())
			redirect('auth/login','refresh');
		else {
			$data = $this->session->flashdata('data');

			$this->load->view('templates/header');
			$data['title']= 'Issue Confirmation';
			$data['username'] = $this->ion_auth->user()->row()->username;
			$data['group'] = $this->ion_auth->get_logged_in_user_group_names();
			$this->load->view('templates/body',$data); 

			$this->load->view('items/issue_confirmation',$data);


			$data['selectedItems'] = $this->input->post('selectedItems[]');
			$data['selectedQuantity'] = $this->input->post('selectedQuantity[]');
			$data['quantityAvailable'] = $this->input->post('quantityAvailable[]');
			$data['latestRate'] = $this->input->post('latestRate[]');
			$data['selectedMess'] = $this->input->post('selectedMess');


			if(isset($_POST['cancel']))
			{
				$data['title'] = 'Create a news item';
				$this->session->set_flashdata('data',$data);
				redirect('items/issue_item');

			}
			else if(isset($_POST['submit']))
			{
				$return = $this->items_model->issue_item_model($data);
				if($return == 1)
				{

					$data['error'] = "Data Inserted Successfully";
					$data['title']="Inserted";

					$this->session->set_flashdata('data',$data);
					redirect('items/issue_item');
				}
				else
				{
					$data['error'] = $return;
					$data['title'] = "Create news item";
					$this->session->set_flashdata('data',$data);
					redirect('items/issue_item');
				}

			}

			$this->load->view('templates/footer');
		}

	}


	public function add_item($data="")
	{


		if(!$this->ion_auth->logged_in())
			redirect('auth/login','refresh');
		else{
			$this->form_validation->set_rules('itemName[]', 'Item Name', 'required');

			$this->form_validation->set_rules('itemRate[]', 'Item Rate', 'required');


			$this->form_validation->set_rules('quantityAvailable[]', 'Quantity Available', 'required');
			$this->form_validation->set_rules('minimumQuantity[]', 'Quantity Available', 'required');

			$this->load->view('templates/header');

			$reload =  $this->session->flashdata('data');
			$data =$reload;
			$data['username'] = $this->ion_auth->user()->row()->username;
			$data['group'] = $this->ion_auth->get_logged_in_user_group_names();
			$data['title'] ='Add Items to Store';



			if(isset($_POST['cancel']))
			{
				redirect('items/add_item');
			}

			else if(isset($_POST['submit'])){
				$data['itemName'] = $this->input->post('itemName[]');
				$data['itemRate'] = $this->input->post('itemRate[]');
				$data['quantityAvailable'] = $this->input->post('quantityAvailable[]');

				$data['minimumQuantity'] = $this->input->post('minimumQuantity[]');
				$this->session->set_flashdata('data',$data);

				if ($this->form_validation->run() === FALSE){

					$this->load->view('templates/body',$data);
					$this->load->view('items/add_item',$data);

				}
				else
					redirect('items/add_confirmation');
			}
			else
			{
				if(isset($reload) && $reload !== null)
				{

					$this->load->view('templates/body',$data);
					$this->load->view('items/add_item',$reload);
				}
				else{

					$this->load->view('templates/body',$data);
					$this->load->view('items/add_item',$data);
				}
			}

			$this->load->view('templates/footer');
		}
	}






	public function add_confirmation()
	{	

		if(!$this->ion_auth->logged_in())
			redirect('auth/login','refresh');
		else {
			$data = $this->session->flashdata('data');
			$this->load->view('templates/header');
			$data['username'] = $this->ion_auth->user()->row()->username;
			$data['group'] = $this->ion_auth->get_logged_in_user_group_names();
			$data['title'] = 'Add Confirmation';

			$this->load->view('templates/body',$data);


			$this->load->view('items/add_confirmation',$data);

			$data['itemName'] = $this->input->post('itemName[]');
			$data['itemRate'] = $this->input->post('itemRate[]');

			$data['quantityAvailable'] = $this->input->post('quantityAvailable[]');

			$data['minimumQuantity'] = $this->input->post('minimumQuantity[]');
			if(isset($_POST['cancel']))
			{
				$data['title'] = 'Create a news item';
				$this->session->set_flashdata('data',$data);
				redirect('items/add_item');	

			}
			else if(isset($_POST['submit']))
			{
				$return = $this->items_model->add_item_model($data);
				unset($data['itemName']);
				unset($data['itemRate']);
				unset($data['quantityAvailable']);
				if($return == 1)
				{
					$data['error'] = "Data Inserted Successfully";

					$this->session->set_flashdata('data',$data);
					redirect('items/add_item');
				}
				else
				{
					$data['error'] = $return;
					$this->session->set_flashdata('data',$data);
					redirect('items/add_item');
				}

			}
		}

	}

	public function edit_issued_items()
	{

		if(!$this->ion_auth->logged_in())
			redirect('auth/login','refresh');
		else {
			$data['username'] = $this->ion_auth->user()->row()->username;

			$data['group'] = $this->ion_auth->get_logged_in_user_group_names();
			$data['title'] = " Edit Mess Consumption";
			$this->load->view('templates/header');
			$this->load->view('templates/body',$data);
			$data['messTypes'] = $this->get_mess_types();
			$this->load->view('items/edit_issued_items',$data);
		}
	}

	public function update_issued_items($data="")
	{
		if(!$this->ion_auth->logged_in())
                        redirect('auth/login','refresh');
                else {
                        $data = $this->session->flashdata('data');
			$data['username'] = $this->ion_auth->user()->row()->username;
                        $data['group'] = $this->ion_auth->get_logged_in_user_group_names();
			$data['suppliedDate'] = $this->input->post('modalSuppliedDate');
			$data['itemName'] = urldecode($this->input->post('modalItemName'));
			$data['quantitySupplied'] = $this->input->post('modalQuantitySupplied');
			$data['latestRate'] = $this->input->post('modalRate');
			$data['messName'] = urldecode($this->input->post('modalMessName'));
			$return = $this->items_model->update_issued_items($data);
			if($return == 1)

				redirect('reports/mess_consumption',$data);
			else
			{
				$data['error'] = $return;
				redirect('reports/mess_consumption',$data);
			}

		}

	}


}
