<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UV_unit extends CI_Controller {

	public function add()
	{	
		if (!$this->user_model->is_logged_in() ) {
			redirect('user/login');
		}elseif ($this->session->userdata('role') == ('clerk')) {
			redirect('booking/all');
		}
		
		$pageTitle = "Add UV Unit";
		$message = $this->session->flashdata('message');
		$this->load->library('form_validation');
		$this->load->view('add_uv_unit_view',['pageTitle'=> $pageTitle, 'message'=> $message]);
	}

	public function add_validation()
	{	
		
		$this->load->library('form_validation');
		$this->load->library('logger');

		$this->form_validation->set_rules('plate_no', 'Plate_no',
        'required|is_unique[uv_unit.plate_no]',
        array(
                'required'      => 'You have not provided %s.',
                'is_unique'     => 'This %s already exists.'
        	)
		);
		$this->form_validation->set_rules('max_pass', 'Max_pass', 'required');
		$this->form_validation->set_rules('franchise_no', 'Franchise_no',
        'required|is_unique[uv_unit.franchise_no]',
        array(
                'required'      => 'You have not provided %s.',
                'is_unique'     => 'This %s already exists.'
        	)
		);
		$this->form_validation->set_rules('model', 'Model', 'required');
		$this->form_validation->set_rules('brand_name', 'Brand_name', 'required');

		$this->session->mark_as_flash('message');

		if ($this->form_validation->run() == FALSE)
                {
                	//set error
                	$message = [
                		'type' => 'danger',
                		'message' => validation_errors()	
                	];
                	$this->session->set_flashdata('message', $message);
                     redirect('uv_unit/add');
                	
                }
                else
                {      
                	//set a success msg        
					if ($this->uv_unit_model->add()) {
						$message = [
                		'type' => 'success',
                		'message' => 'Successfully Added UV Unit!'
		                ];
		                $this->session->set_flashdata('message', $message);

		                //   Activity Logger

		                $this->log_model->log('Added new uv express van');
						     
						redirect('uv_unit/all');
					}
					else
					{
						//set error
						redirect('uv_unit/add');
					}
                }

                
	}

	public function all()
	{
		if (!$this->user_model->is_logged_in() ) {
			redirect('user/login');
		}elseif ($this->session->userdata('role') == ('clerk')) {
			redirect('trip_management/all');
		}

		$pageTitle = "Manage UV's";
		$message = $this->session->flashdata('message');
		$all_uv = $this->uv_unit_model->get_all(); 
		$this->load->view('get_all_uv_view',[
			'pageTitle'=>$pageTitle,
			'all_uv'=> $all_uv,
			'message'=> $message
		]);
	}

	public function update_uv_view($id = "") {
		$message = $this->session->flashdata('message');
		$uv = $this->uv_unit_model->get_uv($id); 
		$pageTitle = "Update Uv Unit";
		$this->load->view('update_uv_unit_view',[
			'pageTitle'=>$pageTitle,
			'uv'=> $uv,
			'message'=> $message
		]);
	}

	public function update_uv($id = "") {

		$this->uv_unit_model->updateUvUnit($id);

		$message = [
                		'type' => 'success',
                		'message' => 'Successfully Updated Uv Express Info!'
		                ];
		                $this->session->set_flashdata('message', $message);

		                //LOGGER

		               $this->log_model->log('Updated uv express van');

						redirect('uv_unit/all');
	}
}



