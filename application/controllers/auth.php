<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('users_model');
	}
	
	function index()
	{
		$this->login();
	}
	

	
	//-------------Login------------------
	
	
	function login()
	{
		$username = $this->input->post('username');
        $password = $this->input->post('password');
		
		if ($this->input->post('btn_login'))
		{
			$data= $this->users_model->findUser( array('username'=> $username, 'password'=> vst_password($password)) );
			if(count($data)){
				$user_id= $data['id'];
				$this->session->set_userdata(array(
								'logged_in'=> true,
								'user_id' => $user_id,
								'username' => $username
							));
				redirect('billing');
			}
			else{
				$this->session->set_flashdata('login_error', TRUE);
				redirect(site_url('auth/login'));
			}  				
	  
		}
		
		$this->load->view('auth/login_view');
	
	}
	
	
	//---------------------Signup--------------------
	
	
	function signup()
	{
		$this->form_validation->set_rules('fullname', 'First name', 'required|min_length[2]|max_length[20]|trim|xss_clean');
		$this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric|min_length[3]|max_length[20]|is_unique[users.Username]|trim|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|trim|xss_clean');
		$this->form_validation->set_rules('passconf', 'Password Confirm', 'required|matches[password]|min_length[6]|trim|xss_clean');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.Email]|trim|xss_clean');
		
		$this->form_validation->set_message('is_unique', 'This %s is already registered.');
		$this->form_validation->set_message('matches', 'That is not the same password as the first one.');
		
		if ($this->form_validation->run() == false)
		{
			$this->load->view('auth/signup_view');
		}
		else
		{
			$data['fullname']= $this->input->post('fullname');
			$data['username']= $this->input->post('username');
			$data['password']= vst_password($this->input->post('password'));
			$data['email']= $this->input->post('email');
			
			$result= $this->auth_model->addUser($data);
			
			if ($result == 1)
			{
				$this->session->set_flashdata('signup_error', TRUE);
				redirect('auth/signup');
			}
			else
			{
				$this->session->set_flashdata('signup_success', TRUE);
				redirect('auth/login');
			}
		}
	}
	

	
	
	
	//----------------------Logout-------------------
	
	
	function logout()
	{
		$this->session->sess_destroy();
		redirect('auth/login');

	}
	
}

?>
