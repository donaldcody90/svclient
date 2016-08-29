<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Deploynewserver extends CI_Controller
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
		if($this->input->post('deploy'))
		{
			$data['cid']= $this->session->userdata('user_id');
			$data['pid']= $this->input->post('plan');
			$data['svid']= $this->input->post('server');
			$data['vps_label']= $this->input->post('label');
			$data['vps_ip']= $this->input->post('ip');
			$data['rootpass']= RandomString(30);
			$data['create_date']= date('Y-m-d H:i:s');
			// $data['space']= $this->input->post('space');
			// $data['ram']= $this->input->post('ram');
			$result= $this->vps_model->addVps($data);
			
			if ($result == TRUE)
			{
				message_flash('Added Successfully!');
				redirect('vps/lists');
			}
			if($result == FALSE)
			{
				message_flash('VPS addition failed.', 'error');
				redirect('vps/add');
			}
		}
		
		$data['servers']= $this->vps_model->findSV(array(),true);
		$data['plans']= $this->vps_model->findPlan(null, true);
		$this->load->view('vps/add', $data);
	}
	
}