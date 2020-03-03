<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Designation extends CI_Controller {

	public function add()
	{	
		$pageTitle = "Add Designation";
		$message = $this->session->flashdata('message');
		$this->load->library('form_validation');
		$this->load->view('add_designation_view',['pageTitle'=> $pageTitle, 'message'=> $message]);
	}

	public function add_validation()
	{	
		
		$this->load->library('form_validation');

		$this->form_validation->set_rules('date_assigned', 'Date_assigned', 'required');
		$this->form_validation->set_rules('driver_id', 'Driver_id', 'required');
		$this->form_validation->set_rules('plate_no', 'Plate_no', 'required');

		$this->session->mark_as_flash('message');

		if ($this->form_validation->run() == FALSE)
                {
                	//set error
                	$message = [
                		'type' => 'danger',
                		'message' => validation_errors()	
                	];
                	$this->session->set_flashdata('message', $message);
                     redirect('designation/add');
                	
                }
                else
                {      
                	//set a success msg        
					if ($this->designation_model->add()) {
						$message = [
                		'type' => 'success',
                		'message' => 'Successfully Added Designation!'
		                ];
		                $this->session->set_flashdata('message', $message);
						redirect('designation/add');
					}
					else
					{
						//set error
						redirect('designation/add');
					}
                }



	}
}