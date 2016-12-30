<?php

defined('BASEPATH') OR exit('No direct script access allowed');

Class Follows_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	function follow($following_id) {
		$follower_id = $this->session->userdata('id');
		$query = $this->db->get_where('followers', array('follower_id' => $follower_id, 'following_id' => $following_id));
		if($query->num_rows() == 0) {
			$query = $this->db->insert('followers', array('follower_id' => $follower_id, 'following_id' => $following_id));		
			if($query) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
	
	function unfollow($following_id) {
		$follower_id = $this->session->userdata('id');
		$query = $this->db->delete('followers', array('follower_id' => $follower_id, 'following_id' => $following_id));
		if($query) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function get_followers($following_id) {
		$this->db->select('users.id,users.username,users.profile_image');
		$this->db->join('users', 'users.id=followers.follower_id');
		$this->db->where('following_id', $following_id);
		$query = $this->db->get_where('followers');
		
		if($query) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}
	
	function get_following($follower_id) {
		$this->db->select('users.id,users.username,users.profile_image');
		$this->db->join('users', 'users.id=followers.following_id');
		$this->db->where('follower_id', $follower_id);
		$query = $this->db->get_where('followers');
		
		if($query) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}
}

?>