<?php

class SearchC extends CI_Controller {
	
	public function index() {
		$this->search_page();
	}
	
	public function search() {
		$this->allow_get_array = TRUE;
		$this->load->model('search_model');
		
		switch ($this->input->get('search_select')) {
			case 'anime':
				$this->search_anime();
				break;
			case 'characters':
				$this->search_characters();
				break;
			case 'users':
				$this->search_users();
				break;
			case 'lists':
				$this->search_lists();
				break;
			default:
				$this->load->library('../controllers/home');
		}
		
	}
	
	public function search_anime() {			
		$this->load->model('search_model');
		$this->load->library('pagination');

		$config['base_url'] = site_url("SearchC/search_anime");
		$config['per_page'] = 30;
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
		
		
		if($this->input->get('search') !== NULL) { //get user search
			$anime = $this->input->get('search');					
		} else {
			$anime = $this->input->get('last_search');//get last search if user sorts results, goes to next page
		}
		
		if($this->input->get('sort_selected') !== NULL) {// check which sort option is selected
			$sort_by = $this->input->get('sort_selected');
		} else {
			if($anime == "") {
				$sort_by = 'start_date';
			} else {
				$sort_by = 'slug';
			}
		}
		
		$user_sorted_results = FALSE;
				
		 if($this->input->get('sort_order') !== NULL) {
		 	$user_sorted_results = TRUE;
		 	if($sort_by == 'start_date')
		 		$order = "DESC";
		 	else if($sort_by == 'average_rating')
		 		$order = "DESC";
			else	
				$order = $this->input->get('sort_order');
		} else {
			$user_sorted_results = FALSE;
			if($anime == "") {	
				$order = "DESC";
			} else {
				$order = "ASC";
			}	
		}
		
		if($this->input->get('page') != NULL) { //calculate the offset for next page
			$start =$this->input->get('page') * $config['per_page'] - $config['per_page'];
		} else {
			$start = 0;
		}
		
		$temp = $anime;	
		$anime = addslashes($anime);
		$sort_by = addslashes($sort_by);
		$order = addslashes($order);
		
		$query = $this->search_model->search_animes($anime, $config['per_page'], $start, $sort_by, $order, $user_sorted_results);
		$config['total_rows'] = $this->search_model->get_animes_count($anime, $config['per_page'], $start, $sort_by, $order);
		
		$anime = $temp;
		
		if(($anime != "") and $this->input->get('sort_order') !== NULL) {
			$query = $this->array_sort($query, $sort_by, $order);
			$data['sort_by'] = $sort_by;
		} else if($anime == "") {
			$data['sort_by'] = $sort_by;
		} else {
			$data['sort_by'] = "";
		}

		if($query) {		
			$this->pagination->initialize($config);
			$data['pagination'] = $this->pagination->create_links();		
			$data['animes_matched'] = $query;			
			//$data['animes_matched'] = $this->array_sort($data['animes_matched'], $sort_by, $order);
		}
		
		$data['last_search'] = $anime;	
		$data['title'] = 'V-Anime';
		if($anime == "")
			$data['header'] = "Browse Anime";
		else
			$data['header'] = 'Results for ' . "\"" . $anime . "\"";
		$data['css'] = 'login.css';
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

	
	
	public function search_characters() {
		$character = $this->input->get('search');
		
		$query = $this->search_model->search_characters($character);
		
		if($query) {
			$data['characters_matched'] = $query;
			if($character == '')
				$data['header'] = 'All characters';
			else
				$data['header'] = 'Results for "' . $character . '"';
		} else {
			$data['header'] = 'Browse Characters';
		}
		
		$data['title'] = 'V-Anime';
		$data['css'] = 'login.css';
		$data['javascript'] = 'home.js';
		$this->load->view('search_page', $data);
	}
	
	public function search_users() {

		$user = $this->input->get('search');
		
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
		$data['css'] = 'login.css';
		$data['javascript'] = 'home.js';
		$this->load->view('search_page', $data);
		
	}
	
	public function search_lists() {
		
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
		$data['javascript'] = 'home.js';
		$this->load->view('search_page', $data);
	}
	

}

?>