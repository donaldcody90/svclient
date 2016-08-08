<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		vkt_checkAuth();
		$this->load->model('users_model');
		$this->load->helper('vultr_helper');
	}
	
	
	function index()
	{
		$id= $this->session->userdata('user_id');
		redirect("users/profile/$id");
	}
	
	// function list_user()
	// {
	
		// $userinfo=$this->users_model->findUser(array('username'=>'meo'));
		// var_dump($userinfo);
		// $role= $this->session->userdata('role');
		// if ($role == 'Administrator')
		// {
			// $filterData= vst_filterData(array('filter_id'));
			// /*$search= trim($this->input->get('filter_id'));
			// $filterData=array();
			// if(!empty($search))
				// $filterData=array(
				   // 'id'=>array('value'=>$search,'condition'=>'where'),
				   // 'username'=>array('value'=>$search,'condition'=>'or_like'),
				   // 'fullname'=>array('value'=>$search,'condition'=>'or_like'),
				   // 'email'=>array('value'=>$search,'condition'=>'or_like'),
				// );*/
				
			// $this->load->library('pagination');
			// $total= $this->users_model->totalUser($filterData);
			
			// $config= vst_Pagination($total);
			// $this->pagination->initialize($config);
			
			// $start = $this->input->get('page');
			// $limit= $config['per_page'];
			
			// $data['result']= $this->users_model->listUser($filterData, $limit, $start);
			// $data['link']= $this->pagination->create_links();
			// $this->load->view('users/list_user_view', $data);
		// }
		// if ($role == 'Customer')
		// {
			// $id= $this->session->userdata('user_id');
			// redirect("users/profile/$id");
		// }
		
	// }
	
	function profile($uid)
	{
		
		if ($uid != $this->session->userdata('user_id'))
		{
			redirect('auth/login');
		}
		else
		{
			$params_where= array('id'=> $uid);
			$data['row']= $this->users_model->findUser($params_where);
					
			$this->load->view('users/profile_view', $data);
		}
	
	}
	
	
	function update($uid)
	{
		
		if ($uid != $this->session->userdata('user_id'))
		{
			redirect('auth/login');
		}
		else
		{
			$params_where= array('id'=> $uid);
			$data['row']= $this->users_model->findUser($params_where);
			
			$this->load->view('users/update_view', $data);

			
			$this->form_validation->set_rules('edit_username', 'Username', 'alpha_numeric|min_length[3]|max_length[20]|is_unique[users.username]|trim|xss_clean');
			$this->form_validation->set_rules('edit_fullname', 'First name', 'min_length[2]|max_length[20]|trim|xss_clean');
			$this->form_validation->set_rules('edit_password', 'Password', 'min_length[6]|trim|xss_clean');
			$this->form_validation->set_rules('edit_email', 'Email', 'valid_email|is_unique[users.email]|trim|xss_clean');
			
			if ($this->form_validation->run() == true)
			{
				$params_where= array('id' => $uid);
				$data = array();
				$username = $this->input->post('edit_username');
				if($username!='' ){
					$data['username'] = $this->input->post('edit_username');
				}
				$fullname = $this->input->post('edit_fullname');
				if($fullname!='' ){
					$data['fullname'] = $this->input->post('edit_fullname');
				}
				$password = $this->input->post('edit_password');
				if($password!=''){
					$data['password'] = hash('sha512', $this->input->post('edit_password'));
				}
				$email = $this->input->post('edit_email');
				if($email!=''){
					$data['email'] = $this->input->post('edit_email');
				}
				if(count($data)>0 ){
					$success= $this->users_model->updateUser($data, $params_where);
					if ($success == TRUE)
					{
						$this->session->set_flashdata('success', TRUE);
					}
					else
					{
						$this->session->set_flashdata('error', TRUE);
					}
					redirect("users/update/$uid");
				}
			}
		}
		
	}
	
	/*
	function add_new_user()
	{
		
		if ($this->session->userdata('access') == 'Administrator')
		{
			$this->form_validation->set_rules('fullname', 'First name', 'required|min_length[2]|max_length[20]|trim|xss_clean');
			$this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric|min_length[3]|max_length[20]|is_unique[users.username]|trim|xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|trim|xss_clean');
			$this->form_validation->set_rules('passconf', 'Password Confirm', 'required|matches[password]|min_length[6]|trim|xss_clean');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]|trim|xss_clean');
			
			$this->form_validation->set_message('is_unique', 'This %s is already registered.');
			$this->form_validation->set_message('matches', 'That is not the same password as the first one.');
			
			if ($this->form_validation->run() == false)
			{
				$this->load->view('users/addnew_view');
			}
			else
			{
				$data['fullname']= $_POST['fullname'];
				$data['username']= $_POST['username'];
				$data['password']= hash('sha512', $_POST['password']);
				$data['email']= $_POST['email'];
				$data['role']= 'Customer';
				
				$result= $this->Auth_model->add_user($data);
				
				if ($result == TRUE)
				{
					$this->session->set_flashdata('success', true);
					redirect('users/add_new_user');
				}
				if($result == FALSE)
				{
					$this->session->set_flashdata('error', true);
					redirect('users/add_new_user');
				}
			}
		}
		if ($this->session->userdata('access') == 'Customer')
		{
			$id= $this->session->userdata('user_id');
			redirect("users/update/$id");
		}
		
	}
	
	
	function delete_user($uid)
	{
		if ($this->session->userdata('access') == 'Administrator')
		{
			$params_where= array('id'=> $uid);
			$result= $this->users_model->deleteUser($params_where);
			if ($result == true)
			{
				$this->session->set_flashdata('success', true);
			}
			else
			{
				$this->session->set_flashdata('error', true);
			}
			redirect('users/list_user');
		}
		else
		{
			redirect('auth/login');
		}
		
	}  */
}

