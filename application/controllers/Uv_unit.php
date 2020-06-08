<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UV_unit extends CI_Controller {

	public function index(){
		$this->checkUser();
		$this->load->view('get_all_uv_view',[
			'pageTitle' => "UV Express"
		]);
	}

 	public function insertUvExpress(){
 		$insertId = $this->uv_unit_model->insertUvExpress();
 		if ($insertId > 0){
 			$this->log_model->log([
				'activity' => 'Added new UV Express with plate number '.$_POST['plate_no'].'.',
				'data' 		 => $this->log_model->getUvExpress($insertId),
				'table'		 => 'uv_unit',
				'ref_id'   => $insertId
			]);
 		}
 		echo json_encode([
			'data' => $insertId > 0 ? true : false
		]);
 	}

 	public function updateUvExpress($uvId){
 		$affected_rows = $this->uv_unit_model->updateUvExpress($uvId);
 		if ($affected_rows > 0){
 			$this->log_model->log([
				'activity' => 'Update UV Express with plate number '.$_POST['plate_no'].'.',
				'data' 		 => $this->log_model->getUvExpress($uvId),
				'table'		 => 'uv_unit',
				'ref_id'   => $uvId
			]);
 		}
 		echo json_encode([
			'data' => $affected_rows > 0 ? true : false
		]);
 	}

	public function fetchUvExpress(){
		echo json_encode([
			'data' => $this->uv_unit_model->get_all()
		]);
	}

	// Private methods
 	private function checkUser(){
 		if (!$this->user_model->is_logged_in() ) {
			redirect('user/login');
		}elseif ($this->session->userdata('role') == ('clerk')) {
			redirect('booking/all');
		}
 	}

}
