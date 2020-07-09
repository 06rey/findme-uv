<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking extends CI_Controller {

	public function get_seat() {
		echo json_encode($this->booking_model->get_allocated_seat(191, 572));
	}

	public function insert_seat($seat_no, $queue_id, $trip_id) {
		echo json_encode($this->booking_model->insert_seat($seat_no, $queue_id, $trip_id));
	}

	public function delete_seat($seat_no, $queue_id) {
		$this->booking_model->delete_seat($seat_no, $queue_id);
	}

	public function insert_queue($no_of_pass, $trip_id) {
		echo json_encode($this->booking_model->insert_booking_queue($no_of_pass, $trip_id));
	}

	public function delete_book($queue_id = "") {
		$this->booking_model->delete_book($queue_id);
	}

	public function save_booking() {
		$this->booking_model->save_booking();
	}
}
