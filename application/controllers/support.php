<?php 
if ( ! defined ('BASEPATH')) exit('No direct script access allowed');

class Support extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		vkt_checkAuth();
		$this->load->model('support_model');
		date_default_timezone_set('Asia/Ho_Chi_Minh');
	}
	
	
	function index()
	{
		redirect('support/lists');
	}
	
	
	function lists()
	{
		$id= $this->session->userdata('user_id');
		$param['c.u_id']= $id;
		$filterData= vst_filterData(array('filter_c_title'));
		
		$this->load->library('pagination');
		
		$total= $this->support_model->totalTicket($filterData, $param);
		$config= vst_Pagination($total);
		$this->pagination->initialize($config);
		
		$start= $this->input->get('page');
		$limit= $config['per_page'];
		
		$data['result']= $this->support_model->listTicket($filterData, $limit, $start, $param);
		
		$data['link']= $this->pagination->create_links();
		//print_r($data['result']);die;
		
		$this->load->view('support/ticket_list_view', $data);
	}
	
	
	function addnew()
	{
		$this->form_validation->set_rules('ticket-subject', 'Subject', 'required|min_length[3]|xss_clean');
		$this->form_validation->set_rules('ticket-message', 'Message', 'required|min_length[3]|xss_clean');
		
		if ($this->form_validation->run() == false)
		{
			$this->load->view('support/addnew_ticket_view');
		}
		else
		{
			
			/*-----------------Add new ticket---------------------*/
			
			$subject= $this->input->post('ticket-subject');
			$message= $this->input->post('ticket-message');
			$uid= $this->session->userdata('user_id');
			$openingdate= date('Y-m-d H:i:s');
			$status= 'opening';
			$type= $this->input->post('ticket-type');
			
			$data = array('u_id'=>$uid, 'c_title'=>$subject, 'c_message'=>$message, 'c_openingdate'=>$openingdate, 'c_status'=>$status, 'c_type'=> $type);
			$result = $this->support_model->addnew_ticket($data);
			$kq = $result['lists']; 
			$insert_id= $result['insert_id'];
			
			/*----------------Add first message---------------------*/
			
			$data1['u_id']= $this->session->userdata('user_id');
			$data1['c_id']= $insert_id;
			$data1['m_content']= $message;
			$data1['m_date']= date('Y-m-d H:i:s');
			
			$first_mess= $this->support_model->addnew_message($data1);
			
			/*-----------------Send mail---------------------------*/
			
			$adminmail= $this->support_model->get_adminmail($type);
			$mail_subject= '*** Vultr support ticket ('.$type.' question): '.$subject;
			foreach ($adminmail as $data)
			{
				$param= array('email'=> $data->email, 'subject'=> $mail_subject, 'message'=> $message);
				$sendmail= vst_sendmail($param);
			}
			
			/*------------------Result------------------------------*/
			
			if($kq>=1 && $sendmail == true && $first_mess > 0)
			{
				redirect("support/ticket/$insert_id");
			}
			else
			{
				redirect('support/addnew');
			}
		}
			
	}
	
	
	function ticket($insert_id)
	{
		$this->form_validation->set_rules('reply', 'Reply', 'required|max_length[1000]|trim|xss_clean');
		
		
		if ($this->form_validation->run() == true)
		{
			$data['u_id']= $this->session->userdata('user_id');
			$data['c_id']= $insert_id;
			$data['m_content']= $this->input->post('reply');
			$data['m_date']= date('Y-m-d H:i:s');
			
			$data2['c_status']= 'opening';
			$param['c_id']= $insert_id;
		
			$kq= $this->support_model->addnew_message($data);
			$kq2= $this->support_model->reopen($data2, $param);
			if($kq > 0)
			{
				redirect(current_url());
			}
		}
		
		$message['info']= $this->support_model->conv_info($insert_id);
		$message['result']= $this->support_model->get_message($insert_id);
		$this->load->view('support/ticket_content_view', $message);	
	
	}
	
	
	function close_ticket($insert_id)
	{
		$data= array('c_id' => $insert_id);
		$result= $this->support_model->close_ticket($data);
		if ($result > 0)
		{
			redirect ('support/lists');
		}
	}
	
	
}