<?php
if (!defined ('BASEPATH')) exit ('No direct script access allowed');

class Vps_model extends MY_Model
{
	private $vps= 'vps';
	private $servers= 'servers';
	
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
	  
	  
	 // function listVps($param_where, $filter, $limit, $start){
          // vst_buildFilter($filter);
		  // $this->db->where($param_where);
          // $this->db->limit($limit, $start);
          // $query = $this->db->get($this->vps);
          // return $query->result();
     // }
	 
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