<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {
	
	public function index() {
		$this->profile();
	}
	
	public function profile($username = null) {
		
		if($username != null) {
			$this->load->model('users_model');
			$this->load->model('posts_model');
			$data['title'] = $username . '\'s profile';
			$data['css'] = 'user.css';
			$data['header'] = $username;
			if((isset($this->session->userdata['is_logged_in'])) and ($this->session->userdata['username'] == $username)) {
				$query = $this->users_model->get_user_info_logged($username);
				//$this->nocache();
			} else {
				$query = $this->users_model->get_user_info($username);
				if(!$query) {
					$this->page_not_found();
				}
			}
			
			$data['user'] = $query;
			
			$total_posts = $this->posts_model->get_total_posts($query['id']);
			
			$posts_per_page = 10;
			$data['total_groups'] = ceil($total_posts/$posts_per_page);
			
			$this->load->view('user_page', $data);
		} else {
			$this->page_not_found();
		}
	}
	
	function nocache() {
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	}
	
	function page_not_found() {
		header('HTTP/1.0 404 Not Found');
		echo "<h1>Error 404 Not Found</h1>";
		echo "The page that you have requested could not be found.";
		exit();
	}
	
	function server_error() {
		header('HTTP/1.1 500 Internal Server Error');
		echo "<h1>Error 500 Internal Server Error</h1>";
		echo "There was a problem with the server";
		exit();
	}
}
?>