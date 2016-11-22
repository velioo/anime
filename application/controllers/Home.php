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
		$this->load->view('home_page', $data);
	}
	
	public function insert_anime($id, $values) {
		$this->load->model('insert_model');	
		$this->insert_model->insert_anime($id, $values);
	}
	
	public function write_json($anime_id = "") {
		ini_set("memory_limit","512M");
		$this->load->model('animes_model');
	
		$result_array = $this->animes_model->get_anime_json_data();
		
		if($result_array) {
			
			$response = array();
			$all_names = "";
			
			foreach ($result_array as $anime) {		
				$temp = $anime['titles'];
				$titles = convert_titles_to_hash($temp); //$anime['canonical_title']
				$all_names = "";
				if($titles['en'] != "" && $titles['en'] != "NULL") {
					$all_names.=$titles['en'] . " ";
				} if($titles['en_jp'] != "" && $titles['en_jp'] != "NULL") {
					$all_names.=$titles['en_jp'];
				} 
				
				$name = $titles[$anime['canonical_title']];
				$id = $anime['id'];
				$image = $anime['poster_image_file_name'];
				
				$result[] = array('name'=> $name, 'all_names' => $all_names, 'id' => $id, 'image'=> $image);
			}		
			
			$fp = fopen('assets/json/autocomplete.json', 'w');
			fwrite($fp, json_encode($result));
			fclose($fp);

			if($anime_id != "") {
				redirect("AnimeContent/show_anime_page/{$anime_id}");
			} else {
				$this->go_home();
			}
			
		}

	}
	
/* 	public function write() {
		$this->load->model('insert_model');
	
		$myfile = fopen("have_to_insert_into_animes.txt", "r") or die("Unable to open file!");
	
		while ($line = fgets($myfile)) {
			$split_by_tab = explode("\t", $line);
			$values = array();
			$id = $split_by_tab[0];
			$id = trim($id);
			$id = "'" . $id."'";
			$values[] = $id;
			$slug = $split_by_tab[1];
			$slug = str_replace("-", " ", $slug);
			$values[] = "'" . $slug . "'";
			$age_rating = $split_by_tab[2];
			$values[] = "'".$age_rating."'";
			$episode_count = $split_by_tab[3];
			$values[] = "'".$episode_count."'";
			$episode_length = $split_by_tab[4];
			$values[] = "'".$episode_length."'";
			$synopsis = $split_by_tab[5];
			$synopsis = trim($synopsis);
			$synopsis = addslashes($synopsis);
			$synopsis = "'" . $synopsis . "'";
			$values[] = $synopsis;
			$youtube_video_id = $split_by_tab[6];
			$values[] = "'" . $youtube_video_id . "'";
			$cover_image_file_name = $split_by_tab[9];
			$values[] = "'" . $cover_image_file_name ."'";
			$age_rating_guide = $split_by_tab[15];
			$values[] = "'".  $age_rating_guide."'";
			$show_type = $split_by_tab[16];
			$values[] = "'".$show_type."'";
			$start_date = $split_by_tab[17];
			$values[] = "'". $start_date."'";
			$end_date = $split_by_tab[18];
			$values[] = "'".$end_date."'";
			$poster_image_file_name = $split_by_tab[20];
			$values[] = "'".$poster_image_file_name."'";
			$cover_image_top_offset = $split_by_tab[24];
			$values[] = "'".$cover_image_top_offset."'";
			$titles = $split_by_tab[26];
			$titles = addslashes($titles);
			$values[] = "'".$titles."'";
			$canonical_title = $split_by_tab[27];
			$values[] = "'".$canonical_title."'";
			$abbreviated_titles = $split_by_tab[28];
			$abbreviated_titles = rtrim($abbreviated_titles, "\n");
			$values[] = "'".$abbreviated_titles."'";
			
			$values = implode(", ", $values);
			
			//$this->insert_model->insert_anime($id, $values);
		}
		fclose($myfile);
	} */
	
}
?>