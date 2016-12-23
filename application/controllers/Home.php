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
	
	function character_v1() {	
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
		
		echo "Access token: " . $access_token;
	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//curl_setopt($ch, CURLOPT_URL, 'https://anilist.co/api/anime/21825/characters?access_token=' . $access_token);
		//curl_setopt($ch, CURLOPT_URL, "https://anilist.co/api/anime/search/danganronpa?access_token=" . $access_token);
		$result = curl_exec($ch);
		curl_close($ch);
		
		$character = json_decode($result);
		
		var_dump($character);
		
	}

	
}
?>