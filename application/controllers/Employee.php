<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee extends CI_Controller {


	public $employee = [
		'f_name' => '',
		'm_name' => '',
		'l_name' => '',
		'license_no' => '',
		'contact_no' => '',
		'address' => '',
		'role' => ''
	];

	public function add_driver(){	
		if (!$this->user_model->is_logged_in() ) {
			redirect('user/login');
		}elseif ($this->session->userdata('role') == ('clerk')) {
			redirect('booking/all');
		}
		
		$pageTitle = "Add Driver";
		$message = $this->session->flashdata('message');
		$this->load->library('form_validation');
		$this->load->view('add_driver_view', [
			'pageTitle'=> $pageTitle, 
			'message'=> $message, 
			'employee' => json_decode(json_encode($this->employee))
		]);
	}

	public function add_driver_validation($role = ""){	

		$this->load->library('form_validation');

		$this->form_validation->set_rules('f_name', 'First Name', 'required');
		$this->form_validation->set_rules('m_name', 'Middle Name', 'required');
		$this->form_validation->set_rules('l_name', 'Last Name', 'required');

		$this->form_validation->set_rules(
			'license_no', 
			'License Number',
        	'required|is_unique[employee.license_no]',
        	array(
                'is_unique'     => 'This %s already exists.'
        	)
		);
		$this->form_validation->set_rules(
			'contact_no', 
			'Contact Number',
        	'required|is_unique[employee.contact_no]|regex_match[/^[0-9]{11}$/]',
        	array(
                'required'      => 'You have not provided %s.',
                'is_unique'     => 'This %s already exists.'
        	)
		);
		$this->form_validation->set_rules('address', 'Address', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|max_length[20]');
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');


		$this->session->mark_as_flash('message');

		if ($this->form_validation->run() == FALSE) {
                	//set error
                	$message = [
                		'type' => 'danger',
                		'message' => validation_errors()	
                	];
                	$this->session->set_flashdata('message', $message);

                	$employee = [];
					foreach ($_POST as $key => $value) {
						$employee[$key] = $value;
					}

                    $pageTitle = "Add Driver";
					$message = $this->session->flashdata('message');
					$this->load->library('form_validation');
					$this->load->view('add_driver_view',['pageTitle'=> $pageTitle, 'message'=> $message,  'employee'=>json_decode(json_encode($employee))]);
                }
                else
                {      
                	//set a success msg        
					if ($this->employee_model->register($role)) {
						$message = [
                		'type' => 'success',
                		'message' => 'Successfully Added Driver!'
		                ];
		                $this->session->set_flashdata('message', $message);

		                //   Activity Logger

		               $this->log_model->log('Added new driver');

						redirect('employee/driver');
					}
					else
					{
						//set error
						redirect('employee/add_driver');
					}
                }
	}

	public function add_clerk()
	{	
		if (!$this->user_model->is_logged_in() ) {
			redirect('user/login');
		}elseif ($this->session->userdata('role') == ('clerk')) {
			redirect('trip_management/all');
		}
		
		$pageTitle = "Add Clerk";
		$message = $this->session->flashdata('message');
		$this->load->library('form_validation');
		$this->load->view('add_clerk_view',[
			'pageTitle'=> $pageTitle, 
			'message'=> $message,
			'employee' => json_decode(json_encode($this->employee))
		]);
	}

	public function add_clerk_validation($role = "")
	{	

		
		$this->load->library('form_validation');

		$this->form_validation->set_rules('f_name', 'f_name', 'required');
		$this->form_validation->set_rules('m_name', 'm_name', 'required');
		$this->form_validation->set_rules('l_name', 'l_name', 'required');

		$this->form_validation->set_rules('username', 'username',
        'is_unique[user.username]',
        array(
                'is_unique'     => 'This %s already exists.'
        	)
		);
		$this->form_validation->set_rules(
			'contact_no', 
			'Contact Number',
        	'required|is_unique[employee.contact_no]|regex_match[/^[0-9]{11}$/]',
        	array(
                'required'      => 'You have not provided %s.',
                'is_unique'     => 'This %s already exists.'
        	)
		);
		$this->form_validation->set_rules('address', 'address', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|max_length[20]');
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');


		$this->session->mark_as_flash('message');

		if ($this->form_validation->run() == FALSE)
                {
                	$employee = [];
					foreach ($_POST as $key => $value) {
						$employee[$key] = $value;
					}
                	//set error
                	$message = [
                		'type' => 'danger',
                		'message' => validation_errors()	
                	];
                	$this->session->set_flashdata('message', $message);
                    $pageTitle = "Add Driver";
					$message = $this->session->flashdata('message');
					$this->load->library('form_validation');
					$this->load->view('add_clerk_view',[
						'pageTitle'=> $pageTitle, 
						'message'=> $message,  
						'employee'=>json_decode(json_encode($employee))
					]);
                	
                }
                else
                {      
                	//set a success msg        
					if ($this->employee_model->register($role)) {
						$message = [
                		'type' => 'success',
                		'message' => 'Successfully Added Clerk!'
		                ];
		                $this->session->set_flashdata('message', $message);

		                //   Activity Logger
		                $this->log_model->log('Added new clerk');
		                

						redirect('employee/clerk');
					}
					else
					{
						//set error
						redirect('employee/add_clerk');
					}
                }
	}

	public function driver()
	{
		if (!$this->user_model->is_logged_in() ) {
			redirect('user/login');
		}elseif ($this->session->userdata('role') == ('clerk')) {
			redirect('trip_management/all');
		}

		$pageTitle = "Manage Drivers";
		$message = $this->session->flashdata('message');
		$all_driver = $this->employee_model->get_driver(); 
		$this->load->view('get_all_driver_view',[
			'pageTitle'=>$pageTitle,
			'all_driver'=> $all_driver,
			'message'=> $message
		]);
	}

	public function clerk()
	{
		if (!$this->user_model->is_logged_in() ) {
			redirect('user/login');
		}elseif ($this->session->userdata('role') == ('clerk')) {
			redirect('trip_management/all');
		}

		$pageTitle = "Manage Clerks";
		$message = $this->session->flashdata('message');
		$all_clerk = $this->employee_model->get_clerk(); 
		$this->load->view('get_all_clerk_view',[
			'pageTitle'=>$pageTitle,
			'all_clerk'=> $all_clerk,
			'message'=> $message
		]);
	}

	public function edit_driver($id = "")
	{
		if (!$this->user_model->is_logged_in() ) {
			redirect('user/login');
		}elseif ($this->session->userdata('role') == ('clerk')) {
			redirect('trip_management/all');
		}

		$pageTitle = "Edit Driver Info";
		$message = $this->session->flashdata('message');
		$driver = $this->employee_model->get($id);

		$this->load->view('edit_driver_view',[
			'pageTitle'=>$pageTitle,
			'employee' => json_decode(json_encode($driver))
		]);
	}
	public function edit_driver_validation($id = "", $user_id = "")
	{	  

		$this->load->library('form_validation');

		$this->form_validation->set_rules('f_name', 'First Name', 'required');
		$this->form_validation->set_rules('m_name', 'Middle Name', 'required');
		$this->form_validation->set_rules('l_name', 'Last Name', 'required');

		$this->form_validation->set_rules('address', 'Address', 'required');

		$this->session->mark_as_flash('message');

		if ($this->form_validation->run() == FALSE)
                {
              
						$employee = ['employee_id'=>$id];
						foreach ($_POST as $key => $value) {
							$employee[$key] = $value;
						}
	                	//set error
	                	$message = [
	                		'type' => 'danger',
	                		'message' => validation_errors()	
	                	];
	                	$this->session->set_flashdata('message', $message);
	                    $pageTitle = "Edit Driver Info";
						$message = $this->session->flashdata('message');
						$this->load->library('form_validation');
						$this->load->view('edit_driver_view',[
							'pageTitle'=> $pageTitle, 
							'message'=> $message,  
							'employee'=>json_decode(json_encode($employee))
						]);
                        
                }
                else
                {
                    if ($this->employee_model->edit($id, $user_id)) {
                    	$message = [
                		'type' => 'success',
                		'message' => 'Successfully Updated Driver!'
		                ];
		                $this->session->set_flashdata('message', $message);

		                //   Activity Logger
		               $this->log_model->log('Updated driver record');

						redirect('employee/driver');
					}else{
						$message = [
                		'type' => 'info',
                		'message' => 'No changes made!'
		                ];
		                $this->session->set_flashdata('message', $message);
						redirect('employee/driver'); 
					}

                }		
	}


	public function edit_clerk($id)
	{
		if (!$this->user_model->is_logged_in() ) {
			redirect('user/login');
		}elseif ($this->session->userdata('role') == ('clerk')) {
			redirect('trip_management/all');
		}

		$pageTitle = "Edit Profile";
		$message = $this->session->flashdata('message');
		$clerk = $this->employee_model->get($id);

		$this->load->view('edit_clerk_view',[
			'pageTitle'=>$pageTitle,
			'employee'=> $clerk
		]);
	}
	public function edit_clerk_validation($id = "", $user_id = "")
	{	  

		$this->load->library('form_validation');

		$this->form_validation->set_rules('f_name', 'First Name', 'required');
		$this->form_validation->set_rules('m_name', 'Middle Name', 'required');
		$this->form_validation->set_rules('l_name', 'Last Name', 'required');

		$this->form_validation->set_rules('address', 'Address', 'required');

		$this->session->mark_as_flash('message');

		if ($this->form_validation->run() == FALSE)
                {
                	$message = [
                		'type' => 'danger',
                		'message' => validation_errors()
		                ];
		                $this->session->set_flashdata('message', $message);
						redirect('employee/edit_clerk');
                        
                }
                else
                {
                    if ($this->employee_model->edit($id, $user_id)) {
                    	$message = [
                		'type' => 'success',
                		'message' => 'Successfully Updated Clerk!'
		                ];
		                $this->session->set_flashdata('message', $message);

		                //   Activity Logger

		               $this->log_model->log('Updated clerk record');

						redirect('employee/clerk');
					}else{
						$message = [
                		'type' => 'info',
                		'message' => 'No changes made!'
		                ];
		                $this->session->set_flashdata('message', $message);
						redirect('employee/clerk'); 
					}

                }		
	}






}