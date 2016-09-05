<?php
if (!defined ('BASEPATH')) exit ('No direct script access allowed');

class Billing_model extends MY_Model
{
	private $billing= 'billing';
	private $settings= 'settings';
	
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
	
	function getPaypal($param_where)
	{
		return $this->_getwhere(array(
				'table'				=>	$this->settings,
				'param_where'		=>	$param_where
		));
	}
	
	function servercharge(){
		$sql= "UPDATE vps as v 
				INNER JOIN plans as p 
				ON p.id = v.pid 
				SET v.charge= v.charge + p.price/720";
				
		$result= $this->db->query($sql);
		return $result;
	}
	
	function moneydecrease(){
		$sql= "UPDATE balance as b
				INNER JOIN (SELECT cid, SUM(charge) as total FROM vps GROUP BY cid) as v
				ON b.cid = v.cid
				SET b.amount = b.amount - v.total ";
		
		$result= $this->db->query($sql);
		return $result;
	}
	
	function noteservercharge(){
		$sql= "INSERT INTO billing (cid, payment) 
				SELECT cid, SUM(charge) as total FROM vps GROUP BY cid";
				
		$result= $this->db->query($sql);
		return $result;
	}
	
	function resetservercharge(){
		$sql= "UPDATE vps SET charge= 0";
				
		$result= $this->db->query($sql);
		return $result;
	}
	
}