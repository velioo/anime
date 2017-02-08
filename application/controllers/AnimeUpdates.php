<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AnimeUpdates extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('helpers_model');
	}
	
	public function update_anime($anime_id) {
		$this->load->model('animes_model');
		$this->load->library('upload');
		
		if($this->session->userdata('is_logged_in') && $this->session->userdata('admin')) {
		
			$unique_id = uniqid();
			
			$offset = $this->input->post('top_offset');		
			if($offset !== NULL) {
				$this->animes_model->update_cover_offset($anime_id, $offset);
			}
			
			$result = $this->animes_model->get_anime_slug($anime_id);
			$slug = str_replace(" ", "-", $result['slug']);
			
			if (!empty($_FILES['edit_cover']['name'])) {
				
				$config['upload_path'] = './assets/anime_cover_images/';
				$config['allowed_types'] = 'gif|jpg|png|jpeg';
				$config['max_size'] = 8192;
				$config['file_name'] = "manual_" . $anime_id . "_" . $unique_id . ".jpg";
				$config['overwrite'] = TRUE;
			
				$this->upload->initialize($config);
	
				if (!$this->upload->do_upload('edit_cover')) {
					$error = array('error' => $this->upload->display_errors('<p class="error">(Cover) ', '</p>'));
					$this->session->set_flashdata('error', $error['error']);
					redirect("animeContent/anime/{$slug}");
				} else {
					$cover_image = $this->animes_model->get_anime_cover_image($anime_id)['cover_image_file_name'];
					if($cover_image != '') {
						unlink("./assets/anime_cover_images/{$cover_image}");
					}
					$query = $this->animes_model->update_cover_image($anime_id,  $config['file_name']);
					if($query === FALSE) {
						$this->helpers_model->server_error();
					} 
				}
			}
		
			if (!empty($_FILES['edit_poster']['name'])) {
			
				$config['upload_path'] = './assets/poster_images/';
				$config['allowed_types'] = 'gif|jpg|png|jpeg';
				$config['max_size'] = 8192;
				$config['file_name'] = "manual_". $anime_id . "_" . $unique_id . ".jpg";
				$config['overwrite'] = TRUE;
				
				$this->upload->initialize($config);
				
				if (!$this->upload->do_upload('edit_poster')) {
					$error = array('error_a' => $this->upload->display_errors('<p class="error_a">(Poster) ', '</p>'));
					$this->session->set_flashdata('error_a', $error['error_a']);
					redirect("animeContent/anime/{$slug}");
				} else {
					$poster_image = $this->animes_model->get_anime_poster_image($anime_id)['poster_image_file_name'];
					if($poster_image != '') {
						unlink("./assets/poster_images/{$poster_image}");
					}
					$query = $this->animes_model->update_poster_image($anime_id,  $config['file_name']);
					if($query === FALSE) {
						$this->helpers_model->server_error();
					}
				}
			}
			
			if (!empty($_FILES['edit_poster']['name'])) {
				$this->write_json_autocomplete(VERIFICATION_TOKEN, $slug);
			} else {
				redirect("animeContent/anime/{$slug}");
			}
		} else {
			$this->helpers_model->unauthorized();
		}
	
	}
	
	function get_update_animes($verification_token=null) {	

		if($verification_token === VERIFICATION_TOKEN) {
			
			$this->load->model('animes_model');
		
			$file_to_write_counter = 'assets/txt/anime_id_counter.txt';
			$fp = fopen($file_to_write_counter, 'r');
		
			$failed_request = 0;
			$poster_images_path = asset_url() . "poster_images/";
			$cover_images_path = asset_url() . "anime_cover_images/";
			$anime_id_counter = fgets($fp);
			$anime_id_counter-=500;
				
			while($failed_request < 100) {
					
				$anime_id_counter++;
				
				$headers = array(
						"Accept: application/vnd.api+json",
						"Content-Type: application/vnd.api+json"
				);
				
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_URL, 'https://kitsu.io/api/edge/anime/' . $anime_id_counter);			
				$result = curl_exec($ch);
				$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				curl_close($ch);
					
				if($httpcode == 200) {
		
					$failed_request = 0;
		
					$anime_object = json_decode($result);	
					
					$genres = array();
					
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_URL, $anime_object->data->relationships->genres->links->self);			
					$result = curl_exec($ch);
					$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
					curl_close($ch);		
					
					$genres_object;
					
					if($httpcode == 200) {
						$genres_object = json_decode($result);
						foreach($genres_object->data as $key => $value) {
							$genres[] = $value->id;
						}
						$anime_object->data->genres = $genres;				
					} 					
																	
					if(!in_array(UNALLOWED_GENRE, $genres)) {
		
						if($anime_object->data->attributes->posterImage != null) {					
						 	$poster_url = $anime_object->data->attributes->posterImage->original;
							$poster_name = "auto_" . $anime_object->data->id . ".jpg";
							if (!@getimagesize($poster_images_path . $poster_name)) {
								$poster_path = 'assets/poster_images/' . $poster_name;
								file_put_contents($poster_path, file_get_contents($poster_url));
							}																				
	 						$anime_object->data->attributes->posterImage = $poster_name;
						} else {
							$anime_object->data->attributes->posterImage = "";
						}
						
 						if($anime_object->data->attributes->coverImage != null) { 						
 							$cover_url = $anime_object->data->attributes->coverImage->original;
	 						$cover_name = "auto_" . $anime_object->data->id . ".jpg";
	 						if (!@getimagesize($cover_images_path . $cover_name)) {
	 							$cover_path = 'assets/anime_cover_images/' . $cover_name;
	 							file_put_contents($cover_path, file_get_contents($cover_url));
	 						}
	 						$anime_object->data->attributes->coverImage = $cover_name;
 						} else {
 							$anime_object->data->attributes->coverImage = "";
 						}												
 						
						$anime_object->data->attributes->slug = str_replace("-", " ", $anime_object->data->attributes->slug);
						$anime_object->data->attributes->ageRating = get_age_rating($anime_object->data->attributes->ageRating, $anime_object->data->attributes->ageRatingGuide);
						$anime_object->data->attributes->showType = get_show_type($anime_object->data->attributes->showType);
							
						if($anime_object->data->attributes->startDate == null) {
							$anime_object->data->attributes->startDate = "0000-00-00";
						}
						if($anime_object->data->attributes->endDate == null) {
							$anime_object->data->attributes->endDate = "0000-00-00";
						}
						if($anime_object->data->attributes->episodeCount == null) {
							$anime_object->data->attributes->episodeCount = 0;
						}
						if($anime_object->data->attributes->episodeLength == null) {
							$anime_object->data->attributes->episodeLength = 0;
						}
						
						if($anime_object->data->attributes->youtubeVideoId == null) {
							$anime_object->data->attributes->youtubeVideoId = "";
						}
						
						$anime_object->data->attributes->alternateTitle = get_alternate_title($anime_object->data->attributes->canonicalTitle, $anime_object->data->attributes->titles);
																												
 						$titles = '"alt"=>"' . $anime_object->data->attributes->alternateTitle . '", "main"=>"' . $anime_object->data->attributes->canonicalTitle . '"';
						$anime_object->data->attributes->titles = $titles;
						
						echo "Id: " . $anime_id_counter . " " . $anime_object->data->attributes->titles . "<br/>";
		
						$anime_exists = $this->animes_model->check_if_anime_exists($anime_object->data->id);
						if($anime_exists === FALSE) {
							$success =  $this->animes_model->add_anime($anime_object);
						} else {
							$success = $this->animes_model->update_anime($anime_object);
						}   
												
					}
				} else {	
					$failed_request++;
				}
			}
		
			fclose($fp);
			$anime_id_counter-=99;
			file_put_contents($file_to_write_counter, $anime_id_counter);
			
			$this->write_json_autocomplete(VERIFICATION_TOKEN);
		} else {
			$this->helpers_model->unauthorized();
		}
	}
	
	public function write_json_autocomplete($verification_token=null, $slug = "") {
		
		if($verification_token === VERIFICATION_TOKEN) {
		
			$this->load->model('animes_model');
		
			$result_array = $this->animes_model->get_anime_json_data();
		
			if($result_array !== FALSE) {
					
				$all_names = "";
					
				foreach ($result_array as $anime) {
					
					$temp = $anime['titles'];
					$titles = convert_titles_to_hash($temp);
	
					$all_names = "";
					if($titles['main'] != "" && $titles['main'] != "NULL") {
						$all_names.=$titles['main'] . " ";
					} if($titles['alt'] != "" && $titles['alt'] != "NULL") {
						$all_names.=$titles['alt'] . " ";
					}		
					
					$all_names.=$anime['slug'];
		
					$name = $titles['main'];
					$id = $anime['id'];
					$image = $anime['poster_image_file_name'];
					$anime_slug = str_replace(" ", "-", $anime['slug']);					
		
					$result[] = array('name'=> $name, 'all_names' => $all_names, 'slug' => $anime_slug, 'id' => $id, 'image'=> $image);
				}
	
				$fp = fopen('assets/json/autocomplete.json', 'w');
				flock($fp, LOCK_EX);
				fwrite($fp, json_encode($result));
				flock($fp, LOCK_UN);
				fclose($fp);
				
				if($slug != "") {
					redirect("animeContent/anime/{$slug}");
				} else {
					redirect("home");
				}			
			}
		} else {
			$this->helpers_model->unauthorized();
		}
	}


}

?>