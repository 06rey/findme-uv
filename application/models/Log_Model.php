<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Log_Model extends CI_MODEL {

	public function log($activity = "") {
		$log_data = [
			'id'   => '',
			'activity'   => $activity,
			'created_on' => date('Y-m-d H:i:s'),
			'ip_address' 	=> $_SERVER['REMOTE_ADDR'],
			'role' 	=> $this->session->userdata('role'),
			'user_id' => $this->session->userdata('user_id')
		];
	    $this->db->insert('logger', $log_data);
	    return $this->db->insert_id();
	}

	public function get_log() {
		$logs = $this->db->get('logger')->result_array();
		foreach ($logs as $key => $value) {
			if ($value['role'] == 'owner') {
				$table = 'company_owner';
			} else {
				$table = 'employee';
			}
			$user = $this->db->select('*')->
						from('user')->
						join($table, "user.user_id = $table.user_id")->
						where('user.user_id', $value['user_id'])->
						get()->
						row_array();
			if ($user != null) {
				$logs[$key]['fullname'] = "$user[f_name] $user[l_name]";
			}
		}
		return $logs;
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

	public function get_all_alert_contact() {
		return $this->db->get('accident_contact')->result();
	}

	public function update_contact($id) {
		$this->db->update('accident_contact', ['contact_name'=>$_POST['contact_name'], 'contact_no'=>$_POST['contact_no']], ['contact_id' => $id]);
	}

	public function add_contact() {
		$this->db->insert('accident_contact', $_POST);
	}

	public function remove_contact($id){
		$this->db->where('contact_id', $id)->delete("accident_contact");
	}


	public function get_all_feedback() {
		$res = $this->db->join('passenger', 'feedback.passenger_id = passenger.passenger_id')->
						get('feedback')->
						result();
		foreach ($res as $key => $value) {
			if (strlen($value->message) > 50) {
				$res[$key]->{'temp_msg'} = substr($value->message, 0, 50). '...';
				$res[$key]->{'shorten'} = true;
			} else {
				$res[$key]->{'temp_msg'} = $res[$key]->message;
				$res[$key]->{'shorten'} = false;
			}
		}
		return $res;
	}

	public function delete_feedback($id){
		$this->db->where('feedback_id', $id)->delete("feedback");
	}
}

?>