<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Posts_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	function get_total_posts($wall_owner) {
		$this->db->where('wall_owner', $wall_owner);
		$this->db->select('id');
		$query = $this->db->get('posts');
		return $query->num_rows();
	}
	
	function get_posts($wall_owner, $limit, $offset = 0) {
		$query = $this->db->query("SELECT p.id, p.post_owner, p.content, p.created_at, p.updated_at, up.username, up.profile_image FROM posts as p 
										JOIN users as u ON u.id=p.wall_owner 
										JOIN users as up ON up.id=p.post_owner WHERE wall_owner = {$wall_owner} ORDER BY created_at DESC LIMIT {$limit} OFFSET {$offset}");
		return $query->result_array();
	}
	
	function get_post_comments($post_id) {
		$query = $this->db->query("SELECT pc.id, pc.content, pc.created_at, pc.updated_at, u.username, u.profile_image FROM post_comments as pc JOIN posts ON posts.id=pc.post_id
															JOIN users as u ON u.id=pc.commenter WHERE post_id = {$post_id} ORDER BY created_at ASC");
		return $query->result_array();
	}
	
	function add_post($wall_owner, $content) {		
		$data = array(
				'wall_owner' => $wall_owner,
				'post_owner' => $this->session->userdata('id'),
				'content' => $content
		);		
		$query = $this->db->insert('posts', $data);		
		if($query) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	function edit_post($post_id, $content) {
		$this->db->where('id', $post_id);
		$query = $this->db->update('posts', array('content' => $content));		
		return $query;
	}
	
	function delete_post($post_id) {
		$this->db->where('id', $post_id);		
		$query = $this->db->delete('posts');	
		return $query;
	}
	
	function add_comment($post_id, $content) {
		$data = array(
				'commenter' => $this->session->userdata('id'),
				'post_id' => $post_id,
				'content' => $content
		);		
		$query = $this->db->insert('post_comments', $data);		
		return $query;
	}
	
	function edit_comment($comment_id, $content) {	
		$this->db->where('id', $comment_id);
		$query = $this->db->update('post_comments', array('content' => $content));		
		return $query;
	}
	
	function delete_comment($comment_id) {
		$this->db->where('id', $comment_id);
		$query = $this->db->delete('post_comments');
		return $query;
	}
	
}

?>