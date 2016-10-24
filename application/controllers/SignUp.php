<?php

class SignUp extends CI_Controller {
	
	public function index() {
		$this->signup_page();
	}
	
	public function signup_page() {
		$data['title'] = 'Sign Up';
		$data['css'] = 'login.css';
		$data['javascript'] = 'home.js';
		$data['header'] = 'Join V-Anime !';
		$this->load->view('signup_page', $data);
	}
	
	public function create_user() {
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]|max_length[15]|callback_check_if_username_exists');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_check_if_email_exists');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
		$this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'trim|required|matches[password]');
		
		if($this->form_validation->run() == FALSE) {
			$this->signup_page();
		} else {
			$this->load->model('users_model');
			
			if($query = $this->users_model->create_user()) {
				$data['account_created'] = "Your account has been created.<br/><br/> You may now log in";
				$data['title'] = 'Login';
				$data['css'] = 'login.css';
				$data['javascript'] = "";
				$data['header'] = ' ';
				$this->load->view('login_page', $data);
			} else {
				$this->load->view('signup_page');
			}
		}
	}
	
	public function check_if_username_exists($requested_username) {
		$this->load->model('users_model');
		
		$username_available = $this->users_model->check_if_username_exists($requested_username);
		
		if($username_available) {
			return TRUE;
		} else {
			return FALSE;
		}
	
	}
	
	public function check_if_email_exists($requested_email) {
		$this->load->model('users_model');
		
		$email_available = $this->users_model->check_if_email_exists($requested_email);
		
		if($email_available) {
			return TRUE;
		} else {
			return FALSE;
		}
	
	}	
}
	

?>