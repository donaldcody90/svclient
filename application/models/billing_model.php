<?php
if (!defined ('BASEPATH')) exit ('No direct script access allowed');

class Billing_model extends MY_Model
{
	private $billing_history= 'billing_history';
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
		$this->db->insert($this->billing_history, $data);
	}
	
	function getPaypal($param_where)
	{
		return $this->_getwhere(array(
				'table'				=>	$this->settings,
				'param_where'		=>	$param_where
		));
	}
	
	function PendingCharges($param_where)
	{
		$this->db->select('b.cid, SUM(vl.amount) as total, b.amount');
		$this->db->from("vps_lifetime as vl");
		$this->db->join("balance as b", 'vl.cid= b.cid');
		$this->db->where($param_where);
		$this->db->group_by('b.cid');
		return $this->db->get()->row_array();
	}
	
	function billing($param_where, $is_list= false)
	{
		return $this->_get(array(
						'table' => $this->billing_history,
						'param_where' => $param_where,
						'orderby' => "created_date DESC",
						'list' => $is_list
		));
	}
	
	
	/*
		1. Đảm bảo là khi tạo VPS thì cần phải insert vào vps_lifetime
		
		2. Cần 1 hàm update lại tiền, ngày cho 1 VPS, truyền vào VPS ID --> Tháng hiện tại, Năm hiện tại 
		
		3. 1 hàm cronjob tự động tính tiền
		 Duyệt tòan bộ VPS --> sau đó gọi cái hàm ở mục 2
	*/
	
	function calVPSPrice($VPSID)
	{
		$currentDate=date('Y-m-d h:i:s');
		$month=xxx
		$year = xxx
		$vps_hour_price= xxxx;
		
		UPDATE vps_lifetime set end_date=currentDate and amount=amount+$vps_hour_price where vps_id=$VPSID and month=$monht and year=yeah
		
	}
	
	function cronjobAllVPS
	{
		// lấy ra tòan bộ VPS
		$vpss=$this->model->getAllVPS();
		if($vpss)
		{
			foreach($vpss as $vp)
			{
				$this->calVPSPrice($vp['id']);
			}
			
		}
	}
	
	function servercharge($date){
		$sql= "UPDATE vps_lifetime as vl
				INNER JOIN vps as v ON vl.vps_id = v.id
				INNER JOIN plans as p ON p.id = v.pid
				SET vl.amount= vl.amount + p.price/720, vl.end_date= ?";
		
		$result= $this->db->query($sql, array($date));
		return $result;
	}
	
	function moneydecrease(){
		$sql= "UPDATE balance as b
				INNER JOIN (SELECT cid, SUM(p.price)/720 as total 
							FROM vps as v 
							INNER JOIN plans as p ON v.pid=p.id 
							GROUP BY cid) as t
				ON b.cid = t.cid
				SET b.amount = b.amount - t.total ";
		
		$result= $this->db->query($sql);
		return $result;
	}
	
	function noteservercharge($month, $year, $date){
		$sql= "INSERT INTO billing_history (cid, description, created_date, amount, balance, type, ref_id)
				SELECT vl.cid, concat('Invoice#', i.invoiceid), ? as 'created_date', sum(vl.amount), b.amount, '0' as 'type', i.id
				FROM vps_lifetime as vl
				INNER JOIN balance as b ON b.cid = vl.cid
				INNER JOIN invoices as i ON i.cid = vl.cid
				WHERE i.month= ?
				  AND i.year= ?
				GROUP BY vl.cid";
				
		$result= $this->db->query($sql, array($date, $month, $year));
		return $result;
	}
	
	function resetservercharge($month, $year, $date_time){
		$sql= "UPDATE vps_lifetime SET amount= 0, month= ?, year= ?, start_date= ?, end_date= ?";
				
		$result= $this->db->query($sql, array($month, $year, $date_time, $date_time));
		return $result;
	}
	
}