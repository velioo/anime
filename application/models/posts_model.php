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
		$this->db->select('p.id, p.post_owner, p.content, p.created_at, p.updated_at, up.username, up.profile_image');
		$this->db->join('users as u', 'u.id=p.wall_owner');
		$this->db->join('users as up', 'up.id=p.post_owner');
		$this->db->where('wall_owner', $wall_owner);
		$this->db->order_by('created_at', 'DESC');
		$this->db->limit($limit, $offset);
		$query = $this->db->get('posts as p');
		return $query->result_array();
	}
	
	function get_post($post_id) {
		$this->db->select('posts.*,users.id as user_id,users.username, users.profile_image');
		$this->db->join('users', 'users.id=posts.post_owner');
		$post = $this->db->get_where('posts', array('posts.id' => $post_id))->row_array();
		if($post) {
			$post_comments = $this->get_post_comments($post_id);		
			$post['comments'] = $post_comments;		
			return $post;
		} else {
			return FALSE;
		}
	}
	
	function get_post_comments($post_id) {
		$this->db->select('pc.id, pc.content, pc.created_at, pc.updated_at, u.username, u.profile_image');
		$this->db->join('posts', 'posts.id=pc.post_id');
		$this->db->join('users as u', 'u.id=pc.commenter');
		$this->db->where('post_id', $post_id);
		$this->db->order_by('created_at', 'ASC');
		$query = $this->db->get('post_comments as pc');
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
		
		return $this->db->insert_id();
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
	
	function get_post_owner($post_id) {
		$query = $this->db->get_where('posts', array('id' => $post_id));		
		return $query->row_array()['post_owner'];	
	}
	
	function get_post_from_comment($comment_id) {
		$query = $this->db->get_where('post_comments', array('id' => $comment_id));	
		return $query->row_array()['post_id'];
	}
}

?>