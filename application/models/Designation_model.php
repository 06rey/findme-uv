<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class designation_model extends CI_Model {
	public $date_assigned;
	public $driver_id;
	public $plate_no;

	public function add()
	{

		$this->date_assigned =$this->input->post('date_assigned',true);
		$this->driver_id =$this->input->post('driver_id',true);
		$this->plate_no =$this->input->post('plate_no',true);

		 $this->db->insert('designation', $this);

		 return $this->db->insert_id();
	}
}