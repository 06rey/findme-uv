<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class employee_model extends CI_Model {

	public $f_name;
	public $m_name;
	public $l_name;
	public $license_no;
	public $contact_no;
	public $address;
	public $role;
	public $company_id;
	public $is_login;
	public $token;
	public $user_id;

	public function register($role = "")
	{

		$this->f_name = $this->input->post('f_name',true);
		$this->m_name = $this->input->post('m_name',true);
		$this->l_name = $this->input->post('l_name',true);
		$this->contact_no = $this->input->post('contact_no',true);
		$this->address = $this->input->post('address',true);
		$this->role = $role;
		$this->company_id = $this->session->userdata('company_id');
		$this->is_login = $this->session->userdata('company_id');
		$this->token = '';
		$this->license_no = '';

		if ($role == 'driver') {
			$this->license_no =$this->input->post('license_no',true);
			$username = $this->contact_no;
		} else {
			$username = $this->input->post('username',true);
		}

		$user = [
			'username' => $username,
			'password' => sha1($this->input->post('password',true)),
			'status' => 1,
			'role' => $this->role
		];

		$this->db->insert('user', $user);
		$this->user_id = $this->db->insert_id();

		$this->db->insert('employee', $this);

		return	$this->db->insert_id();
	}
	
	public function get_driver()
	{	
		$this->db->select('*');
		$this->db->from('user');
		$this->db->join('employee', 'user.user_id = employee.user_id');
		$this->db->where(['employee.role'=>'driver']);
		$query = $this->db->get();
 		return $query->result();
	}

	public function get_clerk()
	{	
		$this->db->select('*');
		$this->db->from('user');
		$this->db->join('employee', 'user.user_id = employee.user_id');
		$this->db->where(['employee.role'=>'clerk']);
		$query = $this->db->get();
 		return $query->result();
	}

	public function get($id = "")
	{	
			
		$this->db->select('*');
		$this->db->from('user');
		$this->db->join('employee', 'user.user_id = employee.user_id');
		$this->db->where(['user.user_id'=>$id]);
		$query = $this->db->get();
 		return $query->row();
	}

	public function edit($id = "",  $user_id = "")
	{
		$employee = [
			'f_name' => $this->input->post('f_name',true),
			'm_name' => $this->input->post('m_name',true),
			'l_name' => $this->input->post('l_name',true),
			'license_no' => $this->input->post('license_no',true),
			'contact_no' => $this->input->post('contact_no',true),
			'address' => $this->input->post('address',true)
		];

		$this->db->update('user', ['status'=>$this->input->post('status',true)], ['user_id' => $user_id]);
		$user = $this->db->affected_rows();
		$this->db->update('employee',$employee,['employee_id' => $id]);
		$emp = $this->db->affected_rows();
		return $user + $emp;
	}
}


?>