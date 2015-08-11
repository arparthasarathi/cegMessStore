<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mess extends CI_Controller {

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

	public function check_for_lesser_items()
	{
		$items = $this->items_model->get_lesser_items();
		return $items;
	}



	public function mess_details()
	{

		if(!$this->ion_auth->logged_in())
			redirect('auth/login','refresh');
		else {
			$data['username'] = $this->ion_auth->user()->row()->username;

			$data['group'] = $this->ion_auth->get_logged_in_user_group_names();
			$data['title'] = "Mess Details";
			$this->load->view('templates/header');
			$this->load->view('templates/body',$data);
			$this->load->view('mess/mess_details',$data);
		}
	}

	public function get_mess_details()
	{
		if(!$this->ion_auth->logged_in())
			redirect('auth/login','refresh');
		else
		{
			$data['username'] = $this->ion_auth->user()->row()->username;

			$data['group'] = $this->ion_auth->get_logged_in_user_group_names();

			$messList = ($this->mess_model->get_mess_types_model());
			echo ($messList);
		}

	}


	public function edit_mess_form()
	{

		
		$messName= $this->input->post('messName');
		$messIncharge = $this->input->post('messIncharge');
		
		$contact = $this->input->post('contact');

		$form = "
			<form name = 'edit_row' action = 'update_mess_details' method = 'post'>
			<div class='input-field'>
			<span class='blue-text text-darken-2'>Mess Name</span>
			<input type='hidden' value='".urldecode($messName)."' id= '".$messName."' name='modalMessName'/>	
			<input type='text' value='".urldecode($messName)."' id= '".$messName."Disabled' name='messNameDisabled' disabled/>

			</div>
			</div>
			<div class = 'row'>
			<div class='input-field'>
			<span class='blue-text text-darken-2'>Mess Incharge</span>
			<input type='text' value='".urldecode($messIncharge)."' id='".$messIncharge."' name='modalMessIncharge'/>
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

	
	public function add_mess()
	{

		 if(!$this->ion_auth->logged_in())
                        redirect('auth/login','refresh');
         else {
			$post_data = $_POST['data'];
                        $data['username'] = $this->ion_auth->user()->row()->username;
                        $data['group'] = $this->ion_auth->get_logged_in_user_group_names();
			$mess = json_decode($post_data,true);

			$mess['messName'] = urldecode($mess['messName']);
			$mess['messIncharge'] = urldecode($mess['messIncharge']);
			$return = $this->mess_model->add_mess($mess);
			if($return == 1)
			echo 'Mess added succesfully';
			else
			echo $return;
                }

	}

	public function delete_mess()
	{
		if(!$this->ion_auth->logged_in())
                        redirect('auth/login','refresh');
	         else {
			$post_data = $_POST['data'];
                        $data['username'] = $this->ion_auth->user()->row()->username;
                        $data['group'] = $this->ion_auth->get_logged_in_user_group_names();
			$return = $this->mess_model->delete_mess($post_data);
			if($return == 1)
			echo 'Mess deleted succesfully';
			else
			echo $return;
                }

	}

	public function update_mess_details($data="")
	{
		if(!$this->ion_auth->logged_in())
                        redirect('auth/login','refresh');
                else {
                        $data = $this->session->flashdata('data');
			$data['username'] = $this->ion_auth->user()->row()->username;
                        $data['group'] = $this->ion_auth->get_logged_in_user_group_names();
			$data['messIncharge'] = $this->input->post('modalMessIncharge');
			$data['contact'] = $this->input->post('modalContact');
			$data['messName'] = urldecode($this->input->post('modalMessName'));
			$return = $this->mess_model->update_mess_details($data);
			if($return == 1)

				redirect('mess/mess_details',$data);
			else
			{
				$data['error'] = $return;
				redirect('mess/mess_details',$data);
			}

		}

	}

	public function get_mess_bill_report($messName,$from,$to)
	{


		if(!$this->ion_auth->logged_in())
			redirect('auth/login','refresh');
		else{
			$data['username'] = $this->ion_auth->user()->row()->username;

			$data['group'] = $this->ion_auth->get_logged_in_user_group_names();

			$messName = urldecode($messName);
			$from = urldecode($from);
			$to= urldecode($to);
			$from = date('Y-m-d',strtotime($from));
			$to = date('Y-m-d',strtotime($to));
			$messBill = ($this->mess_model->generate_mess_bill($messName,$from,$to));
			echo json_encode($messBill);
		}

	}

	public function get_mess_vegetable_bill_report($messName,$from,$to)
	{


		if(!$this->ion_auth->logged_in())
			redirect('auth/login','refresh');
		else{
			$data['username'] = $this->ion_auth->user()->row()->username;

			$data['group'] = $this->ion_auth->get_logged_in_user_group_names();

			$messName = urldecode($messName);
			$from = urldecode($from);
			$to= urldecode($to);
			$from = date('Y-m-d',strtotime($from));
			$to = date('Y-m-d',strtotime($to));
			$messBill = ($this->mess_model->generate_mess_vegetable_bill($messName,$from,$to));
			echo json_encode($messBill);
		}

	}

	public function get_mess_consumption_report($messName,$from,$to)
	{
		if(!$this->ion_auth->logged_in())
			redirect('auth/login','refresh');
		else
		{
			$data['username'] = $this->ion_auth->user()->row()->username;

			$data['group'] = $this->ion_auth->get_logged_in_user_group_names();

			$messName = urldecode($messName);
			$from = urldecode($from);
			$to= urldecode($to);
			$from = date('Y-m-d',strtotime($from));
			$to = date('Y-m-d',strtotime($to));
			$messConsumption = ($this->mess_model->generate_mess_consumption($messName,$from,$to));
			echo json_encode($messConsumption);
		}

	}


	public function get_mess_vegetable_consumption_report($messName,$from,$to)
	{
		if(!$this->ion_auth->logged_in())
			redirect('auth/login','refresh');
		else
		{
			$data['username'] = $this->ion_auth->user()->row()->username;

			$data['group'] = $this->ion_auth->get_logged_in_user_group_names();

			$messName = urldecode($messName);
			$from = urldecode($from);
			$to= urldecode($to);
			$from = date('Y-m-d',strtotime($from));
			$to = date('Y-m-d',strtotime($to));
			$messConsumption = ($this->mess_model->generate_mess_vegetable_consumption($messName,$from,$to));
			echo json_encode($messConsumption);
		}

	}


	public function get_mess_return_report($messName,$from,$to)
	{
		if(!$this->ion_auth->logged_in())
			redirect('auth/login','refresh');
		else
		{
			$data['username'] = $this->ion_auth->user()->row()->username;

			$data['group'] = $this->ion_auth->get_logged_in_user_group_names();

			$messName = urldecode($messName);
			$from = urldecode($from);
			$to= urldecode($to);
			$from = date('Y-m-d',strtotime($from));
			$to = date('Y-m-d',strtotime($to));
			$messReturn = ($this->mess_model->generate_mess_return($messName,$from,$to));
			echo json_encode($messReturn);
		}

	}

	public function mess_bill()
	{

		if(!$this->ion_auth->logged_in())
			redirect('auth/login','refresh');
		else{
			$data['title'] = "Mess Bill";
			$data['username'] = $this->ion_auth->user()->row()->username;


			$data['group'] = $this->ion_auth->get_logged_in_user_group_names();

			$this->load->view('templates/header');
			$this->load->view('templates/body',$data);
			$data['messTypes'] = $this->get_mess_types();

			$this->load->view('mess/mess_bill',$data);
		}
	}

	public function mess_vegetable_bill()
	{

		if(!$this->ion_auth->logged_in())
			redirect('auth/login','refresh');
		else{
			$data['title'] = "Mess Vegetable Bill";
			$data['username'] = $this->ion_auth->user()->row()->username;


			$data['group'] = $this->ion_auth->get_logged_in_user_group_names();

			$this->load->view('templates/header');
			$this->load->view('templates/body',$data);
			$data['messTypes'] = $this->get_mess_types();

			$this->load->view('mess/mess_vegetable_bill',$data);
		}
	}

	public function mess_consumption()
	{
		if(!$this->ion_auth->logged_in())
			redirect('auth/login','refresh');
		else {
			$data['title'] = "Mess Consumption";
			$data['username'] = $this->ion_auth->user()->row()->username;

			$data['group'] = $this->ion_auth->get_logged_in_user_group_names();

			$this->load->view('templates/header');
			$this->load->view('templates/body',$data);
			$data['messTypes'] = $this->get_mess_types();
			$this->load->view('mess/mess_consumption',$data);
		}
	}

	public function mess_vegetable_consumption()
	{
		if(!$this->ion_auth->logged_in())
			redirect('auth/login','refresh');
		else {
			$data['title'] = "Mess Vegetable Consumption";
			$data['username'] = $this->ion_auth->user()->row()->username;

			$data['group'] = $this->ion_auth->get_logged_in_user_group_names();

			$this->load->view('templates/header');
			$this->load->view('templates/body',$data);
			$data['messTypes'] = $this->get_mess_types();
			$this->load->view('mess/mess_vegetable_consumption',$data);
		}
	}

	public function mess_return()
	{
		if(!$this->ion_auth->logged_in())
			redirect('auth/login','refresh');
		else {
			$data['title'] = "Mess Returns";
			$data['username'] = $this->ion_auth->user()->row()->username;

			$data['group'] = $this->ion_auth->get_logged_in_user_group_names();

			$this->load->view('templates/header');
			$this->load->view('templates/body',$data);
			$data['messTypes'] = $this->get_mess_types();

			$this->load->view('mess/mess_return',$data);
		}
	}



}
