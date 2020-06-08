<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class employee_model extends CI_Model {

	private $companyId;

	function __construct(){
		$this->companyId = $this->session->userdata('company_id');
	}

	/*
  |--------------------------------------------------------------------------
  | ADD/INSERT
  |--------------------------------------------------------------------------
  */

	public function insertEmployee(){
		$_POST['is_login'] = '';
		$_POST['token'] = '';
		$_POST['company_id'] = $this->companyId;
		$_POST['user_id'] = $this->insertUser();
		$_POST['img_url'] = $this->saveImage($_FILES); // Save user image
		unset($_POST['password']);
		unset($_POST['cpassword']);
		unset($_POST['imgUrl']);
		$this->db->insert('employee', $_POST);
		return $this->db->insert_id();
	}

	public function insertUser(){
		$this->db->insert('user', [
			'username' => $_POST['contact_no'],
			'password' => sha1($_POST['password']),
			'status' => 1,
			'role' => $_POST['role']
		]);
		return $this->db->insert_id();
	}

	/*
  |--------------------------------------------------------------------------
  | GET/FETCH
  |--------------------------------------------------------------------------
  */

	public function getAllDriver(){
		return $this->db->join('user', 'user.user_id = employee.user_id')
										->where("employee.role = 'driver' AND employee.company_id = $this->companyId")
										->get('employee')->result();
	}

	public function getAllClerk(){
		return $this->db->join('user', 'user.user_id = employee.user_id')
										->where("employee.role = 'clerk' AND employee.company_id = $this->companyId")
										->get('employee')->result();
	}

	public function getDriverSched($employeeId){
		return $this->db->where("driver_id = $employeeId AND (status = 'Pending' || status = 'Traveling')")
										->get('trip')->result();
	}

	/*
  |--------------------------------------------------------------------------
  | UPDATE
  |--------------------------------------------------------------------------
  */
	public function updateStatus(){
		$this->db->update('user', ['status'=>$_POST['status']], ['user_id' => $_POST['user_id']]);
		return $this->db->affected_rows();
	}

	public function updateEmployee(){
		$data = [
			'address'=> $_POST['address'], 
			'contact_no' => $_POST['contact_no']
		];
		if (isset($_FILES['img']['name'])){
			$this->deleteImage($_POST['employee_id']);
			$data['img_url'] = $this->saveImage($_FILES);
		}
		$this->db->update('employee', $data, ['employee_id'=> $_POST['employee_id']]);
		return $this->db->affected_rows();
	}

	public function updateAccount(){
		$data = [
			'address'=> $_POST['address'], 
			'contact_no' => $_POST['contact_no']
		];
		$this->db->update('employee', $data, ['employee_id'=> $this->session->userdata('employee_id')]);
		return $this->db->affected_rows();
	}

	/*
  |--------------------------------------------------------------------------
  | MISC
  |--------------------------------------------------------------------------
  */

	public function checkDuplicate(){
		return $this->db->get_where('employee', [
			'f_name'=>$_POST['f_name'], 
			'm_name'=>$_POST['m_name'], 
			'l_name'=>$_POST['l_name']
		])->num_rows() > 0 ? false : true;
	}

	public function checkMobileNumber(){
		return $this->db->get_where('employee', [
			'contact_no'=>$_POST['contact_no']
		])->num_rows() > 0 ? false : true;
	}

	public function checkAccountMobileNumber(){
		return $this->db->where("contact_no = '".$_POST['contact_no']."' AND employee_id != ".$this->session->userdata('employee_id'))
										->get('employee')
										->num_rows() > 0 ? false : true;
	}

	public function deleteImage($employeeId){
		$filePath = $this->db->get_where('employee', ['employee_id'=>$employeeId])->result()[0]->img_url;
		if ($filePath != ''){
			unlink($filePath);
		}
	}

	public function changeImage(){
		$this->deleteImage($this->session->userdata('employee_id'));
		$src = $this->saveImage($_FILES);
		$this->db->update(
			'employee', 
			['img_url'=>$src],
			['employee_id'=>$this->session->userdata('employee_id')]);
		if ($this->db->affected_rows() > 0){
			$this->session->set_userdata('img_url', $src);
			return $src;
		}else{
			return false;
		}
	}

	private function saveImage($files){
		if (isset($files['img']['name'])){
			$unique = 0;
  		$dir = 'assets/img/user/'; //Set image directory
  		$fileType = strtolower(pathinfo(basename($files['img']['name']), PATHINFO_EXTENSION)); // Get filetype extension
  		if ($fileType == 'jpg' || $fileType == 'png' || $fileType == 'jpeg'){
  			while (file_exists($dir.$unique.basename($files['img']['name']))) {
  				$unique++;
  			}
  			$file = $dir.$unique.basename($files['img']['name']); // Append $unique to form unique target file
  			move_uploaded_file($files['img']['tmp_name'], $file);
  			return $file;
  		}else{
  			return 'assets/img/user-gray.png';
  		}
  	}else{
  		return 'assets/img/user-gray.png';
  	}
	}
}

?>