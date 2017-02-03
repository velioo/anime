<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('helpers_model');
	}
	
	public function index() {
		$this->profile();
	}
	
	public function profile($username = NULL) {
		
		if($username != NULL) {
			$this->load->model('users_model');
			$this->load->model('posts_model');
			$this->load->model('characters_model');
			$this->load->model('watchlist_model');
			$data['title'] = $username . '\'s profile';
			$data['css'] = 'user.css';
			$data['header'] = $username;
			if((isset($this->session->userdata['is_logged_in'])) and ($this->session->userdata['username'] == $username)) {
				$user = $this->users_model->get_user_info_logged($username);
			} else {
				$user = $this->users_model->get_user_info($username);
				if($user === FALSE) {
					$this->helpers_model->page_not_found();
				}
			}
			
			$data['user'] = $user;
			
			$total_posts = $this->posts_model->get_total_posts($user['id']);			
			$posts_per_page = 10;
			$data['total_groups'] = ceil($total_posts/$posts_per_page);
			
			$status = 1;			
			$data['random_loved_characters'] = $this->characters_model->get_user_characters($user['id'], $status);
			shuffle($data['random_loved_characters']);
			$status = 0;
			$data['random_hated_characters'] = $this->characters_model->get_user_characters($user['id'], $status);
			shuffle($data['random_hated_characters']);
			
			$watchlist_statuses = $this->watchlist_model->get_all_watchlist_statuses($user['id']);
			
			$data = $this->process_statuses_scores_time($data, $watchlist_statuses);
			
			// /60 - hours, /60*24 - days, 60*24*7 - weeks, /60*24*7*4 - months, /60*24*7*4*12
						
			$this->load->view('user_page', $data);
		} else {
			$this->helpers_model->page_not_found();
		}
	}
	
	function process_statuses_scores_time($data, $rows) {
		
		$total_episodes = 0;
		$total_minutes = 0;
		$total_scores = 0;
			
		$statuses = array_fill(0, 5, 0);
		$scores = array_fill(0, 11, 0);
			
		foreach($rows as $row) {
			switch($row['status']) {
				case 1:
					$statuses[0]++;
					break;
				case 2:
					$statuses[1]++;
					break;
				case 3:
					$statuses[2]++;
					break;
				case 4:
					$statuses[3]++;
					break;
				case 5:
					$statuses[4]++;
					break;
				default:
					break;
			}
		
			switch($row['score']) {
				case 1:
					$scores[0]++;
					break;
				case 2:
					$scores[1]++;
					break;
				case 3:
					$scores[2]++;
					break;
				case 4:
					$scores[3]++;
					break;
				case 5:
					$scores[4]++;
					break;
				case 6:
					$scores[5]++;
					break;
				case 7:
					$scores[6]++;
					break;
				case 8:
					$scores[7]++;
					break;
				case 9:
					$scores[8]++;
					break;
				case 10:
					$scores[9]++;
					break;
				default:
					break;
			}
		
			if($row['score'] != 0) {
				$scores[10]++;
			}
		
			$total_episodes+=$row['eps_watched'];
			$total_minutes+=($row['eps_watched'] * $row['episode_length']);
		}
			
		$data['scores'] = $scores;
			
		$data['user']['watched'] = $statuses[0];
		$data['user']['watching'] = $statuses[1];
		$data['user']['want_to_watch'] = $statuses[2];
		$data['user']['stalled'] = $statuses[3];
		$data['user']['dropped'] = $statuses[4];
		$data['user']['total_episodes'] = $total_episodes;
		
		$years = 0;
		$months = 0;
		$weeks = 0;
		$days = 0;
		$hours = 0;
		$minutes = 0;
			
		while($total_minutes >= YEAR_DELIM) {
			$years++;
			$total_minutes-=YEAR_DELIM;
		}
		while($total_minutes >= MONTH_DELIM) {
			$months++;
			$total_minutes-=MONTH_DELIM;
		}
		while($total_minutes >= WEEK_DELIM) {
			$weeks++;
			$total_minutes-=WEEK_DELIM;
		}
		while($total_minutes >= DAY_DELIM) {
			$days++;
			$total_minutes-=DAY_DELIM;
		}
		while($total_minutes >= HOUR_DELIM) {
			$hours++;
			$total_minutes-=HOUR_DELIM;
		}
			
		$data['minutes'] = $total_minutes;
		$data['hours'] = $hours;
		$data['days'] = $days;
		$data['weeks'] = $weeks;
		$data['months'] = $months;
		$data['years'] = $years;
		
		return $data;
	}
	
	function nocache() {
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	}
}
?>