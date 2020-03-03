<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking extends CI_Controller {

	//----------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------
	//--------------------------------------- BOOKING PAGES ----------------------------------
	//----------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------

	public function all(){
		if (!$this->user_model->is_logged_in() ) {
			redirect('user/login');
		}

		$pageTitle = "Bookings";
		$message = $this->session->flashdata('message');

		$all_trip = $this->booking_model->get_pending_trip();

		foreach ($all_trip as $key => $value) {
			$all_trip[$key]->{'count'} = $this->booking_model->get_trip_count($value->route_id);
		}

		$this->load->view('booking_view',[
			'pageTitle'=>$pageTitle,
			'all_trip'=> $all_trip,
			'message'=> $message
		]);
	}

	public function route_trip($route_name = '', $route_id = '') {
		if (!$this->user_model->is_logged_in() ) {
			redirect('user/login');
		}

		$this->session->set_userdata('url', "booking/route_trip/$route_name/$route_id");

		$pageTitle = strtoupper($route_name);
		$message = $this->session->flashdata('message');
		$trip_list = $this->booking_model->get_trip_sched($route_id); 
		$this->load->view('get_all_bookings_view',[
			'pageTitle'=>$pageTitle,
			'trip_list'=> $trip_list,
			'message'=> $message,
			'route_name' => $route_name
		]);
	}

	public function passenger_list($route_name = '', $trip_id = "") {
		if (!$this->user_model->is_logged_in() ) {
			redirect('user/login');
		}

		$pageTitle = strtoupper($route_name).' | UVTRIP-'.$trip_id;
		$message = $this->session->flashdata('message');
		$passenger_list = $this->booking_model->get_trip_passenger($trip_id);
		$trip = $this->booking_model->get_trip_info($trip_id);
		$this->load->view('passenger_list_view',[
			'pageTitle'=>$pageTitle,
			'passenger_list'=> $passenger_list,
			'trip' => $trip,
			'message'=> $message
		]);
	}

	public function removePassenger($route_name = "", $trip_id = "", $booking_id = "", $seat_id = "") {
		if ($this->booking_model->removeReservedSeat($booking_id, $seat_id) > 0) {
			$message = [
                'type' => 'success',
                'message' => 'Successfully cancelled seat reservation.'	
            ];
		} else {
			$message = [
                'type' => 'danger',
                'message' => 'Failed to cancel seat reservation.'	
            ];
		}
		$this->log_model->log('Remove reserved seat.');
		$this->session->set_flashdata('message', $message);
		redirect("booking/passenger_list/$route_name/$trip_id");
	}

	//----------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------
	//------------------------------- BOOKING SEAT FUNCTIONS ---------------------------------
	//----------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------

	public function count_available_seat($trip_id = "") {
		echo json_encode(['available'=>$this->booking_model->get_available_seat($trip_id)]);
	}

	public function get_seat($trip_id, $queue_id) {
		echo json_encode($this->booking_model->get_allocated_seat($trip_id, $queue_id));
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
		$this->log_model->log('Added seat reservation');
	}
} 
