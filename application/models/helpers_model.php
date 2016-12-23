<?php

defined('BASEPATH') OR exit('No direct script access allowed');

Class Helpers_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	function bad_request() {
		header('HTTP/1.1 400 Bad Request');
		echo "<h1>Error 400 Bad request</h1>";
		echo "The requested action cannot be executed.";
		exit();
	}
	
	function unauthorized() {
		header('HTTP/1.0 401 Unauthorized');
		echo "<h1>Error 401 Unauthorized</h1>";
		echo "You aren't authorized to access this page.";
		exit();
	}
	
	function unauthorized_error() {
		header("HTTP/1.1 401 Unauthorized");
		echo "<h1>Error 401 Unauthorized</h1>";
		echo "Your access token is invalid";
		exit;
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