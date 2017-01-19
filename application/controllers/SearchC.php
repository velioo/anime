<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SearchC extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('helpers_model');
	}
	
	public function index() {
		redirect("home");
	}
	
	public function search() {
		$this->allow_get_array = TRUE;
		$this->load->model('search_model');
		
		switch ($this->input->get('search_select')) {
			case 'animes':
				$this->search_anime();
				break;
			case 'characters':
				$this->search_character();
				break;
			case 'users':
				$this->search_users();
				break;
			case 'people':
				$this->search_people();
				break;
			default:
				$this->index();
		}
		
	}
	
	public function search_anime() {			
		$this->load->model('search_model');
		$this->load->model('watchlist_model');
		$this->load->library('pagination');

		$config = $this->configure_pagination();	
		
		$config['base_url'] = site_url("SearchC/search_anime");
		$config['per_page'] = 30;
		
		$sortable_columns = $this->get_sortable_columns_anime();
		
		if($this->input->get('search') !== NULL) { //get user search
			$anime = htmlspecialchars($this->input->get('search'));					
		} else if($this->input->get('last_search') !== NULL) {
			$anime = htmlspecialchars($this->input->get('last_search')); //get last search if user sorts results, goes to next page
		} else {
			$anime = "";
		}
		
		
		if($this->input->get('sort_selected') !== NULL) { // check which sort option is selected
			if(!in_array($this->input->get('sort_selected'), $sortable_columns)) {
				$sort_by = 'slug';
			} else {
				$sort_by = $this->input->get('sort_selected');
			}
		} else {
			if($anime == "") {
				$sort_by = 'average_rating';
			} else {
				$sort_by = 'slug';
			}
		}
		
		$user_sorted_results = FALSE;
		
		 if($this->input->get('sort_order') !== NULL) {
		 	if($this->input->get('sort_selected') != "") {
		 		$user_sorted_results = TRUE;
		 	} 
		 	if($sort_by == 'start_date')
		 		$order = "DESC";
		 	else if($sort_by == 'average_rating')
		 		$order = "DESC";
			else {	
				$order = $this->input->get('sort_order');
				if($order != "ASC" && $order != "DESC") {
					$order = "ASC";
				}
			}
		} else {
			if($anime == "") {	
				$order = "DESC";
			} else {
				$order = "ASC";
			}	
		}
		
		
		if($this->input->get('page') != NULL and is_numeric($this->input->get('page'))) { //calculate the offset for next page
			$start = $this->input->get('page') * $config['per_page'] - $config['per_page'];
		} else {
			$start = 0;
		}
		
		//filters
		
		$ratings_filter = array();
		
		if($this->input->get('avg_amount1') != NULL && $this->input->get('avg_amount1') != 0) {
			$ratings_filter['greater'] = $this->input->get('avg_amount1');
		} 
		
		if($this->input->get('avg_amount2') != NULL && $this->input->get('avg_amount2') != 5) {
			$ratings_filter['less'] = $this->input->get('avg_amount2');
		} 

		if($this->input->get('genre[]') != NULL) {
			$checked_genres = $this->input->get('genre[]');
			for ($i = 0; $i < count($checked_genres); $i++) {
				$checked_genres[$i] = str_replace("_", " ", $checked_genres[$i]);
				$checked_genres[$i] = ucwords($checked_genres[$i]);
			}
		} else {
			$checked_genres = array();
		}
		
		if($this->input->get('type') != NULL) {
			$type = $this->input->get('type');
		} else {
			$type = NULL;
		}
		
		$episodes_filter = array();
		
		if($this->input->get('min_episodes') != NULL) {
			$episodes_filter['min'] = $this->input->get('min_episodes');
		} 
		
		if($this->input->get('max_episodes') != NULL) {
			$episodes_filter['max'] = $this->input->get('max_episodes');
		}
		
		$year_filter = array();
		
		if($this->input->get('min_year') != NULL) {
			$year_filter['min'] = $this->input->get('min_year');
		}
		
		if($this->input->get('max_year') != NULL) {
			$year_filter['max'] = $this->input->get('max_year');
		} 
		
		$filters = array('ratings' => $ratings_filter, 'genres' => $checked_genres, 'type' => $type, 'episodes' => $episodes_filter, 'year' => $year_filter);
		
		//end filters
		
		$temp = $anime;
		$anime = addslashes($anime);
		$sort_by = addslashes($sort_by);
		$order = addslashes($order);
		
		$animes = $this->search_model->search_animes($anime, $config['per_page'], $start, $sort_by, $order, $filters, $user_sorted_results);
		$config['total_rows'] = $this->search_model->get_animes_count($anime, $config['per_page'], $start, $sort_by, $order, $filters);
		
		if($this->session->userdata('is_logged_in') && $config['total_rows'] > 0) {
			$animes = $this->watchlist_model->add_user_statuses($animes);
		}
		
		$anime = $temp;
		
		if(($anime != "") && ($user_sorted_results == TRUE) && ($animes !== FALSE)) {
			$animes = $this->array_sort($animes, $sort_by, $order);
			$data['sort_by'] = $sort_by;
		} else if($anime == "") {
			$data['sort_by'] = $sort_by;
		} else {
			$data['sort_by'] = "";
		}

		//if($animes) {		
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();		
		$data['animes_matched'] = $animes;			
		//}
		
		$data['filters'] = $filters;
		$data['last_search'] = $anime;	
		$data['title'] = 'V-Anime';
		if($anime == "")
			$data['header'] = "Browse Anime";
		else
			$data['header'] = 'Results for ' . "\"" . $anime . "\"";
		$data['css'] = 'search_animes.css';
		$data['javascript'] = 'home.js';
		$this->load->view('search_page', $data);
	}
	
	function array_sort($array, $on, $order="ASC") {
		$new_array = array();
		$sortable_array = array();
	
		if (count($array) > 0) {
			foreach ($array as $k => $v) {
				if (is_array($v)) {
					foreach ($v as $k2 => $v2) {
						if ($k2 == $on) {
							$sortable_array[$k] = $v2;
						}
					}
				} else {
					$sortable_array[$k] = $v;
				}
			}
	
			switch ($order) {
				case "ASC":
					asort($sortable_array);
					break;
				case "DESC":
					arsort($sortable_array);
					break;
			}
	
			foreach ($sortable_array as $k => $v) {
				$new_array[$k] = $array[$k];
			}
		}
	
		return $new_array;
	}

	function configure_pagination() {
		$config['num_links'] = 4;
		$config['use_page_numbers'] = TRUE;
		$config['page_query_string'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		$config['query_string_segment'] = 'page';
		$config['full_tag_open'] = "<ul class='pagination'>";
		$config['full_tag_close'] ="</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tagl_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";
		
		return $config;
	}
	
	function get_sortable_columns_anime() {		
		$columns = "id,slug,episode_count,episode_length,synopsis,average_rating, age_rating_guide,show_type,start_date,end_date,poster_image_file_name,titles,created_at";	
		$columns = explode(",", $columns);
		
		return $columns;
	}
	
	public function search_character() {
		
		$this->load->model('search_model');
		$this->load->model('characters_model');
		$this->load->library('pagination');
		
		$config = $this->configure_pagination();
		
		$config['base_url'] = site_url("SearchC/search_character");
		$config['per_page'] = 50;
		
		if($this->input->get('search') !== NULL) { //get user search
			$character = htmlspecialchars($this->input->get('search'));
		} else if($this->input->get('last_search') !== NULL) {
			$character = htmlspecialchars($this->input->get('last_search')); //get last search if user sorts results, goes to next page
		} else {
			$character = "";
		}
		
		if($this->input->get('page') != NULL and is_numeric($this->input->get('page'))) { //calculate the offset for next page
			$start = $this->input->get('page') * $config['per_page'] - $config['per_page'];
		} else {
			$start = 0;
		}
		
		$temp = $character;
		$character = addslashes($character);

		$query = $this->search_model->search_characters($character, $config['per_page'], $start);
		$config['total_rows'] = $this->search_model->get_characters_count($character, $config['per_page'], $start);
		
		$character = $temp;
		
		if($query) {
			$this->pagination->initialize($config);
			$data['pagination'] = $this->pagination->create_links();
			$data['characters_matched'] = $query;
		}
		
		$data['last_search'] = $character;
		$data['title'] = 'V-Anime';
		if($character == "")
			$data['header'] = "Browse Characters";
		else
			$data['header'] = 'Results for ' . "\"" . $character . "\"";
		
		$data['css'] = 'search_characters.css';
		$data['javascript'] = 'home.js';
		$this->load->view('search_page', $data);
	}
	
	public function search_users() {

		$user = htmlspecialchars($this->input->get('search'));
		
		$query = $this->search_model->search_users($user);
		
		if($query) {
			$data['users_matched'] = $query;
			if($user == '')
				$data['header'] = 'All users';
			else
				$data['header'] = 'Results for "' . $user . '"';
		} else {
			$data['header'] = 'Browse Users';
		}
		
		$data['title'] = 'V-Anime';
		$data['css'] = 'search_users.css';
		$this->load->view('search_page', $data);
		
	}
	
	public function search_people() {
		$this->load->model('search_model');
		$this->load->model('actors_model');
		$this->load->library('pagination');
		
		$config = $this->configure_pagination();
		
		$config['base_url'] = site_url("SearchC/search_people");
		$config['per_page'] = 50;
		
		if($this->input->get('search') !== NULL) { //get user search
			$actor = htmlspecialchars($this->input->get('search'));
		} else if($this->input->get('last_search') !== NULL) {
			$actor = htmlspecialchars($this->input->get('last_search')); //get last search if user sorts results, goes to next page
		} else {
			$actor = "";
		}
		
		if($this->input->get('page') != NULL and is_numeric($this->input->get('page'))) { //calculate the offset for next page
			$start = $this->input->get('page') * $config['per_page'] - $config['per_page'];
		} else {
			$start = 0;
		}
		
		$temp = $actor;
		$actor = addslashes($actor);
		
		$query = $this->search_model->search_actors($actor, $config['per_page'], $start);
		$config['total_rows'] = $this->search_model->get_actors_count($actor, $config['per_page'], $start);
		
		$actor = $temp;
		
		if($query) {
			$this->pagination->initialize($config);
			$data['pagination'] = $this->pagination->create_links();
			$data['actors_matched'] = $query;
		}
		
		$data['last_search'] = $actor;
		$data['title'] = 'V-Anime';
		if($actor == "")
			$data['header'] = "Browse Actors";
			else
				$data['header'] = 'Results for ' . "\"" . $actor . "\"";
		
				$data['css'] = 'search_actors.css';
				$data['javascript'] = 'home.js';
				$this->load->view('search_page', $data);
	}
	
	public function search_lists() {
		redirect("home");
		$list = $this->input->get('search');
		
		$query = $this->search_model->search_lists($list);
		
		if($query) {
			$data['lists_matched'] = $query;
			if($list == '')
				$data['header'] = 'All lists';
			else
				$data['header'] = 'Results for "' . $list . '"';
		} else {
			$data['header'] = 'Browse Lists';
		}
		
		$data['title'] = 'V-Anime';
		$data['css'] = 'login.css';
		$this->load->view('search_page', $data);
	}
	

}

?>