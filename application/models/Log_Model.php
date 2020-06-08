<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Log_Model extends CI_MODEL {

	/*
	|--------------------------------------------------------------------------
	| LOGGER
	|--------------------------------------------------------------------------
	*/

	public function log($log) {
		$log_data = [
			'id'   				=> '',
			'activity'  	=> $log['activity'],
			'data'   			=> $log['data'],
			'table'   		=> $log['table'],
			'created_on' 	=> date('Y-m-d H:i:s'),
			'ip_address' 	=> $_SERVER['REMOTE_ADDR'],
			'role' 				=> $this->session->userdata('role'),
			'ref_id' 			=> $log['ref_id'],
			'user_id' 		=> $this->session->userdata('user_id')
		];
    $this->db->insert('logger', $log_data);
    return $this->db->insert_id();
	}

	public function getRouteData($routeId){
		return json_encode(
			$this->db->select('route_id, origin, destination, via, fare')
							 ->where('route_id = '.$routeId)->get('route')->result()[0]
		);
	}

	public function getTripData($tripId){
		return json_encode(
			$this->db->select(
										'trip.trip_id, trip.date, trip.depart_time, trip.arrival_time, trip.status, 
										 route.origin, route.destination, uv_unit.plate_no, 
										 concat(employee.f_name, " ", employee.l_name) as driver_name'
								)
						  ->join('route', 'route.route_id = trip.route_id')
						  ->join('uv_unit', 'uv_unit.uv_id = trip.uv_id', 'left')
						  ->join('employee', 'employee.employee_id = trip.driver_id', 'left')
						  ->where('trip_id = '.$tripId)->get('trip')->result()[0]
		);
	}

	public function getUvExpress($uvId){
		return json_encode(
			$this->db->select('uv_id, plate_no, max_pass, franchise_no, model, brand_name')
							 ->where('uv_id = '.$uvId)->get('uv_unit')->result()[0]
		);
	}

	public function getEmployee($id){
		return json_encode(
			$this->db->select('employee_id, f_name, m_name, l_name, license_no, contact_no, address, employee.role, status')
							 ->join('user', 'user.user_id = employee.user_id')
							 ->where('employee_id = '.$id)->get('employee')->result()[0]
		);
	}

	public function getReservation($id){
		return json_encode(
			$this->db->select('seat_id, boarding_pass, full_name, contact_no, seat_no, booking_id')
							 ->where('seat_id = '.$id)->get('seat')->result()[0]
		);
	}

	public function getEmergencyContact($id){
		return json_encode(
			$this->db->select('contact_id, contact_no, description, status, date_added, concat(origin, " <span>&#8644;</span> ", destination) as route')
										->join('route', 'accident_contact.route_id = route.route_id')
										->where('contact_id = '.$id)
										->get('accident_contact')->result()[0]
		);
	}

	public function getReply($replyId){
		return json_encode(
			$this->db->select('
					feedback.message as feedback_message, 
					feedback.date_added as feedback_date, 
					reply.message as reply_message,
					reply.date_added as reply_date, 
				')
				->join('reply', 'reply.feedback_id = feedback.feedback_id')
				->where('reply.reply_id = '.$replyId)
				->get('feedback')->result()[0]
		);
	}
	// END LOGGER
	
	public function countActivity($lastId){
		$date = '';
		if ($_POST['date'] != ''){
			$date = "AND LEFT(created_on, 10) = '".$_POST['date']."' ";
		}
		return $this->db->select('count(*) as count')
										->where(
												"id < ".$lastId."
												AND user_id = ".$this->session->userdata('user_id')."
												AND table LIKE '%".$_POST['filter']."%' ".$date
										)
										->get('logger')->result()[0]->count;
	}

	public function fetchAccountActivity(){
		if ($_POST['last_id'] < 1){
			$limit = '';
		}else{
			$limit = "AND id < $_POST[last_id]";
		}
		$date = '';
		if ($_POST['date'] != ''){
			$date = "AND LEFT(created_on, 10) = '".$_POST['date']."' ";
		}
		$dateList = $this->db->select('DISTINCT(LEFT(created_on, 10)) as created_on')
														->where(
															'user_id = '.$this->session->userdata('user_id')." 
															 AND table LIKE '%".$_POST['filter']."%' "
															 .$date.$limit
														)
													  ->order_by('created_on DESC')->limit($_POST['limit'])
													  ->get('logger')->result();
		if (count($dateList) > 0){
			$res = [];
			foreach ($dateList as $key => $value) {
				$logDate = date('Y-m-d', strtotime($value->created_on));
				$data = $this->db->where(
													'user_id = '.$this->session->userdata('user_id')." 
													 AND table LIKE '%".$_POST['filter']."%'
													 AND LEFT(created_on, 10) LIKE '%".$logDate."%'"
												)
											  ->order_by('created_on DESC')
											  ->get('logger')->result();
				array_push($res, $data);
			}
			return $res;
		}else{
			return false;
		}
	}

	public function get_log() {
		return $this->db->join('user', 'logger.user_id = user.user_id')
									 ->join('employee', 'employee.user_id = user.user_id')
									 ->order_by('created_on DESC')
									 ->get('logger')
									 ->result();
	}

	public function get_all_over_speed() {
		return $this->db->join('trip', 'trip.trip_id = over_speed_log.trip_id')->
						join('employee', 'trip.driver_id = employee.employee_id')->
						join('uv_unit','trip.uv_id = uv_unit.uv_id')->
						get('over_speed_log')->
						result();
	}

	public function get_all_accident() {
		$res = $this->db->join('trip', 'trip.trip_id = accident_log.trip_id')->
						join('employee', 'trip.driver_id = employee.employee_id')->
						join('uv_unit','trip.uv_id = uv_unit.uv_id')->
						get('accident_log')->
						result();
		foreach ($res as $key => $value) {
			$loc = json_decode($res[$key]->location);
			$res[$key]->lat = $loc->lat;
			$res[$key]->lng = $loc->lng;
		}
		return $res;
	}

	public function getRoute(){
		return $this->db->select('route_id, origin, destination')
										->where("origin = 'Tacloban City'")
										->get('route')->result();
	}

	public function saveEmergencyContact(){
		$date = date('Y-m-d');
		$data = [
			'contact_id'  => '',
			'contact_no'  => $_POST['contactNo'],
			'description' => $_POST['description'],
			'status'			=> 'Active',
			'date_added'	=> date('Y-m-d'),
			'route_id'		=> $_POST['routeId']
		];
		$this->db->insert('accident_contact', $data);
		$id = $this->db->insert_id();
		$logData = json_decode($this->log_model->getEmergencyContact($id));
		$this->log([
			'activity' => 'Added new emergency contact for '.$logData->route.' route.',
			'data' 		 => json_encode($logData),
			'table'		 => 'accident_contact',
			'ref_id'   => $id
		]);
		return $id;
	}

	public function setContactStatus(){
		$this->db->update(
			'accident_contact',
			['status'=>$_POST['status']],
			['contact_id' => $_POST['contact_id']]
		);
		$logData = json_decode($this->log_model->getEmergencyContact($_POST['contact_id']));
		$this->log([
			'activity' => $_POST['status'].' emergency contact for '.$logData->route.' route.',
			'data' 		 => json_encode($logData),
			'table'		 => 'accident_contact',
			'ref_id'   => $_POST['contact_id']
		]);
		return $this->db->affected_rows();
	}

	public function get_all_alert_contact() {
		return $this->db->join('route', 'accident_contact.route_id = route.route_id')
										->get('accident_contact')->result();
	}

	public function updateContact() {
		$this->db->update(
			'accident_contact',
			[
				'description' => $_POST['description'],
				'contact_no' => $_POST['contactNo'],
			],
			['contact_id' => $_POST['contactId']]
		);
		$logData = json_decode($this->log_model->getEmergencyContact($_POST['contactId']));
		$this->log([
			'activity' => 'Updated emergency contact for '.$logData->route.' route.',
			'data' 		 => json_encode($logData),
			'table'		 => 'accident_contact',
			'ref_id'   => $_POST['contactId']
		]);
		return $this->db->affected_rows();
	}	

	public function add_contact() {
		$this->db->insert('accident_contact', $_POST);
	}

	public function deleteContact(){
		$logData = json_decode($this->log_model->getEmergencyContact($_POST['contact_id']));
		$this->log([
			'activity' => 'Deleted emergency contact for '.$logData->route.' route.',
			'data' 		 => json_encode($logData),
			'table'		 => 'accident_contact',
			'ref_id'   => $_POST['contact_id']
		]);
		$this->db->delete('accident_contact', ['contact_id'=>$_POST['contact_id']]);
		return $this->db->affected_rows();
	}

	public function remove_contact($id){
		$this->db->where('contact_id', $id)->delete("accident_contact");
	}


	public function get_all_feedback($limit) {
		$res = $this->db->join('passenger', 'feedback.passenger_id = passenger.passenger_id')->
						order_by('date_added DESC')->limit($limit)->
						get('feedback')->
						result();
		return $res;
	}

	public function loadMoreFeedBack(){
		if ($_POST['last_id'] < 1){
			$last_id = $this->db->select('max(feedback_id) as last_id')->get('feedback')->result()[0]->last_id + 1;
		}else{
			$last_id = $_POST['last_id'];
		}
		return $this->db->join('passenger', 'feedback.passenger_id = passenger.passenger_id')
						->where(
								"feedback_id < ".$last_id." AND 
								(passenger.f_name LIKE '%".$_POST['filter']."%'
								 OR passenger.l_name LIKE '%".$_POST['filter']."%'
								 OR CONCAT(passenger.f_name, ' ', passenger.l_name) LIKE '%".$_POST['filter']."%')"
							)
						->order_by('date_added DESC')->limit($_POST['limit'])
						->get('feedback')
						->result();
	}

	public function fetchLatestFeedback(){
		$lastId = $_POST['feed0'];
		$notIn = '(0';
		for($i=0; $i<count($_POST)-1; $i++){
			$notIn .= ', '.$_POST['feed'.$i];
		}
		$notIn .= ')';
		return $this->db->join('passenger', 'feedback.passenger_id = passenger.passenger_id')
						->where(
								"feedback_id > ".$lastId." AND
								 feedback_id NOT IN $notIn AND
								(passenger.f_name LIKE '%".$_POST['filter']."%'
								 OR passenger.l_name LIKE '%".$_POST['filter']."%'
								 OR CONCAT(passenger.f_name, ' ', passenger.l_name) LIKE '%".$_POST['filter']."%')"
							)
						->order_by('date_added DESC')
						->get('feedback')
						->result();
	}

	public function fetchReply($feedback_id){
		return $this->db->where('feedback_id = '.$feedback_id)
										->order_by('reply_id ASC')
										->get('reply')
										->result();
	}

	public function fetchSender($sender_type, $sender_id){
		$table = ($sender_type == 'admin') ? 'employee' : 'passenger';
		$col = ($sender_type == 'admin') ? "concat(f_name, ' ', l_name) as name, img_url" : "concat(f_name, ' ', l_name) as name";
		return $this->db->select($col)
								->where($table.'_id = '.$sender_id)
								->get($table)->result()[0];
	}

	public function fetchLatestReply($ids){
		$data = $this->db->where('feedback_id = '.$_POST['id'].' AND reply_id NOT IN '.$ids)
										->get('reply')->result();
		if (count($data)){
			foreach ($data as $key => $value) {
				$sender = $this->fetchSender($value->sender_type, $value->sender_id);
				$data[$key]->sender = $sender->name;
				if (isset($sender->img_url)){
					$data[$key]->img_url = $sender->img_url;
				}else{
					$data[$key]->img_url = '';
				}
			}
		}
		return $data;
	}

	public function count_feedback($last_id, $keyword){
		return $this->db->select('count(*) as count')
										->join('passenger', 'feedback.passenger_id = passenger.passenger_id')
										->where('feedback_id < '.$last_id." AND 
												(passenger.f_name LIKE '%".$keyword."%'
												 OR passenger.l_name LIKE '%".$keyword."%'
												 OR CONCAT(passenger.f_name, ' ', passenger.l_name) LIKE '%".$keyword."%')"
											)
										->get('feedback')
										->result()[0]->count;
	}

	public function countFeedbackReply($feedback_id){
		return $this->db->select('count(*) as count')
										->where('feedback_id = '.$feedback_id)
										->get('reply')
										->result()[0]->count;
	}

	public function saveReply(){
		$reply = [
			'reply_id' => '',
			'message' => $_POST['message'],
			'sender_type' => 'admin',
			'date_added' => date('Y-m-d H:i:s'),
			'feedback_id' => $_POST['feedback_id'],
			'sender_id' => $this->session->userdata('employee_id')
		];
		$this->db->insert('reply', $reply);
		$id = $this->db->insert_id();

		$passenger = $this->db->join('passenger', 'passenger.passenger_id = feedback.passenger_id')
													->where('feedback.feedback_id = '.$_POST['feedback_id'])
													->get('feedback')->result()[0];

		$this->log_model->log([
			'activity' => 'Replied to '.$passenger->f_name.' '.$passenger->l_name.' feedback.',
			'table'		 => 'feedback',
			'data'		 => $this->getReply($id),
			'ref_id'   => $this->session->userdata('employee_id')
		]);

		return $this->db->join('employee', 'employee.employee_id = reply.sender_id')
						->where('reply_id = '.$id)
						->get('reply')
						->result();
	}

	public function delete_feedback($id){
		$this->db->where('feedback_id', $id)->delete("feedback");
	}
}

?>