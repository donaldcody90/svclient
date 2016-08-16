<?php
if (!defined ('BASEPATH')) exit ('No direct script access allowed');

class Billing_model extends MY_Model
{
	private $billing= 'billing';
	
	public function __construct()
	{
		parent::__construct();
	}
	
	/*
		Function findUser
		param_where = array(fieldName=>fieldValue)
	*/
	
	
	function insertTransaction($data)
	{
		$this->db->insert($this->billing, $data);
	}

}