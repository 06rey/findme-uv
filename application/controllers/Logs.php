<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logs extends CI_Controller {

	public function all()
	{
		if (!$this->user_model->is_logged_in() ) {
			redirect('user/login');
		}elseif ($this->session->userdata('role') == ('clerk')) {
			redirect('booking/all');
		}

		$pageTitle = "Activity Logs";
		$all_logs = $this->log_model->get_log();


		$this->load->view('get_all_logs_view',[
			'pageTitle'=>$pageTitle,
			'all_logs'=> $all_logs
		]);
	}

	public function get_over_speed() {
		if (!$this->user_model->is_logged_in() ) {
			redirect('user/login');
		}elseif ($this->session->userdata('role') == ('clerk')) {
			redirect('booking/all');
		}

		$pageTitle = "Drigver's Over Speed Logs";
		$all_logs = $this->log_model->get_all_over_speed();

		//echo "<pre>";
		//print_r($all_logs);die();

		$this->load->view('over_speed_log',[
			'pageTitle'=>$pageTitle,
			'all_logs'=> $all_logs
		]);
	}

	public function get_accident() {
		if (!$this->user_model->is_logged_in() ) {
			redirect('user/login');
		}elseif ($this->session->userdata('role') == ('clerk')) {
			redirect('booking/all');
		}

		$pageTitle = "UV Express Acccident Logs";
		$all_logs = $this->log_model->get_all_accident();

		$this->load->view('accident_log',[
			'pageTitle'=>$pageTitle,
			'all_logs'=> $all_logs
		]);
	}

	public function contact_list() {
		if (!$this->user_model->is_logged_in() ) {
			redirect('user/login');
		}elseif ($this->session->userdata('role') == ('clerk')) {
			redirect('booking/all');
		}

		$pageTitle = "Trip Acccident Logs";
		$all_contact = $this->log_model->get_all_alert_contact();

		//echo "<pre>";
		//print_r($all_contact);die();
		$message = $this->session->flashdata('message');
		$this->load->view('accident_contact_list',[
			'pageTitle'=>$pageTitle,
			'message' => $message,
			'all_contact'=> $all_contact
		]);
	}

	public function update_contact($id) {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('contact_name', 'Contact Name', 'required');
		$this->form_validation->set_rules('contact_no', 'Contact Number', 'required');
		if ($this->form_validation->run() == FALSE){
            $message = [
                'type' => 'danger',
                'message' => validation_errors()	
            ];
            $this->session->set_flashdata('message', $message);
        } else {
        	$this->log_model->update_contact($id);
        	$this->log_model->log("Updated accident alert contact");
        	$message = [
        		'type' => 'success',
        		'message' => 'Successfully Update Accident Contact!'
                ];
            $this->session->set_flashdata('message', $message);
        }
        redirect('logs/contact_list');
	}

	public function add_contact() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('contact_name', 'Contact Name', 'required');
		$this->form_validation->set_rules('contact_no', 'Contact Number', 'required');
		if ($this->form_validation->run() == FALSE){
            $message = [
                'type' => 'danger',
                'message' => validation_errors()	
            ];
            $this->session->set_flashdata('message', $message);
        } else {
        	$this->log_model->add_contact();
        	$this->log_model->log("Added accident alert contact");
        	$message = [
        		'type' => 'success',
        		'message' => 'Successfully Added Accident Contact!!'
                ];
            $this->session->set_flashdata('message', $message);
        }
        redirect('logs/contact_list');
	}
	
	public function remove_contact($id) {
		$this->log_model->remove_contact($id);
		$this->log_model->log("Deleted accident alert contact");
       	$message = [
        		'type' => 'success',
        		'message' => 'Successfully Remove Accident Alert Contact!!'
                ];
        $this->session->set_flashdata('message', $message);
        redirect('logs/contact_list');
	}

	public function get_feedback() {
		if (!$this->user_model->is_logged_in() ) {
			redirect('user/login');
		}elseif ($this->session->userdata('role') == ('clerk')) {
			redirect('booking/all');
		}

		$pageTitle = "Feedback";
		$all_feedback = $this->log_model->get_all_feedback();

		//echo "<pre>";
		//print_r($all_feedback);die();

		$message = $this->session->flashdata('message');
		$this->load->view('feedback',[
			'pageTitle'=>$pageTitle,
			'message' => $message,
			'all_feedback'=> $all_feedback
		]);
	}

	public function delete_feedback($id) {
		$this->log_model->delete_feedback($id);
		$this->log_model->log("Deleted passenger's feedback");
       	$message = [
        		'type' => 'success',
        		'message' => 'Successfully deleted feedback!'
                ];
        $this->session->set_flashdata('message', $message);
        redirect('logs/get_feedback');
	}

}



