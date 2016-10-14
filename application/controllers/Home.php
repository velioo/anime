<?php

class Home extends CI_Controller {
	
	public function index()
	{
		$this->goHome();
	}
	
	public function goHome() {
		$data['title'] = 'V-Anime';
		$data['css'] = 'home.css';
		$this->load->helper('url');
		$this->load->view('home_page', $data);
	}
	
	
	
}