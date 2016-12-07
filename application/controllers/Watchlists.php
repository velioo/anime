<?php
class Watchlists extends CI_Controller {
	
	public function user_watchlist($username) {		
		$this->load->model('watchlist_model');
		$this->load->model('users_model');
		
		if((isset($this->session->userdata['is_logged_in'])) and ($this->session->userdata['username'] == $username)) {
			$query = $this->users_model->get_user_info_logged($username);
		} else {
			$query = $this->users_model->get_user_info($username);
			if(!$query) {
				$this->page_not_found();
			}
		}
		
		$data['user'] = $query;
		
		$data['title'] = $username . '\'s profile';
		$data['css'] = 'watchlist.css';
		$this->load->view('watchlist', $data);
	}

	
	public function update_status() {
		$this->load->model('watchlist_model');
		
		$anime_id = $this->input->post('anime_id');
		$status = $this->input->post('status');

		$query = $this->watchlist_model->update_status($anime_id, $status);			

		if($query) {
			echo "Success";
		} else {
			echo "Fail";
		}
	}
	
	public function update_score() {
		$this->load->model('watchlist_model');
	
		$anime_id = $this->input->post('anime_id');
		$value = $this->input->post('value');
	
		$query = $this->watchlist_model->update_score($anime_id, $value);
	
		if($query) {
			echo "Success";
		} else {
			echo "Fail";
		}
	}
	
	public function update_eps() {
		$this->load->model('watchlist_model');
	
		$anime_id = $this->input->post('anime_id');
		$eps_watched = $this->input->post('eps_watched');
	
		$query = $this->watchlist_model->update_eps($anime_id, $eps_watched);
	
		if($query) {
			echo "Success";
		} else {
			echo "Fail";
		}
	}
	
	public function load_watchlist() {
		$this->load->model('watchlist_model');
		
		$user_id = $this->input->post('user_id');
		
		$query = $this->watchlist_model->get_watchlist($user_id);
		
		if($query) {
			
			 $rows = array();
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
				  			  	
				  	
					$element.= '<tr data-id="' . $status .'">
								<td data-id="' . $row['anime_id'] .'" class="title" style="padding-top: 17px;"><a href="' . site_url("animeContent/anime/{$slug}") .'" class="disable-link-decoration" ><span class="red-text">' . convert_titles_to_hash($row['titles'])['main'] .'</span></a></td>
								<td class="type">' . $type .'</td>
								<td class="year">' . $year .'</td>';
					if($this->session->userdata('id') == $user_id) {
						$element.='<td class="anime_progress" style="text-align: center;"><input type="text" class="progress_input" name="progress_input" value="'. $row['eps_watched'] .'"> / '. '<span class="max_episodes" style="display: inline;">' . $row['episode_count'] . '</span>' .' <button class="button-black count_up">+Ep</button></td>';
					} else {
						$element.='<td class="anime_progress" style="text-align: center;">'. $row['eps_watched'] .' / '. $row['episode_count'] .'</td>';
						
					}
						$element.='<td class="avg" style="padding-top: 17px;">' . number_format($row['average_rating']/2, 2) .'</td>';
					
					if($this->session->userdata('id') == $user_id) {
						
						$element.='<td class="anime_rating" >
								<div data-id="' . $row['score'] . '" class="star-rating">

								    <input id="Ans_' . $id_counter++ .'" class="rb0" name="userScore'. $row_counter .'" type="radio" value="0"/>                       
								    <input id="Ans_' . $id_counter++ .'" class="rb1" name="userScore'. $row_counter .'" type="radio" value="1"/>
								    <input id="Ans_' . $id_counter++ .'" class="rb2" name="userScore'. $row_counter .'" type="radio" value="2"/>
								    <input id="Ans_' . $id_counter++ .'" class="rb3" name="userScore'. $row_counter .'" type="radio" value="3"/>    
								    <input id="Ans_' . $id_counter++ .'" class="rb4" name="userScore'. $row_counter .'" type="radio" value="4"/>    
								    <input id="Ans_' . $id_counter++ .'" class="rb5" name="userScore'. $row_counter .'" type="radio" value="5"/>    
								    <input id="Ans_' . $id_counter++ .'" class="rb6" name="userScore'. $row_counter .'" type="radio" value="6"/>
								    <input id="Ans_' . $id_counter++ .'" class="rb7" name="userScore'. $row_counter .'" type="radio" value="7"/>    
								    <input id="Ans_' . $id_counter++ .'" class="rb8" name="userScore'. $row_counter .'" type="radio" value="8"/>
								    <input id="Ans_' . $id_counter++ .'" class="rb9" name="userScore'. $row_counter .'" type="radio" value="9"/>
								    <input id="Ans_' . $id_counter++ .'" class="rb10" name="userScore'. $row_counter .'" type="radio" value="10"/>';
						
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
						$element.='<td title="' . $row['score']/2 . ' out of 5'  .'" class="anime_rating" style="padding-left: 0px;">
									<div class="star-ratings-sprite" style="display: inline-block;">
										<span style="width:' . $score_percentage .';" class="star-ratings-sprite-rating"></span>
									</div>
								  </td>';
					}
					
					$element.='</tr>';
						
					$rows[] = $element;
			  }  			  
			  
			  foreach($rows as $element) {
			     echo $element;
			  }
		}
		
	}
	
	function page_not_found() {
		header('HTTP/1.0 404 Not Found');
		echo "<h1>Error 404 Not Found</h1>";
		echo "The page that you have requested could not be found.";
		exit();
	}
}
?>