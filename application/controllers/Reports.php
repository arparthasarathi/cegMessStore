<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

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
		$this->load->model('reports_model');
		$this->load->model('mess_model');
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

	public function getMessTypes()
	{
		return $this->messTypes;
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
			$messBill = ($this->reports_model->generate_mess_bill($messName,$from,$to));
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
			$messConsumption = ($this->reports_model->generate_mess_consumption($messName,$from,$to));
			echo json_encode($messConsumption);
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

			$this->load->view('reports/mess_bill',$data);
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
			$this->load->view('reports/mess_consumption',$data);
		}
	}

}

