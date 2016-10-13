<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	
	public function index()
	{
		$this->goHome();
	}
	
	public function goHome() {
		$this->load->view('home_page');
	}
	
}