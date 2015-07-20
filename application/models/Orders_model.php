<?php
class Orders_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
		$this->db->db_debug = FALSE;
		$this->load->model('items_model');
	}

	public function order_receive_model($data)
	{
		$remainingStock = array();
		$itemNames = $data['selectedItems'];
		$quantityAvailable = $data['quantityAvailable'];
		$selectedQuantity = $data['selectedQuantity'];
		for($i=0;$i<count($itemNames);$i++)
		{
			$eachAvailable = $quantityAvailable[$i];
			$eachSelected = $selectedQuantity[$i];
			$sum = $eachAvailable + $eachSelected;
			array_push($remainingStock,$sum);
		}
		$data['remainingStock'] = $remainingStock;
		$return = $this->items_model->insert_to_orders_table($data);
		if($return == 1)
		{
			$return = $this->items_model->update_stock($data);
		}
		else
			return $return;

		return $return;

	}


	public function insert_to_orders_table($data)
	{
		$size = count($data['selectedItems']);
		$totalAmount = 0;
		$amount = 0;
		for($i=0;$i<$size;$i++)
		{
			$amount = ($data['selectedQuantity'][$i] * $data['latestRate'][$i]);
			$totalAmount += $amount;
			$insert = array(
					'orderID' => $data['orderNo'],
					'vendorName' => $data['selectedVendor'],
					'itemName' => $data['selectedItems'][$i],
					'receivedDate' => date('Y-m-d'),
					'quantityReceived' => $data['selectedQuantity'][$i],
					'rate' => $data['latestRate'][$i],
					'amount' => ($amount),
				       );
			$this->db->trans_start();
			if(!$this->db->insert('ordersTable',$insert))
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


	public function get_vendors()
	{

		$return['vendorName'] = array();
		$return['ownerName'] = array();
		$return['address'] = array();
		$return['contact'] = array();
		$this->db->where('functioning','1');
		$vendors = $this->db->get('vendorsTable');
		foreach($vendors->result() as $row){

			array_push($return['vendorName'],$row->vendorName);
			array_push($return['ownerName'],$row->ownerName);
			array_push($return['address'],$row->address);
			array_push($return['contact'],$row->contact);
		}
		return json_encode(array("vendorName" => $return['vendorName'],
					"ownerName" => $return['ownerName'],
					"address" => $return['address'],
					"contact" => $return['contact']));

	}

	public function add_vendor($data)
	{
		$insert = array( "vendorName" => $data['vendorName'],
				"ownerName" => $data['ownerName'],
				"address" => $data['address'],
				"contact" => $data['contact']);
		$this->db->trans_start();
		if(!$this->db->insert('vendorsTable',$insert))
		{
			$error=$this->db->error();
			$this->db->trans_complete();
			return $error['message'];
		}
		$this->db->trans_complete();
		return 1;

	}

	public function update_vendor_details($data)
	{
		$this->db->trans_start();
		$this->db->where('vendorName',$data['vendorName']);
		$this->db->set('contact',$data['contact']);

		$this->db->set('ownerName',$data['ownerName']);
		$this->db->set('address',$data['address']);


		if(!$this->db->update('vendorsTable'))
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

	public function delete_vendor($vendorName)
	{
		$this->db->where('vendorName',$vendorName);
		$this->db->set('functioning','0');
		$this->db->trans_start();
		if(!$this->db->update('vendorsTable'))
		{
			$error=$this->db->error();
			$this->db->trans_complete();
			return $error['message'];
		}
		$this->db->trans_complete();
		return 1;

	}

	public function get_pending_payments()
	{
		$this->db->select('*');
		$this->db->where('paymentStatus','0');
		$this->db->group_by('orderID');
		$this->db->trans_start();
		$orders = $this->db->get('ordersTable');
		$output = array();
		$this->db->trans_complete();
		foreach($orders->result() as $row)
		{
			$temp['orderID'] = $row->orderID;
			$temp['vendorName'] = $row->vendorName;
			$temp['receivedDate'] = $row->receivedDate;
			$temp['items'] = array();
			$this->db->select('itemName,quantityReceived,rate');
			$this->db->where('orderID',$row->orderID);
			$this->db->trans_start();
			$itemsObj = $this->db->get('ordersTable');
			$this->db->trans_complete();
			foreach($itemsObj->result() as $itemsRow){
				$items = array();
				$items['itemName'] = $itemsRow->itemName;
				$items['quantityReceived'] = $itemsRow->quantityReceived;
				$items['rate'] = $itemsRow->rate;
				array_push($temp['items'],$items);
			}
			array_push($output,$temp);
		}
		return json_encode($output);
	}


	public function get_order_details($orderID)
	{
		$this->db->select('*');
		$this->db->where('orderID',$orderID);
		$this->db->trans_start();
		$orderDetails = $this->db->get('ordersTable');

		$output['orderID'] = array();
		$output['vendorName'] = array();
		$output['itemName'] = array();
		$output['quantityReceived'] = array();
		$output['rate'] = array();
		$output['amount'] = array();

		foreach($orderDetails->result() as $row)
		{
			array_push($output['orderID'], $row->orderID);
			array_push($output['vendorName'],$row->vendorName);
			array_push($output['itemName'],$row->itemName);

			array_push($output['quantityReceived'],$row->quantityReceived);

			array_push($output['rate'],$row->rate);
			array_push($output['amount'],$row->amount);
		}
		return $output;
	}

	public function payments_update_model($data)
	{	
		$return = $this->insert_to_payments_table($data);
		if($return ==1)
		{
			$return = $this->update_payment_status($data);
			return $return;
		}
		else return $return;
	}


	public function insert_to_payments_table($data)
	{
		$paymentDate = date('Y-m-d',strtotime($data['paymentDate']));
		$insert = array( 'paymentID' => "PAY".$data['orderID'],
				'orderID' => $data['orderID'],
				'paymentDate' => $paymentDate,
				'paymentMode' => $data['paymentMode'],
				'paymentNumber' => $data['paymentNumber'],
				'bankName' => $data['bankName'],
				'inFavourOf' => $data['inFavourOf'],
			       );
		$this->db->trans_start();
		if(!$this->db->insert('paymentsTable',$insert))
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

	public function update_payment_status($data)
	{
		$this->db->where('orderID',$data['orderID']);
		$this->db->set('paymentStatus','1');
		if(!$this->db->update('ordersTable'))
		{
			$error=$this->db->error();
			return $error['message'];


		}
		else return 1;
	}

}		
