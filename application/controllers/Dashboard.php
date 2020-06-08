<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function index()
	{
		if (!$this->user_model->is_logged_in() ) {
			redirect('user/login');
		}elseif ($this->session->userdata('role') == ('clerk')) {
			redirect('booking');
		}
		
		$pageTitle = "Dashboard";
		$this->load->model('dashboard_model');     
	    $tripNo =  $this->dashboard_model->get_tripNumber();   
	    $trip = $tripNo[0]->no;

	    $routeNo =  $this->dashboard_model->get_routeNumber();   
	    $route = $routeNo[0]->no;

	    $uvNo =  $this->dashboard_model->get_uvNumber();   
	    $uv = $uvNo[0]->no;

	    $employeeNo =  $this->dashboard_model->get_employeeNumber();   
	    $employee = $employeeNo[0]->no;

	    $bookNo =  $this->dashboard_model->get_bookNumber();   
	    $book = $bookNo[0]->no;

		$this->load->view('dashboard_view',[
			'pageTitle'=>$pageTitle,
			'trip' => $trip,
			'route' => $route,
			'uv' => $uv,
			'employee' => $employee,
			'book' => $book,
			'all_feedback'=> $this->log_model->get_all_feedback(5)

		]);
	}
}
