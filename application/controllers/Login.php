<?php
class Login extends CI_Controller {
	
	public function index() {
		$this->login_page(TRUE);
	}
	
	public function login_page($correct, $header = "Please Login") {
		$data['title'] = 'Login';
		$data['css'] = 'login.css';
		$data['javascript'] = 'home.js';
		$data['header'] = $header;		
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
	
	public function forgotten_password() {
		$data['title'] = 'V-Anime';
		$data['css'] = 'login.css';
		$data['javascript'] = 'home.js';
		$data['header'] = 'Forgot your password ?';
		$this->load->view('forgot_password_page', $data);
	}
	
	public function send_password_reset_link() {
		$this->load->library('form_validation');

		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_check_if_email_exists_forgot');
		
		if ($this->form_validation->run() == FALSE) {
			$this->forgotten_password();
		} else {
			$temp_pass = md5(uniqid());
			
			$this->load->library('email', array('mailtype'=>'html'));
			$this->email->from('velioocs@gmail.com', "V-Anime Reset Password");
			$this->email->to($this->input->post('email'));
			$this->email->subject("Reset your Password");
			
			$message = "<p>This email has been sent as a request to reset our password</p>";
			$message .= "<p><a href='".site_url("login/reset_password/$temp_pass")."'>Click here </a>if you want to reset your password,
			if not, then ignore</p>";
			
			$this->email->message($message);
			
			if($this->email->send()){
				$this->load->model('users_model');
				
				$query = $this->users_model->get_id_by_email($this->input->post('email'));
			
				if($query) {
					$user_id = $query['id'];
					$this->users_model->temp_reset_password($user_id, $temp_pass);
					$this->login_page(TRUE, "Email was sent to {$this->input->post('email')}. <br/>Follow the instructions in it to reset your password.");
				} else {
					$this->login_page(TRUE, "Failed to send email...");
				}				
			
			} else {
				$this->login_page(TRUE, "Failed to send email...");
			}
				
		}
	}
	
	public function reset_password($temp_pass){
	    $this->load->model('users_model');
	    $query = $this->users_model->is_temp_pass_valid($temp_pass);
	    if($query){
			$data['user_id'] = $query['user_id'];
	    	$data['title'] = 'V-Anime';
	    	$data['css'] = 'login.css';
	    	$data['javascript'] = 'home.js';
	    	$data['header'] = 'Reset your password';
	        $this->load->view('reset_password_page', $data);
	
	    } else{
	        $this->login_page(TRUE, "Link is invalid or has expired");
	    }
	
	}
	
	public function update_password($user_id) {
		  $this->load->library('form_validation');
		  $this->load->model('users_model');
		  
		  $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
		  $this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'trim|required|matches[password]');
		  
		  if($this->form_validation->run() == FALSE) {
		  	  $data['user_id'] = $user_id;
		  	  $data['title'] = 'V-Anime';
		  	  $data['css'] = 'login.css';
		  	  $data['javascript'] = 'home.js';
		  	  $data['header'] = 'Reset your password';
		  	  $this->load->view('reset_password_page', $data);
		  } else {
		  	  $password = md5($this->input->post('password'));
		  	  $query = $this->users_model->update_user_password($user_id, $password);
		  	  if($query) {
		  	  	  $this->users_model->delete_temp_pass($user_id);
		  	      $this->login_page(TRUE, "You successfully changed your password !");
		  	  }
		  }
	}
	
	public function check_if_email_exists_forgot($requested_email) {
		$this->load->model('users_model');
	
		$email_not_exist = $this->users_model->check_if_email_exists($requested_email);
	
		if(!$email_not_exist) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function nocache() {
	    $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
	    $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
	    $this->output->set_header('Pragma: no-cache');
	    $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	}
	
}

?>