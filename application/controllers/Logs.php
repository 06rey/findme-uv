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
		//echo "<pre>";print_r(var_dump($all_logs));die;


		$this->load->view('get_all_logs_view',[
			'pageTitle'=>$pageTitle,
			'all_logs'=> $all_logs
		]);
	}
}



