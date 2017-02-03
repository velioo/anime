<?php

defined('BASEPATH') OR exit('No direct script access allowed');

Class Notifications_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	function get_notifications($limit=null, $offset=null) {
		$user_id = $this->session->userdata('id');
		$this->db->select('nu.seen,u.username,u.profile_image, n.*');
		$this->db->join('notifications as n', 'n.id=nu.notification_id');
		$this->db->join('users as u', 'u.id=n.creator_id');
		$this->db->where("nu.user_id = {$user_id}");
		$this->db->order_by("nu.seen", "asc");
		$this->db->order_by("n.created_at", "desc");
		if($limit != NULL) {
			if($offset != NULL) {
				$this->db->limit($limit, $offset);				
			} else {
				$this->db->limit($limit);
			}		
		}
		$query = $this->db->get('notification_users as nu');
		return $query->result_array();;
	}
	
	function get_unseen_count() {
		$user_id = $this->session->userdata('id');
		$query = $this->db->get_where('notification_users', array('user_id' => $user_id, 'seen' => 0));
		return $query->num_rows();
	}
	
	function get_all_count() {
		$user_id = $this->session->userdata('id');
		$query = $this->db->get_where('notification_users', array('user_id' => $user_id));
		return $query->num_rows();
	}
	
	function add_notification($source_id, $description, $type, $additional_info="") {		
		$creator_id = $this->session->userdata('id');		
		$query = $this->db->insert('notifications', array('creator_id' => $creator_id, 'source_id' => $source_id, 
														  'description' => $description, 'type' => $type, 
														  'additional_info' => $additional_info));		
		return $this->db->insert_id();
	}
	
	function mark_as_read() {
		$user_id = $this->session->userdata('id');
		$this->db->where('user_id', $user_id);
		$this->db->where('seen', 0);
		$this->db->update('notification_users', array('seen' => 1));
	}

	function spread_notification($notification_id, $user_id) {
		$query = $this->db->insert('notification_users', array('notification_id' => $notification_id, 'user_id' => $user_id));
		return $query;
	}
	
	function delete_notifications($post_id, $type, $additional_info="") {
		$this->db->delete('notifications', array('source_id' => $post_id, 'type' => $type, 'additional_info' => $additional_info));
	}
}

?>