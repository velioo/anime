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
	
	public function write() {
		$this->load->model('insert_model');
	
		$myfile = fopen("synopsis_update_fix.sql", "r") or die("Unable to open file!");
	
		while ($line = fgets($myfile)) {
			$split_by_tab = explode("\t", $line);
			
			$id = $split_by_tab[0];
			
			$synopsis = $split_by_tab[5];
			
			$id = trim($id);
			$synopsis = trim($synopsis);
			$synopsis = addslashes($synopsis);
			
/* 			$id = substr($split_by_comma[0], 2, (strlen($split_by_comma[0]) - 3));
			$id = trim($id);
			$synopsis = trim($split_by_comma[5]);
			if( (substr($synopsis, -1) == '\'') and (substr($synopsis, -2) != "\\'")) {
				$done = true;
			} else {
				$done = false;
			}
			$counter = 6;
			while($done == false) {
				$synopsis = $synopsis . "," . $split_by_comma[$counter];
				if((substr($synopsis, -1) == '\'') and (substr($split_by_comma[$counter + 1],0, 1) == '\'')) {
					$done = true;
				}
				$counter++;
			}
				
			$synopsis = trim($synopsis);
			$synopsis = substr($synopsis, 1, (strlen($synopsis) - 2));
				
			echo $synopsis . "<br/>"; */
			
 	/* 		if($this->insert_model->check_if_anime_exists($id)) {
				$this->insert_model->update_synopsis($id, $synopsis);
				echo $id  . "    " . $synopsis . "<br/>";
			}  */
		}
		fclose($myfile);
	}
	
}
?>