<?php
class Mess_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
		$this->db->db_debug = FALSE;
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
