<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class user_model extends CI_Model {
	public $username;
	public $password;
	public $status;
	public $role;
	public $user_id;

	public function register()
	{

		$this->username =$this->input->post('username',true);
		$this->password =sha1($this->input->post('password',true));
		$this->status =$this->input->post('status',true);
		$this->role =$this->input->post('role',true);
		$this->user_id =$this->input->post('user_id',true);

		$this->db->insert('user', $this);

		 return $this->db->insert_id();
	}

	// User authentication
	public function login() {

		 $username =$this->input->post('username',true);
		 $password =sha1($this->input->post('password',true));

		 $query = $this->db->get_where('user',['username'=>$username,'password'=>$password]);
		 $user = $query->row_array();

		 if ($query->num_rows()) {
		 	$query2 = $this->db->get_where('user',['user_id'=>$user['user_id'],'status'=>1]);
		 	$status = $query->row_array();
		 	if ($query2->num_rows()) {

		 		switch ($user['role']) {
				 	case 'owner':
				 		$user_info = $this->get_owner_info($user['user_id']);
				 		break;
				 	case 'admin':
				 		$user_info = $this->get_admin_info($user['user_id']);
				 		break;
				 	case 'clerk':
				 		$user_info = $this->get_clerk_info($user['user_id']);
				 		break;
				 }

				 if (count($user_info) > 0) {
				 	$this->set_user_session($user_info);
				 	return 'success';
				 } else {
				 	return 'not_found';
				 }
		 	} else {
		 		return 'inactive';
		 	}
		 } else {
		 	return 'not_found';
		 }
	}

	// Check if session have active user
	public function is_logged_in() {
		return $this->session->userdata('is_loggedd_in');	
	}

	// Logout user and destroy user session
	public function logout() {
		$this->session->sess_destroy();	
	}

	// Set login result to user session
	private function set_user_session($data = "") {
		foreach ($data as $key => $value) {
			$this->session->set_userdata($key,$value);
		}
		$this->session->set_userdata('is_loggedd_in',true);
	}
	// Get owner info
	private function get_owner_info($user_id = "") {
		$this->db->select('*');
		$this->db->from('company_owner');
		$this->db->join('user', 'company_owner.user_id = user.user_id');
		$this->db->where(['company_owner.user_id'=>$user_id]);
		$query = $this->db->get();
		return $query->row_array();
	}
	// Get admin info
	private function get_admin_info($user_id = "") {
		$this->db->select('*');
		$this->db->from('company');
		$this->db->join('employee', 'company.company_id = employee.company_id');
		$this->db->join('user', 'employee.user_id = user.user_id');
		$this->db->where(['employee.user_id'=>$user_id, 'employee.role'=>'admin']);
		$query = $this->db->get();
 		return $query->row_array();
	}
	// Get clerk info
	private function get_clerk_info($user_id = "") {
		$this->db->select('*');
		$this->db->from('company');
		$this->db->join('employee', 'company.company_id = employee.company_id');
		$this->db->join('user', 'employee.user_id = user.user_id');
		$this->db->where(['employee.user_id'=>$user_id, 'employee.role'=>'clerk']);
		$query = $this->db->get();
 		return $query->row_array();
	}


	// Forgot password

	public function get_account($username = "") {
		$user = $this->db->get_where('user',['username'=>$username])->row();
		if ($user != null) {
			if ($user->role != 'driver') {
				if ($user->role == 'owner') {
					$table = 'company_owner';
					} else {
						$table = 'employee';
				}
				return $this->get_user($user->user_id, $table);
			}
		} else {
			return null;
		}
	}

	public function changeAccountPassword(){
		$this->db->where('user_id = '.$this->session->userdata('user_id')." AND password = '".sha1($_POST['old_pass'])."'")
						 ->update('user', ['password'=>sha1($_POST['new_pass'])]);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function get_user($user_id = "", $table = "") {
		return $this->db->join($table, $table.'.user_id = user.user_id')->
						where('user.user_id = '.$user_id)->
						get('user')->row();
	}

	public function get_max_user_id() {
		return $this->db->select('MAX(user_id) as max_id')->from('user')->get()->row()->max_id;
	}

	public function log_reset_code($code = "", $user_id = "") {
		$code = sha1($code);
		$data = [
			'reset_id' => '',
			'code' => $code,
			'user_id' => $user_id
		];
		$this->db->insert('employee_reset_code', $data);
		return $this->db->insert_id();
	}

	public function check_reset_code($id = "") {
		$code = sha1($_POST['code']);
		$res = $this->db->get_where('employee_reset_code', ['code'=>$code, 'reset_id'=>$id])->result_array();
		return (count($res) > 0) ? true : false;
	}

	public function change_password($user_id = "") {
		$data = ['password'=>sha1($_POST['password'])];
		$this->db->where('user_id', $user_id)->
					update('user', $data);

	}

}