<?php

class Content_model extends CI_Model
{
	
	
	// func findUser      	*
	
	// func listUsers		
	
	// func getTotal	
	
	
	// func insertUser	
	
	// func updateUser
	
	// func deleteUser
	
	
//----------User_list----------------
	
	function user_list_db()
	{
		$this->db->select("*");
		$this->db->from('user');
		$query= $this->db->get();
		
		return $query->result();
		
	}
	
	function user_list_db2($limit, $row)
	{
		$this->db->or_like('Username',$this->input->get('user-search'));
		return $this->db->get('user', $limit, $row)->result();
	}
	
	
//-----------Search_user---------------

	function search_user_model($search)
	{
		
		$this->db->select('*');
		$this->db->from('user');
		$this->db->like('id', $search);
		$this->db->or_like('Username', $search);
		$this->db->or_like('Firstname', $search);
		$this->db->or_like('Lastname', $search);
		$this->db->or_like('Email', $search);
		$query= $this->db->get();
		
		return $query->result();
		
	}
	
	function search_user_model2($search, $limit, $row)
	{
		
		$this->db->select('*');
		$this->db->from('user');
		$this->db->like('id', $search);
		$this->db->or_like('Username', $search);
		$this->db->or_like('Firstname', $search);
		$this->db->or_like('Lastname', $search);
		$this->db->or_like('Email', $search);
		$this->db->limit($limit, $row);
		$query= $this->db->get();
		
		return $query->result();
		
	}

//-----------Edit_user-----------------


	function get_user_info($uid)
	{
		$query= "SELECT * FROM user WHERE id = ?";
		$result= $this->db->query($query, array($uid));
		return $result->row();
	}
	
	function edit_user($id,$data)
	{
		$this->db->where('id', $id);
		$this->db->update('user', $data); 
		//$sql= "UPDATE user SET Firstname=?, Lastname=?, Username=?, Password=?, Email=? WHERE id = ?";
		//$this->db->query($sql, array($firstname, $lastname, $username, $password, $email, $id));
		
		if ($this->db->affected_rows() == 1)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	function delete_user($id)
	{
		$this->db->delete('user', array('id' => $id));
		
		if($this->db->affected_rows() == 1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}








