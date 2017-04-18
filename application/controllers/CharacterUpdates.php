<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CharacterUpdates extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('posts_model');
		$this->load->model('helpers_model');
		$this->logged = $this->session->userdata('is_logged_in');	
	}
	
	public function get_add_characters_actors($verification_token=null) {
		
		if($verification_token === VERIFICATION_TOKEN) {
			
			$this->load->model('characters_model');
			$this->load->model('actors_model');
			$this->load->model('animes_model');
			
			$file_to_write_counter = 'assets/txt/character_anime_counter.txt';
			$fp = fopen($file_to_write_counter, 'r+');
			
			$failed_request = 0;			
			$anime_counter = fgets($fp);
			$access_token = "";
			$character_images_path = asset_url() . "character_images/";
			$actors_images_path = asset_url() . "actor_images/";
			$anime_id = 0;			
			$counter = 0;
			
			$anime_counter-=5000;
			//$anime_counter = 8500;
			
			while($failed_request < 100) {
				
				$anime_counter++;
				
				//$anime_counter = 7059;
											
				$anime = $this->get_anime($anime_counter, $access_token);
				
				if($anime == null) {				
					$access_token = $this->get_access_token();					
					$anime = $this->get_anime($anime_counter, $access_token);
				}												

				if($anime != "non-existent") {
										
					$failed_request = 0;
					
					$anime_title = $anime->title_english;
					if($anime_title == null) {
						$anime_title = $anime->title_romaji;
						if($anime_title == null) {
							$anime_title = $anime->title_japanese;
						}
					} 			
					
					$anime_query = $this->animes_model->get_anime_id_by_title($anime->title_english, $anime->title_romaji, $anime->title_japanese);				
					
					//$anime_query = $this->animes_model->get_anime(4854);
										
					if($anime_query !== FALSE) {
						$temp = 0;
 	 	 			 	foreach($anime_query as $a) {
 	 	 			 		 	 			 		
 	 	 			 		$titles = array(strtolower(convert_titles_to_hash($a['titles'])['main']));	 	 			 			
 	 	 			 		if(isset(convert_titles_to_hash($a['titles'])['en'])) {
 	 	 			 			$titles[] = strtolower(convert_titles_to_hash($a['titles'])['en']);
 	 	 			 		} 	 			 			
 	 	 			 		if(isset(convert_titles_to_hash($a['titles'])['en_jp'])) {
 	 	 			 			$titles[] = strtolower(convert_titles_to_hash($a['titles'])['en_jp']);
 	 	 			 		}	 	 			 			
 	 	 			 		if(isset(convert_titles_to_hash($a['titles'])['ja_jp'])) {
 	 	 			 			$titles[] = strtolower(convert_titles_to_hash($a['titles'])['ja_jp']);
 	 	 			 		}
 	 	 			 		
 	 	 			 		$abbreviated_titles = explode("___", $a['abbreviated_titles']);	 	 			 		
 	 	 			 		foreach($abbreviated_titles as $at) {
 	 	 			 			$titles[] = $at;
 	 	 			 		}
 	 	 			 		
 	 	 			 		foreach($titles as $t) {
 	 	 			 			if($t == strtolower($anime->title_english) ||
 	 	 			 			   $t == strtolower($anime->title_romaji) ||
 	 	 			 			   $t == strtolower($anime->title_japanese)) {
 	 	 			 			   	
 	 	 			 			   $temp++;
 	 	 			 			   
 	 	 			 			   if($temp > 1) { 	 			 			   	
	 	 	 			 			   if($t == strtolower($anime->title_english) ||
	 	 	 			 			   	  $t == strtolower($anime->title_romaji)) {
	 	 	 			 			   	  	
	 	 	 			 			   	  $anime_id = $a['id'];
	 	 	 			 			   	  
	 	 	 			 			   } 	 	 			 			   
 	 	 			 			   } else {
 	 	 			 			   	 $anime_id = $a['id'];
 	 	 			 			   }
 	 	 			 			}
 	 	 			 		}	
 	 	 			 		
 	 	 			 		$titles = NULL;
				/* 			if(     (strtolower(convert_titles_to_hash($a['titles'])['main']) == strtolower($anime->title_english)) || 
									(strtolower(convert_titles_to_hash($a['titles'])['alt']) == strtolower($anime->title_english)) || 
									(strtolower(convert_titles_to_hash($a['titles'])['main']) == strtolower($anime->title_romaji)) || 
									(strtolower(convert_titles_to_hash($a['titles'])['alt']) == strtolower($anime->title_romaji)) ||
									(strtolower(convert_titles_to_hash($a['titles'])['main']) == strtolower($anime->title_japanese)) ||
 									(strtolower(convert_titles_to_hash($a['titles'])['alt']) == strtolower($anime->title_japanese))) { // problem with japanese duplicate names
 										
 								$temp++;								
 								if($temp > 1) {
 									if(    (strtolower(convert_titles_to_hash($a['titles'])['main']) == strtolower($anime->title_english)) ||
 											(strtolower(convert_titles_to_hash($a['titles'])['alt']) == strtolower($anime->title_english)) ||
 											(strtolower(convert_titles_to_hash($a['titles'])['main']) == strtolower($anime->title_romaji)) ||
 											(strtolower(convert_titles_to_hash($a['titles'])['alt']) == strtolower($anime->title_romaji))) {
 												$anime_id = $a['id'];
 											}
 								} else {
 									$anime_id = $a['id'];
 								}															
								
							}  */
						}   
						
						$temp = 0;
						
						//$anime_id = $anime_query['id'];
						
				 		if($anime_id != NULL) {
						
				 			$counter++;
				 			
							echo "Counter: " . $anime_counter . "<br>";
							echo "Animes went through: " . $counter . "<br>";
							echo "<br>" . $anime_title . " Id: " . $anime_id . "<br>";
							
							echo "<br>Characters<br>";
							
							foreach($anime->characters as $character) {
								
				 			 	$character_array = array();
								$character_array['id'] = $character->id; 
								
								$character_details = $this->get_character($character->id, $access_token);
								
								if($character_details == null) {
									$access_token = $this->get_access_token();
									$character_details = $this->get_character($character->id, $access_token);
								}					
								
 								echo $character_details->name_first . "<br>";
								$character_array['first_name'] =  addslashes($character_details->name_first);
								$character_array['last_name'] =  addslashes($character_details->name_last);
								$character_array['japanese_name'] =  addslashes($character_details->name_japanese);
								$character_array['alt_name'] =  addslashes($character_details->name_alt);
								$character_array['info'] =  addslashes($character_details->info);
	
								if($character->image_url_lge != null) {
									$image_url = $character->image_url_lge;
									$image_name = "auto_" . $character->id . ".jpg";
									if (!@getimagesize($character_images_path . $image_name)) {
										$image_path = 'assets/character_images/' . $image_name;
										file_put_contents($image_path, file_get_contents($image_url));
									}
									$character_array['image_file_name'] = $image_name;
								} else {
									$character_array['image_file_name'] = "";
								}	
																					
								var_dump($character_array);
														
								$character_exists = $this->characters_model->check_if_character_exists($character_array);
									
								if($character_exists !== FALSE) {
									$this->characters_model->update_character($character_array);
								} else {
									$this->characters_model->add_character($character_array);
								} 
								
								$role = $character->role;
								
								if(!character_skip($character_array['id'])) {
									$this->characters_model->make_character_anime_relation($anime_id, $character->id, $role); 
								}
								
 								echo "<br>Actors<br>";
								
								foreach($character->actor as $actor) {
									$actor_array = array();
									$actor_array['id'] = $actor->id;
									
									$actor_details = $this->get_actor($actor->id, $access_token);
										
									if($actor_details == null) {
										$access_token = $this->get_access_token();
										$actor_details = $this->get_actor($actor->id, $access_token);
									}
									
									echo $actor_details->name_first . "<br>";								
									$actor_array['first_name'] =  addslashes($actor_details->name_first);
									$actor_array['last_name'] =  addslashes($actor_details->name_last);
									$actor_array['first_name_japanese'] =  addslashes($actor_details->name_first_japanese);
									$actor_array['last_name_japanese'] =  addslashes($actor_details->name_last_japanese);
									$actor_array['info'] =  addslashes($actor_details->info);
									$actor_array['language'] =  addslashes($actor_details->language);
									
									if($actor->image_url_lge != null) {
										$image_url = $actor->image_url_lge;
										$image_name = "auto_" . $actor->id . ".jpg";
										if (!@getimagesize($actors_images_path . $image_name)) {
											$image_path = 'assets/actor_images/' . $image_name;
											file_put_contents($image_path, file_get_contents($image_url));
										}
										$actor_array['image_file_name'] = $image_name;
									} else {
										$actor_array['image_file_name'] = "";
									}		
									
									var_dump($actor_array);
									
									$actor_exists = $this->actors_model->check_if_actor_exists($actor_array);
									
									if($actor_exists !== FALSE) {
										$this->actors_model->update_actor($actor_array);
									} else {
										$this->actors_model->add_actor($actor_array);
									}
									
									$this->characters_model->make_character_actor_relation($character->id, $actor->id, $anime_id);
								}	 													
							}							
							
							echo "------------------------------------------------------------------------------------------------------";																
						}

						
						$anime_id = null;
					}  
					
					//die();
					
				} else {
					$failed_request++;
				}
				
				file_put_contents($file_to_write_counter, $anime_counter);
				
				//fclose($fp);							
			}
			
			$anime_counter-=99;		
			file_put_contents($file_to_write_counter, $anime_counter);									
			fclose($fp);
			
			$this->write_characters_json(VERIFICATION_TOKEN);
			$this->write_actors_json(VERIFICATION_TOKEN);
			
		} else {
			$this->helpers_model->unauthorized();
		}
	}
	
	function write_characters_json($verification_token=null, $character_id=null) {
		
		if($verification_token === VERIFICATION_TOKEN) {
		
			$this->load->model('characters_model');
		
			$result_array = $this->characters_model->get_characters_json_data();
		
			if($result_array !== FALSE) {
				
				$all_names = "";
					
				foreach ($result_array as $character) {
					
					$all_names = "";
					
					$id = $character['id'];
					$name = $character['first_name'] . " " . $character['last_name'];
					$image = $character['image_file_name'];		
					
					$character_slug = "";
						
					if($character['first_name'] != "") {
						$character_slug.=$character['first_name'];
					}
					if($character['last_name'] != "") {
						if($character_slug != "")
							$character_slug.="-";
							$character_slug.=$character['last_name'];
					}
						
					$character_slug = preg_replace('/[^\00-\255]+/u', ' ', $character_slug);
					
					$all_names.=$character['first_name']. " " . $character['last_name']. " " . $character['alt_name']. " " . 
						$character['japanese_name'] . " " . $character_slug;	
					
					$character_slug = str_replace(" ", "-", $character_slug);
		
					$result[] = array('name'=> $name, 'all_names' => $all_names, 'slug' => $character_slug, 'id' => $id, 'image'=> $image);
				}
		
				$fp = fopen('assets/json/autocomplete_characters.json', 'w');
				flock($fp, LOCK_EX);
				fwrite($fp, json_encode($result));
				flock($fp, LOCK_UN);
				fclose($fp);
		
				if($character_id != "") {
					redirect("characters/character/{$character_id}");
				}
			}
		} else {
			$this->helpers_model->unauthorized();
		}
	}
	
	function write_actors_json($verification_token=null, $actor_id=null) {
	
		if($verification_token === VERIFICATION_TOKEN) {
	
			$this->load->model('actors_model');
	
			$result_array = $this->actors_model->get_actors_json_data();
	
			if($result_array !== FALSE) {
	
				$all_names = "";
					
				foreach ($result_array as $actor) {
						
					$all_names = "";
						
					$id = $actor['id'];
					$name = $actor['first_name'] . " " . $actor['last_name'];
					$image = $actor['image_file_name'];
					$all_names.=$actor['first_name']. " " . $actor['last_name']. " " . $actor['first_name_japanese']. " " . $actor['last_name_japanese'];
						
					$actor_slug = "";
						
					if($actor['first_name'] != "") {
						$actor_slug.=$actor['first_name'];
					}
					if($actor['last_name'] != "") {
						if($actor_slug != "")
							$actor_slug.="-";
							$actor_slug.=$actor['last_name'];
					}
	
					$result[] = array('name'=> $name, 'all_names' => $all_names, 'slug' => $actor_slug, 'id' => $id, 'image'=> $image);
				}
	
				$fp = fopen('assets/json/autocomplete_actors.json', 'w');
				flock($fp, LOCK_EX);
				fwrite($fp, json_encode($result));
				flock($fp, LOCK_UN);
				fclose($fp);
	
				if($actor_id != "") {
					redirect("actors/actor/{$actor_id}/{$actor_slug}");
				}
			}
		} else {
			$this->helpers_model->unauthorized();
		}
	}
	
	function get_access_token() {
		echo "Getting new access token<br>";
		$data = array(
			'grant_type' => 'client_credentials',
			'client_id' => 'velioo-umtdx',
			'client_secret' => 'bSvLMpaKvwSo55Cv8zrF',
		);
		$data = http_build_query($data);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_URL, "https://anilist.co/api/auth/access_token");
		$result = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($result);
		$access_token = $response->access_token;
		
		return $access_token;
	}
	
	function get_anime($anime_counter, $access_token) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, "https://anilist.co/api/anime/{$anime_counter}/characters?access_token=" . $access_token);
		$result = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		$anime = json_decode($result);
		
		if($httpcode == 401) {
			$anime = null;
		} else if($httpcode == 404) {
			$anime = "non-existent";
		}
		
		return $anime;
	}
	
	function get_character($character_id, $access_token) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, "https://anilist.co/api/character/{$character_id}?access_token=" . $access_token);
		$result = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		$character = json_decode($result);

		if($httpcode == 401) {
			$character = null;
		} 
			
		return $character;
	}
	
	function get_actor($actor_id, $access_token) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, "https://anilist.co/api/staff/{$actor_id}?access_token=" . $access_token);
		$result = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		$actor = json_decode($result);
	
		if($httpcode == 401) {
			$actor = null;
		}
			
		return $actor;
	}
}

?>