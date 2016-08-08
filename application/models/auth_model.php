<?php
class Auth_model extends CI_Model{
	
	private $customers= 'customers';
	
	function __construct()
	{
		parent::__construct();
		
	}
	
	function checkLogin($username, $password)
	{
		$query= "SELECT * FROM $this->customers WHERE (username = ? OR email = ?) AND password = ?";
		$result= $this->db->query($query, array($username, $username, hash('sha512', $password)));
		
		return $result->row(0);
	}
	
	//----------signup------------
	
	function addUser($data)
	{
		$this->db->insert($this->customers, $data);
		
		return $this->db->affected_rows();
	}

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}