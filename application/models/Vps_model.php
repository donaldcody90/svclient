<?php
if (!defined ('BASEPATH')) exit ('No direct script access allowed');

class Vps_model extends MY_Model
{
	private $vps= 'vps';
	private $servers= 'servers';
	private $plans= 'plans';
	private $customers= 'customers';
	
	public function __construct()
	{
		parent::__construct();
	}
	
	/*
		Function findUser
		param_where = array(fieldName=>fieldValue)
	*/
	
	
	function findVps($params_where,$is_list=false){
		 return  $this->_getwhere(array(
							'table'        => $this->vps,
							'param_where'  => $params_where,
							'list'         => $is_list
				));
	}
	 

	function getVps($params_where){
		$this->db->select('v.id, v.cid, s.location, s.label, v.vps_ip, c.username, v.rootpass, p.cpu_core, p.ram, p.disk_space, p.bandwidth, v.vps_label');
		$this->db->from("$this->vps as v");
		$this->db->join("$this->customers as c", 'c.id = v.cid');
		$this->db->join("$this->plans as p", 'p.id = v.pid');
		$this->db->join("$this->servers as s", 's.id = v.svid');
		$this->db->where($params_where);
		$result= $this->db->get();
		return $result->row_array();
	}
	
	
	
	function updateVps($data, $params_where){
           return  $this->_save(array(
							'table'        => $this->vps,
							'data'         => $data,
							'param_where'  => $params_where
					   ));
       }

    
	
	function addVps($data){
          return $this->_save(array(
					   'table' => $this->vps,
					   'data' => $data
				  ));
     }
	

	 function deleteVps($params_where){
          return $this->_del(array(
					   'table'        => $this->vps,
					   'param_where'  => $params_where
				  ));
     }
	  
	  
	 
	 
	 function listVps($filter, $limit, $start){
		$this->db->select('v.id, s.label, v.vps_label, v.vps_ip, v.create_date');
		$this->db->from("$this->vps as v");
		$this->db->join("$this->servers as s", 'v.svid = s.id');
		vst_buildFilter($filter);
		$this->db->limit($limit, $start);
		return $this->db->get()->result();
	 }
	 
	 
	 function totalVps($filter){
		$this->db->select('v.id, s.label, v.vps_label, v.vps_ip, v.create_date');
		$this->db->from("$this->vps as v");
		$this->db->join("$this->servers as s", 'v.svid = s.id');
		vst_buildFilter($filter);
		return $this->db->get()->num_rows();
     }
	 
	 
	 function findSV($params_where= null,$is_list=false){
		 return $this->_getwhere(array(
							'table'        => $this->servers,
							'param_where'  => $params_where,
							'list'         => $is_list
				));
		}
	
	function findPlan($params_where= null, $is_list=false){
		return $this->_getwhere(array(
							'table'			=> $this->plans,
							'param_where'  	=> $params_where,
							'list'         	=> $is_list
		));
	}
	 
	 
	 
	 // function findVps($params_where)
	// {
		// $this->db->where($params_where);
		// $result= $this->db->get($this->vps);
		// return $result->row();
	// }
	
	// function updateVps($data, $params_where)
	// {
        // $this->db->where($params_where);
		// $this->db->update($this->vps, $data);
		
		// return $this->db->affected_rows();
    // }
	
	// function addVps($data){
        // $this->db->insert($this->vps, $data);
        
		// return $this->db->affected_rows();
    // }
	
	// function deleteVps($params_where){
        // $this->db->delete($this->vps, $params_where);
		  
		// return $this->db->affected_rows();
     // }

}