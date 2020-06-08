<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trip_management extends CI_Controller {

	/*
	|--------------------------------------------------------------------------
	| ROUTE
	|--------------------------------------------------------------------------
	*/

	public function fetchUvLocation(){
		echo json_encode([
			'status' => true,
			'data' => json_decode($this->trip_management_model->getUvLocation()[0]->current_location)
		]);
	}

	public function fetchRouteGeoPoints(){
		$data = $this->trip_management_model->fetchRouteGeoPoints();
		foreach ($data as $key => $value) {
			$data[$key]->origin_lat_lng = json_decode($value->origin_lat_lng);
			$data[$key]->destination_lat_lng = json_decode($value->destination_lat_lng);
			$data[$key]->way_point = json_decode($value->way_point);
		}
		echo json_encode([
			'status' => true,
			'data' => $data
		]);
	}

	public function fetchRoute(){
		echo json_encode([
			'status' => true,
			'data' => $this->trip_management_model->fetchRoute()
		]);
	}

	public function addRoute(){
		$routes = $this->trip_management_model->fetchRoute();
		$duplicate = false;
		foreach ($routes as $key => $route) {
			if ($route->origin == $_POST['origin'] && $route->destination == $_POST['destination']){
				$duplicate = true;
			}
		}

		if (!$duplicate){
			$id = $this->trip_management_model->insertRoute();
			$this->log_model->log([
				'activity' => 'Added '.$_POST['origin'].' to '.$_POST['destination'].' route.',
				'data' 		 => $this->log_model->getRouteData($id),
				'table'		 => 'route',
				'ref_id'   => $id
			]);

			echo json_encode([
				'status' => true,
				'data' => $id
			]);
		}else{
			echo json_encode([
				'status' => false,
				'data' => 'Has duplicate'
			]);
		}
	}

	public function updateRoute(){
		$this->log_model->log([
			'activity' => 'Updated '.$_POST['origin'].' to '.$_POST['destination'].' route.',
			'data' 		 => $this->log_model->getRouteData($_POST['route_id']),
			'table'		 => 'route',
			'ref_id'   => $_POST['route_id']
		]);
		echo json_encode([
			'data' => $this->trip_management_model->updateRoute()
		]);
	}

	public function getRouteInfo($routeId = ""){
		echo json_encode([
			'status'=> true,
			'data' => $this->trip_management_model->routeInfo($routeId)
		]);
	}

	public function setMapRoute(){
		$route = $this->trip_management_model->routeInfo($_POST['route_id']);
		$this->log_model->log([
			'activity' => 'Set '.$route[0]->origin.' to '.$route[0]->destination.' route direction',
			'data' 		 => 'None',
			'table'		 => 'route',
			'ref_id'   => $_POST['route_id']
		]);
		echo json_encode([
			'data' => $this->trip_management_model->saveMapRoute()
		]);
	}

	/*
	|--------------------------------------------------------------------------
	| TRIP PRIVATE METHODS
	|--------------------------------------------------------------------------
	*/
	private function autoCancelTrip(){
		// Cancel trip with invalid date of departure
		$result = $this->trip_management_model->update_trip_record();
		if (count($result) > 0) {
			foreach ($result as $key => $value) {
				$this->trip_management_model->set_trip_status('Cancelled', $value->trip_id);
				$this->log_model->log([
					'activity' => 'Due trip schedule. Auto cancelled by the system',
					'data' 		 => $this->log_model->getTripData($value->trip_id),
					'table'		 => 'trip',
					'ref_id'   => $value->trip_id
				]);
			}
		}
	}

	private function checkLogin(){
		if (!$this->user_model->is_logged_in() ) {
			redirect('user/login');
		} elseif ($this->session->userdata('role') == ('clerk')) {
			redirect('booking/all');
		}
	}
	/*
	|--------------------------------------------------------------------------
	| JSON
	|--------------------------------------------------------------------------
	*/


	public function getTripJSON($status, $route_id, $date){
		$allTrip = $this->trip_management_model->getTrip($route_id, $status, $date);
		foreach ($allTrip as $key => $value) {
			if ($value->f_name == ''){
				$allTrip[$key]->{'driver_name'} = "No assign";
			}else{
				$allTrip[$key]->{'driver_name'} = "$value->f_name $value->l_name";
			}
			if ($value->plate_no == ''){
				$allTrip[$key]->plate_no = 'No assign';
			}
			$allTrip[$key]->depart_time = date("g:i A", strtotime($allTrip[$key]->depart_time));
		}
		echo json_encode(['status'=>true,'data'=> $allTrip]);
	}

	public function setTripStatus() {
		$trip = $this->trip_management_model->get_trip($_POST['trip_id']);
		if ($this->trip_management_model->set_trip_status($_POST['status'], $_POST['trip_id']) > 0) {
			echo json_encode(['status'=>true]);
			$this->log_model->log([
				'activity' => 'Cancelled '.$trip->origin.' to '.$trip->destination.' trip schedule.',
				'data' 		 => $this->log_model->getTripData($_POST['trip_id']),
				'table'		 => 'trip',
				'ref_id'   => $_POST['trip_id']
			]);
		} else {
			echo json_encode(['status'=>false]);
		}
	}

	// Add trip
	public function addTrip(){
		$id = $this->trip_management_model->insertTrip();
		if ($id > 0) {
			$response = [
				'status' => true,
				'msg' => 'New trip successfully added.'
			];
			$trip = $this->trip_management_model->get_trip($id);
			$this->log_model->log([
				'activity' => 'Added '.$trip->origin.' to '.$trip->destination.' new trip schedule.',
				'data' 		 => $this->log_model->getTripData($id),
				'table'		 => 'trip',
				'ref_id'   => $id
			]);
		}else{
			//set error
			$response = [
				'status' => false,
				'msg' => 'Failed to save trip. Departure date is not valid'
			];
		}
		echo json_encode($response);
	}

	public function getNumberOfTrip($routeId){
		echo json_encode([
			'status' => true,
			'data' => $this->trip_management_model->countTrip($routeId)
		]);
	}

	public function getAllRouteTrip(){
		$this->autoCancelTrip();
		echo json_encode([
			'status' => true,
			'data' => $this->trip_management_model->getAllTrip()
		]);
	}

	public function numberOfTrip($date, $routeId, $status){
		$this->autoCancelTrip();
		echo json_encode([
			'status' => true,
			'data' => $this->trip_management_model->getNoOfTrip($date, $routeId, $status)
		]);
	}

	public function tripPassenger($tripId){
		echo json_encode([
			'status' => true,
			'data' => $this->trip_management_model->getTripPassenger($tripId)
		]);
	}

	public function countPassenger($tripId){
		echo json_encode([
			'status' => true,
			'data' => $this->trip_management_model->getCountPassenger($tripId)
		]);
	}
	// Check Uv Express if available
	public function checkUvExpress($tripId){
		echo json_encode([
			'status' => true,
			'data' => $this->trip_management_model->getUvExpressAvailabilty($tripId) > 0 ? false : true
		]);
	}
	// Check Driver if available
	public function checkDriver($driverId){
		echo json_encode([
			'status' => true,
			'data' => $this->trip_management_model->geDriverAvailabilty($driverId) > 0 ? false : true
		]);
	}
	// Get available driver
	public function availableDriver(){
		echo json_encode([
			'status' => true,
			'data' => $this->trip_management_model->get_driver()
		]);
	}
	// Get UV Express
	public function availableUvExpress(){
		echo json_encode([
			'status' => true,
			'data' => $this->trip_management_model->get_plate_no()
		]);
	}
	// Update trip
	public function updateTrip(){
		$status = $this->trip_management_model->updateTripSchedule();
		echo json_encode([
			'status' => $status
		]);
		if ($status){
			$trip = $this->trip_management_model->get_trip($_POST['trip_id']);
			$this->log_model->log([
				'activity' => 'Updated '.$trip->origin.' to '.$trip->destination.' trip schedule.',
				'data' 		 => $this->log_model->getTripData($_POST['trip_id']),
				'table'		 => 'trip',
				'ref_id'   => $_POST['trip_id']
			]);
		}
	}

	// Fetch trip date -> Cancelled/Trip history
	public function fetchTripDate(){
		$data = $this->trip_management_model->fetchTripDate();
		echo json_encode([
			'status' => COUNT($data) > 0 ? true : false,
			'data' => $data
		]);
	}

	/*
	|--------------------------------------------------------------------------
	| VIEWS
	|--------------------------------------------------------------------------
	*/
	public function allTrip(){
		$this->checkLogin();
		$this->autoCancelTrip();
		// Load trip management table
		$this->load->view('trip/trip_management_view',[
			'pageTitle'=>"Trip Management",
			'all_trip'=> $this->trip_management_model->getAllTrip(),
			'routes'=> $this->trip_management_model->get_route(),
			'uv_unit'=> $this->trip_management_model->get_plate_no(),
			'employee'=> $this->trip_management_model->get_driver(),
			'message'=> $this->session->flashdata('message')
		]);
	}

	public function trip($status, $route_id, $date){
		$this->checkLogin();
		if ($status == 'Arrived' && $date > date('Y-m-d')){
			$date = date('Y-m-d');
		}
		$this->load->view('trip/tripView',[
			'pageTitle'=>$status. ' Trips',
			'status'=>$status,
			'date' => $date,
			'route' => $this->trip_management_model->getRoute($route_id),
			'allTrip'=> $this->trip_management_model->getTrip($route_id, $status, $date),
			'uv_unit'=> $this->trip_management_model->get_plate_no(),
			'employee'=> $this->trip_management_model->get_driver(),
			'message'=> $this->session->flashdata('message')
		]);
	}
}