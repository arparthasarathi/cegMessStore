<?php
class Items_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
		$this->db->db_debug = FALSE;
	}

	public function add_item_model($data)
	{

		for($i=0;$i<count($data['itemName']);$i++)
		{
			$insert = array(
					'itemName' => $data['itemName'][$i],
					'latestRate' => $data['itemRate'][$i],
					'quantityAvailable' => $data['quantityAvailable'][$i]
				       );
			$this->db->trans_start();
			if(!$this->db->insert('itemsTable',$insert))
			{
				$error=$this->db->error();
				$this->db->trans_complete();
				return $error['message'];
			}
			else
			{
				$this->db->trans_complete();
				continue;
			}
		}
		return 1;
	}

	public function return_item_model($data)
	{
		$remainingStock = array();
		$actualSupplied = array();
		$itemNames = $data['selectedItems'];
		$suppliedQuantity = $data['quantitySupplied'];
		$selectedQuantity = $data['selectedQuantity'];
		$quantityAvailable = $data['quantityAvailable'];
		$selectedMess = $data['selectedMess'];
		for($i=0;$i<count($itemNames);$i++)
		{
			$eachSupplied = $suppliedQuantity[$i];
			$eachSelected = $selectedQuantity[$i];
			$eachAvailable = $quantityAvailable[$i];

			if($eachSupplied > $eachSelected)
			{
				$diff = $eachSupplied - $eachSelected;
				array_push($actualSupplied,$diff);
			}
			else
			{
				return "Excess Quantity Selected for an item. Please Check!";
			} 	
			$addition = $eachAvailable + $eachSelected;
			array_push($remainingStock,$addition);
		}
		$data['actualSupplied'] = $actualSupplied;
		$data['remainingStock'] = $remainingStock;
		$return = $this->update_mess_consumption_table($data);
		if($return == 1)
		{
			$return = $this->update_stock($data);
		}
		else
			return $return;

		return $return;

	}

	public function issue_item_model($data)
	{
		$remainingStock = array();
		$itemNames = $data['selectedItems'];
		$quantityAvailable = $data['quantityAvailable'];
		$selectedQuantity = $data['selectedQuantity'];
		$selectedMess = $data['selectedMess'];

		for($i=0;$i<count($itemNames);$i++)
		{
			$eachAvailable = $quantityAvailable[$i];
			$eachSelected = $selectedQuantity[$i];
			if($eachAvailable > $eachSelected)
			{
				$diff = $eachAvailable - $eachSelected;
				array_push($remainingStock,$diff);
			}
			else
			{
				return "Excess Quantity Selected for an item. Please Check!";
			} 
		}
		$data['remainingStock'] = $remainingStock;
		$return = $this->insert_to_mess_consumption_table($data);
		if($return == 1)
		{
			$return = $this->update_stock($data);
		}
		else
			return $return;

		return $return;

	}

	public function insert_to_mess_consumption_table($data)
	{
		$size = count($data['selectedItems']);
		for($i=0;$i<$size;$i++)
		{
			$insert = array(
					'messName' => $data['selectedMess'],
					'itemName' => $data['selectedItems'][$i],
					'suppliedDate' => date('Y-m-d'),
					'quantitySupplied' => $data['selectedQuantity'][$i],
					'rate' => $data['latestRate'][$i],
				       );
			$this->db->trans_start();
			if(!$this->db->insert('messConsumptionTable',$insert))
			{
				$error=$this->db->error();
				$this->db->trans_complete();
				return $error['message'];
			}
			else
			{
				$this->db->trans_complete();
				continue;
			}
		}
		return 1;	
	}

	public function update_mess_consumption_table($data)
        {
                $size = count($data['selectedItems']);
                for($i=0;$i<$size;$i++)
                {
			
                        $update = array(
                                        'quantitySupplied' => $data['actualSupplied'][$i],
                                       );
			error_log($data['actualSupplied'][$i]);
                        $this->db->trans_start();
			$this->db->where('messName',$data['selectedMess']);
			$this->db->where('itemName',$data['selectedItems'][$i]);
			$this->db->where('suppliedDate',date('Y-m-d'));
                        if(!$this->db->update('messConsumptionTable',$update))
                        {
                                $error=$this->db->error();
                                $this->db->trans_complete();
                                return $error['message'];
                        }
                        else
                        {
                                $this->db->trans_complete();
                                continue;
                        }
                }
                return 1;
        }
	public function update_stock($data)
	{
		$size = count($data['selectedItems']);
		for($i=0;$i<$size;$i++)
		{
			$update = array(
					'quantityAvailable' => $data['remainingStock'][$i],
				       );
			$this->db->trans_start();
			$itemName = $data['selectedItems'][$i];
			$this->db->where('itemName',$itemName);
			if(!$this->db->update('itemsTable',$update))
			{
				$error=$this->db->error();
				$this->db->trans_complete();
				return $error['message'];
			}
			else
			{
				$this->db->trans_complete();
				continue;
			}
		}
		return 1;
	}

	public function get_consumed_items($messName,$date)
	{
		$this->db->where('suppliedDate',$date);
		$this->db->where('messName',$messName);
		$return['itemNames'] = array();
		$return['quantitySupplied'] = array();
		$items = $this->db->get('messConsumptionTable');
		foreach($items->result() as $row)
		{
			array_push($return['itemNames'],$row->itemName);
			array_push($return['quantitySupplied'],$row->quantitySupplied);
		}
		return $return;
	}


	public function get_consumed_quantity($messName,$date,$itemNames=null)
	{

		$this->db->where('suppliedDate',$date);
		$this->db->where('messName',$messName);
		$quantitySupplied = array();
		$this->db->where_in('itemName',$itemNames);
		$items = $this->db->get('messConsumptionTable');
		foreach($items->result() as $row)
			array_push($quantitySupplied,$row->quantitySupplied);
		return	json_encode(array("itemNames" => $itemNames,"quantitySupplied" => $quantitySupplied));
	}





	public function get_items($names=null)
	{
		$itemName = array();
		$latestRate = array();
		$quantityAvailable = array();
		if(isset($names) && $names != null)
		{
			$this->db->where_in('itemName',$names);
			$items = $this->db->get('itemsTable');
		}
		else
		{
			$items = $this->db->get('itemsTable');
		}
		foreach($items->result() as $row)
		{
			array_push($itemName,$row->itemName);
			array_push($latestRate,$row->latestRate);
			array_push($quantityAvailable,$row->quantityAvailable);
		}
		return json_encode(array("itemNames" => $itemName, "latestRate" => $latestRate, "quantityAvailable" => $quantityAvailable));
	}

	public function get_available_stock($itemList = null)
	{
		$itemName = array();
		$quantityAvailable = array();
		if(isset($itemList) && $itemList != null)
		{
			$this->db->where_in('itemName',$itemList);
			$items = $this->db->get('itemsTable');
		}
		else
		{
			$items = $this->db->get('itemsTable');
		}
		foreach($items->result() as $row)
		{
			array_push($itemName,$row->itemName);
			array_push($quantityAvailable,$row->quantityAvailable);
		}
		return json_encode(array("itemNames" => $itemName, "quantityAvailable" => $quantityAvailable));
	}
}
