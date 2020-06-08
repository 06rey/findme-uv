<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class route_model extends CI_Model {
	public $company_id;
	public $route_name;
	public $origin;
	public $destination;
	public $via;
	public $fare;
	public $origin_lat_lng;
	public $destination_lat_lng;
	public $way_point;

	public function create(){

		$this->origin =$this->input->post('origin',true);
		$this->destination =$this->input->post('destination',true);

		$temp = strpos($this->origin, ' ');
		if ($temp) {
			$origin = substr($this->origin, $temp+1);
			$num = strlen($this->origin)-$temp;
			$origin = substr($this->origin, 0, $num - ($num*2));
		} else {
			$origin = $this->origin;
		}

		$temp2 = strpos($this->destination, ' ');
		if ($temp2) {
			$destination = substr($this->destination, $temp2+1);
			$num = strlen($this->destination)-$temp2;
			$destination = substr($this->destination, 0, $num - ($num*2));
		} else {
			$destination = $this->destination;
		}

		$this->route_name = "$origin-$destination";

		$this->origin =$this->input->post('origin',true);
		$this->destination =$this->input->post('destination',true);
		$this->via =$this->input->post('via',true);
		$this->fare =$this->input->post('fare',true);
		$this->origin_lat_lng =$this->input->post('origin_lat_lng',true);
		$this->destination_lat_lng =$this->input->post('destination_lat_lng',true);
		$this->way_point =$this->input->post('way_point',true);
		$this->company_id =$this->session->userdata('company_id');

		$this->db->insert('route', $this);

		return $this->db->insert_id();
	}

	public function get_all()
	{	
			
		$query = $this->db->get('route');

		return $query->result();
	}

	public function get($id)
	{	
			
		$query = $this->db->get_where('route',['route_id'=>$id]);

		return $query->row();
	}

	public function edit($id)
	{
		$data_array = [
		'via' => $this->input->post('via',true),
		'fare' => $this->input->post('fare',true)
		];

		 $this->db->update('route',$data_array,['route_id' => $id]);

		 return $this->db->affected_rows();
	}

	public function get_route_path() {
		return $this->db->select('*')->
							from('route')->
							where('route.company_id', $this->session->userdata('company_id'))->
							get()->
							result();
	}

	public function check_route($origin ="", $destination = "") {
		return $this->db->query("
							SELECT * FROM route
							WHERE origin LIKE '%$origin%'
							AND destination LIKE '%$destination%'
							AND company_id = ".$this->session->userdata('company_id')
						)->result();
	}
}