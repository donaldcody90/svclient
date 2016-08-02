<?php 
if ( ! defined ('BASEPATH')) exit ('No direct script access allowed');

class Support_model extends My_Model
{
	private $table_mess= 'message';
	private $table_conv= 'conversation';
	private $table_users= 'users';
	
	
	public function __construct()
	{
		parent::__construct();
	}
	
	
	function listTicket($filterData, $limit, $start, $param)
	{
		$this->db->select('c.c_id, c.c_title, u.username, c.c_status');
		$this->db->from("$this->table_mess as m");
		$this->db->join("$this->table_conv as c", 'c.c_id = m.c_id', 'inner');
		$this->db->join("$this->table_users as u", 'm.u_id = u.id', 'inner');
		$this->db->join("$this->table_mess as m2", "m.c_id = m2.c_id && m.m_id < m2.m_id", 'left');
		$this->db->where('m2.m_id', NULL);
		//vst_abc($param);
		if($this->session->userdata('access') == 'Customer')
		{
			$this->db->where($param);
		}
		vst_buildFilter($filterData);
		$this->db->order_by('m.m_date', 'DESC');
		$this->db->limit($limit, $start);
		
		$result= $this->db->get();
		
		/*$sql=  "select c.c_id, c.c_title, u.username, c.c_status
				from message as m INNER JOIN users as u ON m.u_id = u.id
								INNER JOIN conversation as c ON c.c_id = m.c_id
								LEFT JOIN message as m2 ON (m.c_id = m2.c_id AND m.m_id < m2.m_id)
				where m2.m_id IS NULL
				  and c.u_id = ?";
				
		$result= $this->db->query($sql, array($id));*/
				
		return $result->result();
	}
	
	function totalTicket($filterData, $param)
	{
		$this->db->select('c.c_id, c.c_title, u.username, c.c_status');
		$this->db->from("$this->table_mess as m");
		$this->db->join("$this->table_conv as c", 'c.c_id = m.c_id', 'inner');
		$this->db->join("$this->table_users as u", 'm.u_id = u.id', 'inner');
		$this->db->join("$this->table_mess as m2", "m.c_id = m2.c_id && m.m_id < m2.m_id", 'left');
		$this->db->where('m2.m_id', NULL);
		$this->db->where($param);
		vst_buildFilter($filterData);
		
		$result= $this->db->get();
		return $result->num_rows();
	}
	
	function addnew_ticket($data)
	{
		$lists = $this->_save(array(
					'table'=>$this->table_conv,
					'data' =>$data
		));
		$insert_id = $this->db->insert_id();
		$result =  array('lists'=>$lists, 'insert_id'=>$insert_id);
		return $result;
	}
	
	
	function get_adminmail($type)
	{
		$this->db->select('email');
		$this->db->from($this->table_users);
		$this->db->where('responsibility', $type);
		$this->db->or_where('responsibility', 'All');
		$result= $this->db->get();
		
		return $result->result();
	}
	
	
	function addnew_message($data)
	{
		return $this->_save(array(
						'table'=> $this->table_mess,
						'data'=> $data
		));
	}
	
	function reopen($data, $param)
	{
		return $this->_save(array(
						'table'=> $this->table_conv,
						'data'=> $data,
						'param_where'=> $param
		));
	}
	
	function get_message($insert_id)
	{
		$this->db->select('u.username, u.role, m.m_date, m.m_content');
		$this->db->from("$this->table_users as u, $this->table_mess as m");
		$this->db->where('u.id = m.u_id');
		$this->db->where('c_id', $insert_id);
		$this->db->order_by('m.m_date', 'DESC');

		$result = $this->db->get();
		return $result->result();
	}
	
	function conv_info($insert_id)
	{
		$this->db->where('c_id', $insert_id);
		$result= $this->db->get($this->table_conv);
		return $result->result();
		
	}
	
	function close_ticket($data)
	{
		$this->db->where($data);
		$this->db->update("$this->table_conv", array('c_status'=> 'closed'));
		
		return $this->db->affected_rows();
	}
	
	
	
	
	
	
}