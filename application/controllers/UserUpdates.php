<?php
class UserUpdates extends CI_Controller {
	
	function update_profile() {
		
		$this->load->model('users_model');
		$this->load->library('upload');
		
	    $config['upload_path']          = './assets/user_cover_images/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 2048;
        $config['max_width']            = 4000;
        $config['max_height']           = 2250;
        $config['file_name'] = $this->session->userdata['id'] . ".jpg";
        $config['overwrite'] = TRUE;
        
        $this->upload->initialize($config);

        $offset = $this->input->post('top_offset');
        
        $this->users_model->update_cover_offset($this->session->userdata['id'], $offset);
        
        if (!$this->upload->do_upload('edit_cover')) {
        	$error = array('error' => $this->upload->display_errors('<p class="error">', '</p>'));
        	$this->session->set_flashdata('error',$error['error']);
        } else {
        	$query = $this->users_model->update_cover_image($this->session->userdata['id'],  $config['file_name']);
         }       
         
         
         $config['upload_path']          = './assets/user_profile_images/';
         $config['allowed_types']        = 'gif|jpg|png';
         $config['max_size']             = 1024;
         $config['max_width']            = 1000;
         $config['max_height']           = 1000;
         $config['file_name'] = $this->session->userdata['id'] . ".jpg";
         $config['overwrite'] = TRUE;
         
         $this->upload->initialize($config);     
         
         if (!$this->upload->do_upload('edit_avatar')) {
         	$error = array('error_a' => $this->upload->display_errors('<p class="error_a">', '</p>'));
         	$this->session->set_flashdata('error_a',$error['error_a']);
         } else {
         	$query = $this->users_model->update_avatar_image($this->session->userdata['id'],  $config['file_name']);
         }         
         //$data = array('upload_data' => $this->upload->data());
         
         redirect("login/profile/{$this->session->userdata['username']}");
	}
		
	public function check_image_upload() {
		$rootFolder = "anime";
	
		echo $_FILES['edit_cover']['name'];
		die();
		
		if(!empty($_FILES['edit_cover']['name'])) {
			
			$errors= array();
			$file_name = $_FILES['edit_cover']['name'];
			$file_size =$_FILES['edit_cover']['size'];
			$file_tmp =$_FILES['edit_cover']['tmp_name'];
			$file_type=$_FILES['edit_cover']['type'];
			$file_ext=@strtolower(end(explode('.',$_FILES['edit_cover']['name'])));
	
			$expensions= array("jpeg","jpg","png");
	
			if(in_array($file_ext,$expensions)=== false){
				$errors[]="extension not allowed, please choose a JPEG or PNG file.";
			}
	
			if($file_size > 5242880){
				$errors[]='File size must be up to 5 MB';
			}
	
			$target_file = $_SERVER['DOCUMENT_ROOT'] . "/$rootFolder/assets/user_cover_images/" .$file_name;
	
			if(empty($errors)==true){
				move_uploaded_file($file_tmp, $target_file);
			}else{
				print_r($errors);
			}
	
			return $file_name;
		} else {
			$file_name = "None";
			return $file_name;
		}
	
	}
	
}
	
?>