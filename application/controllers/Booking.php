<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking extends CI_Controller {

	private function checkUser(){
		if (!$this->user_model->is_logged_in() ) {
			redirect('user/login');
		}
	}

	public function cancelReservation(){
		echo json_encode([
			'data' => $this->booking_model->cancelReservation() > 0 ? true : false
		]);
	}

	/*
	|--------------------------------------------------------------------------
	| VIEW
	|--------------------------------------------------------------------------
	*/

	public function index(){
		$this->checkUser();
		$result = $this->trip_management_model->update_trip_record();
		if (count($result) > 0) {
			foreach ($result as $key => $value) {
				$this->trip_management_model->set_trip_status('Cancelled', $value->trip_id);
				$this->log_model->log('Due trip schedule. Auto cancelled by the system');
			}
		}
		$this->load->view('booking_view',[
			'pageTitle'=>'Booking',
			'routes'=> $this->trip_management_model->fetchRoute()
		]);
	}

	public function trip($routeId, $date) {
		$this->checkUser();
		$this->load->view('booking_trip', [
			'pageTitle' => "Booking Trip",
			'data' => $this->trip_management_model->routeInfo($routeId)[0],
			'date' => $date
		]);
	}

	/*
	|--------------------------------------------------------------------------
	| BOOKING
	|--------------------------------------------------------------------------
	*/

	# 1
	public function insert_queue($no_of_pass, $trip_id) {
		echo json_encode([
			'data' => $this->booking_model->insert_booking_queue($no_of_pass, $trip_id)
		]);
	}

	public function count_available_seat($trip_id = "") {
		echo json_encode([
			'data' => $this->booking_model->get_available_seat($trip_id)
		]);
	}

	public function get_seat($trip_id, $queue_id) {
		$res = $this->booking_model->get_allocated_seat($trip_id, $queue_id);
		echo json_encode([
			'status' => COUNT($res) > 0,
			'data' => $res
		]);
	}

	public function insert_seat($seat_no, $queue_id, $trip_id) {
		echo json_encode([
			'data' => $this->booking_model->insert_seat($seat_no, $queue_id, $trip_id)
		]);
	}

	public function delete_seat($seat_no, $queue_id) {
		echo json_encode([
			'data' => $this->booking_model->delete_seat($seat_no, $queue_id) > 0 ? true : false
		]);
	}

	public function delete_book($queue_id = "") {
		$this->booking_model->delete_book($queue_id);
	}

	public function save_booking() {
		echo json_encode([
			'data' => $this->booking_model->save_booking() > 0 ? true : false
		]);
		
	}
} 
