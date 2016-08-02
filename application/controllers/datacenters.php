<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Datacenters extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		vkt_checkAuth();
		$this->load->model('datacenters_model');
		$this->load->helper('vultr_helper');
	}
	
	function index()
	{
		redirect('datacenters/lists');
	}
	
	function lists()
	{	
		if ($this->session->userdata('access') == 'Administrator')
		{
		$filterData= vst_filterData(array('filter_ip'));
			
				
		$this->load->library('pagination');
		$total= $this->datacenters_model->totalDC($filterData);
			
		$config= vst_Pagination($total);
		$this->pagination->initialize($config);
			
		$start = $this->input->get('page');
		$limit= $config['per_page'];
			
		$data['result']= $this->datacenters_model->listDC($filterData, $limit, $start);
		$data['link']= $this->pagination->create_links();
		$this->load->view('datacenters/list_dc_view', $data);
		}
		/*else
		{
			redirect('datacenters/profile/'.$this->access->userdata('id'));
		}*/
		
	}
	
	
	function getlist()
	{
		require_once APPPATH.'third_party/virtualizor/sdk/admin.php';
		
		$key =  'yvpuctwv6sdyymgagxsga4pedom1rwte';
		$pass = 'igrdzwnadpxzx18xevlmktw178ybrksc';
		$ip = '46.166.139.241';
		
		$admin = new Virtualizor_Admin_API($ip, $key, $pass);

		$output = $admin->status(1002);
		
		if($output == 1){
			 echo 'VPS is currently running';
		}
	}
	
	
	function profile($uid)
	{
		if ($this->session->userdata('access') == 'Customer' && $uid != $this->session->userdata('user_id'))
		{
			redirect('auth/login');
		}
		else
		{
			$params_where= array('id'=> $uid);
			$data['row']= $this->users_model->findUser($params_where);
			$ip= $data['row']->ip;
			$pass= $data['row']->sv_pass;
			$key= $data['row']->sv_key;
			
			$result= status_sv($ip, $key, $pass);
			if($result == 1)
			{
				$data['status']= 'Running';
			}
			if($result == 0)
			{
				$data['status']= 'Stopped';
			}
					
			$this->load->view('users/profile_view', $data);
		}
	}
	
	
	function restart($id)
	{
		$id1= $id;
		$params_where= array('id' => $id1);
		$result= $this->datacenters_model->findDC($params_where);
		$ip= $result->ip;
		$key= $result->sv_key;
		$pass= $result->sv_pass;
		$output= sv_restart($ip, $key, $pass);
		redirect('datacenters/lists');
	}
	
	
	function stop($id)
	{
		$id1= $id;
		$params_where= array('id' => $id1);
		$result= $this->datacenters_model->findDC($params_where);
		$ip= $result->ip;
		$key= $result->sv_key;
		$pass= $result->sv_pass;
		$output= sv_stop($ip, $key, $pass);
		redirect('datacenters/lists');
	}
	
	
	function start($id)
	{
		$id1= $id;
		$params_where= array('id' => $id1);
		$result= $this->datacenters_model->findDC($params_where);
		$ip= $result->ip;
		$key= $result->sv_key;
		$pass= $result->sv_pass;
		$output= sv_start($ip, $key, $pass);
		redirect('datacenters/lists');
	}
	
	
	function update($uid)
	{
		
		if ($this->session->userdata('access') == 'Administrator')
		{
			$params_where= array('id'=> $uid);
			$data['row']= $this->datacenters_model->findDC($params_where);
			
			$this->load->view('datacenters/update_dc_view', $data);

			
			$this->form_validation->set_rules('edit_id', 'ID', 'numeric|max_length[20]|is_unique[datacenters.id]|trim|xss_clean');
			$this->form_validation->set_rules('edit_ip', 'IP Address', 'valid_ip|is_unique[datacenters.ip]|trim|xss_clean');
			$this->form_validation->set_rules('edit_key', 'Key', 'min_length[6]|trim|xss_clean');
			$this->form_validation->set_rules('edit_password', 'Password', 'min_length[6]|trim|xss_clean');
			
			if ($this->form_validation->run() == true)
			{
				$params_where= array('id' => $uid);
				$data = array();
				$id = $this->input->post('edit_id');
				if($id!='' ){
					$data['id'] = $this->input->post('edit_id');
				}
				$ip = $this->input->post('edit_ip');
				if($ip!='' ){
					$data['ip'] = $this->input->post('edit_ip');
				}
				$sv_key = $this->input->post('edit_key');
				if($sv_key!=''){
					$data['sv_key'] = $this->input->post('edit_key');
				}
				$password = $this->input->post('edit_password');
				if($password!=''){
					$data['sv_pass'] = $this->input->post('edit_password');
				}
				if(count($data)>0 ){
					$success= $this->datacenters_model->updateDC($data, $params_where);
					if ($success == TRUE)
					{
						$this->session->set_flashdata('success', TRUE);
					}
					else
					{
						$this->session->set_flashdata('error', TRUE);
					}
					redirect('datacenters/lists');
				}
			}
		}
		
		
	}
	
	
	function add()
	{
		
		if ($this->session->userdata('access') == 'Administrator')
		{
			$this->form_validation->set_rules('ip', 'IP Address', 'required|valid_ip|is_unique[datacenters.ip]|trim|xss_clean');
			$this->form_validation->set_rules('key', 'Key', 'required|alpha|min_length[3]|max_length[20]|is_unique[datacenters.sv_key]|trim|xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|trim|xss_clean');
			//$this->form_validation->set_rules('passconf', 'Password Confirm', 'required|matches[password]|min_length[6]|trim|xss_clean');
			
			$this->form_validation->set_message('is_unique', 'This %s is already registered.');
			$this->form_validation->set_message('matches', 'That is not the same password as the first one.');
			
			if ($this->form_validation->run() == false)
			{
				$this->load->view('datacenters/add_dc_view');
			}
			else
			{
				$data['ip']= $this->input->post('ip');
				$data['sv_key']= $this->input->post('key');
				$data['sv_pass']= $this->input->post('password');
				
				
				$result= $this->datacenters_model->addDC($data);
				
				if ($result == TRUE)
				{
					$this->session->set_flashdata('success', true);
					redirect('datacenters/lists');
				}
				if($result == FALSE)
				{
					$this->session->set_flashdata('error', true);
					redirect('datacenters/add');
				}
			}
		}
		else
		{
			redirect('auth/login');
		}
		
	}
	
	
	function deletedc($uid)
	{
		if ($this->session->userdata('access') == 'Administrator')
		{
			$params_where= array('id'=> $uid);
			$result= $this->datacenters_model->deleteDC($params_where);
			
			$info= $this->datacenters_model->findDC($params_where);
			$key =  $info->sv_key;
			$pass = $info->sv_pass;
			$ip = $info->ip;
			
			$output= delete_sv($ip, $key, $pass);

			if ($result == true && $output == true)
			{
				$this->session->set_flashdata('success', true);
			}
			else
			{
				$this->session->set_flashdata('error', true);
			}
			redirect('datacenters/lists');
			
		}
		else
		{
			redirect('auth/login');
		}
	}
	
}