<?php
$path =  $_SERVER['DOCUMENT_ROOT'] . '/anime/' . 'vendor/autoload.php';
require_once $path;

class Login extends CI_Controller {
	
	public function index() {
		$this->login_page();
	}
	
	public function login_page($data = null) {
		$data['title'] = 'Login';
		$data['css'] = 'login.css';
		if(!isset($data['header'])) {
			$data['header'] = "Please Login";	
		}

		$this->load->view('login_page', $data);
	}
	
	public function log_in() {
		
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		
		if ($this->form_validation->run() == FALSE) {
			if(isset($this->session->userdata['is_logged_in'])) {
				redirect("users/profile/{$this->session->userdata['username']}");
			} else{
				$data['incorrect'] = "Username or password is incorrect !";
				$this->login_page($data);
			}
		} else {
		
			$this->load->model('users_model');
			$query = $this->users_model->validate();
			if($query) {				
				$username = $this->input->post('username');
				$result = $this->users_model->get_user_info_logged($username);
				if ($result != false) {
					
					$data = array(
							'id' => $result['id'],
							'username' => $result['username'],
							'email' => $result['email'],
							'is_logged_in' => true
					);
					
					$is_admin = $this->users_model->check_if_user_is_admin($result['id']);
					
					if($is_admin) {
						$data['admin'] = TRUE;
					}
					
					$this->session->set_userdata($data);
					redirect("users/profile/{$username}");
				}
			} else {
				$data['incorrect'] = "Username or password is incorrect !";
				$this->login_page($data);
			}
		}
	}
	
	public function logout() {
		if(isset($this->session->userdata['is_logged_in'])) {
			$this->session->sess_destroy();
		} else {
			$data['message_display'] = 'You are not logged in ';
			redirect('home');
		}
		$this->nocache();
		redirect('home');
	}
	
	public function forgotten_password() {
		$data['title'] = 'V-Anime';
		$data['css'] = 'login.css';
		$data['header'] = 'Forgot your password ?';
		$this->load->view('forgot_password_page', $data);
	}
	
	function nocache() {
	    $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
	    $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
	    $this->output->set_header('Pragma: no-cache');
	    $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	}
	
	function facebook_login($connect_existing_account = "") {	
		$fb = new Facebook\Facebook([
		  'app_id' => APP_ID,
		  'app_secret' => APP_SECRET,
		  'default_graph_version' => 'v2.5',
		]);
		
		$helper = $fb->getRedirectLoginHelper();
		$permissions = ['email']; 
		$loginUrl = $helper->getLoginUrl(site_url("login/facebook_login_callback/{$connect_existing_account}"), $permissions);
		
		redirect($loginUrl);
	}
	
	function facebook_login_callback($connect_existing_account = "") {
		$fb = new Facebook\Facebook([
		  'app_id' => APP_ID,
		  'app_secret' => APP_SECRET,
		  'default_graph_version' => 'v2.5',
		]);
		
		$helper = $fb->getRedirectLoginHelper();

		try {
		  $accessToken = $helper->getAccessToken();
		  $response = $fb->get('/me?fields=id,name,email', $accessToken);
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
		  	echo 'Graph returned an error: ' . $e->getMessage();
			die();
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			$data['header'] = "There was an error connecting with Facebook !";
			$this->login_page($data);
		}
		
		if (!isset($accessToken)) {
		  if ($helper->getError()) {
		    header('HTTP/1.0 401 Unauthorized');
		    echo "Error: " . $helper->getError() . "\n";
		    echo "Error Code: " . $helper->getErrorCode() . "\n";
		    echo "Error Reason: " . $helper->getErrorReason() . "\n";
		    echo "Error Description: " . $helper->getErrorDescription() . "\n";
		  } else {
		    header('HTTP/1.0 400 Bad Request');
		    echo 'Bad request';
		  }
		  die();
		}
		
		$oAuth2Client = $fb->getOAuth2Client();
		
		$tokenMetadata = $oAuth2Client->debugToken($accessToken);
		
		$tokenMetadata->validateAppId(APP_ID); 
		$tokenMetadata->validateExpiration();
		
		if (!$accessToken->isLongLived()) {
		  try {
		    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
		  } catch (Facebook\Exceptions\FacebookSDKException $e) {
		    echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
		    die();
		  }
		  echo '<h3>Long-lived</h3>';
		  //var_dump($accessToken->getValue());
		}
		
		$user = $response->getGraphUser();
		$fb_user_id = $user['id'];
		$email = $user['email'];
		
		$this->load->model('users_model');
		
		$user = $this->users_model->check_if_fb_acc_exist_and_return_user($fb_user_id);
		
		if($connect_existing_account != "connect") {
			if($user) {			
					$data = array(
							'id' => $user['id'],
							'username' => $user['username'],
							'is_logged_in' => true,
							'email' => $user['email'],
							'fb_access_token' => (string) $accessToken
					);
					
					$is_admin = $this->users_model->check_if_user_is_admin($user['id']);
					
					if($is_admin) {
						$data['admin'] = TRUE;
					}
					
					$this->session->set_userdata($data);			
					redirect("users/profile/{$user['username']}");
			} else {
				
				$email_available = $this->users_model->check_if_email_exists($email);		
				
				if(!$email_available) {
					$data['message'] = "Choose your Username and Email";
					$email = FALSE;
				} else {
					$data['message'] = 'Choose your Username';
				}
	
				$data['fb_access_token'] = (string) $accessToken;
				$data['fb_user_id'] = $fb_user_id;
				$data['fb_email'] = $email;
				$data['header'] = 'Create new account';
				$data['title'] = "Sign Up";
				$data['css'] = 'login.css';
				$this->load->view('fb_user_signup', $data);
				
			}
		} else {
			if($user) {
				$message = "This Facebook account is already connected with another account.";
				redirect("userUpdates/user_settings/{$message}");
			} else {
				if($this->sesssion->userdata('is_logged_in')) {				
					if(($email != $this->session->userdata['email'])) {
						$email_available = $this->users_model->check_if_email_exists($email);
						if(!$email_available) {
							$email = $this->session->userdata['email'];
						} 
					} else {
						$email_available = TRUE;
					}
								
					if($email_available)
						$message = "";
					else 
						$message = "The email associated with your Facebook account was taken but you were still connected.";
					
					$query = $this->users_model->connect_facebook($this->session->userdata['id'], $email, $fb_user_id, $accessToken);
						
					if($query) {
						$data = array(
								'id' => $this->session->userdata['id'],
								'username' => $this->session->userdata['username'],
								'is_logged_in' => true,
								'email' => $query['email'],
								'fb_access_token' => (string) $accessToken
						);
							
						$this->session->set_userdata($data);
					} else {
						$message = "There was an error connecting your account.";
					}
					redirect("userUpdates/user_settings/{$message}");
				} else {
					$this->unauthorized();
				}
			}
		}
	}
	
	function unauthorized() {
		header('HTTP/1.0 401 Unauthorized');
		echo "<h1>Error 401 Unauthorized</h1>";
		echo "You aren't authorized to access this page.";
		exit();
	}
}

?>