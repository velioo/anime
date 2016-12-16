<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Home extends CI_Controller {
	
	public function index() {
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
		$this->load->view('home_page', $data);
	}
	
	function test_v2() {
		
		$header = "X-Client-Id: b5ebe77052b61879c8c5";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, array($header));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);		
		curl_setopt($ch, CURLOPT_URL, 'https://hummingbird.me/api/v2/anime/100');
		$result = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		$anime_object = json_decode($result);
		
		var_dump($anime_object);
	}
	
	function test_v3() {
		$headers = array( 
				"Accept: application/vnd.api+json",
				"Content-Type: application/vnd.api+json"			
		);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, 'https://kitsu.io/api/edge/anime/8270');
		$result = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		$anime_object = json_decode($result);
		
		var_dump($anime_object);
	}

	
}
?>