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


	}

	public function getMessTypes()
	{
		return $this->messTypes;
	}

	public function return_item($data="")
	{
		$this->load->view('templates/header');
		$this->load->view('templates/body');

		$data['title'] = 'Create a news item';
		$reload =  $this->session->flashdata('data');
		$data = $reload;
		$data['messTypes'] = $this->getMessTypes();

		$this->form_validation->set_rules('selectedItems[]', 'Atleast select one item', 'required');
		if(isset($_POST['submit']) && ($this->form_validation->run() === TRUE)){
			
			$data['selectedItems'] = $this->input->post('selectedItems[]');
			$data['selectedMess'] = $this->input->post('selectedMess');
			$quantityAvailable = json_decode($this->items_model->get_items($data['selectedItems']),true);
                        $data['quantityAvailable'] = $quantityAvailable['quantityAvailable'];

			$consumedQuantity = $this->items_model->get_consumed_quantity(
					$data['selectedMess'],date('Y-m-d'),$data['selectedItems']);
			$quantitySupplied = json_decode($consumedQuantity,true);
			$data['quantitySupplied'] = $quantitySupplied['quantitySupplied'];
			$this->session->set_flashdata('data',$data);
			redirect('items/return_quantity');
		}
		else
		{
			if(isset($reload) && $reload !== null)
				$this->load->view('items/return_item',$data);
			else
				$this->load->view('items/return_item',$data);
		}
		$this->load->view('templates/footer');
	}


	public function getMessConsumptionForToday($messName = null)
	{

		$date = date('Y-m-d');	
		$messName = urldecode($messName);
		$consumedItems = ($this->items_model->get_consumed_items($messName,$date)); 
		echo json_encode($consumedItems['itemNames']);
	}
	
	
	public function return_quantity()
	{
		$data = $this->session->flashdata('data');

		$this->load->view('templates/header');
		$this->load->view('templates/body');
		$reload =  $this->session->flashdata('data');	
		$this->form_validation->set_rules('quantitySupplied[]', 'Quantity Required', 'required');


		if(isset($_POST['cancel']))
		{
			$this->session->set_flashdata('data',$data);
			redirect('items/return_item');
		}

		else if(isset($_POST['submit']))
		{
			$data['selectedItems'] = $this->input->post('selectedItems[]');

			$data['quantitySupplied'] = $this->input->post('quantitySupplied[]');


			$data['quantityAvailable'] = $this->input->post('quantityAvailable[]');
			$data['selectedQuantity'] = $this->input->post('selectedQuantity[]');
			$data['latestRate'] = $this->input->post('latestRate[]');
			$data['selectedMess'] = $this->input->post('selectedMess');
			$this->session->set_flashdata('data',$data);

			if ($this->form_validation->run() === FALSE)
			{
				$this->load->view('items/return_quantity',$data);
			}
			else{
				redirect('items/return_confirmation');
			}
		}
		else
		{
			if(isset($reload) && $reload !== null)
				$this->load->view('items/return_quantity',$reload);
			else
				$this->load->view('items/return_quantity',$data);

		}
		$this->load->view('templates/footer');

	}


	public function return_confirmation()
	{
		$data = $this->session->flashdata('data');

		$this->load->view('templates/header');
                $this->load->view('templates/body');

		$this->load->view('items/return_confirmation',$data);

		$data['selectedItems'] = $this->input->post('selectedItems[]');
		$data['quantitySupplied'] = $this->input->post('quantitySupplied[]');
		$data['quantityAvailable'] = $this->input->post('quantityAvailable[]');
		$data['selectedQuantity'] = $this->input->post('selectedQuantity[]');
		$data['latestRate'] = $this->input->post('latestRate[]');
		$data['selectedMess'] = $this->input->post('selectedMess');
		$this->load->view('templates/header');
		$this->load->view('templates/body');

		if(isset($_POST['cancel']))
		{
			$data['title'] = 'Create a news item';
			$this->session->set_flashdata('data',$data);
			redirect('items/return_quantity');

		}
		else if(isset($_POST['submit']))
		{
			$return = $this->items_model->return_item_model($data);
			if($return == 1)
			{

				$data['msg'] = "Data Inserted Successfully";
				$data['title']="Inserted";

				$this->session->set_flashdata('data',$data);
				redirect('items/return_item');
			}
			else
			{
				$data['msg'] = $return;
				$data['title'] = "Create news item";
				$this->session->set_flashdata('data',$data);
				redirect('items/return_quantity');
			}

		}
		$this->load->view('templates/footer');

	}

	public function issue_item($data="")
	{

		$this->load->view('templates/header');
		$this->load->view('templates/body');

		$data['title'] = 'Create a news item';
		$reload =  $this->session->flashdata('data');
		$data = $reload;
		$tableData = $this->items_model->get_items();
		$data['tableData'] = $tableData;
		$data['messTypes'] = $this->getMessTypes();

		$this->form_validation->set_rules('selectedItems[]', 'Atleast select one item', 'required');
		if(isset($_POST['submit']) && ($this->form_validation->run() === TRUE)){
			$data['selectedItems'] = $this->input->post('selectedItems[]');
			$data['selectedMess'] = $this->input->post('selectedMess');
			$quantityAvailable = json_decode($this->items_model->get_items($data['selectedItems']),true);
			$data['quantityAvailable'] = $quantityAvailable['quantityAvailable'];
			$data['latestRate'] = $quantityAvailable['latestRate'];
			$this->session->set_flashdata('data',$data);
			redirect('items/issue_quantity');
		}
		else
		{
			if(isset($reload) && $reload !== null)
				$this->load->view('items/issue_item',$data);
			else
				$this->load->view('items/issue_item',$data);
		}

		$this->load->view('templates/footer');
	}


	public function issue_quantity()
	{
		$data = $this->session->flashdata('data');

		$this->load->view('templates/header');
		$this->load->view('templates/body');
		$reload =  $this->session->flashdata('data');	
		$this->form_validation->set_rules('selectedQuantity[]', 'Quantity Required', 'required');


		if(isset($_POST['cancel']))
		{
			$this->session->set_flashdata('data',$data);
			redirect('items/issue_item');
		}

		else if(isset($_POST['submit']))
		{
			$data['selectedItems'] = $this->input->post('selectedItems[]');
			$data['selectedQuantity'] = $this->input->post('selectedQuantity[]');
			$data['quantityAvailable'] = $this->input->post('quantityAvailable[]');
			$data['latestRate'] = $this->input->post('latestRate[]');
			$data['selectedMess'] = $this->input->post('selectedMess');
			$this->session->set_flashdata('data',$data);

			if ($this->form_validation->run() === FALSE)
			{
				$this->load->view('items/issue_quantity',$data);
			}
			else{
				redirect('items/issue_confirmation');
			}
		}
		else
		{
			if(isset($reload) && $reload !== null)
				$this->load->view('items/issue_quantity',$reload);
			else
				$this->load->view('items/issue_quantity',$data);

		}
		
		$this->load->view('templates/footer');

	}


	public function issue_confirmation()
	{
		$data = $this->session->flashdata('data');

		$this->load->view('templates/header');
                $this->load->view('templates/body');
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
			redirect('items/issue_quantity');

		}
		else if(isset($_POST['submit']))
		{
			$return = $this->items_model->issue_item_model($data);
			if($return == 1)
			{

				$data['msg'] = "Data Inserted Successfully";
				$data['title']="Inserted";

				$this->session->set_flashdata('data',$data);
				redirect('items/issue_item');
			}
			else
			{
				$data['msg'] = $return;
				$data['title'] = "Create news item";
				$this->session->set_flashdata('data',$data);
				redirect('items/issue_quantity');
			}

		}
		
		$this->load->view('templates/footer');

	}






	public function add_item($data="")
	{
		$this->form_validation->set_rules('itemName[]', 'Item Name', 'required');

		$this->form_validation->set_rules('itemRate[]', 'Item Rate', 'required');

		$this->form_validation->set_rules('quantityAvailable[]', 'Quantity Available', 'required');

		$this->load->view('templates/header');
		$this->load->view('templates/body');

		$data['title'] = 'Create a news item';
		$reload =  $this->session->flashdata('data');


		if(isset($_POST['cancel']))
		{
			redirect('items/add_item');
		}

		else if(isset($_POST['submit'])){
			$data['itemName'] = $this->input->post('itemName[]');
			$data['itemRate'] = $this->input->post('itemRate[]');
			$data['quantityAvailable'] = $this->input->post('quantityAvailable[]');
			$data['error'] = $this->form_validation->error_array();

			$this->session->set_flashdata('data',$data);

			if ($this->form_validation->run() === FALSE){
				$this->load->view('items/add_item',$data);

			}
			else
				redirect('items/add_confirmation');
		}
		else
		{
			if(isset($reload) && $reload !== null)
				$this->load->view('items/add_item',$reload);
			else
				$this->load->view('items/add_item',$data);

		}

		$this->load->view('templates/footer');
	}






	public function add_confirmation()
	{
		$data = $this->session->flashdata('data');
		$this->load->view('templates/header');
		$this->load->view('templates/body');


		$this->load->view('items/add_confirmation',$data);

		$data['itemName'] = $this->input->post('itemName[]');
		$data['itemRate'] = $this->input->post('itemRate[]');
		$data['quantityAvailable'] = $this->input->post('quantityAvailable[]');

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
				$data['msg'] = "Data Inserted Successfully";
				$data['title']="Inserted cess";

				$this->session->set_flashdata('data',$data);
				redirect('items/add_item');
			}
			else
			{
				$data['msg'] = $return;
				$data['title'] = "Create news item";
				$this->session->set_flashdata('data',$data);
				redirect('items/add_item');
			}

		}

	}
}
