<?php
class Emails extends CI_Controller {
	
	public function index() {
		redirect("home");
	}
	
	public function send_password_reset_link() {
		$this->load->library('form_validation');
	
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_check_if_email_exists_forgot');
	
		if ($this->form_validation->run() == FALSE) {
			$this->forgotten_password();
		} else {
			$temp_pass = md5(uniqid());
				
			$this->load->library('email', array('mailtype'=>'html'));
			$this->email->from('vanime.staff@gmail.com', "V-Anime Reset Password");
			$this->email->to($this->input->post('email'));
			$this->email->subject("Reset your Password");
				
			$message = "<p>This email has been sent as a request to reset our password</p>";
			$message .= "<p><a href='".site_url("userUpdates/reset_password/$temp_pass")."'>Click here </a>if you want to reset your password,
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
					$this->login_page(TRUE, "There was an internal error...");
				}
					
			} else {
				$this->login_page(TRUE, "Failed to send email...");
			}
	
		}
	}
	
}

?>