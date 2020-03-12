<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function register()
	{	
		$pageTitle = "Add User";
		$message = $this->session->flashdata('message');
		$this->load->library('form_validation');
		$this->load->view('add_user_view',['pageTitle'=> $pageTitle, 'message'=> $message]);
	}

	public function register_validation()
	{	
		
		$this->load->library('form_validation');

		$this->form_validation->set_rules(
        'username', 'Username',
        'required|min_length[5]|max_length[15]|is_unique[user.username]',
        array(
                'required'      => 'You have not provided %s.',
                'is_unique'     => 'This %s already exists.'
        	)
		);

		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('cpassword', 'Password Confirmation', 'required|matches[password]');
		$this->form_validation->set_rules('status', 'Status', 'required');
		$this->form_validation->set_rules('role', 'Role', 'required');
		$this->form_validation->set_rules('user_id', 'user_id', 'required');


		$this->session->mark_as_flash('message');

		if ($this->form_validation->run() == FALSE)
                {
                	//set error
                	$message = [
                		'type' => 'danger',
                		'message' => validation_errors()	
                	];
                	$this->session->set_flashdata('message', $message);
                     redirect('user/register');
                	
                }
                else
                {      
                	//set a success msg        
					if ($this->user_model->register()) {
						$message = [
                		'type' => 'success',
                		'message' => 'Successfully Added User!'
		                ];
		                $this->session->set_flashdata('message', $message);



		                //   Activity Logger

		                $this->logger
						     ->user($this->session->userdata('username')) //Set Username, who created this  Action
						     ->role($this->session->userdata('role')) //Set User role, who created this  Action
						     ->type('Added user') //Entry type like, Post, Page, Entry
						     ->id(1) //Entry ID
						     ->token('Add') //Token identify Action
						     ->log(); //Add Database Entry

						redirect('user/register');
					}
					else
					{
						//set error
						redirect('user/register');
					}
                }



	}


	public 	function login() {
		
		$pageTitle = "Login Page";
		$message = $this->session->flashdata('message');
		$this->load->library('form_validation');

		$this->load->view('login_page_view',[
			'pageTitle'=>$pageTitle,
			'message'=> $message
		]);
	}
	
	// Validate login
	public 	function login_validation() {	

		$this->session->mark_as_flash('message');

		$login = $this->user_model->login();
		if ($login == 'success') {

			$this->log_model->log('Logged in to the system');

			switch ($this->session->userdata('role')) {
				case 'owner':
					redirect('dashboard');
					break;
				case 'admin':
					redirect('dashboard');
					break;
				case 'clerk':
					redirect('trip_management/all');
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

	public function logout() {
		$this->log_model->log('Logged out from the system');
		$this->user_model->logout();
			redirect('user/login');
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