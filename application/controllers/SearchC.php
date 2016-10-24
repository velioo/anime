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
	
	public function search_anime($order=SORT_ASC) {
		$this->load->model('search_model');
		if($this->input->get('search') !== NULL) {
			$anime = $this->input->get('search');
			$query = $this->search_model->search_animes($anime);
		} else {
			$query = $this->search_model->search_animes($this->input->get('last_search'));
		}
		
		if($this->input->get('sort_selected') !== NULL)
			$sort_by = $this->input->get('sort_selected');
		else {
			$sort_by = 'name';
		}
		
		if($query) {
			$data['animes_matched'] = $query;
			if(isset($anime)) {
				if($anime == '')
					$data['header'] = 'All animes';
				else
					$data['header'] = 'Results for "' . $anime . '"';
			} else {
				if($this->input->get('last_search') == '')
					$data['header'] = 'All animes';
				else
					$data['header'] = 'Results for "' . $this->input->get('last_search') . '"';
			}

			if($this->input->get('sort_order') !== NULL) {
				$order = $this->input->get('sort_order');
				switch ($order) {
					case "ASC":
						$order = SORT_ASC;
						break;
					case "DESC":
						$order = SORT_DESC;
						break;
					default:
						$order = SORT_ASC;
						break;
				}
			}
			
			if($this->input->get('sort_selected') !== NULL) {
				$data['sort_type'] = $this->input->get('sort_selected');
			} else {
				$data['sort_type'] = 'name';
			}
			
			$data['animes_matched'] = $this->array_sort($data['animes_matched'], $sort_by, $order);
		} else {
			$data['header'] = 'Browse Animes';
		}
		
		if(isset($anime))
			$data['last_search'] = $anime;
		else 
			$data['last_search'] = $this->input->get('last_search');
		
		$data['title'] = 'V-Anime';
		$data['css'] = 'login.css';
		$data['javascript'] = 'home.js';
		$this->load->view('search_page', $data);
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
	
	function array_sort($array, $on, $order=SORT_ASC) {
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
	            case SORT_ASC:
	                asort($sortable_array);
	            break;
	            case SORT_DESC:
	                arsort($sortable_array);
	            break;
	        }
	
	        foreach ($sortable_array as $k => $v) {
	            $new_array[$k] = $array[$k];
	        }
	    }
	
	    return $new_array;
	}
}

?>