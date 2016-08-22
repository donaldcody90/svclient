<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Vps extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		vkt_checkAuth();
		$this->load->model('vps_model');
		$this->load->helper('vultr_helper');
	}
	
	function index()
	{
		redirect('vps/lists');
	}
	
	function lists()
	{	
		$uid= $this->session->userdata('user_id');
		$filterData= vst_filterData(
					array('filter_vps_ip'),
					array(),
					array('vps_ip'=> 'v')
		);
		$filterData['cid']= array('value'=>$uid,'condition'=>'where');
		
		$total= $this->vps_model->totalVps($filterData);
			
		$config= vst_Pagination($total);
		$this->pagination->initialize($config);
			
		$start = $this->input->get('page');
		$limit= $config['per_page'];
			
		$data['result']= $this->vps_model->listVps($filterData, $limit, $start);
		$data['link']= $this->pagination->create_links();
		$this->load->view('vps/list_vps_view', $data);
		
	}
	
	
	function profile($id)
	{
		$params_where= array('id'=> $id);
		$data['row']= $this->vps_model->findVps($params_where);
				
		if ($data['row']['cid']= $this->session->userdata('user_id'))
		{
			$this->load->view('vps/profile_vps_view', $data);
		}
		else
		{
			redirect('auth/login');
		}
	}
	
	
	function update($uid)
	{
		
		//$this->form_validation->set_rules('edit_id', 'ID', 'numeric|max_length[20]|is_unique[vps.id]|trim|xss_clean');
		$this->form_validation->set_rules('edit_ip', 'IP Address', 'valid_ip|is_unique[vps.vps_ip]|trim|xss_clean');
		$this->form_validation->set_rules('edit_label', 'Label', 'min_length[6]|trim|xss_clean');
		$this->form_validation->set_rules('edit_rootpass', 'Rootpass', 'min_length[6]|trim|xss_clean');
		
		if ($this->form_validation->run() == false)
		{
			$params_where= array('id'=> $uid);
			$data['row']= $this->vps_model->findVps($params_where);
			
			$this->load->view('vps/update_vps_view', $data);
		}
		if ($this->form_validation->run() == true)
		{
			$params_where= array('id' => $uid);
			$data = array();
			
			$ip = $this->input->post('edit_ip');
			if($ip!='' ){
				$data['vps_ip'] = $this->input->post('edit_ip');
			}
			$svkey = $this->input->post('edit_label');
			if($svkey!=''){
				$data['vps_label'] = $this->input->post('edit_label');
			}
			$password = $this->input->post('edit_rootpass');
			if($password!=''){
				$data['rootpass'] = $this->input->post('edit_rootpass');
			}
			if(count($data)>0 ){
				$success= $this->vps_model->updateVps($data, $params_where);
				if ($success == 1)
				{
					message_flash('Updated Successfully');
				}
				else
				{
					message_flash('Can not update at this time.', 'error');
				}
				redirect('vps/lists');
			}
		}
		
	}
	
	
	
	
	
	
	function add()
	{
		
		$this->form_validation->set_rules('label', 'Label', 'required|trim');
		$this->form_validation->set_rules('ip', 'IP Address', 'required|valid_ip|is_unique[vps.vps_ip]|trim');
		$this->form_validation->set_rules('space', 'Space', 'required|numeric|trim');
		$this->form_validation->set_rules('ram', 'Ram', 'required|numeric|trim');
		
		$this->form_validation->set_message('is_unique', 'This %s is already registered.');
		
		if ($this->form_validation->run() == false)
		{
			$data['servers']= $this->vps_model->findSV(array(),true);
			$this->load->view('vps/add_vps_view', $data);
		}
		else
		{
			$data['cid']= $this->session->userdata('user_id');
			$data['svid']= $this->input->post('servers');
			$data['vps_label']= $this->input->post('label');
			$data['vps_ip']= $this->input->post('ip');
			$data['rootpass']= RandomString(30);
			$data['create_date']= date('Y-m-d H:i:s');
			$data['space']= $this->input->post('space');
			$data['ram']= $this->input->post('ram');
			//print_r($data); die;
			
			$result= $this->vps_model->addVps($data);
			
			if ($result == TRUE)
			{
				message_flash('Added Successfully!');
				redirect('vps/lists');
			}
			if($result == FALSE)
			{
				message_flash('Can not add at this time.', 'error');
				redirect('vps/add');
			}
		}
		
		
	} 
	
	
	function delete($id)
	{
		$params_where= array('id'=> $id);
		$result= $this->vps_model->deleteVps($params_where);
		
		if ($result == 1)
		{
			message_flash('Deleted Successfully!');
		}
		else
		{
			message_flash('Can not delete.', 'error');
		}
		redirect('vps');
			
	}
	
	
	
	
	
	
	
	// function getlist()
	// {
		// require_once APPPATH.'third_party/virtualizor/sdk/admin.php';
		
		// $key =  'yvpuctwv6sdyymgagxsga4pedom1rwte';
		// $pass = 'igrdzwnadpxzx18xevlmktw178ybrksc';
		// $ip = '46.166.139.241';
		
		// $admin = new Virtualizor_Admin_API($ip, $key, $pass);

		// $output = $admin->status(1002);
		
		// if($output == 1){
			 // echo 'VPS is currently running';
		// }
	// }
	
	
	// function restart($id)
	// {
		// $id1= $id;
		// $params_where= array('id' => $id1);
		// $result= $this->vps_model->findVps($params_where);
		// $ip= $result->ip;
		// $key= $result->svkey;
		// $pass= $result->svpass;
		// $output= sv_restart($ip, $key, $pass);
		// redirect('vps/lists');
	// }
	
	
	// function stop($id)
	// {
		// $id1= $id;
		// $params_where= array('id' => $id1);
		// $result= $this->vps_model->findVps($params_where);
		// $ip= $result->ip;
		// $key= $result->svkey;
		// $pass= $result->svpass;
		// $output= sv_stop($ip, $key, $pass);
		// redirect('vps/lists');
	// }
	
	
	// function start($id)
	// {
		// $id1= $id;
		// $params_where= array('id' => $id1);
		// $result= $this->vps_model->findVps($params_where);
		// $ip= $result->ip;
		// $key= $result->svkey;
		// $pass= $result->svpass;
		// $output= sv_start($ip, $key, $pass);
		// redirect('vps/lists');
	// }
	
}