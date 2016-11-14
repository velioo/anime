<?php 

class AnimeContent extends CI_Controller {

	public function show_anime_page($anime_id) {
		$this->load->model('animes_model');
		
		$query =  $this->animes_model->get_anime($anime_id);
		
		if($query) {
			$data['anime'] = $query;
			$data['title'] = 'V-Anime';
			$data['css'] = 'animes.css';
			$data['javascript'] = 'home.js';
			$data['header'] = "Anime";
			$this->load->view('anime_page', $data);
			
		} else {
			redirect("home");
		}
		
	}

}


?>