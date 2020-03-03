<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Route extends CI_Controller {

	public function add()
	{
		if (!$this->user_model->is_logged_in() ) {
			redirect('user/login');
		}elseif ($this->session->userdata('role') == ('clerk')) {
			redirect('booking/all');
		}
			
		$pageTitle = "Add Route";
		$message = $this->session->flashdata('message');
		$this->load->library('form_validation');
		$this->load->view('add_route_view',[
			'pageTitle'=> $pageTitle, 
			'message'=> $message
		]);
	}

	public function add_validation(){	
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('origin', 'Origin', 'required');
		$this->form_validation->set_rules('destination', 'Destination', 'required');
		$this->form_validation->set_rules('via', 'Via', 'required');
		$this->form_validation->set_rules('fare', 'Fare', 'required');

		$this->session->mark_as_flash('message');

		if ($this->form_validation->run() == FALSE){
                	//set error
            $message = [
                'type' => 'danger',
                'message' => validation_errors()	
            ];
            $this->session->set_flashdata('message', $message);
        	redirect('route/add');
        }else{      
                	//set a success msg        
					if ($this->route_model->create()) {
						$message = [
                		'type' => 'success',
                		'message' => 'Successfully Created Route!'
		                ];
		                $this->session->set_flashdata('message', $message);
		                $this->log_model->log('Added new route');
						redirect('route/all');
					}
					else
					{
						//set error
						redirect('route/add');
					}
                }



	}


	public function all()
	{	
		
		if (!$this->user_model->is_logged_in() ) {
			redirect('user/login');
		}elseif ($this->session->userdata('role') == ('clerk')) {
			redirect('trip_management/all');
		}

		$pageTitle = "Manage Routes";
		$message = $this->session->flashdata('message');
		$all_route = $this->route_model->get_all();
		$this->load->view('get_all_route_view',[
			'pageTitle'=>$pageTitle,
			'all_route'=> $all_route,
			'message'=> $message
		]);
	}




	public function edit_route($id)
	{
		if (!$this->user_model->is_logged_in()) {
			redirect('user/login');
		}

		$pageTitle = "Edit Route";
		$message = $this->session->flashdata('message');
		$route = $this->route_model->get($id);

		$this->load->view('edit_route_view',[
			'pageTitle'=>$pageTitle,
			'route'=> $route
		]);
	}
	public function edit_route_validation($id){	   

		$this->load->library('form_validation');

		$this->session->mark_as_flash('message');

		if ($this->form_validation->run() == FALSE)
                {
                	$message = [
                		'type' => 'danger',
                		'message' => validation_errors()
		                ];
		                $this->session->set_flashdata('message', $message);
						redirect("route/edit_route/$id");
                        
                }
                else
                {
                    if ($this->route_model->edit($id)) {
                    	$message = [
                		'type' => 'success',
                		'message' => 'Successfully Updated Route!'
		                ];
		                $this->session->set_flashdata('message', $message);

		                //LOGGER

		               $this->log_model->log('Updated route');

						redirect('route/all');
					}else{
						
						redirect('route/all'); 
					}

                }		
	}

	public function route_path() {
		$route_path = $this->route_model->get_route_path();
		if (count($route_path) > 0) {
			$route_path[0]->{'status'} = 'has_record';
			foreach ($route_path as $key => $route) {
				$route_path[$key]->origin_lat_lng = json_decode($route->origin_lat_lng);
				$route_path[$key]->destination_lat_lng = json_decode($route->destination_lat_lng);
				$route_path[$key]->way_point = json_decode($route->way_point);
			}
		} else {
			$route_path[0]->{'status'} = 'no_record';
		}
		echo json_encode($route_path);
	}

	public function validate_route($origin ="", $destination = "") {
		echo json_encode($this->route_model->check_route($origin, $destination));
	}
}