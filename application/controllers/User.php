<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	private function checkUser(){
		if (!$this->user_model->is_logged_in() ) {
			redirect('user/login');
		}else if($this->session->userdata('role') == ('clerk')) {
			redirect('booking');
		}
	}

	public 	function login() {
		if ($this->user_model->is_logged_in() ) {
			redirect('dashboard');
		}

		$this->load->library('form_validation');
		$this->load->view('login_page_view',[
			'pageTitle'=>"Login Page",
			'message'=> $this->session->flashdata('message')
		]);
	}
	
	// Validate login
	public 	function login_validation() {	
		if ($this->user_model->is_logged_in() ) {
			redirect('dashboard');
		}
		$this->session->mark_as_flash('message');
		$login = $this->user_model->login();

		if ($login == 'success') {
			$this->log_model->log([
				'activity' => 'Logged in to the system',
				'data' 		 => 'None',
				'table'		 => 'None',
				'ref_id'   => 'None'
			]);
			switch ($this->session->userdata('role')) {
				case 'owner':
					redirect('dashboard');
					break;
				case 'admin':
					redirect('dashboard');
					break;
				case 'clerk':
					redirect('booking');
					break;
			}
		} else if ($login == 'not_found') {
			$message = [
                'type' => 'danger',
                'message' => 'Invalid Login Data.'	
            ];
            $this->session->set_flashdata('message', $message);
			redirect('user/login');
		} else {
			$message = [
          'type' => 'danger',
          'message' => 'Sorry! Your account is currently unathorized. Please contact your administrator.'
      ];
      $this->session->set_flashdata('message', $message);
			redirect('user/login');
		}
	}

	public function account($type){
		if (!$this->user_model->is_logged_in() ) {
			redirect('user/login');
		}
		$this->load->view('user_profile',[
			'pageTitle'=>"Account",
			'type' => $type,
			'data' => $this->session->userdata()
		]);
	}

	public function updateProfile(){
		if ($this->employee_model->checkAccountMobileNumber()){
			$affected_rows = $this->employee_model->updateAccount();
			if ($affected_rows > 0){
	 			$this->log_model->log([
					'activity' => 'Update account information',
					'data' 		 => $this->log_model->getEmployee($this->session->userdata('employee_id')),
					'table'		 => 'none',
					'ref_id'   => $this->session->userdata('employee_id')
				]);
				$this->session->set_userdata('contact_no', $_POST['contact_no']);
				$this->session->set_userdata('address', $_POST['address']);
	 		}
			echo json_encode([
				'status' => true,
				'data' => $affected_rows > 0 ? true : false
			]);
		}else{
			echo json_encode([
				'status' => false,
				'data' => 'contact exists'
			]);
		}
	}

	public function logout() {
		$this->user_model->logout();
		$this->log_model->log([
			'activity' => 'Logged out from the system',
			'data' 		 => 'None',
			'table'		 => 'None',
			'ref_id'   => 'None'
		]);
		redirect('user/login');
	}

	public function changeAccountPassword(){
		if ($this->user_model->changeAccountPassword()){
			$data = true;
			$this->log_model->log([
				'activity' => 'Change account password.',
				'data' 		 => 'None',
				'table'		 => 'None',
				'ref_id'   => 'None'
			]);
		}else{
			$data = false;
		}
		echo json_encode([
			'data' => $data
		]);
	}

	public function changeImage(){
		if (isset($_FILES['img'])){
			$status = true;
			if ($this->employee_model->changeImage() != false){
				$data = true;
				$this->log_model->log([
					'activity' => 'Change account profile picture.',
					'data' 		 => 'None',
					'table'		 => 'None',
					'ref_id'   => 'None'
				]);
			}else{
				$data = false;
			}
		}else{
			$status = false;
			$data = false;
		}
		echo json_encode([
			'status' => $status,
			'data' => $data
		]);
	}

	public function forgot_password() {
		$pageTitle = "Forgot Password";
		$message = $this->session->flashdata('message');

		$this->load->view('forgot_password_step1',[
			'pageTitle'=>$pageTitle,
			'message'=> $message
		]);
	}

	public function search_account($param = "") {
		if ($param == null) {
			$username = $_POST['username'];
		} else {
			$username = $param;
		}
		$data = $this->user_model->get_account($username);
		$message = $this->session->flashdata('message');
		if ($data == null) {
			$message = [
                'type' => 'danger',
                'message' => 'Username does not exists.'
            ];
            $this->session->set_flashdata('message', $message);
			redirect('user/forgot_password');
		} else {
			$pageTitle = "Send Reset Password Code";

			$this->load->view('forgot_password_step2',[
				'pageTitle'=>$pageTitle,
				'message'=> $message,
				'data' => $data
			]);
		}
	}

	public function enter_code($username = "") {

		$user = $this->user_model->get_account($username);
	
		$pageTitle = "Password Reset Code";
		$code = $this->util->generate_code($this->user_model->get_max_user_id());
		$message = $this->session->flashdata('message');
		$msg = "$code is your FIND ME UV password reset code";
		if ($this->util->send_message($user->contact_no, $msg) == 0) {

			$id = $this->user_model->log_reset_code($code, $user->user_id);
			$data = ['reset_id'=>$id,'username'=>$user->username];
			$this->load->view('forgot_password_step3',[
				'pageTitle'=>$pageTitle,
				'message'=> $message,
				'data' => $data
			]);
		} else {

			
			$message = [
                'type' => 'danger',
                'message' => 'Failed to send reset code. Service is not available at the moment.'
            ];
            $this->session->set_flashdata('message', $message);
            redirect('user/search_account/'.$username);
		}
	}

	public function verify_reset_code($reset_id = "", $username = "") {
		$user = $this->user_model->get_account($username);
		if ($this->user_model->check_reset_code($reset_id)) {
			$this->reset_password($user->user_id);
		} else {
			$pageTitle = "Password Reset Code";
			$message = [
                'type' => 'danger',
                'message' => 'Incorrect reset code. Try again.'
            ];
			$data = ['reset_id'=>$reset_id,'username'=>$username];
			$this->load->view('forgot_password_step3',[
				'pageTitle'=>$pageTitle,
				'message'=> $message,
				'data' => $data
			]);
		}
	}

	public function reset_password($user_id = "") {
		$pageTitle = "Reset Password";
		$message = $this->session->flashdata('message');
		$this->load->view('reset_password',[
			'pageTitle'=>$pageTitle,
			'message'=> $message,
			'user_id'=> $user_id
		]);
	}

	public function change_forgot_password($user_id = "") {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('password2', 'Password Confirmation', 'required|matches[password]');
		
		if ($this->form_validation->run() == TRUE) {
			$this->user_model->change_password($user_id);
			$pageTitle = "Password Reset Code";
			$message = [
                'type' => 'success',
                'message' => 'Successfully changed forgotten password. Enter your username and new password to login.'
            ];
             $this->session->set_flashdata('message', $message);
            redirect('user/login');
		} else {
			$message = [
        		'type' => 'danger',
        		'message' => validation_errors()	
        	];
        	$this->session->set_flashdata('message', $message);
            redirect('user/reset_password/'.$user_id);
		}
	}
}