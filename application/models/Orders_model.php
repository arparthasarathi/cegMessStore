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
		$return = $this->insert_to_orders_table($data);
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
//					'receivedDate' => date('Y-m-d'),
					'receivedDate' => $data['receivedDate'],
                    'billNo' => $data['billNo'],
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


	public function vegetable_order_receive_model($data)
	{
		$itemNames = $data['selectedItems'];
		$selectedQuantity = $data['selectedQuantity'];
		$return = $this->insert_to_vegetable_orders_table($data);
		
		return $return;

		

	}

	public function insert_to_vegetable_orders_table($data)
	{
		$size = count($data['selectedItems']);
		$totalAmount = 0;
		$amount = 0;
		for($i=0;$i<$size;$i++)
		{
            $this->db->select_min('proposedRate');
            $this->db->where('vegetableName',$data['selectedItems'][$i]);
            $this->db->where('receivedDate',$data['receivedDate']);
            $this->db->trans_start();
            $result = $this->db->get('vegetableOrdersTable');
            
            if($result->num_rows() > 0)
            {
                foreach($result->result() as $row)
                if(isset($row->proposedRate) && $row->proposedRate != '')
                {
                $actualRate = $row->proposedRate;
                if($actualRate > $data['latestRate'][$i])
                $actualRate = $data['latestRate'][$i];
                }
                else
                $actualRate = $data['latestRate'][$i];
            }
            else
                $actualRate = $data['latestRate'][$i];

			$amount = ($data['selectedQuantity'][$i] * $actualRate);
			$totalAmount += $amount;
			$insert = array(
					'orderID' => $data['orderNo'],
					'vendorName' => $data['selectedVendor'],
					'vegetableName' => $data['selectedItems'][$i],
                    'messName' => $data['selectedMess'],
                    'billNo' => $data['billNo'],
					'receivedDate' => $data['receivedDate'],
					'quantityReceived' => $data['selectedQuantity'][$i],
					'proposedRate' => $data['latestRate'][$i],
                    'actualRate' => $actualRate,
					'amount' => ($amount),
				       );
            $this->db->trans_complete();
			$this->db->trans_start();
			if(!$this->db->insert('vegetableOrdersTable',$insert))
			{
				$error=$this->db->error();
				$this->db->trans_complete();
				return $error['message'];
			}
			else
			{	
                $this->db->trans_complete();
    			$return = $this->update_minimum_rate($data['selectedItems'][$i],$actualRate,$data['receivedDate']);
                if($return != 1)
                {
                return $return;
            	}
                else
				continue;			
			}
		}
		
        $return=$this->items_model->insert_to_mess_vegetable_bill($data['selectedMess'],
                                                            $data['receivedDate'],$totalAmount);
        if($return != 1)
            return $return;
        else{
           $return=$this->update_vegetable_bill($data['receivedDate']);
        }

		return $return;	
	}

    public function update_minimum_rate($vegetableName,$actualRate,$receivedDate)
    {
            $this->db->select('*');
            $this->db->where('vegetableName',$vegetableName);
            $this->db->where('receivedDate',$receivedDate);
            $this->db->trans_start();
            $result = $this->db->get('vegetableOrdersTable');
            $this->db->trans_complete();
            foreach($result->result() as $row)
            {
                $quantityReceived = $row->quantityReceived;
                $vendorName = $row->vendorName;
                $amount = $quantityReceived * $actualRate;
                $update = array( 'amount' => $amount,
                                 'actualRate' => $actualRate
                                );
                $this->db->trans_start();
                $this->db->where('vendorName',$vendorName);
                $this->db->where('vegetableName',$vegetableName);
                $this->db->where('receivedDate',$receivedDate);
                if(!$this->db->update('vegetableOrdersTable',$update))
                {
                    $error = $this->db->error();
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

    public function update_vegetable_bill($receivedDate)
    {
       $messDetails = json_decode($this->mess_model->get_mess_types_model(),true);
       $messNames = $messDetails['messName'];
       foreach($messNames as $each)
       {
          $this->db->select_sum('amount');
          $this->db->where('messName',$each);
          $this->db->where('receivedDate',$receivedDate);
          $this->db->trans_start();
          $result = $this->db->get('vegetableOrdersTable');
          $this->db->trans_complete();
          if(isset($result) && $result->num_rows() > 0)
          {


             foreach($result->result() as $row)
             $totalAmount = $row->amount;
             $this->db->where('date',$receivedDate);
             $this->db->where('messName',$each);

             $this->db->trans_start();
             $update = array('totalAmount' => $totalAmount);
             if(!$this->db->update('messVegetableBill',$update))
             {
                $error = $this->db->error();
                $this->db->trans_complete();
                return $error['message'];
             }
             else{
                $this->db->trans_complete();
                continue;
             }

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

		$insert = array( "vendorName" => strtoupper(urldecode($data['vendorName'])),
				"ownerName" => strtoupper(urldecode($data['ownerName'])),
				"address" => strtoupper(urldecode($data['address'])),
				"contact" => strtoupper(urldecode($data['contact']))
				);
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



	public function get_vegetable_pending_payments()
	{
		$this->db->select('*');
		$this->db->where('paymentStatus','0');
		$this->db->group_by('orderID');
		$this->db->trans_start();
		$orders = $this->db->get('vegetableOrdersTable');
		$output = array();
		$this->db->trans_complete();
		foreach($orders->result() as $row)
		{
			$temp['orderID'] = $row->orderID;
			$temp['vendorName'] = $row->vendorName;
			$temp['receivedDate'] = $row->receivedDate;
			$temp['items'] = array();
			$this->db->select('vegetableName,quantityReceived,actualRate');
			$this->db->where('orderID',$row->orderID);
			$this->db->trans_start();
			$itemsObj = $this->db->get('vegetableOrdersTable');
			$this->db->trans_complete();
			
			foreach($itemsObj->result() as $itemsRow){
				$items = array();
				$items['itemName'] = $itemsRow->vegetableName;
				$items['quantityReceived'] = $itemsRow->quantityReceived;
				$items['rate'] = $itemsRow->actualRate;
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

        $output['receivedDate'] = array();
        $output['billNo'] = array();

		foreach($orderDetails->result() as $row)
		{
			array_push($output['orderID'], $row->orderID);
			array_push($output['vendorName'],$row->vendorName);
			array_push($output['itemName'],$row->itemName);
            array_push($output['billNo'], $row->billNo);
			array_push($output['quantityReceived'],$row->quantityReceived);
            array_push($output['receivedDate'],date('d-m-Y',strtotime($row->receivedDate)));
			array_push($output['rate'],$row->rate);
			array_push($output['amount'],$row->amount);
		}
		return $output;
	}

    public function get_vegetable_order_details($orderID)
	{
		$this->db->select('*');
		$this->db->where('orderID',$orderID);
		$this->db->trans_start();
		$orderDetails = $this->db->get('vegetableOrdersTable');

		$output['orderID'] = array();
		$output['vendorName'] = array();
		$output['vegetableName'] = array();
		$output['quantityReceived'] = array();
		$output['rate'] = array();
		$output['amount'] = array();
        $output['proposedRate'] = array();
        $output['receivedDate'] = array();
        $output['billNo'] = array();

		foreach($orderDetails->result() as $row)
		{
			array_push($output['orderID'], $row->orderID);
			array_push($output['vendorName'],$row->vendorName);
			array_push($output['vegetableName'],$row->vegetableName);
            array_push($output['billNo'], $row->billNo);
			array_push($output['quantityReceived'],$row->quantityReceived);
            array_push($output['receivedDate'],date('d-m-Y',strtotime($row->receivedDate)));

			array_push($output['rate'],$row->actualRate);
			array_push($output['proposedRate'],$row->proposedRate);
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

	public function generate_order_history()
	{
		$this->db->select('*');
		$this->db->group_by('orderID');
		$this->db->trans_start();
		$orders = $this->db->get('ordersTable');
		$output = array();
		$this->db->trans_complete();
		foreach($orders->result() as $row)
		{
			$temp['orderID'] = $row->orderID;
			$temp['vendorName'] = $row->vendorName;
            $temp['receivedDate'] = date('d-m-Y',strtotime($row->receivedDate));
			$temp['billNo'] = $row->billNo;
			$temp['items'] = array();
			$this->db->select('itemName,quantityReceived,rate,amount');
			$this->db->where('orderID',$row->orderID);
			$this->db->trans_start();
			$itemsObj = $this->db->get('ordersTable');
			$this->db->trans_complete();
			foreach($itemsObj->result() as $itemsRow){
				$items = array();
				$items['itemName'] = $itemsRow->itemName;
				$items['quantityReceived'] = $itemsRow->quantityReceived;
				$items['rate'] = $itemsRow->rate;
				$items['amount'] = $itemsRow->amount;
				array_push($temp['items'],$items);
			}
			array_push($output,$temp);
		}
		return ($output);
	}

    public function generate_vegetable_order_history()
	{
		$this->db->select('*');
		$this->db->group_by('orderID');
		$this->db->trans_start();
		$orders = $this->db->get('vegetableOrdersTable');
		$output = array();
		$this->db->trans_complete();
		foreach($orders->result() as $row)
		{
			$temp['orderID'] = $row->orderID;
			$temp['vendorName'] = $row->vendorName;
			$temp['receivedDate'] = date('d-m-Y',strtotime($row->receivedDate));
			$temp['billNo'] = $row->billNo;
			$temp['items'] = array();
			$this->db->select('vegetableName,quantityReceived,proposedRate,actualRate,amount');
			$this->db->where('orderID',$row->orderID);
			$this->db->trans_start();
			$itemsObj = $this->db->get('vegetableOrdersTable');
			$this->db->trans_complete();
			foreach($itemsObj->result() as $itemsRow){
				$items = array();
				$items['itemName'] = $itemsRow->vegetableName;
				$items['quantityReceived'] = $itemsRow->quantityReceived;

				$items['rate'] = $itemsRow->actualRate;
				$items['proposedRate'] = $itemsRow->proposedRate;
				$items['amount'] = $itemsRow->amount;
				array_push($temp['items'],$items);
			}
			array_push($output,$temp);
		}
		return ($output);
	}

	public function generate_payment_history()
	{
		$this->db->select('*');
		$this->db->trans_start();
		$orders = $this->db->get('paymentsTable');
		$output = array();
		$this->db->trans_complete();
		foreach($orders->result() as $row)
		{
			$temp['paymentID'] = $row->paymentID;
			$temp['paymentMode'] = $row->paymentMode;
			$temp['bankName'] = $row->bankName;
			$temp['inFavourOf'] = $row->inFavourOf;
			$temp['paymentDate'] = date('d-m-Y',strtotime($row->paymentDate));
			$temp['paymentNumber'] = $row->paymentNumber;
			array_push($output,$temp);
		}
		return ($output);
	}

}		
