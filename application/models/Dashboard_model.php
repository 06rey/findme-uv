<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class dashboard_model extends CI_Model {




	public function get_tripNumber(){   

	    $this->db->select("count(*) as no");                        
	    $query = $this->db->get("trip");          
	    return $query->result();            

	}

	public function get_routeNumber(){   

	    $this->db->select("count(*) as no");                        
	    $query = $this->db->get("route");          
	    return $query->result();            

	}  

	public function get_uvNumber(){   

	    $this->db->select("count(*) as no");                        
	    $query = $this->db->get("uv_unit");          
	    return $query->result();            

	}  

	public function get_employeeNumber(){   

	    $this->db->select("count(*) as no");                        
	    $query = $this->db->get("employee");          
	    return $query->result();            

	}  

	public function get_bookNumber(){   

	    $this->db->select("count(*) as no");                        
	    $query = $this->db->get("booking");          
	    return $query->result();            

	}  






}