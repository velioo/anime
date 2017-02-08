<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Notifications extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('helpers_model');
		$this->load->model('notifications_model');
	}
	
	public function load_notifications() {	
		
		$limit = $this->input->post('limit');
		$group_number = $this->input->post('group_number');	
		$first_load = $this->input->post('first_load');
		
		if($group_number == NULL) {
			$notifications = $this->notifications_model->get_notifications($limit);
		} else {
			$offset = ceil($group_number * $limit);
			$notifications = $this->notifications_model->get_notifications($limit, $offset);			
		}							
		
		$unseen_count = $this->notifications_model->get_unseen_count();	
		
		echo "<span>$unseen_count</span>";
		
		if($unseen_count != 0 || $first_load == 1 || $group_number != NULL) {
		
			$elements = array();			
			$link = "";		
			$types = array('post', 'user', 'post_comment');
			
			foreach($notifications as $notification) {	
				
				$link = "";
				
				$current_time = $date = date('Y-m-d H:i:s'); 
				$current_time = strtotime($current_time);
				$date_created = strtotime($notification['created_at']);
				
				$time_difference = $current_time - $date_created;
				
				if($time_difference < 60) {
					$time_ago = $time_difference . " seconds ago";
				} else if($time_difference >= 60 && $time_difference < 3600) {
					$time_ago = round($time_difference / 60);
					if($time_ago == 1) {
						$time_ago.=" minute ago";
					} else {
						$time_ago.=" minutes ago";
					}
				} else if($time_difference >= 3600 && $time_difference < 86400) {
					$time_ago = round($time_difference / 3600);
					if($time_ago == 1) {
						$time_ago.=" hour ago";
					} else {
						$time_ago.=" hours ago";
					}
				} else {
					$time_ago = round(($time_difference / (3600 * 24)));
					if($time_ago == 1) {
						$time_ago.=" day ago";
					} else {
						$time_ago.=" days ago";
					}
				}
	
				switch($notification['type']) {
					case $types[0]:
						$link = site_url("posts/post/{$notification['source_id']}");
						break;
					case $types[1]:
						$link = site_url("users/profile/{$notification['username']}");
						break;
					case $types[2]:
						$link = site_url("posts/post/{$notification['source_id']}");
						break;
				}
				
				$element ='<a href="' . $link .'" class="disable-link-decoration"> <div class="notification">
								<div class="user_image_div notify"><img src="' . asset_url() . "user_profile_images/{$notification['profile_image']}" . '" class="user_image"></div>
								<div class="wrap_notification_username_description">
										<span class="notification_username">&nbsp;' . $notification['username'] . '&nbsp;</span><span class="description">'. $notification['description'] .'</span>
										<span class="ago">' . $time_ago .'</span>
								</div>
							</div></a>';			
				$elements[] = $element;
			}		
					
			foreach($elements as $element) {
				echo $element;
			}
		}
	}
	
	public function add_notification() {		
		if($this->session->userdata('is_logged_in')) {			
			$source_id = $this->input->post('source_id');
			$description = $this->input->post('description');
			$type = $this->input->post('type');		
			
			$description = addslashes($description);	
			
			$this->notifications_model->add_notification($source_id, $description, $type);
			
		} else {
			$this->helpers_model->bad_request();
		}		
	}
	
	public function mark_as_read() {		
		$this->notifications_model->mark_as_read();	
	}
	
	public function all_notifications() {
		if($this->session->userdata('is_logged_in')) {
			
			$total_notifications = $this->notifications_model->get_all_count();
			$notifications_per_page = 15;
			$data['total_groups'] = ceil($total_notifications/$notifications_per_page);
			
			$data['title'] = 'V-Anime';
			$data['css'] = 'all_notifications.css';
			$this->load->view('all_notifications', $data);
		} else {
			$this->helpers_model->unauthorized();
		}
	}
	
	

}
?>