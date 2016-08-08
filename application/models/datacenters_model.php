<?php
if (!defined ('BASEPATH')) exit ('No direct script access allowed');

class Datacenters_model extends CI_Model
{
	private $datacenters= 'datacenters';
	
	public function __construct()
	{
		parent::__construct();
	}
	
	/*
		Function findUser
		param_where = array(fieldName=>fieldValue)
	*/
	function findDC($params_where)
	{
		$this->db->where($params_where);
		$result= $this->db->get($this->datacenters);
		return $result->row();
	}
	
	  
	function updateDC($data, $params_where)
	{
        $this->db->where($params_where);
		$this->db->update($this->datacenters, $data);
		
		return $this->db->affected_rows();
    }

    function addDC($data){
        $this->db->insert($this->datacenters, $data);
        
		return $this->db->affected_rows();
    }
	

    function deleteDC($params_where){
        $this->db->delete($this->datacenters, $params_where);
		  
		return $this->db->affected_rows();
     }
	  
	 function listDC($param_where, $filter, $limit, $start){
          vst_buildFilter($filter);
		  $this->db->where($param_where);
          $this->db->limit($limit, $start);
          $query = $this->db->get($this->datacenters);
          return $query->result();
     }
	 
	 function totalDC($param_where, $filter){
		vst_buildFilter($filter);
		$this->db->where($param_where);
		$query = $this->db->get($this->datacenters);
		return $query->num_rows();
     }

}