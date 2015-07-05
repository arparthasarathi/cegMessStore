<?php
class Reports_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
		$this->db->db_debug = FALSE;
	}


	public function generate_mess_consumption($messName,$from,$to)
	{
		$this->db->select('*');
		$this->db->where('messName',$messName);
		$this->db->where('suppliedDate >= ',$from);
		$this->db->where('suppliedDate <= ',$to);
		$this->db->order_by('suppliedDate','desc');
		$return['itemNames'] = array();
		$return['quantitySupplied'] = array();
		$return['suppliedDate'] = array();
		$return['rate'] = array();
		$return['amount'] = array();
		$items = $this->db->get('messConsumptionTable');
		foreach($items->result() as $row)
		{
			array_push($return['itemNames'],$row->itemName);
			array_push($return['quantitySupplied'],$row->quantitySupplied);
			array_push($return['suppliedDate'],$row->suppliedDate);
			array_push($return['rate'],$row->rate);
			array_push($return['amount'],$row->amount);
		}
		return $return;
	}

	public function generate_mess_bill($messName,$from,$to)
	{
		$this->db->select('*');
		$this->db->where('messName',$messName);
		$this->db->where('date >= ',$from);
		$this->db->where('date <= ',$to);
		$this->db->order_by('date','desc');
		$return['suppliedDate'] = array();
		$return['totalAmount'] = array();
		$items = $this->db->get('messBill');
		foreach($items->result() as $row)
		{
			array_push($return['suppliedDate'],$row->date);
			array_push($return['totalAmount'],$row->totalAmount);
		}
		return $return;
	}


}
