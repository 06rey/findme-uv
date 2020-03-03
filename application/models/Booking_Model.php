<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking_Model extends CI_Model {

	public function __construct() {
		parent::__construct();
        $this->load->database();
		date_default_timezone_set('Asia/Manila');
	}

	//----------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------
	//---------------------------------------- GETTERS ---------------------------------------
	//----------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------
	
	public function get_all(){	
		$query = $this->db->get('booking');
		return $query->result();
	}
	
	public function get_pending_trip(){
		return $this->db->query("
				SELECT distinct trip.route_id, route.* 
				FROM trip LEFT JOIN route ON trip.route_id = route.route_id
				WHERE trip.status = 'Pending'
			")->result();
	}

	public function get_trip_count($route_id = ""){
		return $this->db->select('count(*) as count')->from('trip')->where('route_id', $route_id)->where('status', 'Pending')->get()->row()->count;
	}

	public function get_trip_sched($route_id = '') {
		$trip_list = $this->db->select('*')->
					from('trip')->
					where('route_id', $route_id)->
					where('status', 'Pending')->
					get()->
					result();
		foreach ($trip_list as $key => $value) {
			$trip_list[$key]->{'no_of_pass'} = $this->get_trip_booked_seat_count($value->trip_id);
			$trip_list[$key]->date = date_format(date_create($trip_list[$key]->date), "D, M d, Y");
			$trip_list[$key]->depart_time = date("g:i A", strtotime($trip_list[$key]->depart_time));

			if ($trip_list[$key]->driver_id == null) {
				$trip_list[$key]->{'plate_no'} = 'No Assign';
			} else {
				$trip_list[$key]->{'plate_no'} = $this->trip_management_model->get_uv($trip_list[$key]->uv_id, 'plate_no')->plate_no;
			}
		}
		return $trip_list;
	}

	public function get_trip_booked_seat_count($trip_id = ""){
		$this->db->select('count(*) as count');
		$this->db->from('trip');
		$this->db->join('booking', 'trip.trip_id = booking.trip_id');
		$this->db->join('seat', 'booking.booking_id = seat.booking_id');
		$this->db->where('trip.trip_id', $trip_id);
		return $this->db->get()->row()->count;
	}

	public function get_trip_passenger($trip_id = '') {
		return $this->db->select('*')->
					from('booking')->
					join('seat', 'booking.booking_id = seat.booking_id')->
					where('booking.trip_id', $trip_id)->
					get()->
					result();
	}

	public function get_trip_info($trip_id = '') {
		return $this->db->select('*')->
					from('trip')->
					join('route', 'trip.route_id = route.route_id')->
					where('trip.trip_id', $trip_id)->
					get()->
					row();
	}


	//----------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------
	//------------------------------- BOOKING SEAT FUNCTIONS ---------------------------------
	//----------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------

	// Return number of available seat of a trip
	public function get_available_seat($trip_id) {
		$booked = $this->db->select('COUNT(*) as booked')->
					from('trip')->
					join('booking', 'trip.trip_id = booking.trip_id')->
					join('seat', 'booking.booking_id = seat.booking_id')->
					where('trip.trip_id', $trip_id)->
					get()->
					row()->booked;
		$allocated = $this->db->select('SUM(booking_queue.no_of_pass) as allocated')->
					from('trip')->
					join('booking_queue', 'trip.trip_id = booking_queue.trip_id')->
					where('trip.trip_id', $trip_id)->
					get()->
					row()->allocated;
		return $booked + $allocated;
	}

	public function removeReservedSeat($booking_id = "", $seat_id = "") {
		$this->db->delete('seat', array('seat_id' => $seat_id));
		$count = $this->db->select('count(*) as count')->
							from('seat')->
							where('booking_id', $booking_id)->
							get()->row()->count;
		if ($count < 1) {
			$this->db->delete('booking', array('booking_id' => $booking_id));
		}
		return $count;//$this->db->affected_rows();
	}

	public	function insert_booking_queue($no_of_pass, $trip_id) {
			$sql = "INSERT INTO booking_queue";
			$data = array(
						"queue_id" => "", 
						"no_of_pass" => $no_of_pass, 
						"status" => "Terminal", 
						"time_stamp" => date("Y-m-d H:i:s"),
						"trip_id" => $trip_id
					);
			$this->db->insert("booking_queue", $data);
			return array("queue_id" => $this->db->insert_id());
		}

	public function get_allocated_seat($trip_id = "", $queue_id = "") {
			$res = $this->db->query("
				SELECT seat.seat_no FROM trip 
				INNER JOIN booking ON trip.trip_id = booking.trip_id
				INNER JOIN seat ON booking.booking_id = seat.booking_id
				WHERE trip.trip_id = ".$trip_id
			)->result_array();

			$res2 = $this->db->query("
				SELECT selected_seat.selected_seat_no FROM selected_seat
					INNER JOIN booking_queue ON selected_seat.queue_id = booking_queue.queue_id
					INNER JOIN trip ON booking_queue.trip_id = trip.trip_id
					WHERE trip.trip_id = ".$trip_id." 
					AND booking_queue.status != 'expired' 
					AND booking_queue.queue_id != ".$queue_id
			)->result_array();
			$temp = 0;
			$a = 1;
			if (COUNT($res) > 0) {
				foreach ($res as $key => $value) {
					$result[0]["seat".$a] = $value["seat_no"];
					$a++;
				}
			} else {
				$temp++;
			}
			if (COUNT($res2) > 0) {
				foreach ($res2 as $key => $val) {
					$result[0]["seat".$a] = $val["selected_seat_no"];	
					$a++;
				}
			} else {
				$temp++;
			}
			if ($temp > 1) {
				$result = array("message"=>"no result");
			}
			return $result;
		}

	public	function delete_seat($seat_no, $queue_id) {
		$sql = "DELETE FROM selected_seat WHERE selected_seat_no = ".$seat_no." AND queue_id = ".$queue_id;
		$this->db->query($sql);
	}

	public	function insert_seat($seat_no, $queue_id, $trip_id) {
			$seat = $this->get_allocated_seat($trip_id, 0);
			$available = true;
			if (!isset($seat["message"])) {
				foreach ($seat as $key => $value) {
					foreach ($value as $val) {
						if ($seat_no == $val) {
							$available = false;
							break;
						}
					}
				}
			}
			if ($available) {
				$sql = "INSERT INTO selected_seat VALUES(select_id, ".$seat_no.", ".$queue_id.")";
				$this->db->query($sql);
				return array("available"=>"true");
			} else {
				return array("available"=>"false");
			}
		}

	public	function delete_book($queue_id = "") {
			$sql = "DELETE FROM selected_seat WHERE queue_id = ".$queue_id;
			$this->db->query($sql);
			$sql = "DELETE FROM booking_queue WHERE queue_id = ".$queue_id;
			$this->db->query($sql);
		}

	public	function save_booking() {
		// sAVE BOOKING
		$trip_id = $this->input->post("trip_id", true);
		$data = array(
				"booking_id" => "",
				"no_of_passenger" => $this->input->post("no_of_pass", true),
				"amount" => $this->input->post("amount", true),
				"pass_type" => "Terminal",
				"time_stamp" => date("Y-m-d H:i:s"),
				"notes" => "",
				"trip_id" => $trip_id,
				"passenger_id" => null
			);
		$this->db->insert("booking", $data);
		$id = $this->db->insert_id();

		// SAVE SEAT
		 for ($i=0; $i<$this->input->post("no_of_pass", true); $i++) { 
		 	$seat_no = $this->input->post("seat".$i, true);
			$data = array(
				"seat_id" => "",
				"boarding_pass" => "UV$trip_id-$seat_no",
				"full_name" => $this->input->post("fullname".$i, true),
				"contact_no" => $this->input->post("contact".$i, true),
				"seat_no" => $seat_no,
				"pick_up_loc" => "Terminal",
				"drop_off_loc" => "",
				"boarding_status" => "waiting",
				"booking_id" => $id
			);
			$this->db->insert("seat", $data);
		}

	}
}