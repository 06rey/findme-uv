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
}

?>