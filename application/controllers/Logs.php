<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logs extends CI_Controller {

	private function checkUser(){
		if (!$this->user_model->is_logged_in() ) {
			redirect('user/login');
		}elseif ($this->session->userdata('role') == ('clerk')) {
			redirect('booking');
		}
	}

	/*
	|--------------------------------------------------------------------------
	| OVER SPEED
	|--------------------------------------------------------------------------
	*/

	// View
	public function over_speed() {
		$this->checkUser();
		$this->load->view('over_speed_log',[
			'pageTitle'=>"Driver Over Speed",
		]);
	}

	public function overSpeedLogs(){
		echo json_encode([
			'data' => $this->log_model->get_all_over_speed()
		]);
	}
	/*
	|--------------------------------------------------------------------------
	| USER ACTIVITY
	|--------------------------------------------------------------------------
	*/

	// View
	public function userActivity(){
		$this->checkUser();
		$this->load->view('user_activity',[
			'pageTitle'=>"User Activity"
		]);
	}

	public function getAllLogs(){
		echo json_encode([
			'data' => $this->log_model->get_log()
		]);
	}

	public function accountActivity(){
		$data = $this->log_model->fetchAccountActivity();
		$lastId = 0;
		if ($data){
			$lastId = $data[count($data) - 1][count($data[count($data) - 1]) - 1]->id;
		}
		echo json_encode([
			'lastId' => $lastId,
			'count' => $this->log_model->countActivity($lastId),
			'data' => $data
		]);
	}
	
	/*
	|--------------------------------------------------------------------------
	| ACCIDENT LOG
	|--------------------------------------------------------------------------
	*/

	public function allAccident() {
		$this->checkUser();
		$this->load->view('accident_log',[
			'pageTitle'=>"Acccident Logs",
			'all_logs'=> $this->log_model->get_all_accident(),
			'route' => $this->log_model->getRoute()
		]);
	}

	public function allContact() {
		echo json_encode([
			'data' => $this->log_model->get_all_alert_contact()
		]);
	}

	public function saveContact(){
		
		echo json_encode([
			'data' => $this->log_model->saveEmergencyContact() > 0 ? true : false
		]);
	}

	public function contactStatus(){
		echo json_encode([
			'data' => $this->log_model->setContactStatus() > 0 ? true : false
		]);
	}

	public function updateContact(){
		echo json_encode([
			'data' => $this->log_model->updateContact() > 0 ? true : false
		]);
	}

	public function deleteContact(){
		echo json_encode([
			'data' => $this->log_model->deleteContact() > 0 ? true : false
		]);
	}


	/*
	|--------------------------------------------------------------------------
	| FEEDBACK
	|--------------------------------------------------------------------------
	*/

	public function get_feedback($limit) {
		$this->checkUser();
		$data = $this->log_model->get_all_feedback($limit);
		$last_id = 0;
		if ($data){
			$last_id = $data[count($data) - 1]->feedback_id;
		}

		$this->load->view('feedback',[
			'pageTitle'=>'Feedback',
			'last_id' => $last_id,
			'count' => $this->log_model->count_feedback($last_id, ''),
			'all_feedback'=> $data
		]);
	}

	public function loadMoreFeedBack(){
		$data = $this->log_model->loadMoreFeedBack();
		$last_id = 0;
		if (COUNT($data) > 0){
			$last_id = $data[count($data) - 1]->feedback_id;

			foreach ($data as $key => $value) {
				$data[$key]->reply_count = $this->log_model->countFeedbackReply($value->feedback_id);
			}
		}
		echo json_encode([
			'status' => COUNT($data) > 0 ? true : false,
			'data' => $data,
			'last_id' => $last_id,
			'count' => $this->log_model->count_feedback($last_id, $_POST['filter'])
		]);
	}

	public function syncFeedback(){
		$data = $this->log_model->fetchLatestFeedback();
		if (COUNT($data) > 0){
			foreach ($data as $key => $value) {
				$data[$key]->reply_count = $this->log_model->countFeedbackReply($value->feedback_id);
			}
		}
		echo json_encode([
			'status' => count($data) > 0 ? true : false,
			'data' => $data
		]);
	}

	public function getFeedback(){
		$data = $this->log_model->fetchLatestFeedback($_POST['feedback_id'], $_POST['filter']);
		if (COUNT($data) > 0){
			$last_id = $data[count($data) - 1]->feedback_id;

			foreach ($data as $key => $value) {
				$data[$key]->reply_count = $this->log_model->countFeedbackReply($value->feedback_id);
			}
		}
		echo json_encode([
			'status' => COUNT($data) > 0 ? true : false,
			'data' => $data,
			'count' => $this->log_model->count_feedback($_POST['feedback_id'], $_POST['filter'])
		]);
	}

	public function feedbackReply(){
		$data = $this->log_model->fetchReply($_POST['feedback_id']);
		if (count($data) > 0){
			foreach ($data as $key => $value) {
				$sender = $this->log_model->fetchSender($value->sender_type, $value->sender_id);
				$data[$key]->sender = $sender->name;
				if (isset($sender->img_url)){
					$data[$key]->img_url = $sender->img_url;
				}else{
					$data[$key]->img_url = '';
				}
			}
		}
		echo json_encode([
			'data' => $data
		]);
	}

	public function syncReply(){
		if ($_POST['idLen'] > 0){
			$ids = json_decode($_POST['replyIds']);
			$notIn = '(0';
			foreach ($ids as $key => $value) {
				$notIn .= ','.$value;
			}
			$notIn .= ')';
			$data = $this->log_model->fetchLatestReply($notIn);
			echo json_encode([
				'status' => count($data) > 0 ? true : false,
				'type' => 'data',
				'data' => $data
			]);
		}else{
			echo json_encode([
				'type' => 'count',
				'data' => $this->log_model->countFeedbackReply($_POST['id'])
			]);
		}
	}

	public function sendReply(){
		$data = $this->log_model->saveReply();
		$data[0]->{'sender'} = $data[0]->f_name.' '.$data[0]->l_name;
		echo json_encode([
			'data' => $data
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