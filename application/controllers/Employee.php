<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee extends CI_Controller {

	/*
  |--------------------------------------------------------------------------
  | VIEW
  |--------------------------------------------------------------------------
  */
	public function view($param){
		$this->checkUser();
		$this->load->view('employee_view',[
			'view' => $param,
			'pageTitle'=>"Employee"
		]);
	}

	/*
  |--------------------------------------------------------------------------
  | ADD/INSERT
  |--------------------------------------------------------------------------
  */
  public function insertEmployee(){
  	$insertId = $this->employee_model->insertEmployee();
  	if ($insertId > 0){
 			$this->log_model->log([
				'activity' => 'Added '.$_POST['f_name'].' '.$_POST['l_name'].' as '.strtolower($_POST['role']).'.',
				'data' 		 => $this->log_model->getEmployee($insertId),
				'table'		 => 'employee',
				'ref_id'   => $insertId
			]);
 		}
		echo json_encode([
			'data' => $insertId > 0 ? true : false
		]);
	}

	/*
  |--------------------------------------------------------------------------
  | GET/FETCH
  |--------------------------------------------------------------------------
  */
	public function fetchAllDriver(){
		echo json_encode([
			'data' => $this->employee_model->getAllDriver()
		]);
	}

	public function fetchAllClerk(){
		echo json_encode([
			'data' => $this->employee_model->getAllClerk()
		]);
	}


	/*
  |--------------------------------------------------------------------------
  | UPDATE
  |--------------------------------------------------------------------------
  */
	public function updateStatus(){
		$_POST['status'] = ($_POST['status'] == 0) ? 1 : 0;
		if ($_POST['status'] == 0){
			if ( count( $this->employee_model->getDriverSched($_POST['employee_id']) ) > 0 ){
				echo json_encode([
					'status'=> false
				]);
				die();
			}
		}
		$affected_rows = $this->employee_model->updateStatus();
		if ($affected_rows > 0){
			$stat = $_POST['status'] == 1 ? 'Activate':'Deactivate';
 			$this->log_model->log([
				'activity' => $stat.' '.$_POST['f_name'].' '.$_POST['l_name'].'`s account.',
				'data' 		 => $this->log_model->getEmployee($_POST['employee_id']),
				'table'		 => 'employee',
				'ref_id'   => $_POST['employee_id']
			]);
 		}
		echo json_encode([
			'status'=> true,
			'data' => $affected_rows > 0 ? true : false
		]);
	}

	public function updateEmployee(){
		$affected_rows = $this->employee_model->updateEmployee();
		if ($affected_rows > 0){
 			$this->log_model->log([
				'activity' => 'Update '.$_POST['f_name'].' '.$_POST['l_name'].'`s information.',
				'data' 		 => $this->log_model->getEmployee($_POST['employee_id']),
				'table'		 => 'employee',
				'ref_id'   => $_POST['employee_id']
			]);
 		}
		echo json_encode([
			'data' => $affected_rows > 0 ? true : false
		]);
	}


	/*
  |--------------------------------------------------------------------------
  | MISC
  |--------------------------------------------------------------------------
  */
 	private function checkUser(){
		if (!$this->user_model->is_logged_in() ) {
			redirect('user/login');
		}elseif ($this->session->userdata('role') == ('clerk')) {
			redirect('booking/all');
		}
	}

	public function checkDuplicate(){
		echo json_encode([
			'data' => $this->employee_model->checkDuplicate()
		]);
	}

	public function checkMobileNumber(){
		echo json_encode([
			'data' => $this->employee_model->checkMobileNumber()
		]);
	}

}