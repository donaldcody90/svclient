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
		$param['c.uid']= $id;
		$filterData= vst_filterData(array('filter_title'));
		
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
			$param_where= array('name' => 'Billing');
			$param_where2= array('name' => 'General');
			$data['billing']= $this->support_model->getCategory($param_where);
			$data['general']= $this->support_model->getCategory($param_where2);
			//print_r($data['general']);die;
			$this->load->view('support/addnew_ticket_view', $data);
		}
		else
		{
			
			/*-----------------Add new ticket---------------------*/
			
			$subject= $this->input->post('ticket-subject');
			$message= $this->input->post('ticket-message');
			$uid= $this->session->userdata('user_id');
			$openingdate= date('Y-m-d H:i:s');
			$status= 1;
			$category= $this->input->post('ticket-type');
			
			$data = array('uid'=>$uid, 'title'=>$subject, 'message'=>$message, 'openingdate'=>$openingdate, 'status'=>$status, 'caid'=> $category);
			$result = $this->support_model->addTicket($data);
			$kq = $result['lists']; 
			$insert_id= $result['insert_id'];
			
			/*----------------Add first message---------------------*/
			
			$data1['uid']= $this->session->userdata('user_id');
			$data1['cid']= $insert_id;
			$data1['content']= $message;
			$data1['date']= date('Y-m-d H:i:s');
			
			$first_mess= $this->support_model->addMessage($data1);
			
			/*-----------------Send mail---------------------------*/
			$adminmail=  $this->support_model->getAdminmail($category)['email'];
			
			$mail_subject= '*** Vultr support ticket : '.$subject;
			$param= array('email'=> $adminmail, 'subject'=> $mail_subject, 'message'=> $message);
			$sendmail= vst_sendmail($param);
			
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
		
		if ($this->form_validation->run() == false)
		{
			$param_where2= array('cid'=> $insert_id);
			$message['info']= $this->support_model->getConversationinfo($param_where2);
			$message['result']= $this->support_model->getMessage($insert_id);
			$this->load->view('support/ticket_content_view', $message);
		}
		if ($this->form_validation->run() == true)
		{
			$data['uid']= $this->session->userdata('user_id');
			$data['cid']= $insert_id;
			$data['content']= $this->input->post('reply');
			$data['date']= date('Y-m-d H:i:s');
			
			$param_where['cid']= $insert_id;
			$data2['status']= 1;
			
			// -----------------------
			
			$conv= $this->support_model->getConversationinfo($param_where);
			$category= $conv['caid'];
			$subject= $conv['title'];
			$content= $this->input->post('reply');
			$adminmail=  $this->support_model->getAdminmail($category)['email'];
			
			$mail_subject= '*** Vultr support ticket : '.$subject;
			
			if($conv['status'] == 0)
			{
				$param= array('email'=> $adminmail, 'subject'=> $mail_subject, 'message'=> $content);
				$sendmail= vst_sendmail($param);
			}
			
			// ------------------------------
			
		
			$kq= $this->support_model->addMessage($data);
			$kq2= $this->support_model->reopen($data2, $param_where);
			if($kq > 0)
			{
				redirect(current_url());
			}
		}
		
		
	
	}
	
	
	
	
	
}