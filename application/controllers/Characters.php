<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Characters extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('characters_model');
		$this->load->model('animes_model');
		$this->load->model('helpers_model');
	}
	
	public function index() {
		redirect("home");
	}
	
	public function character($character_id=null, $name=null) {	
		if($character_id != null && is_numeric($character_id)) {
				
			$this->load->model('characters_model');
				
			$character = $this->characters_model->get_character($character_id);	
			
			if($character) {
				$info_text = stripslashes($character['info']);								
				$info_text = format_info($info_text);

				$users_per_page = 25;
				$data['total_love_groups'] = ceil($character['character_love_count']/$users_per_page);
				$data['total_hate_groups'] = ceil($character['character_hate_count']/$users_per_page);
						
				$character['info'] = $info_text;
				$data['character'] = $character;
				$data['title'] = 'V-Anime';
				$data['css'] = 'character.css';
				$this->load->view('character', $data);
			} else {
				$this->helpers_model->page_not_found();
			}
		} else {
			$this->helpers_model->bad_request();
		}
	}
	
	public function loves($username=null) {
		if($username != null) {
			
			$this->load->model('characters_model');
			$this->load->model('users_model');		
			$this->load->library('pagination');
			
			if((isset($this->session->userdata['is_logged_in'])) and ($this->session->userdata['username'] == $username)) {
				$query = $this->users_model->get_user_info_logged($username);
			} else {
				$query = $this->users_model->get_user_info($username);
				if(!$query) {
					$this->helpers_model->page_not_found();
				}
			}				
			$data['user'] = $query;
					
			$config = $this->configure_pagination();
			
			$config['base_url'] = site_url("characters/loves/{$username}");
			$config['per_page'] = 25;
			
			if($this->input->get('page') != NULL and is_numeric($this->input->get('page'))) { //calculate the offset for next page
				$start = $this->input->get('page') * $config['per_page'] - $config['per_page'];
			} else {
				$start = 0;
			}
			
			$status = 1;			
			$query = $this->characters_model->get_user_characters($data['user']['id'], $status, $config['per_page'], $start, TRUE);
			$config['total_rows'] = $this->characters_model->get_user_characters_count($data['user']['id'], $status);
			
			if($query) {
				$this->pagination->initialize($config);
				$data['pagination'] = $this->pagination->create_links();
				$data['characters'] = $query;
			}
			
			$data['title'] = 'V-Anime';
			$data['css'] = 'search_characters.css';
			$data['additional_css'] = 'loves.css';
			$data['header'] = 'Loved Characters';
			$data['status'] = 'LOVE';
			$this->load->view('user_characters', $data);
		} else {
			$this->helpers_model->bad_request();
		}
	}
	
	public function hates($username=null) {
		if($username != null) {
			
			$this->load->model('characters_model');
			$this->load->model('users_model');
			$this->load->library('pagination');
			
			if((isset($this->session->userdata['is_logged_in'])) and ($this->session->userdata['username'] == $username)) {
				$query = $this->users_model->get_user_info_logged($username);
			} else {
				$query = $this->users_model->get_user_info($username);
				if(!$query) {
					$this->helpers_model->page_not_found();
				}
			}				
			$data['user'] = $query;
			
			$config = $this->configure_pagination();
				
			$config['base_url'] = site_url("characters/hates/{$username}");
			$config['per_page'] = 25;
			
			if($this->input->get('page') != NULL and is_numeric($this->input->get('page'))) { //calculate the offset for next page
				$start = $this->input->get('page') * $config['per_page'] - $config['per_page'];
			} else {
				$start = 0;
			}
			
			$status = 0;			
			$query = $this->characters_model->get_user_characters($data['user']['id'], $status, $config['per_page'], $start, TRUE);
			$config['total_rows'] = $this->characters_model->get_user_characters_count($data['user']['id'], $status);
			
			if($query) {
				$this->pagination->initialize($config);
				$data['pagination'] = $this->pagination->create_links();
				$data['characters'] = $query;
			}
		
			$data['title'] = 'V-Anime';
			$data['css'] = 'search_characters.css';
			$data['additional_css'] = 'hates.css';
			$data['header'] = 'Hated Characters';
			$data['status'] = 'HATE';
			$this->load->view('user_characters', $data);
		} else {
			$this->helpers_model->bad_request();
		}
	}
	
	public function load_characters($anime_id=null) {
		if($anime_id != null and is_numeric($anime_id)) {
			
			$group_number = $this->input->post('group_number');
			$characters_per_page = 50;
			$offset = ceil($group_number * $characters_per_page);
				
			$result = $this->characters_model->get_characters_actors($anime_id, $characters_per_page, $offset);
			
			$rows = array();
			
			foreach($result as $character) {
				
				$element = "";
				
				if($character['role'] == 'Main') {
					$role = 2;
				} else {
					$role = 1;
				}
				
				$character_slug = "";
				
				if($character['first_name'] != "") {
					$character_slug.=$character['first_name'];
				} 				
				if($character['last_name'] != "") {
					if($character_slug != "")
						$character_slug.="-";
					$character_slug.=$character['last_name'];
				}								
				
				if(isset($character['character_user_status'])) {
					if($character['character_user_status'] == 1) { 
						$character_user_status = 1; 
					} else if($character['character_user_status'] == 0) {
						$character_user_status = 0;
					} 
				} else {
					$character_user_status = 2;
				}
				
				$element.='<tr data-id=' . $role .'>		
				    		<td class="character_name_image">
					    		<a href="' . site_url("characters/character/{$character['id']}/{$character_slug}") .'" class="disable-link-decoration">
					    			<img src="' . asset_url() . "character_images/". $character['image_file_name'] .'" class="character_actor_image">
					    		</a>
					    		<div class="wrap_character_name_div">
					    			<a href="' . site_url("characters/character/{$character['id']}/{$character_slug}") .'" class="disable-link-decoration red-text character_name">' . stripslashes($character['first_name']) . " " . stripslashes($character['last_name']) .'</a>';
				
								if(($character['alt_name'] != "") && ($character['alt_name'] != null)) {
					    			$element.='<p class="aliases"><strong>Aliases: </strong><span class="aliases_text">' . stripslashes($character['alt_name']) .'</span></p>';
								}
								
					    		$element.='</div>
						    		</td>
						    		<td class="character_user_status">
						    			<div class="wrap_user_status" data-id=' . $character['id'] .'>
								    		<span title="I love this character" class="fa-stack fa-2x love ';  
					    					if($character_user_status == 1) $element.='love_on';
								    		$element.='" data-value="1">
								    			<i class="fa fa-heart"></i>
								    		</span>
							    			<span title="I hate this character" class="fa-stack fa-2x hate '; 
								    		if($character_user_status == 0) $element.='hate_on';
							    			$element.='" data-value="0">
											    <i class="fa fa-heart fa-stack-1x"></i>
											    <i class="fa fa-bolt fa-stack-1x fa-inverse"></i>
											</span>
										</div>
						    		</td>
						    		<td class="character_voice_actor">
						    			<div class="wrap_all_actors_div">';
					    				$count = 0;
					    				foreach($character['actors'] as $actor) {
					    					if($actor['language'] == 'Japanese' || $actor['language'] == 'English') {
						    					$element.='<div class="actor">
								    			<div class="wrap_actor_name_div">
									    			<a href="' . site_url("actors/actor/{$actor['id']}/{$actor['actor_slug']}") .'" class="disable-link-decoration red-text actor_name">' . stripslashes($actor['first_name']) . " " . stripslashes($actor['last_name']) .'</a>
									    			<p class="language">' . $actor['language'] . '</p>
									    		</div>
								    			<a href="' . site_url("actors/actor/{$actor['id']}/{$actor['actor_slug']}") . '" class="disable-link-decoration">
								    				<img src="' . asset_url() . "actor_images/" . $actor['image_file_name'] . '" class="character_actor_image">
								    			</a>
							    				</div>';
						    					$count++;
					    					}
					    				}
					    				if($count == 0) {
					    					$element.='<div class="actor">
								    			<div class="wrap_actor_name_div">
									    			<p href="#" class="disable-link-decoration actor_name">Unavailable</p>
									    		</div>
								    			<a href="#" class="disable-link-decoration">
								    				<img src="'. asset_url()  . "character_images/Default.jpg" . '" class="character_actor_image">
								    			</a>
							    				</div>';
					    				}
					    				$count = 0;
		
				    			$element.='</div>			    			
				    		</td>	    	
				    	</tr>';
				$rows[] = $element;
			}
			
			foreach($rows as $row) {
				echo $row;
			}
			
		} else {
			$this->helpers_model->bad_request();
		}
	}
	
	public function load_character_users_statuses($character_id) {
		if($character_id != null and is_numeric($character_id)) {
				
			$group_number = $this->input->post('group_number');
			$status = $this->input->post('status');
			$users_per_page = 25;
			$offset = ceil($group_number * $users_per_page);

			$users = $this->characters_model->get_all_character_users_statuses($character_id, $status, $users_per_page, $offset);
			
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
	
	public function change_character_user_status() {	
		
		$character_id = $this->input->post('character_id');
		$status = $this->input->post('status');
		
		if($this->session->userdata('is_logged_in')) {									
			if($character_id != null && $status != null) {			
				$this->load->model('characters_model');				
				$query = $this->characters_model->change_character_user_status($character_id, $status);
				
				if($query) {
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