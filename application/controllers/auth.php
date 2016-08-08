<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller {
	public function __construct(){
		parent::__construct();
		
	}
	
	function index()
	{
		$this->login();
	}
	

	
	//-------------Login------------------
	
	
	function login()
	{
		$this->form_validation->set_rules('username', 'Username', 'required|trim|max_length[50]|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'required|trim|max_length[50]|xss_clean');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('auth/login_view');
		}
		else
		{
			extract($_POST);
			
			$user_id= $this->Auth_model->check_login($username, $password)->id;
			
			if(! $user_id)
			{
				//login failed
				$this->session->set_flashdata('login_error', TRUE);
				redirect('auth/login');
			}
			else
			{
				$username= $this->Auth_model->check_login($username, $password)->username;
				$this->session->set_userdata(array(
									'logged_in'=> true,
									'user_id' => $user_id,
									'username' => $username
								));
				redirect('users');
			}
		}
	}
	
	
	//---------------------Signup--------------------
	
	
	function signup()
	{
		$this->form_validation->set_rules('firstname', 'First name', 'required|min_length[2]|max_length[20]|trim|xss_clean');
		$this->form_validation->set_rules('lastname', 'Last name', 'required|min_length[2]|max_length[20]|trim|xss_clean');
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
			$data['firstname']= $_POST['firstname'];
			$data['lastname']= $_POST['lastname'];
			$data['username']= $_POST['username'];
			$data['password']= hash('sha512', $_POST['password']);
			$data['email']= $_POST['email'];
			
			$result= $this->auth_model->add_user($data);
			
			if ($result == false)
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
