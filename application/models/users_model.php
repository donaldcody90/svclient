<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends MY_Model
{
	 private $table_users = 'users';
	 private $table_cust = 'customers';

     function __construct()
     {
          parent::__construct();
     }
	 
	 
	/*
		Function findUser
		param_where = array(fieldName=>fieldValue)
	*/
    function findUser($params_where)
	{
		
		$this->db->where($params_where);
		$result= $this->db->get($this->table_cust);
		return $result->row();
	}
	
	  
	function updateUser($data,$params_where){
           $user = $this->_save(array(
                                        'table'        => $this->table_cust,
                                        'data'         => $data,
                                        'param_where'  => $params_where
                                   ));
          return $user;
       }

     /*function insertUser($data){
          return $this->_save(array(
               'table' => $this->table_cust,
               'data' => $data
          ));
     }*/

     function deleteUser($params_where){
        $this->db->delete($this->table_cust, $params_where);
		  
		if($this->db->affected_rows() == 1)
		{
			return true;
		}	
		else
		{
			return false;
		}
     }
	  
	 function listUser($filter, $limit, $start){
          vst_buildFilter($filter);
          $query = $this->db->limit($limit, $start);
          $query = $this->db->get($this->table_cust);
          return $query->result();
     }
	 
	 function totalUser($filter){
		vst_buildFilter($filter);
		$query = $this->db->get($this->table_cust);
		return $query->num_rows();
     }

}
?>