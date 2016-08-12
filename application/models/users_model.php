<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends MY_Model
{
	 private $customers = 'customers';

     function __construct()
     {
          parent::__construct();
     }
	 
	 
	/*
		Function findUser
		param_where = array(fieldName=>fieldValue)
	*/
    
	
	function findUser($params_where, $is_list=false){
		 return  $this->_getwhere(array(
                    'table'        => $this->customers,
                    'param_where'  => $params_where,
                    'list'         => $is_list
        ));
	}
	
	  
	function updateUser($data, $params_where){
           return  $this->_save(array(
                                        'table'        => $this->customers,
                                        'data'         => $data,
                                        'param_where'  => $params_where
                                   ));
       }

	   
	 function listUser($filter, $limit, $start){
          vst_buildFilter($filter);
          $query = $this->db->limit($limit, $start);
          $query = $this->db->get($this->customers);
          return $query->result();
     }
	 
	 
	 function totalUser($filter){
		vst_buildFilter($filter);
		$query = $this->db->get($this->customers);
		return $query->num_rows();
     }
	 
	 
	 // function findUser($params_where)
	// {
		
		// $this->db->where($params_where);
		// $result= $this->db->get($this->customers);
		// return $result->row();
	// }
	
	/*function insertUser($data){
          return $this->_save(array(
               'table' => $this->customers,
               'data' => $data
          ));
     }*/

}
?>