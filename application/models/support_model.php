<?php 
if ( ! defined ('BASEPATH')) exit ('No direct script access allowed');

class Support_model extends My_Model
{
	private $message= 'message';
	private $conversation= 'conversation';
	private $users= 'users';
	private $categories= 'categories';
	
	
	public function __construct()
	{
		parent::__construct();
	}
	
	
	function listTicket($filterData, $limit, $start, $param)
	{
		$this->db->select('c.cid, c.title, m.content, c.status');
		$this->db->from("$this->message as m");
		$this->db->join("$this->conversation as c", 'c.cid = m.cid', 'inner');
		$this->db->join("$this->message as m2", "m.cid = m2.cid && m.mid < m2.mid", 'left');
		$this->db->where('m2.mid', NULL);
		//vst_abc($param);
		$this->db->where($param);
		vst_buildFilter($filterData);
		$this->db->order_by('m.date', 'DESC');
		$this->db->limit($limit, $start);
		

		$result= $this->db->get();
		
		
		/*$sql=  "select c.cid, c.title, u.username, c.status
				from message as m INNER JOIN users as u ON m.uid = u.id
								INNER JOIN conversation as c ON c.cid = m.cid
								LEFT JOIN message as m2 ON (m.cid = m2.cid AND m.mid < m2.mid)
				where m2.mid IS NULL
				  and c.uid = ?";
				
		$result= $this->db->query($sql, array($id));*/
				
		return $result->result();
	}
	
	function totalTicket($filterData, $param)
	{
		$this->db->select('c.cid, c.title, m.content, c.status');
		$this->db->from("$this->message as m");
		$this->db->join("$this->conversation as c", 'c.cid = m.cid', 'inner');
		$this->db->join("$this->message as m2", "m.cid = m2.cid && m.mid < m2.mid", 'left');
		$this->db->where('m2.mid', NULL);
		$this->db->where($param);
		vst_buildFilter($filterData);
		
		$result= $this->db->get();
		return $result->num_rows();
	}
	
	function addTicket($data)
	{
		$lists = $this->_save(array(
					'table'=>$this->conversation,
					'data' =>$data
		));
		$insert_id = $this->db->insert_id();
		$result =  array('lists'=>$lists, 'insert_id'=>$insert_id);
		return $result;
	}
	
	
	function getAdminmail($type)
	{
		$this->db->select('email');
		$this->db->from("$this->users as u");
		$this->db->join("$this->categories as ca", 'u.id = ca.uid');
		$this->db->where('ca.id', $type);
		$result= $this->db->get();
		return $result->row_array();
		
	}
	
	
	function addMessage($data)
	{
		return $this->_save(array(
						'table'=> $this->message,
						'data'=> $data
		));
	}
	
	function reopen($data, $param)
	{
		return $this->_save(array(
						'table'=> $this->conversation,
						'data'=> $data,
						'param_where'=> $param
		));
	}
	
	function getMessage($insert_id)
	{
		// $this->db->select('u.username, u.role, m.date, m.content');
		// $this->db->from("$this->users as u, $this->message as m");
		// $this->db->where('u.id = m.uid');
		// $this->db->where('cid', $insert_id);
		// $this->db->order_by('m.date', 'DESC');
		// $result = $this->db->get();
		
		$sql="select * from(select u.username, m.date, m.content, u.role
				from users as u, message as m
				where u.id = m.uid
				  and cid = ?
				UNION
				select cu.username, m.date, m.content, null
				from customers as cu, message as m
				where cu.id = m.uid
				  and cid = ?) as uniontable
				order by date DESC";

		$result= $this->db->query($sql, array($insert_id, $insert_id));
		
		return $result->result();
	}
	
	
	
	function getConversationinfo($params_where=null, $is_list=false){
		 return  $this->_getwhere(array(
						'table'        => $this->conversation,
						'param_where'  => $params_where,
						'list'         => $is_list
			));
	}
	
	
	
	function getCategory($param_where, $is_list= false)
	{
		return $this->_getwhere(array(
					'table'=> $this->categories,
					'param_where' => $param_where,
					'list' => $is_list
		));
	}
	
	
	
	
	// function getConversationinfo($insert_id)
	// {
		// $this->db->where('cid', $insert_id);
		// $result= $this->db->get($this->conversation);
		// return $result->row();
		
	// }
	
	// function getCategory($type)
	// {
		
		// $this->db->where('name', $type);
		// $this->db->order_by('rand()');
		// $this->db->limit(1);
		// $result= $this->db->get($this->categories);
		// return $result->row();
	// }
	
	
}