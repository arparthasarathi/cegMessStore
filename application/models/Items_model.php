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
			$item = strtoupper($data['itemName'][$i]);
			$insert = array(
					'itemName' => $item,
					'latestRate' => $data['itemRate'][$i],
					'quantityAvailable' => $data['quantityAvailable'][$i],
					'minimumQuantity' => $data['minimumQuantity'][$i],
					'clearanceStock' => $data['quantityAvailable'][$i]
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
			if($return == 1)
				$return = $this->insert_to_mess_return_table($data);
			else
				return $return;		

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


	public function get_clearance_stock($itemName)
	{
		$this->db->select('clearanceStock');
		$this->db->where('itemName',$itemName);
		$items = $this->db->get('itemsTable');
		$result = $items->result();
		return $result[0]->clearanceStock;
		
	}

	public function get_rate($itemName)
        {
                $this->db->select('latestRate');
                $this->db->where('itemName',$itemName);
                $items = $this->db->get('itemsTable');
                $result = $items->result();
                return $result[0]->latestRate;

        }


	public function update_clearance_stock($itemName)
	{
		$this->db->trans_start();
		$this->db->select('*');
		$this->db->where('itemName',$itemName);
		$this->db->where('consumed','0');
		$newItems = $this->db->get('ordersTable');
		$result = $newItems->result();
		$newStock['latestRate'] = $result[0]->rate;
		$newStock['quantityReceived'] = $result[0]->quantityReceived;
		$this->db->trans_complete();

		$this->db->trans_start();
		$this->db->where('itemName',$itemName);
		$this->db->set('latestRate',$newStock['latestRate']);
		$this->db->set('clearanceStock', $newStock['quantityReceived']);
		$this->db->update('itemsTable');	
		$this->db->trans_complete();

		$this->db->trans_start();
		$this->db->where('itemName',$itemName);
		$this->db->set('consumed','1');
		$this->db->update('ordersTable');		
		$this->db->trans_complete();
	}
	

	public function insert_to_mess_consumption_table($data)
	{
		$size = count($data['selectedItems']);
		$totalAmount = 0;
		$amount = 0;
		for($i=0;$i<$size;$i++)
		{
			$clearanceStock = $this->get_clearance_stock($data['selectedItems'][$i]);
			if($data['selectedQuantity'][$i] > $clearanceStock)
			{
				$this->update_clearance_stock($data['selectedItems'][$i]);
				$amount = $clearanceStock * $data['latestRate'][$i];
				$newRate = $this->get_rate($data['selectedItems'][$i]);
				$amount += ($data['selectedQuantity'][$i] - $clearanceStock) * $newRate;
			}
			else {
			$amount = ($data['selectedQuantity'][$i] * $data['latestRate'][$i]);
			}
			$totalAmount += $amount;
			$insert = array(
					'messName' => $data['selectedMess'],
					'itemName' => $data['selectedItems'][$i],
					'suppliedDate' => date('Y-m-d'),
					'quantitySupplied' => $data['selectedQuantity'][$i],
					'rate' => $data['latestRate'][$i],
					'amount' => ($amount)
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

		$billReturn = $this->insert_to_mess_bill($data['selectedMess'],date('Y-m-d'),$totalAmount);
		if($billReturn == 1)
		{
			return 1;
		}
		else 
		{
			return $billReturn;
		}

		return 1;	
	}

	public function insert_to_mess_return_table($data)
	{
		$size = count($data['selectedItems']);
		$totalAmount = 0;
		$amount = 0;
		for($i=0;$i<$size;$i++)
		{
			$amount = ($data['selectedQuantity'][$i] * $data['latestRate'][$i]);
			$totalAmount += $amount;
			$insert = array(
					'messName' => $data['selectedMess'],
					'itemName' => $data['selectedItems'][$i],
					'returnedDate' => date('Y-m-d'),
					'quantityReturned' => $data['selectedQuantity'][$i],
					'rate' => $data['latestRate'][$i],
					'amount' => ($amount)
				       );
			$this->db->trans_start();
			if(!$this->db->insert('messReturnTable',$insert))
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

	public function update_issued_items($data)
	{
		$this->db->trans_start();
		$this->db->where('suppliedDate',$data['suppliedDate']);
		$this->db->where('itemName',$data['itemName']);
		$this->db->where('messName',$data['messName']);
		$this->db->set('quantitySupplied',$data['quantitySupplied']);
		$this->db->set('rate',$data['latestRate']);
		$amount = $data['quantitySupplied'] * $data['latestRate'];
		$this->db->set('amount',$amount);
		if(!$this->db->update('messConsumptionTable'))
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

	public function insert_to_mess_bill($selectedMess,$billDate,$totalAmount)
	{
		$insert = array(
				'messName' => $selectedMess,
				'date' => $billDate,
				'totalAmount' => $totalAmount
			       );
		$this->db->trans_start();
		if(!$this->db->insert('messBill',$insert))
		{
			$error=$this->db->error();
			$this->db->trans_complete();
			return $error['message'];
		}
		$this->db->trans_complete();
		return 1;
	}

	public function update_mess_consumption_table($data)
	{
		$size = count($data['selectedItems']);
		$totalReducedAmount = 0;
		$amount =0;
		for($i=0;$i<$size;$i++)
		{
			$amount = ($data['actualSupplied'][$i] * $data['latestRate'][$i]);
			$totalReducedAmount += ($data['selectedQuantity'][$i] * $data['latestRate'][$i]);
			$update = array(
					'quantitySupplied' => $data['actualSupplied'][$i],
					'amount' => $amount
				       );
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
		$billReturn = $this->update_mess_bill($data['selectedMess'],date('Y-m-d'),$totalReducedAmount);
		if($billReturn == 1)
		{

			return 1;
		}
		else
		{
			return $billReturn;
		}


		return 1;
	}


	public function update_mess_bill($selectedMess,$billDate,$reductionAmount)
	{

		$this->db->trans_start();	
		$this->db->where('messName',$selectedMess);
		$this->db->where('date',$billDate);
		$column = 'totalAmount-'.$reductionAmount;
		$this->db->set('totalAmount',$column,FALSE);	

		if(!$this->db->update('messBill'))
		{
			$error=$this->db->error();
			$this->db->trans_complete();
			return $error['message'];
		}
		$this->db->trans_complete();
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


	
	public function get_lesser_items()
	{
		$this->db->select('*');
		$this->db->where('quantityAvailable < minimumQuantity');
		$return['itemNames'] = array();
		$return['quantityAvailable'] = array();
		$items = $this->db->get('itemsTable');
		foreach($items->result() as $row)
		{

			array_push($return['itemNames'],$row->itemName);
			array_push($return['quantityAvailable'],$row->quantityAvailable);
		}
		return $return;
	}

	public function get_consumed_items($messName,$date)
	{
		$this->db->where('suppliedDate',$date);
		$this->db->where('messName',$messName);
		$return['itemNames'] = array();
		$return['quantitySupplied'] = array();
		$return['latestRate'] = array();
		$items = $this->db->get('messConsumptionTable');
		foreach($items->result() as $row)
		{
			array_push($return['itemNames'],$row->itemName);

			array_push($return['quantitySupplied'],$row->quantitySupplied);
			array_push($return['latestRate'],$row->rate);
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
