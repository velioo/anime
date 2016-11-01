<?php

class Home extends CI_Controller {
	
	public function index()
	{
		$this->go_home();
	}
	
	public function go_home() {
		$this->load->model('search_model');
		$query = $this->search_model->get_latest_anime();
		
		if($query) {
			$data['latest_anime'] = $query;
		}
		
		$data['title'] = 'V-Anime';
		$data['css'] = 'home.css';
		$data['javascript'] = 'home.js';
		$this->load->view('home_page', $data);
	}
	
	public function insert_anime($id, $values) {
		$this->load->model('insert_model');	
		$this->insert_model->insert_anime($id, $values);
	}
	
}
?>