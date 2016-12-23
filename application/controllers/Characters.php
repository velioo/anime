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
		if($character_id != null) {
				
			$this->load->model('characters_model');
				
			$character = $this->characters_model->get_character($character_id);				
			$info_text = stripslashes($character['info']);								
			$info_text = format_character_info($info_text);
					
			$character['info'] = $info_text;
			$data['character'] = $character;
			$data['title'] = 'V-Anime';
			$data['css'] = 'character.css';
			$this->load->view('character', $data);
		} else {
			$this->helpers_model->bad_request();
		}
	}
	
	public function load_characters($anime_id=null) {
		if($anime_id != null and is_numeric($anime_id)) {
			
			$group_number = $this->input->post('group_number');
			$characters_per_page = 20;
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
					$character_slug.=$character['first_name'] . "-";
				} 
				
				if($character['last_name'] != "") {
					$character_slug.=$character['last_name'];
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
						    			<div class="wrap_user_status">
								    		<span title="I love this character" class="fa-stack fa-2x love">
								    			<i class="fa fa-heart"></i>
								    		</span>
							    			<span title="I hate this character" class="fa-stack fa-2x hate">
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
									    			<a href="#" class="disable-link-decoration red-text actor_name">' . stripslashes($actor['first_name']) . " " . stripslashes($actor['last_name']) .'</a>
									    			<p class="language">' . $actor['language'] . '</p>
									    		</div>
								    			<a href="#" class="disable-link-decoration">
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
}

?>