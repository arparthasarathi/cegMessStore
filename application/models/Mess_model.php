<?php
class Mess_model extends CI_Model {

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
            array_push($return['suppliedDate'],date('d-m-Y',strtotime($row->suppliedDate)));
			array_push($return['rate'],$row->rate);
			array_push($return['amount'],$row->amount);
		}
		return $return;
	}


	public function generate_mess_vegetable_consumption($messName,$from,$to)
	{
		$this->db->select('*');
		$this->db->where('messName',$messName);
		$this->db->where('receivedDate >= ',$from);
		$this->db->where('receivedDate <= ',$to);
		$this->db->order_by('receivedDate','desc');
		$return['vegetableNames'] = array();
		$return['quantitySupplied'] = array();
		$return['suppliedDate'] = array();

		$return['actualRate'] = array();
		$return['proposedRate'] = array();
		$return['amount'] = array();
		$items = $this->db->get('vegetableOrdersTable');
		foreach($items->result() as $row)
		{
			array_push($return['vegetableNames'],$row->vegetableName);
			array_push($return['quantitySupplied'],$row->quantityReceived);
            array_push($return['suppliedDate'],date('d-m-Y',strtotime($row->receivedDate)));

			array_push($return['actualRate'],$row->actualRate);
			array_push($return['proposedRate'],$row->proposedRate);
			array_push($return['amount'],$row->amount);
		}
		return $return;
	}


	public function generate_mess_return($messName,$from,$to)
	{
		$this->db->select('*');
		$this->db->where('messName',$messName);
		$this->db->where('returnedDate >= ',$from);
		$this->db->where('returnedDate <= ',$to);
		$this->db->order_by('returnedDate','desc');
		$return['itemNames'] = array();
		$return['quantityReturned'] = array();
		$return['returnedDate'] = array();
		$return['rate'] = array();
		$return['amount'] = array();
		$items = $this->db->get('messReturnTable');
		foreach($items->result() as $row)
		{
			array_push($return['itemNames'],$row->itemName);
			array_push($return['quantityReturned'],$row->quantityReturned);
			array_push($return['returnedDate'],date('d-m-Y',strtotime($row->returnedDate)));
			array_push($return['rate'],$row->rate);
			array_push($return['amount'],$row->amount);
		}
		return $return;
	}


	public function generate_mess_vegetable_bill($messName,$from,$to)
	{
		$this->db->select('*');
		$this->db->where('messName',$messName);
		$this->db->where('date >= ',$from);
		$this->db->where('date <= ',$to);
		$this->db->order_by('date','desc');
		$return['suppliedDate'] = array();
		$return['totalAmount'] = array();
		$items = $this->db->get('messVegetableBill');
		foreach($items->result() as $row)
		{
			array_push($return['suppliedDate'],date('d-m-Y',strtotime($row->date)));
			array_push($return['totalAmount'],$row->totalAmount);
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
			array_push($return['suppliedDate'],date('d-m-Y',strtotime($row->date)));
			array_push($return['totalAmount'],$row->totalAmount);
		}
		return $return;
	}
	


	public function get_mess_types_model()
	{
		$return['messName'] = array();
		$return['messIncharge'] = array();
		$return['contact'] = array();
		$this->db->where('functioning','1');
		$vendors = $this->db->get('messTable');
		foreach($vendors->result() as $row){

			array_push($return['messName'],$row->messName);
			array_push($return['messIncharge'],$row->messIncharge);
			array_push($return['contact'],$row->contact);
		}
		return json_encode(array("messName" => $return['messName'],
					"messIncharge" => $return['messIncharge'],
					"contact" => $return['contact']));

	}

	
	public function add_mess($data)
	{
		$insert = array( "messName" => $data['messName'],
				 "messIncharge" => $data['messIncharge'],
				"contact" => $data['contact']);
		$this->db->trans_start();
                if(!$this->db->insert('messTable',$insert))
                {
                        $error=$this->db->error();
                        $this->db->trans_complete();
                        return $error['message'];
                }
                $this->db->trans_complete();
                return 1;

	}	

	public function delete_mess($messName)
	{
		$this->db->where('messName',$messName);
		$this->db->set('functioning','0');
		$this->db->trans_start();
                if(!$this->db->update('messTable'))
                {
                        $error=$this->db->error();
                        $this->db->trans_complete();
                        return $error['message'];
                }
                $this->db->trans_complete();
                return 1;

	}	

	public function update_mess_details($data)
	{
		$this->db->trans_start();
		$this->db->where('messName',$data['messName']);
		$this->db->set('contact',$data['contact']);
		$this->db->set('messIncharge',$data['messIncharge']);
		
		
		if(!$this->db->update('messTable'))
                        {

                                $error=$this->db->error();

                                $this->db->trans_complete();
                                return $error['message'];
                        }
                        else
                        {

                                $this->db->trans_complete();
                                return 1;
                        }

	}



}
