
<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class trip_management_model extends CI_Model {

	public $company_id;
	public $route_name;
	public $origin;
	public $destination;
	public $via;
	public $fare;
	public $origin_lat_lng = '';
	public $destination_lat_lng = '';
	public $way_point = '';

	function __construct() {
		date_default_timezone_set('Asia/Manila');
	}

	/*
	|--------------------------------------------------------------------------
	| ROUTE
	|--------------------------------------------------------------------------
	*/

	public function getUvLocation(){
		return $this->db->select('current_location')->where('trip_id', $_POST['trip_id'])->get('trip')->result();
	}

	public function fetchRouteGeoPoints(){
		return $this->db->get_where('route', ['route_id'=>$_POST['route_id']])->result();
	}

	public function fetchRoute(){
		return $this->db->select('route_id, origin, destination, via, fare')
									->from('route')->get()->result();
	}

	public function insertRoute(){
		$this->origin =$this->input->post('origin',true);
		$this->destination =$this->input->post('destination',true);

		$route = $this->db->where("origin LIKE '%$this->destination%' AND destination LIKE '%$this->origin%'")
											->get('route')->result();

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
		$this->company_id =$this->session->userdata('company_id');

		$this->db->insert('route', $this);
		$id = $this->db->insert_id();

		if (count($route) > 0){
			$origin = $route[0]->destination_lat_lng;
			$destination = $route[0]->origin_lat_lng;
			$path = $route[0]->way_point;

			$this->db->where('route_id', $id)
							->update('route', [
								'origin_lat_lng' => $origin,
								'destination_lat_lng' => $destination,
								'way_point' => $path
							]);
		}

		return $id;
	}

	public function updateRoute(){
		$this->db->where('route_id', $_POST['route_id'])
									->update('route', [
										'origin' => $_POST['origin'],
										'destination' => $_POST['destination'],
										'via' => $_POST['via'],
										'fare' => $_POST['fare'],
									]);
		return $this->db->affected_rows();
	}

	public function routeInfo($routeId){
		return $this->db->select('route_id, origin, destination, via, fare')
							->from('route')
							->where('route_id = '. $routeId)
							->get()->result();
	}

	public function saveMapRoute(){
		$this->db->where('route_id', $_POST['route_id'])
					->update('route', [
						'origin_lat_lng' => $_POST['origin'],
						'destination_lat_lng' => $_POST['destination'],
						'way_point' => $_POST['path']
					]);
		return $this->db->affected_rows();
	}


	/*
	|--------------------------------------------------------------------------
	| TRIP
	|--------------------------------------------------------------------------
	*/

	// FETCH
	public function getAllRoute(){
		return $this->db->select('DISTINCT(route_id), origin, destination, route_name')
										->get('route')->result();
	}

	// ----------------------------------------------------------------------

	// Get all trip
	public function getAllTrip(){
		$allTrip = $this->db->select('route_id, origin, destination, route_name')
			->from('route')
			->get()
			->result();
		foreach ($allTrip as $key => $value) {
			$allTrip[$key]->{'count'} = $this->countTrip($value->route_id);
		}
		return $allTrip;
	}
	// Count trip
	public function countTrip($routeId){
		$count['pending'] = $this->db->select('COUNT(*) as count')
																	->from('trip')
																	->where("route_id=$routeId AND status='Pending'")
																	->get()->result()[0]->count;
		$count['traveling'] = $this->db->select('COUNT(*) as count')
																	->from('trip')
																	->where("route_id=$routeId AND status='Traveling'")
																	->get()->result()[0]->count;
		$count['arrived'] = $this->db->select('COUNT(*) as count')
																	->from('trip')
																	->where("route_id=$routeId AND status='Arrived'")
																	->get()->result()[0]->count;
		$count['cancelled'] = $this->db->select('COUNT(*) as count')
																	->from('trip')
																	->where("route_id=$routeId AND status='Cancelled'")
																	->get()->result()[0]->count;
		return $count;
	}
	// Get pending trip by route
	public function getTrip($route_id, $status, $date){
		$result = $this->db->select('*')
							->from('employee')
							->join('trip', 'employee.employee_id = trip.driver_id', 'right')
							->join('uv_unit', 'trip.uv_id = uv_unit.uv_id', 'left')
							->join('route', 'trip.route_id = route.route_id')
							->where("status = '$status' AND trip.route_id = $route_id AND trip.date = '$date'")
							->order_by('depart_time', 'ASC')
							->get()->result();
		foreach ($result as $key => $trip) {
			$result[$key]->{'passenger'} = $this->getCountPassenger($trip->trip_id);
		}
		return $result;
	}
	// Get route name
	public function get_route(){	
		return $this->db->get_where('route', ['company_id'=> $this->session->userdata('company_id')])
										->result();
	}
	// Get route name
	public function getRoute($routeId){
		return $this->db->get_where('route', ['route_id' => $routeId])->result()[0];
	}

	// Insert new trip Trip
	public function insertTrip(){
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
	// Count trip
	public function getNoOfTrip($date, $routeId, $status){
		return $this->db->select('COUNT(*) as count')
					->where("date = '$date' AND route_id = $routeId AND status = '$status'")
					->get('trip')->result()[0]->count;
	}
	// Get passenger data
	public function getTripPassenger($tripId){
		return $this->db->select('seat.*')
									->join('seat', 'booking.booking_id = seat.booking_id')
									->where("booking.trip_id = $tripId")
									->get('booking')->result();
	}
	// Count passenger
	public function getCountPassenger($tripId){
		return $this->db->select('COUNT(*) as count')
										->from('booking')
										->join('seat', 'booking.booking_id = seat.booking_id')
										->where('booking.trip_id = '. $tripId)
										->get()->result()[0]->count;
	}
	public function getUvExpressAvailabilty($uvId){
		return $this->db->select('count(*) as count')
									->from('trip')
									->where("(status = 'Pending' OR status = 'Traveling') AND uv_id = $uvId")
									->get()->result()[0]->count;
	}
	public function geDriverAvailabilty($driverId){
		return $this->db->select('count(*) as count')
									->from('trip')
									->where("(status = 'Pending' OR status = 'Traveling') AND driver_id = $driverId")
									->get()->result()[0]->count;
	}
	public function updateTripSchedule(){
		$driver_id = $this->input->post('driver_id',true);
		if ($driver_id == '') {
			$driver_id = null;
		}
		$uv_id = $this->input->post('uv_id',true);
		if ($uv_id == '') {
			$uv_id = null;
		}
		$departure = date($this->input->post('date',true) . ' ' . date('H:i:s', strtotime($this->input->post('depart_time',true))));
		if ($departure > date('Y-m-d H:i:s')){
			$this->db->where('trip_id', $_POST['trip_id'])
									->update('trip', [
												'depart_time' =>	$_POST['depart_time'],
												'driver_id'		=>	$driver_id,
												'uv_id'				=>	$uv_id,
												'query_date_time' 	=> date($_POST['date'] . ' ' . date('H:i:s', strtotime($_POST['depart_time'])))
											]);
			return true;
		}else{ return false; }
	}

	public function fetchTripDate(){
		return $this->db->select('DISTINCT(date)')
										->where("route_id = ".$_POST['route_id']." AND status = '".$_POST['status']."'")
										->order_by('date ASC')
										->get('trip')->result();

	}



	/*
  |--------------------------------------------------------------------------
  | OLD METHDS
  |--------------------------------------------------------------------------
  */
	public function update_trip_record(){
		$date = $this->modify_current_date(date('Y-m-d H:i:s'), 1, 0, 0);
		$query = $this->db->query("
				SELECT * FROM trip
				WHERE query_date_time < '$date'
				AND status = 'Pending'
			");
		return $query->result();
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

	public function get_plate_no(){	
		$id = $this->db->query("
				SELECT * FROM trip 
				WHERE status = 'Traveling' OR status = 'Pending'
			")->result_array();
		$str = '0';
		if (count($id) > 0) {
			foreach ($id as $key => $value) {
				if ($value['uv_id'] != '') {
					$str = $str.','.$value['uv_id'];
				}
			}
		}
		$query = $this->db->query("
			SELECT *
			FROM uv_unit 
			WHERE uv_id  NOT IN ($str)
			AND uv_unit.company_id = ". $this->session->userdata('company_id')
		);
		return $query->result();
	}

	public function get_driver(){			
		$id = $this->db->query("
				SELECT driver_id FROM trip 
				WHERE status = 'Traveling' OR status = 'Pending'
			")->result_array();
		$str = '0';
		if (count($id) > 0) {
			foreach ($id as $key => $value) {
				if ($value['driver_id'] != '') {
					$str = $str.','.$value['driver_id'];
				}
			}
		}

		$query = $this->db->query("
			SELECT distinct employee.* 
			FROM employee
			INNER JOIN user ON employee.user_id = user.user_id
			WHERE employee.role='driver'
				AND user.status = 1
				AND employee.employee_id NOT IN ($str)
				AND employee.company_id = ". $this->session->userdata('company_id')
			);
		return $query->result();
	}

	public function modify_current_date($date = "", $hours = "", $minutes = "", $seconds = "") {
		$new_date = new DateTime($date);
		$new_date->modify('-'.$hours.' hour -'.$minutes.' minutes -'.$seconds.' seconds');
		return $new_date->format('Y-m-d H:i:s');
	}

	public function get_origin_point($route_id = "") {
		return $this->db->get_where('route', ['route_id'=>$route_id])->row()->origin_lat_lng;
	}

	public function get_accident_alert() {
		$res = $this->db->join('employee', 'notification.sender = employee.employee_id')->
						join('trip', 'employee.employee_id = trip.driver_id')->
						join('accident_log', 'trip.trip_id = accident_log.trip_id')->
						join('uv_unit', 'trip.uv_id = uv_unit.uv_id')->
						group_by('trip.trip_id')->
						get_where('notification', ['notification_type'=>'accident', 'is_recieve'=>0])->result();
		foreach ($res as $key => $value) {
			$location = json_decode($res[$key]->location);
			$res[$key]->{'lat'} = $location->lat;
			$res[$key]->{'lng'} = $location->lng;
		}
		//echo "<pre>";print_r($res); die();
		return $res;
	}


	public function read_notification($type = '', $notification_id = '') {
		$this->db->query("
				UPDATE notification SET is_recieve = 1
				WHERE notification_type = '$type'
				AND notification_id = $notification_id;
			");
	}
}