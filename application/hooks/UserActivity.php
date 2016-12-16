<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserActivity {
	
	private $CI;
	
	function __construct() {
		$this->CI = & get_instance();
	
		if(!isset($this->CI->session)){  //Check if session lib is loaded or not
			$this->CI->load->library('session');  //If not loaded, then load it here
		}
	}
	
	public function update_user_activity() {		
  		if($this->CI->session->userdata('id')) {
  			$this->CI->load->model('users_model');		
			$this->CI->users_model->update_user_activity();
		} 
	}
}

?>