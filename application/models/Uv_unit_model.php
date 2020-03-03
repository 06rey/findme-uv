<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class uv_unit_model extends CI_Model {
	public $plate_no;
	public $company_id;
	public $max_pass;
	public $franchise_no;
	public $model;
	public $brand_name;

	public function add()
	{

		$this->plate_no =$this->input->post('plate_no',true);
		$this->company_id =$this->session->userdata('company_id');
		$this->max_pass =$this->input->post('max_pass',true);
		$this->franchise_no =$this->input->post('franchise_no',true);
		$this->model =$this->input->post('model',true);
		$this->brand_name =$this->input->post('brand_name',true);

		$this->db->insert('uv_unit', $this);

		 return $this->db->insert_id();
	}

	public function get_all()
	{	
			
		$query = $this->db->get('uv_unit');

		return $query->result();
	}

	public function get_uv($id = "") {
		$query = $this->db->get_where('uv_unit',['uv_id'=>$id]);
		return $query->row();
	}

	public function updateUvUnit($id = "") {
		$data = array(
			'plate_no' => $_POST['plate_no'],
			'max_pass' => $_POST['max_pass'],
			'franchise_no' => $_POST['franchise_no'],
			'model' => $_POST['model'],
			'brand_name' => $_POST['brand_name']
		);
		$this->db->where('uv_id', $id)->update('uv_unit', $data);
	}

}