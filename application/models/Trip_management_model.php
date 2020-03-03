
<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class trip_management_model extends CI_Model {


	function __construct() {
		date_default_timezone_set('Asia/Manila');
	}

	public function get_all(){
		$this->db->select('*');
		$this->db->from('employee');
		$this->db->join('trip', 'employee.employee_id = trip.driver_id', 'right');
		$this->db->join('route', 'trip.route_id = route.route_id');
		$query = $this->db->get();

		$result = $query->result();

		foreach ($result as $key => $value) {
			if ($value->uv_id != null) {
				$result[$key]->{'plate_no'} = $this->get_uv($value->uv_id, 'plate_no')->plate_no;
			} else {
				$result[$key]->{'plate_no'} = null;
			}
		}
		return $query->result();
	}

	public function update_trip_record(){
		$date = $this->modify_current_date(date('Y-m-d H:i:s'), 1, 0, 0);
		$query = $this->db->query("
				SELECT * FROM trip
				WHERE query_date_time < '$date'
				
				AND status = 'Pending'
			");
		return $query->result();
	}

	public function add(){
		$driver_id = $this->input->post('driver_id',true);
		if ($driver_id == '') {
			$driver_id = null;
		}
		$uv_id = $this->input->post('uv_id',true);
		if ($uv_id == '') {
			$uv_id = null;
		}

		$data = [
			'date' 				=> $this->input->post('date',true),
			'depart_time' 		=> date('H:i:s', strtotime($this->input->post('depart_time',true))),
			'arrival_time ' 	=> '',
			'status' 			=> 'Pending',
			'current_location' 	=> $this->get_origin_point($this->input->post('route_id',true)),
			'is_online' 		=> '',
			'last_online' 		=> date('Y-m-d H:i:s'),
			'query_date_time' 	=> date($this->input->post('date',true) . ' ' . date('H:i:s', strtotime($this->input->post('depart_time',true)))),
			'driver_id' 		=> $driver_id,
			'uv_id' 			=> $uv_id,
			'route_id' 			=> $this->input->post('route_id',true)
		];

		if ($data['query_date_time'] < date('Y-m-d H:i:s')) {
			return 0;
		} else {
			$this->db->insert('trip', $data);
		 	return $this->db->insert_id();
		}
	}

	public function set_trip_status($status = "", $trip_id = "") {
		$this->db->where('trip_id', $trip_id);
		$this->db->update('trip', ['status'=>$status]);
		return $this->db->affected_rows();
	}

	public function save_trip($trip_id = "") {
		$driver_id = $this->input->post('driver_id',true);
		if ($driver_id == '') {
			$driver_id = null;
		}
		$uv_id = $this->input->post('uv_id',true);
		if ($uv_id == '') {
			$uv_id = null;
		}
		$data = [
			'date' 				=> $this->input->post('date',true),
			'depart_time' 		=> date('H:i:s', strtotime($this->input->post('depart_time',true))),
			'status' 			=> 'Pending',
			'query_date_time' 	=> date($this->input->post('date',true) . ' ' . date('H:i:s', strtotime($this->input->post('depart_time',true)))),
			'driver_id' 		=> $driver_id,
			'uv_id' 			=> $uv_id
		];

		if ($data['query_date_time'] < date('Y-m-d H:i:s')) {
			return 'invalid date';
		} else {
			$this->db->where('trip_id', $trip_id);
			$this->db->update('trip', $data);
		 	if ($this->db->affected_rows() > 0) {
		 		return 'success';
		 	} else {
		 		return 'failed';
		 	}
		}
	}

	public function get_trip($trip_id = "") {
		$this->db->select('*');
		$this->db->from('route');
		$this->db->join('trip', 'route.route_id = trip.route_id');
		$this->db->where('trip_id', $trip_id);
		$query = $this->db->get();
		$result = $query->row();

		if ($result->uv_id == null) {
			$result->{'plate_no'} = '';
		} else {
			$result->{'plate_no'} = $this->get_uv($result->uv_id, 'plate_no')->plate_no;
		}

		if ($result->driver_id == null) {
			$result->{'driver_name'} = '';
		} else {
			$driver_info = $this->get_driver_info($result->driver_id, '*');
			$result->{'driver_name'} = "$driver_info->f_name $driver_info->l_name";
		}

		return $result;
	}

	public function get_uv($uv_id = "", $col = "") {
    	$this->db->select($col);
		$this->db->from('uv_unit');
		$this->db->where('uv_id', $uv_id);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_driver_info($driver_id = "", $col = "") {
    	$this->db->select($col);
		$this->db->from('employee');
		$this->db->where('employee_id', $driver_id);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_route(){	
		$query = $this->db->get_where('route',['company_id'=>$this->session->userdata('company_id')]);
		return $query->result();
	}

	public function get_plate_no(){	
		$id = $this->db->select('uv_id')->from('trip')->where('status !=', 'Arrived')->get()->result_array();
		$str = '0';
		if (count($id) > 1) {
			foreach ($id as $key => $value) {
				if ($value['uv_id'] != '') {
					$str = $str.','.$value['uv_id'];
				}
			}
		}
		$query = $this->db->query("
			SELECT plate_no, uv_id
			FROM uv_unit 
			WHERE uv_id  NOT IN ($str)
				AND uv_unit.company_id = ". $this->session->userdata('company_id')
		);
		return $query->result();
	}

	public function get_driver(){			
		$id = $this->db->select('driver_id')->from('trip')->get()->result_array();
		$str = '0';
		if (count($id) > 1) {
			foreach ($id as $key => $value) {
				if ($value['driver_id'] != '') {
					$str = $str.','.$value['driver_id'];
				}
			}
		}
		$query = $this->db->query("
			SELECT distinct employee.* 
			FROM employee, uv_unit
			WHERE employee.role='driver' 
				AND employee.employee_id NOT IN ($str)
				AND uv_unit.company_id = ". $this->session->userdata('company_id')
			);
		return $query->result();
	}

	public function modify_current_date($date = "", $hours = "", $minutes = "", $seconds = "") {
		$new_date = new \DateTime($date);
		$new_date->modify('+'.$hours.' hour +'.$minutes.' minutes +'.$seconds.' seconds');
		return $new_date->format('Y-m-d H:i:s');
	}

	public function get_origin_point($route_id = "") {
		return $this->db->get_where('route', ['route_id'=>$route_id])->row()->origin_lat_lng;
	}
}