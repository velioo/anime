<?php
class Login extends CI_Controller {
	
	public function index() {
		$this->login_page(TRUE);
	}
	
	public function login_page($correct) {
		$data['title'] = 'Login';
		$data['css'] = 'login.css';
		$data['javascript'] = 'home.js';
		$data['header'] = 'Please, Login';		
		if($correct == FALSE) 
		 	$data['incorrect'] = 'Username or password is incorrect !';
		
		$this->load->view('login_page', $data);
	}
	
	function write_data($username) {
		$data['title'] = $username . '\'s profile';
		$data['css'] = 'user.css';
		$data['javascript'] = 'home.js';
		$data['header'] = $username;
		return $data;
	}
	
	public function log_in() {
		
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		
		if ($this->form_validation->run() == FALSE) {
			if(isset($this->session->userdata['is_logged_in'])) {
				$this->profile($this->session->userdata['username']);
			} else{
				$this->login_page(FALSE);
			}
		} else {
		
			$this->load->model('users_model');
			$query = $this->users_model->validate();
			if($query) {
				
				$username = $this->input->post('username');
				$result = $this->users_model->get_user_info_logged($username);
				if ($result != false) {
					$data = array(
							'id' => $result['id'],
							'username' => $result['username'],
							'is_logged_in' => true
					);
					
					$this->session->set_userdata($data);
					redirect('home');
				}
			} else {
				$this->login_page(FALSE);
			}
		}
	}
	
	public function logout() {
		if(isset($this->session->userdata['is_logged_in'])) {
			$this->session->sess_destroy();
		} else {
			$data['message_display'] = 'You are not logged in ';
			redirect('home');
		}
		$this->nocache();
		redirect('home');
	}
	
	public function profile($username) {
		$this->load->model('users_model');
		$data = $this->write_data($username);
		if((isset($this->session->userdata['is_logged_in'])) and ($this->session->userdata['username'] == $username)) {
			$query = $this->users_model->get_user_info_logged($username);
			$this->nocache();
			$data['results'] = $query;
		} else {
			$query = $this->users_model->get_user_info($username);
			$data['results'] = $query;
		}
		$this->load->view('user_page', $data);
	}
	
	function nocache() {
	    $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
	    $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
	    $this->output->set_header('Pragma: no-cache');
	    $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	}
	
}

?>