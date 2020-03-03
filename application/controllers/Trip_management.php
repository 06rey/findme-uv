<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trip_management extends CI_Controller {

	public function add()
	{
		if (!$this->user_model->is_logged_in() ) {
			redirect('user/login');
		} elseif ($this->session->userdata('role') == ('clerk')) {
			redirect('booking/all');
		}
			
		$pageTitle = "Add Trip";
		$message = $this->session->flashdata('message');
		$route = $this->trip_management_model->get_route();
		$uv_unit = $this->trip_management_model->get_plate_no();
		$driver = $this->trip_management_model->get_driver(); 
		$this->load->library('form_validation');
		$this->load->view('add_trip_view',[
			'pageTitle'=> $pageTitle, 
			'message'=> $message,
			'route'=> $route,
			'uv_unit'=> $uv_unit,
			'driver'=> $driver
		]);
	}

	public function update_trip($trip_id = "") {
		$pageTitle = "Update Trip";
		$message = $this->session->flashdata('message');

		$trip = $this->trip_management_model->get_trip($trip_id);

		$uv_unit = $this->trip_management_model->get_plate_no();
		$driver = $this->trip_management_model->get_driver();

		$this->load->library('form_validation');
		$this->load->view('update_trip_view',[
			'pageTitle'=> $pageTitle, 
			'message'=> $message,
			'trip'=> $trip,
			'uv_unit'=> $uv_unit,
			'driver'=> $driver
		]);
	}

	public function save_trip($trip_id = "") {
		$stat = $this->trip_management_model->save_trip($trip_id);
		if ($stat == 'success') {
			$message = [
                'type' => 'success',
                'message' => 'Successfully update Trip Schedule!'
		   	];
		    $this->session->set_flashdata('message', $message);

		    //   Activity Logger

		    $this->log_model->log('Update trip schedule');

			redirect('trip_management/all');
		} else if  ($stat == 'invalid date') {
			$message = [
                'type' => 'danger',
                'message' => 'Departure date is not valid!'
		   	];
		   	$this->session->set_flashdata('message', $message);
			redirect('trip_management/update_trip/'.$trip_id);
		} else {
			$message = [
                'type' => 'danger',
                'message' => 'Failed to save trip schedule changes!'
		   	];
		   	$this->session->set_flashdata('message', $message);
			redirect('trip_management/update_trip/'.$trip_id);
		}
	}

	public function trip_status($status = "", $trip_id = "") {
		if ($this->trip_management_model->set_trip_status($status, $trip_id) > 0) {
			$message = [
                'type' => 'success',
                'message' => 'Successfully update Trip Schedule!'
		   	];
		    $this->session->set_flashdata('message', $message);
		    //   Activity Logger
		    $this->log_model->log($status.' trip schedule');

			redirect('trip_management/all');
		} else {
			$message = [
                'type' => 'danger',
                'message' => 'Failed to cancel trip schedule!'
		   	];
		   	$this->session->set_flashdata('message', $message);
			redirect('trip_management/update_trip/'.$trip_id);
		}
	}

	public function add_validation()
	{	
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('route_id', 'Route Name', 'required');
		$this->form_validation->set_rules('date', 'Date', 'required');

		$this->session->mark_as_flash('message');

		if ($this->form_validation->run() == FALSE)
                {
                	//set error
                	$message = [
                		'type' => 'danger',
                		'message' => validation_errors()	
                	];
                	$this->session->set_flashdata('message', $message);
                     redirect('trip_management/add');
                	
                }
                else
                {      
                	//set a success msg        
					if ($this->trip_management_model->add()) {
						$message = [
                		'type' => 'success',
                		'message' => 'Successfully Added Trip!'
		                ];
		                $this->session->set_flashdata('message', $message);

		                //   Activity Logger

		                $this->log_model->log('Added trip schedule');

						redirect('trip_management/all');
					}
					else
					{
						//set error
						$message = [
	                		'type' => 'danger',
	                		'message' => 'Departure time is not valid!'
		                ];
		                $this->session->set_flashdata('message', $message);
						redirect('trip_management/add');
					}
                }
	}


	public function all(){

		if (!$this->user_model->is_logged_in() ) {
			redirect('user/login');
		}
		
		$result = $this->trip_management_model->update_trip_record();
		if (count($result) > 0) {
			foreach ($result as $key => $value) {
				$this->trip_management_model->set_trip_status('Cancelled', $value->trip_id);
				$this->log_model->log('Due trip schedule. Auto cancelled by the system');
			}
		}

		$pageTitle = "Trip Management";
		$message = $this->session->flashdata('message');
		$all_trip = $this->trip_management_model->get_all(); 
		$this->load->view('trip_management_view',[
			'pageTitle'=>$pageTitle,
			'all_trip'=> $all_trip,
			'message'=> $message
		]);
	}

}