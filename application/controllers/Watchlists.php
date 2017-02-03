<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Watchlists extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('helpers_model');
	}
	
	public function user_watchlist($username=NULL, $page=NULL) {		
		$this->load->model('watchlist_model');
		$this->load->model('users_model');
		
		if($username != null) {
		
			if((isset($this->session->userdata['is_logged_in'])) and ($this->session->userdata['username'] == $username)) {
				$query = $this->users_model->get_user_info_logged($username);
			} else {
				$query = $this->users_model->get_user_info($username);
				if($query === FALSE) {
					$this->helpers_model->page_not_found();
				}
			}
			
			$data['user'] = $query;
			
			if($page !== NULL) {
				$data['page'] = get_watchlist_status_id($page);
			} else {
				if((isset($this->session->userdata['is_logged_in'])) and ($this->session->userdata['username'] == $username)) {
					$data['page'] = $data['user']['default_watchlist_page'];
				} else {
					$data['page'] = 0;
				}
			}		
			
			$data = $this->load_watchlist($data);
			
			$data['title'] = $username . '\'s profile';
			$data['css'] = 'watchlist.css';
			$this->load->view('watchlist', $data);
		} else {
			$this->helpers_model->bad_request();
		}
	}

	
	public function update_status() {
		$this->load->model('watchlist_model');
		
		if($this->session->userdata('is_logged_in')) {
		
			$anime_id = $this->input->post('anime_id');
			$status = $this->input->post('status');
	
			$exists = $this->watchlist_model->get_watchlist_status_score($anime_id);
				
			if($exists !== FALSE) { 
				if($status != 6) {
					$query = $this->watchlist_model->update_status($anime_id, $status);		
				} else {
					$query = $this->watchlist_model->delete_status($anime_id, $status);
					if($query) {
						$this->watchlist_model->calculate_anime_score($anime_id);
					}
				}
			} else {
				$query = $this->watchlist_model->add_status($anime_id, $status);		
			}		
	
			if($query !== FALSE) {
				echo "Success";
			} else {
				echo "Fail";
			}
		} else {
			$this->helpers_model->bad_request();
		}
	}
	
	public function update_score() {
		$this->load->model('watchlist_model');
		
		if($this->session->userdata('is_logged_in')) {
	
			$anime_id = $this->input->post('anime_id');
			$value = $this->input->post('value');
		
			$query = $this->watchlist_model->update_score($anime_id, $value);
		
			if($query !== FALSE) {
				echo "Success";
			} else {
				echo "Fail";
			}
		} else {
			$this->helpers_model->bad_request();
		}
	}
	
	public function update_eps() {
		$this->load->model('watchlist_model');
	
		if($this->session->userdata('is_logged_in')) {
		
			$anime_id = $this->input->post('anime_id');
			$eps_watched = $this->input->post('eps_watched');
		
			$query = $this->watchlist_model->update_eps($anime_id, $eps_watched);
		
			if($query !== FALSE) {
				echo "Success";
			} else {
				echo "Fail";
			}
		} else {
			$this->helpers_model->bad_request();
		}
	}
	
	public function update_default_watchlist_sort() {
		$this->load->model('watchlist_model');
		
		if($this->session->userdata('is_logged_in')) {
			
			$default_watchlist_sort = $this->input->post('default_watchlist_sort');
			
			$this->watchlist_model->update_default_watchlist_sort($default_watchlist_sort);
			
		} else {
			$this->helpers_model->bad_request();
		}
	}
	
	public function load_watchlist($data) {
		$this->load->model('watchlist_model');
		
		$user_id = $data['user']['id'];
		
		if(isset($user_id)) {
								
			$query = $this->watchlist_model->get_watchlist($user_id, $data['page']);
			
			if($query !== FALSE) {
								
				 $watched = array();
				 $watching = array();
				 $want_to_watch = array();
				 $stalled = array();
				 $dropped = array();
				
				 $row_counter = 0;
				 $id_counter = 0;
	
				  foreach($query as $row) {	
				  		
				  		$row_counter++;
				  		
					  	$element = "";
					  	$status = $row['status'];
					  	$slug = str_replace(" ", "-", $row['slug']);
					  	$type = get_show_type($row['show_type']);			  	
					  	$temp = explode("-", $row['start_date']);
					  	$year = $temp[0];
					  	
					  	if($year == "0000") {
					  		$year = "????";
					  	}
					  	
					  	$score_percentage = (($row['score'] * 10) . "%");
					  			  	
					  	if($row['episode_count'] == 0) {
					  		if($row['show_type'] == 1) {
					  			$row['episode_count'] = "?";
					  		} else if($row['show_type'] == 5) {
					  			$row['episode_count'] = 1;
					  		}
					  	}
					  					  	
						$element.= '<tr class="anime_row" data-id="' . $status .'"> 
									<td data-id="' . $row['anime_id'] .'" class="title" style="padding-top: 13px;"><span class="hidden_row_number">' . $row_counter . '</span><a href="' . site_url("animeContent/anime/{$slug}") .'" class="disable-link-decoration" ><span class="red-text">' . convert_titles_to_hash($row['titles'])['main'] .'</span></a></td>
									<td class="type">' . $type .'</td>
									<td class="year">' . $year .'</td>';
						if($this->session->userdata('id') == $user_id) {
							$element.='<td class="anime_progress" style="text-align: center;"><span class="hidden_watched_eps">' . $row['eps_watched'] .'</span><input type="text" class="progress_input" name="progress_input" value="'. $row['eps_watched'] .'"> / '. '<span class="max_episodes" style="display: inline;">' . $row['episode_count'] . '</span>' .' <button class="button-black count_up">+Ep</button></td>';
						} else {
							$element.='<td class="anime_progress" style="text-align: center;"><span class="eps_watched_guest">'. $row['eps_watched'] .'</span> / '. $row['episode_count'] .'</td>';
							
						}
							$element.='<td class="avg" style="padding-top: 13px;">' . number_format($row['average_rating']/2, 2) .'</td>';
						
						if($this->session->userdata('id') == $user_id) {
							
							$element.='<td class="anime_rating">
									<span class="hidden_user_score">' . $row['score'] . '</span>
									<div data-id="' . $row['score'] . '" class="star-rating">
	
									    <input id="Ans_' . $id_counter++ .'" class="rb0" name="userScore'. $row_counter .'" type="radio" value="0" ';
									    	if($row['score'] == 0)
									    		$element.='checked="checked"';
									    $element.='/>                       
									    <input id="Ans_' . $id_counter++ .'" class="rb1" name="userScore'. $row_counter .'" type="radio" value="1" ';								    		
									    	if($row['score'] == 1)
									    		$element.='checked="checked"';
									    $element.='/>   
									    <input id="Ans_' . $id_counter++ .'" class="rb2" name="userScore'. $row_counter .'" type="radio" value="2" ';
									    if($row['score'] == 2)
									    	$element.='checked="checked"';
									    $element.='/>			
									    <input id="Ans_' . $id_counter++ .'" class="rb3" name="userScore'. $row_counter .'" type="radio" value="3" ';
									    if($row['score'] == 3)
									    	$element.='checked="checked"';
									    $element.='/>    
									    <input id="Ans_' . $id_counter++ .'" class="rb4" name="userScore'. $row_counter .'" type="radio" value="4" ';
									    if($row['score'] == 4)
									    	$element.='checked="checked"';
									    $element.='/>  
									    <input id="Ans_' . $id_counter++ .'" class="rb5" name="userScore'. $row_counter .'" type="radio" value="5" ';
									    if($row['score'] == 5)
									    	$element.='checked="checked"';
									    $element.='/>  
									    <input id="Ans_' . $id_counter++ .'" class="rb6" name="userScore'. $row_counter .'" type="radio" value="6" ';
									    if($row['score'] == 6)
									    	$element.='checked="checked"';
									    $element.='/>
									    <input id="Ans_' . $id_counter++ .'" class="rb7" name="userScore'. $row_counter .'" type="radio" value="7" ';								    
									    if($row['score'] == 7)
									    	$element.='checked="checked"';
									    $element.='/>    
									    <input id="Ans_' . $id_counter++ .'" class="rb8" name="userScore'. $row_counter .'" type="radio" value="8" ';
									    if($row['score'] == 8)
									    	$element.='checked="checked"';
									    $element.='/>
									    <input id="Ans_' . $id_counter++ .'" class="rb9" name="userScore'. $row_counter .'" type="radio" value="9" ';									    
									    if($row['score'] == 9)
									    	$element.='checked="checked"';
									    $element.='/> 
									    <input id="Ans_' . $id_counter++ .'" class="rb10" name="userScore'. $row_counter .'" type="radio" value="10" ';
									    if($row['score'] == 10)
									    	$element.='checked="checked"';
									    $element.='/>';
							
									   $id_counter-=11; 
									   
							 $element.='<label for="Ans_' . $id_counter++ .'" class="star rb0l"></label>
									    <label for="Ans_' . $id_counter++ .'" class="star rb1l"></label>
									    <label for="Ans_' . $id_counter++ .'" class="star rb2l"></label>
									    <label for="Ans_' . $id_counter++ .'" class="star rb3l"></label>
									    <label for="Ans_' . $id_counter++ .'" class="star rb4l"></label>
									    <label for="Ans_' . $id_counter++ .'" class="star rb5l"></label>
									    <label for="Ans_' . $id_counter++ .'" class="star rb6l"></label>
									    <label for="Ans_' . $id_counter++ .'" class="star rb7l"></label>
									    <label for="Ans_' . $id_counter++ .'" class="star rb8l"></label>
									    <label for="Ans_' . $id_counter++ .'" class="star rb9l"></label>
									    <label for="Ans_' . $id_counter++ .'" class="star rb10l last"></label>';
							 			$id_counter-=11;
							 $element.='<label for="Ans_' . $id_counter++ .'" class="rb">0</label>
									    <label for="Ans_' . $id_counter++ .'" class="rb">1</label>
									    <label for="Ans_' . $id_counter++ .'" class="rb">2</label>
									    <label for="Ans_' . $id_counter++ .'" class="rb">3</label>
									    <label for="Ans_' . $id_counter++ .'" class="rb">4</label>
									    <label for="Ans_' . $id_counter++ .'" class="rb">5</label>
									    <label for="Ans_' . $id_counter++ .'" class="rb">6</label>
									    <label for="Ans_' . $id_counter++ .'" class="rb">7</label>
									    <label for="Ans_' . $id_counter++ .'" class="rb">8</label>
									    <label for="Ans_' . $id_counter++ .'" class="rb">9</label>
									    <label for="Ans_' . $id_counter++ .'" class="rb">10</label>
									    
									    <div class="rating"></div>
									    <div class="rating-bg"></div> 
									</div> 
									</td>
									<td class="status"> 
										<button class="watchlist_button button-red">' .get_watchlist_status_name($status) .'<span class="watchlist_caret fa fa-caret-down"></span></button>
									    <div class="w3-dropdown-content w3-border watchlist_dropdown">
									      <a class="watchlist_item" data-id="1">Watched</a>
									      <a class="watchlist_item" data-id="2">Watching</a>
									      <a class="watchlist_item" data-id="3">Want to Watch</a>
									      <a class="watchlist_item" data-id="4">Stalled</a>
									      <a class="watchlist_item" data-id="5">Dropped</a>
									      <a class="watchlist_item" data-id="6" style="color: red;">Remove</a>
									    </div>
										<div class="loader_image_div">
											<img src="' .  asset_url() . "imgs/loading_icon_2.gif" . '" class="loader_image">
										</div>
									</td>';
							
						} else {
							$element.='<td title="' . $row['score']/2 . ' out of 5'  .'" class="anime_rating">
										<span class="hidden_user_score">' . $row['score'] . '</span>
										<div class="star-ratings-sprite" style="display: inline-block;">
											<span style="width:' . $score_percentage .';" class="star-ratings-sprite-rating"></span>
										</div>
									  </td>
									<td class="status"><div class="watchlist_guest_status">' . get_watchlist_status_name($status) . '</div></td>';
						}
						
						$element.='</tr>';
						
						switch($status) {
							case 1: 
								$watched[] = $element;
								break;
							case 2:
								$watching[] = $element;
								break;
							case 3: 
								$want_to_watch[] = $element;
								break;
							case 4:
								$stalled[] = $element;
								break;
							case 5: 
								$dropped[] = $element;
								break;
						}						
						
				  }  			  
				  
				  $data['watched_animes'] = $watched;
				  $data['watching_animes'] = $watching;
				  $data['want_to_watch_animes'] = $want_to_watch;
				  $data['stalled_animes'] = $stalled;
				  $data['dropped_animes'] = $dropped;  
			} 
		}	
		
		return $data;	
	}
	
	function get_default_watchlist_sort() {
		$this->load->model('users_model');		
		$query = $this->users_model->get_user_info_logged($this->session->userdata('username'));		
		echo $query['default_watchlist_sort'];
	}
}
?>