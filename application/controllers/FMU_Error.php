<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */
class FMU_Error extends CI_Controller
{
	
	public function index(){
		if (!$this->user_model->is_logged_in() ) {
			redirect('user/login');
		}elseif ($this->session->userdata('role') == ('clerk')) {
			redirect('booking');
		}
		$this->load->view('error_404', ['pageTitle' => 'File not found']); 
	}
	
}