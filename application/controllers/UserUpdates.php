<?php
class UserUpdates extends CI_Controller {
	
	function update_profile() {
		
		$this->load->model('users_model');
		$this->load->library('upload');
		
	    $config['upload_path']          = './assets/user_cover_images/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 2048;
        $config['max_width']            = 4000;
        $config['max_height']           = 2250;
        $config['file_name'] = $this->session->userdata['id'] . ".jpg";
        $config['overwrite'] = TRUE;
        
        $this->upload->initialize($config);

        $offset = $this->input->post('top_offset');
        
        $this->users_model->update_cover_offset($this->session->userdata['id'], $offset);
        
        if (!$this->upload->do_upload('edit_cover')) {
        	$error = array('error' => $this->upload->display_errors('<p class="error">', '</p>'));
        	$this->session->set_flashdata('error',$error['error']);
        } else {
        	$query = $this->users_model->update_cover_image($this->session->userdata['id'],  $config['file_name']);
         }       
         
         
         $config['upload_path']          = './assets/user_profile_images/';
         $config['allowed_types']        = 'gif|jpg|png';
         $config['max_size']             = 2048;
         $config['max_width']            = 2000;
         $config['max_height']           = 2000;
         $config['file_name'] = $this->session->userdata['id'] . ".jpg";
         $config['overwrite'] = TRUE;
         
         $this->upload->initialize($config);     
         
         if (!$this->upload->do_upload('edit_avatar')) {
         	$error = array('error_a' => $this->upload->display_errors('<p class="error_a">', '</p>'));
         	$this->session->set_flashdata('error_a',$error['error_a']);
         } else {
         	$query = $this->users_model->update_avatar_image($this->session->userdata['id'],  $config['file_name']);
         }         
         
         redirect("login/profile/{$this->session->userdata['username']}");
	}
	
	public function update_user_info() {
		$this->load->model('users_model');		
		$bio = $_POST['textAreaValue']; 
		$age = $_POST['age'];
		$gender = $_POST['gender'];
		$location = $_POST['location'];
		
		$this->users_model->update_user_info($this->session->userdata['id'], $bio, $age, $gender, $location);

	}
	
	public function update_user_account_info() {		
		$this->load->library('form_validation');
		
		$username = $this->session->userdata['username'];
		$email = $this->session->userdata['email'];
		$password = "";
		
		if($this->input->post('username') != $username) {
			$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]|max_length[15]|callback_check_if_username_exists|alpha_dash');
			$username = $this->input->post('username');
		}

		
		if($this->input->post('email') != $email) {
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_check_if_email_exists');
			$email = $this->input->post('email');
		}
		
		if($this->input->post('password') != "") {
			$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
			$this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'trim|required|matches[password]');
			$password = $this->input->post('password');
		}		
		
		if($this->form_validation->run() == FALSE) {
			$this->user_settings();
		} else {
			$this->load->model('users_model');
				
			if(($username != $this->session->userdata['username']) or ($email != $this->session->userdata['email']) or $password != "") {
				if($password != "")
					$password = hash('sha256', $password);
					$result = $this->users_model->update_user_acc_info($this->session->userdata['id'], $username, $email, $password);
					
				if ($result) {
					$data = array(
							'id' => $result['id'],
							'username' => $result['username'],
							'email' => $result['email'],
							'is_logged_in' => true
					);
					
					$is_admin = $this->users_model->check_if_user_is_admin($result['id']);
						
					if($is_admin) {
						$data['admin'] = TRUE;
					}
						
					$this->session->set_userdata($data);
				}
			} 

			$this->nocache();
			redirect("login/profile/{$this->session->userdata['username']}");
		}
		
		
	}
	
	public function user_settings($fb_message = "") {
		$this->load->model('users_model');
		$query = $this->users_model->get_user_info_logged($this->session->userdata['username']);
		$is_facebook_connected = $this->users_model->check_if_user_connected_to_fb($this->session->userdata['id']);
		if($query) {
			if($is_facebook_connected) {
				$data['is_fb_connected'] = "Disconnect Facebook";
			} else {
				$data['is_fb_connected'] = "Connect Facebook";
			}
			$data['username'] = $query['username'];
			$data['email'] = $query['email'];
			$data['fb_message'] = $fb_message;
			$data['title'] = 'Settings';
			$data['css'] = 'user_settings.css';
			$data['javascript'] = 'home.js';
			$data['header'] = "Settings";
			$this->load->view('user_account_settings', $data);
		} else {
			echo "There was an internal error";
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
	
	function nocache() {
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	}
	
}
	
?>