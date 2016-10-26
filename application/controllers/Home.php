<?php

class Home extends CI_Controller {
	
	public function index()
	{
		$this->goHome();
	}
	
	public function goHome() {
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