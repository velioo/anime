<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Actors extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('actors_model');
		$this->load->model('helpers_model');
	}

	public function index() {
		redirect("home");
	}
	
	public function actor($actor_id=NULL, $name=NULL) {		
		if($actor_id != NULL && is_numeric($actor_id)) {
		
			$this->load->model('actors_model');
		
			$actor = $this->actors_model->get_actor($actor_id);
			
			if($actor !== FALSE) {
				$info_text = stripslashes($actor['info']);
				$info_text = format_info($info_text);
		
				$users_per_page = 25;
				$data['total_love_groups'] = ceil($actor['actor_love_count']/$users_per_page);
				$data['total_hate_groups'] = ceil($actor['actor_hate_count']/$users_per_page);
		
				$actor['info'] = $info_text;
				$data['actor'] = $actor;
				$data['title'] = 'V-Anime';
				$data['css'] = 'actor.css';
				$this->load->view('actor', $data);
			} else {
				$this->helpers_model->page_not_found();
			}
		} else {
			$this->helpers_model->bad_request();
		}		
	}
	
	public function loves($username=NULL) {
		if($username != NULL) {
				
			$this->load->model('actors_model');
			$this->load->model('users_model');
			$this->load->library('pagination');
				
			if((isset($this->session->userdata['is_logged_in'])) and ($this->session->userdata['username'] == $username)) {
				$query = $this->users_model->get_user_info_logged($username);
			} else {
				$query = $this->users_model->get_user_info($username);
				if($query === FALSE) {
					$this->helpers_model->page_not_found();
				}
			}
			$data['user'] = $query;
				
			$config = $this->configure_pagination();
				
			$config['base_url'] = site_url("actors/loves/{$username}");
			$config['per_page'] = 25;
				
			if($this->input->get('page') != NULL and is_numeric($this->input->get('page'))) { //calculate the offset for next page
				$start = $this->input->get('page') * $config['per_page'] - $config['per_page'];
			} else {
				$start = 0;
			}
				
			$status = 1;
			$query = $this->actors_model->get_user_actors($data['user']['id'], $status, $config['per_page'], $start);
			$config['total_rows'] = $this->actors_model->get_user_actors_count($data['user']['id'], $status);
				
			if($query !== FALSE) {
				$this->pagination->initialize($config);
				$data['pagination'] = $this->pagination->create_links();
				$data['actors'] = $query;
			}
				
			$data['title'] = 'V-Anime';
			$data['css'] = 'search_actors.css';
			$data['additional_css'] = 'loves.css';
			$data['header'] = 'Loved Actors';
			$data['status'] = 'LOVE';
			$this->load->view('user_actors', $data);
		} else {
			$this->helpers_model->bad_request();
		}
	}
	
	public function hates($username=NULL) {
		if($username != NULL) {
				
			$this->load->model('actors_model');
			$this->load->model('users_model');
			$this->load->library('pagination');
				
			if((isset($this->session->userdata['is_logged_in'])) and ($this->session->userdata['username'] == $username)) {
				$query = $this->users_model->get_user_info_logged($username);
			} else {
				$query = $this->users_model->get_user_info($username);
				if($query === FALSE) {
					$this->helpers_model->page_not_found();
				}
			}
			$data['user'] = $query;
				
			$config = $this->configure_pagination();
		
			$config['base_url'] = site_url("actors/hates/{$username}");
			$config['per_page'] = 25;
				
			if($this->input->get('page') != NULL and is_numeric($this->input->get('page'))) { //calculate the offset for next page
				$start = $this->input->get('page') * $config['per_page'] - $config['per_page'];
			} else {
				$start = 0;
			}
				
			$status = 0;
			$query = $this->actors_model->get_user_actors($data['user']['id'], $status, $config['per_page'], $start);
			$config['total_rows'] = $this->actors_model->get_user_actors_count($data['user']['id'], $status);
				
			if($query !== FALSE) {
				$this->pagination->initialize($config);
				$data['pagination'] = $this->pagination->create_links();
				$data['actors'] = $query;
			}
		
			$data['title'] = 'V-Anime';
			$data['css'] = 'search_actors.css';
			$data['additional_css'] = 'hates.css';
			$data['header'] = 'Hated Actors';
			$data['status'] = 'HATE';
			$this->load->view('user_actors', $data);
		} else {
			$this->helpers_model->bad_request();
		}
	}
	
	public function load_actors() {		
	}
	
	public function load_actor_users_statuses($actor_id) {
		if($actor_id != NULL and is_numeric($actor_id)) {
		
			$group_number = $this->input->post('group_number');
			$status = $this->input->post('status');
			$users_per_page = 25;
			$offset = ceil($group_number * $users_per_page);
		
			$users = $this->actors_model->get_all_actor_users_statuses($actor_id, $status, $users_per_page, $offset);
				
			$elements = array();
				
			foreach($users as $user) {
				$element = "<tr>
					 	<td><a href=" . site_url("users/profile/{$user['username']}") . " class='disable-link-decoration red-text'>{$user['username']}</a></td>
							 	</tr>";
				$elements[] = $element;
			}
				
			foreach($elements as $element) {
				echo $element;
			}
				
		} else {
			$this->helpers_model->bad_request();
		}
	}
	
	public function change_actor_user_status() {
		$actor_id = $this->input->post('actor_id');
		$status = $this->input->post('status');
		
		if($this->session->userdata('is_logged_in')) {
			if($actor_id != NULL && $status != NULL) {
				$this->load->model('actors_model');
				$query = $this->actors_model->change_actor_user_status($actor_id, $status);
		
				if($query !== FALSE) {
					echo "Success";
				} else {
					echo "Fail";
				}
			} else {
				$this->helpers_model->bad_request();
			}
		} else {
			if(isset($status)) {
				echo "401";
			} else {
				$this->helpers_model->unauthorized();
			}
		}
	}
	
	function configure_pagination() {
		$config['num_links'] = 10;
		$config['use_page_numbers'] = TRUE;
		$config['page_query_string'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		$config['query_string_segment'] = 'page';
		$config['full_tag_open'] = "<ul class='pagination'>";
		$config['full_tag_close'] ="</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tagl_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";
	
		return $config;
	}
	
}

?>