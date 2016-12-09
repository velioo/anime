<?php

class AnimeUpdates extends CI_Controller {

	public function update_anime($anime_id) {
		$this->load->model('animes_model');
		$this->load->library('upload');
		
		if($this->session->userdata('is_logged_in') && $this->session->userdata('admin')) {
		
			$unique_id = uniqid();
			
			$offset = $this->input->post('top_offset');		
			$this->animes_model->update_cover_offset($anime_id, $offset);
			
			$result = $this->animes_model->get_anime_slug($anime_id);
			$slug = str_replace(" ", "-", $result['slug']);
			
			if (!empty($_FILES['edit_cover']['name'])) {
				
				$config['upload_path']          = './assets/anime_cover_images/';
				$config['allowed_types']        = 'gif|jpg|png';
				$config['max_size']             = 8192;
				$config['file_name'] = "manual_" . $anime_id . "_" . $unique_id . ".jpg";
				$config['overwrite'] = TRUE;
			
				$this->upload->initialize($config);
	
				if (!$this->upload->do_upload('edit_cover')) {
					$error = array('error' => $this->upload->display_errors('<p class="error">(Cover) ', '</p>'));
					$this->session->set_flashdata('error', $error['error']);
					redirect("animeContent/anime/{$slug}");
				} else {
					$query = $this->animes_model->update_cover_image($anime_id,  $config['file_name']);
					if(!$query) {
						$this->server_error();
					} 
				}
			}
		
			if (!empty($_FILES['edit_poster']['name'])) {
			
				$config['upload_path']          = './assets/poster_images/';
				$config['allowed_types']        = 'gif|jpg|png';
				$config['max_size']             = 8192;
				$config['file_name'] = "manual_". $anime_id . "_" . $unique_id . ".jpg";
				$config['overwrite'] = TRUE;
				
				$this->upload->initialize($config);
				
				if (!$this->upload->do_upload('edit_poster')) {
					$error = array('error_a' => $this->upload->display_errors('<p class="error_a">(Poster) ', '</p>'));
					$this->session->set_flashdata('error_a', $error['error_a']);
					redirect("animeContent/anime/{$slug}");
				} else {
					$query = $this->animes_model->update_poster_image($anime_id,  $config['file_name']);
					if(!$query) {
						$this->server_error();
					}
				}
			}
			
			if (!empty($_FILES['edit_poster']['name'])) {
				$this->write_json_autocomplete($slug);
			} else {
				redirect("animeContent/anime/{$slug}");
			}
		} else {
			$this->unauthorized();
		}
	
	}
	
	function get_update_animes() {	
		$this->load->model('animes_model');
	
		$file_to_write_counter = 'assets/txt/anime_id_counter.txt';
		$fp = fopen($file_to_write_counter, 'r');
	
		$failed_request = 0;
		$poster_images_path = asset_url() . "poster_images/";
		$anime_id_counter = fgets($fp);
		$anime_id_counter-=500;
			
		while($failed_request < 50) {
				
			$anime_id_counter++;

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_URL, 'https://hummingbird.me/api/v1/anime/' . $anime_id_counter);			
			$result = curl_exec($ch);
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
				
			if($httpcode == 200) {
	
				$failed_request = 0;
	
				$anime_object = json_decode($result);	
				
				$genres = array();
				foreach($anime_object->genres as $key => $value) {
					$genres[] = $value->name;
				}
					
				$anime_object->genres = $genres;
	
				if(!in_array(UNALLOWED_GENRE, $genres)) {
	
					$poster_url = $anime_object->cover_image;
					$poster_name = "auto_" . $anime_object->id . ".jpg";
					if (!@getimagesize($poster_images_path . $poster_name)) {
						$poster_path = 'assets/poster_images/' . $poster_name;
						file_put_contents($poster_path, file_get_contents($poster_url));
					}
						
					$anime_object->cover_image = $poster_name;
						
					$anime_object->slug = str_replace("-", " ", $anime_object->slug);
					$anime_object->age_rating = get_age_rating($anime_object->age_rating);
					$anime_object->age_rating_guide = get_age_rating_guide($anime_object->age_rating);
					$anime_object->show_type = get_show_type($anime_object->show_type);
						
					if($anime_object->finished_airing == null) {
						$anime_object->finished_airing = "0000-00-00";
					}
					if($anime_object->started_airing == null) {
						$anime_object->started_airing = "0000-00-00";
					}
					if($anime_object->episode_count == null) {
						$anime_object->episode_count = 0;
					}
					if($anime_object->episode_length == null) {
						$anime_object->episode_length = 0;
					}
						
					$titles = '"alt"=>"' . $anime_object->alternate_title . '", "main"=>"' . $anime_object->title . '"';
					$anime_object->titles = $titles;
	
					echo $anime_object->title . "<br/>";
	
					$anime_exists = $this->animes_model->check_if_anime_exists($anime_object->id);
					if(!$anime_exists) {
						$success =  $this->animes_model->add_anime($anime_object, $genres);
					} else {
						$success = $this->animes_model->update_anime($anime_object, $genres);
					}
				}
			} else {
				$failed_request++;
			}
		}
	
		fclose($fp);
		$anime_id_counter-=49;
		file_put_contents($file_to_write_counter, $anime_id_counter);
		
		$this->write_json_autocomplete();

	}
	
	public function write_json_autocomplete($slug = "") {
		$this->load->model('animes_model');
	
		$result_array = $this->animes_model->get_anime_json_data();
	
		if($result_array) {
				
			$response = array();
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
			fwrite($fp, json_encode($result));
			fclose($fp);
			
			if($slug != "") {
				redirect("animeContent/anime/{$slug}");
			} else {
				redirect("Home");
			}			
		}
	
	}
	
	function server_error() {
		header('HTTP/1.1 500 Internal Server Error');
		echo "<h1>Error 500 Internal Server Error</h1>";
		echo "There was a problem with the server";
		exit();
	}
	
	function unauthorized() {
		header('HTTP/1.0 401 Unauthorized');
		echo "<h1>Error 401 Unauthorized</h1>";
		echo "You aren't authorized to access this page.";
		exit();
	}

}

?>