<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class uv_unit_model extends CI_Model {

	public function get_all(){	
		return $this->db->get_where('uv_unit', ['company_id'=> $this->session->userdata('company_id')])->result();
	}

	public function insertUvExpress(){
		$_POST['company_id'] = $this->session->userdata('company_id');
		$this->db->insert('uv_unit', $_POST);
		return $this->db->insert_id();
	}

	public function updateUvExpress($uvId){
		$this->db->where('uv_id', $uvId)->update('uv_unit', $_POST);
		return $this->db->affected_rows();
	}

}