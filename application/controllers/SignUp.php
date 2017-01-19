<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SignUp extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('helpers_model');
	}
	
	public function index() {
		$this->signup_page();
	}
	
	public function signup_page() {
		$data['title'] = 'Sign Up';
		$data['css'] = 'login.css';
		$data['header'] = 'Join V-Anime !';
		$this->load->view('signup_page', $data);
	}
	
	public function create_user() {
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]|max_length[15]|callback_check_if_username_exists|alpha_dash');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_check_if_email_exists');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
		$this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'trim|required|matches[password]');
		
		if($this->form_validation->run() == FALSE) {
			$this->signup_page();
		} else {
			$this->load->model('users_model');
			$query = $this->users_model->create_user();
			
			if($query) {
				$this->write_users_json(VERIFICATION_TOKEN);
				$data['account_created'] = "Your account has been created.<br/><br/> You may now log in";
				$data['title'] = 'Login';
				$data['css'] = 'login.css';
				$data['header'] = 'Please Login';
				$this->load->view('login_page', $data);
			} else {
				$this->load->view('signup_page');
			}
		}
	}
	
	public function create_facebook_user($fb_user_id, $fb_access_token, $fb_email="") {
 		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]|max_length[15]|callback_check_if_username_exists|alpha_dash');		
		if($fb_email == "") {
			 $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_check_if_email_exists');
			 $email = $this->input->post('email');
		} else {
			 $email = $fb_email;
		}
		
		if($this->form_validation->run() == FALSE) {
			if($fb_email == "") {
				$data['header'] = 'Choose your Username and Email';
			} else {
				$data['header'] = 'Choose your Username';
			}
			$data['fb_access_token'] = $fb_access_token;
			$data['fb_user_id'] = $fb_user_id;
			$data['fb_email'] = $fb_email;
			$data['title'] = "Sign Up";
			$data['css'] = 'login.css';
			$this->load->view('fb_user_signup', $data);
		} else {
			$this->load->model('users_model');			
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/me?access_token=' . $fb_access_token);
			$result = curl_exec($ch);
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			
			$result = json_decode($result);
			
			if(isset($result->id)) {				
				$query = $this->users_model->create_new_user_by_facebook_login($fb_user_id,$this->input->post('username'), $email, $fb_access_token);					
				if($query) {
					$this->write_users_json(VERIFICATION_TOKEN);
					$data = array(
							'id' => $query['id'],
							'username' => $query['username'],
							'is_logged_in' => true,
							'email' => $query['email'],
							'user_avatar' => $query['profile_image'],
							'fb_access_token' => $fb_access_token
					);
					$this->session->set_userdata($data);
					$this->users_model->update_user_activity();
					
					redirect("users/profile/{$query['username']}");
				} else {
					$this->signup_page();
				}				
			} else {
				$this->helpers_model->unauthorized_error();
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
	
	function write_users_json($verification_token=null) {
		if($verification_token === VERIFICATION_TOKEN) {
	
			$this->load->model('users_model');
	
			$result_array = $this->users_model->get_users_json_data();
	
			if($result_array) {
	
				$all_names = "";
					
				foreach ($result_array as $user) {
	
					$name = $user['username'];
					$image = $user['profile_image'];
	
					$result[] = array('name'=> $name, 'image'=> $image);
				}
	
				$fp = fopen('assets/json/autocomplete_users.json', 'w');
				fwrite($fp, json_encode($result));
				fclose($fp);
			}
		} else {
			$this->helpers_model->unauthorized();
		}
	}

}
	

?>