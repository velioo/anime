<?php

class AnimeUpdates extends CI_Controller {

	public function update_anime($anime_id) {
		$this->load->model('animes_model');
		$this->load->library('upload');
	
		$config['upload_path']          = './assets/anime_cover_images/';
		$config['allowed_types']        = 'gif|jpg|png';
		$config['max_size']             = 4096;
		$config['max_width']            = 4000;
		$config['max_height']           = 2250;
		$config['file_name'] = $anime_id . ".jpg";
		$config['overwrite'] = TRUE;
	
		$this->upload->initialize($config);
	
		$offset = $this->input->post('top_offset');
	
		$this->animes_model->update_cover_offset($anime_id, $offset);
	
		if (!$this->upload->do_upload('edit_cover')) {
			$error = array('error' => $this->upload->display_errors('<p class="error">', '</p>'));
			$this->session->set_flashdata('error',$error['error']);
		} else {
			$query = $this->animes_model->update_cover_image($anime_id,  $config['file_name']);
			if(!$query) {
				$error = array('error' => $this->upload->display_errors('<p class="error">', '</p>'));
			}
		}
		 
		redirect("AnimeContent/show_anime_page/{$anime_id}");
	}

}
?>