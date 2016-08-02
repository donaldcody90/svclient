<?php
class Auth_model extends CI_Model{
	function __construct()
	{
		parent::__construct();
	}
	
	function check_login($username, $password)
	{
		$query= "SELECT * FROM users WHERE (username = ? OR email = ?) AND password = ?";
		$result= $this->db->query($query, array($username, $username, hash('sha512', $password)));
		
		if ($result->num_rows() > 0)
		{
			return $result->row(0);
		}
		else
		{
			return false;
		}
	}
	
	//----------signup------------
	
	function add_user($data)
	{
		$this->db->insert('users', $data);
		
		if ($this->db->affected_rows() == 1)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}