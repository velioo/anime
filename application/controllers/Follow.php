<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Follow extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('helpers_model');
		$this->load->model('follows_model');
	}
	
	public function followers($username=null) {		
		if($username != null) {
			$this->load->model('users_model');
			if((isset($this->session->userdata['is_logged_in'])) and ($this->session->userdata['username'] == $username)) {
				$query = $this->users_model->get_user_info_logged($username);
			} else {
				$query = $this->users_model->get_user_info($username);
				if(!$query) {
					$this->helpers_model->page_not_found();
				}
			}
				
			$data['user'] = $query;
			
			$data['users'] = $this->follows_model->get_followers($query['id']);
			$data['users_count'] = count($data['users']);
			
			$data['title'] = 'V-Anime';
			$data['header'] = 'Followers';
			$data['css'] = 'follow.css';
			$data['additional_css'] = 'followers.css';
			$this->load->view('follow_page', $data);
		} else {
			$this->helpers_model->page_not_found();
		}
	}
	
	public function following($username=null) {		
		if($username != null) {
			$this->load->model('users_model');
			if((isset($this->session->userdata['is_logged_in'])) and ($this->session->userdata['username'] == $username)) {
				$query = $this->users_model->get_user_info_logged($username);
			} else {
				$query = $this->users_model->get_user_info($username);
				if(!$query) {
					$this->helpers_model->page_not_found();
				}
			}
		
			$data['user'] = $query;
			
			$data['users'] = $this->follows_model->get_following($query['id']);
			$data['users_count'] = count($data['users']);
			
			$data['title'] = 'V-Anime';
			$data['header'] = 'Following';
			$data['css'] = 'follow.css';
			$data['additional_css'] = 'following.css';
			$this->load->view('follow_page', $data);
		} else {
			$this->helpers_model->page_not_found();
		}
	}
	
	public function follow_user() {
		if($this->session->userdata('is_logged_in')) {
			$following_id = $this->input->post('following_id');
			$query = $this->follows_model->follow($following_id);
			
			if($query) {
				
				$this->load->model('notifications_model');
				$description = "followed you.";
				$notification_id = $this->notifications_model->add_notification($this->session->userdata('id'), $description, 'user');
				$this->notifications_model->spread_notification($notification_id, $following_id);
				
				echo "Success";
			} else {
				echo "Fail";
			}
		}
	}
	
	public function unfollow_user() {
		if($this->session->userdata('is_logged_in')) {
			$following_id = $this->input->post('following_id');
			$query = $this->follows_model->unfollow($following_id);
				
			if($query) {
				
				$this->load->model('notifications_model');
				$description = "unfollowed you.";
				$notification_id = $this->notifications_model->add_notification($this->session->userdata('id'), $description, 'user');
				$this->notifications_model->spread_notification($notification_id, $following_id);			
				
				echo "Success";
			} else {
				echo "Fail";
			}
		}
	}
	
}

?>