<?php

class AnimeUpdates extends CI_Controller {

	public function update_anime($anime_id) {
		$this->load->model('animes_model');
		$this->load->library('upload');
		
		$offset = $this->input->post('top_offset');		
		$this->animes_model->update_cover_offset($anime_id, $offset);
		
		if (!empty($_FILES['edit_cover']['name'])) {
			
			$config['upload_path']          = './assets/anime_cover_images/';
			$config['allowed_types']        = 'gif|jpg|png';
			$config['max_size']             = 4096;
			$config['max_width']            = 4000;
			$config['max_height']           = 2250;
			$config['file_name'] = $anime_id . ".jpg";
			$config['overwrite'] = TRUE;
		
			$this->upload->initialize($config);

			if (!$this->upload->do_upload('edit_cover')) {
				$error = array('error' => $this->upload->display_errors('<p class="error">(Cover) ', '</p>'));
				$this->session->set_flashdata('error',$error['error']);
				redirect("AnimeContent/show_anime_page/{$anime_id}");
			} else {
				$query = $this->animes_model->update_cover_image($anime_id,  $config['file_name']);
				if(!$query) {
					$error = array('error' => $this->upload->display_errors('<p class="error">', '</p>'));
				}
			}
		}
	
		if (!empty($_FILES['edit_poster']['name'])) {
		
			$config['upload_path']          = './assets/poster_images/';
			$config['allowed_types']        = 'gif|jpg|png';
			$config['max_size']             = 4096;
			$config['max_width']            = 2000;
			$config['max_height']           = 2000;
			$config['file_name'] = uniqid($anime_id . "_poster") . ".jpg";
			$config['overwrite'] = TRUE;
			
			$this->upload->initialize($config);
			
			if (!$this->upload->do_upload('edit_poster')) {
				$error = array('error_a' => $this->upload->display_errors('<p class="error_a">(Poster) ', '</p>'));
				$this->session->set_flashdata('error_a', $error['error_a']);
				redirect("AnimeContent/show_anime_page/{$anime_id}");
			} else {
				$query = $this->animes_model->update_poster_image($anime_id,  $config['file_name']);
				if(!$query) {
					$error = array('error_a' => $this->upload->display_errors('<p class="error_a">', '</p>'));
					redirect("AnimeContent/show_anime_page/{$anime_id}");
				}
			}
		}
		
		if (!empty($_FILES['edit_poster']['name'])) {
			redirect("Home/write_json/{$anime_id}");
		} else {
			redirect("AnimeContent/show_anime_page/{$anime_id}");
		}
	
	}

}
?>