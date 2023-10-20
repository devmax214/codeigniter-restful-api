<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Post Controller
 * Created by: arangde
 * Date: 11/21/13
 * 
 */
class Post extends CI_Controller {

	protected $logged;
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('settings_model');
		$this->session->set_userdata($this->settings_model->getSettings('system'));
		
		$this->load->model('user_model');
		$this->load->model('post_model');
		
		$logged = $this->session->userdata('loggedin');
		if (empty($logged)) {
			$this->logged = 0;
			redirect('/index', 'refresh');
		}
		else {
			$this->logged = 1;
		}
	}
	
	public function index($start=0) {
		$data = array();
		 
		if($this->session->userdata("msg_success") != "") {
			$data['msg_success'] = $this->session->userdata("msg_success");
			$this->session->unset_userdata("msg_success");
		}
		if($this->session->userdata("msg_error") != "") {
			$data['msg_error'] = $this->session->userdata("msg_error");
			$this->session->unset_userdata("msg_error");
		}
		
		$where = '';
		$search = $this->input->post("search");
		if($search == "1") {
			$search_text = $this->input->post("search_text");
			$where = '(posts.content LIKE "%'. $search_text. '%")';
		}
		$posts = $this->post_model->getPostsAll($where);
		 
		$data['posts'] = $posts;
		 
		$this->yall->set('title', $this->session->userdata('system_title'))
			->partial('main_content', 'post/index', $data)
			->render('layouts/admin');
	}
	
	public function delete($post_id) {
		$this->post_model->delete("posts", array("post_id"=>$post_id));
		$this->post_model->delete("comments", array("post_id"=>$post_id));
		$this->post_model->delete("likes", array("post_id"=>$post_id));
		$this->post_model->delete("post_photos", array("post_id"=>$post_id));
		$this->post_model->delete("shares", array("post_id"=>$post_id));
		 
		redirect('/post/index');
	}
	
	public function detail($post_id) {
		$data = array();
		
		$user_id = $this->session->userdata('user_id');
		
		if($this->session->userdata("msg_success") != "") {
			$data['msg_success'] = $this->session->userdata("msg_success");
			$this->session->unset_userdata("msg_success");
		}
		if($this->session->userdata("msg_error") != "") {
			$data['msg_error'] = $this->session->userdata("msg_error");
			$this->session->unset_userdata("msg_error");
		}
		
		if($this->input->post("cmd") != "") {
			$cmd = $this->input->post("cmd");
		
			$errors = array();
			
			if(empty($errors)) {
					
			}
		}
		
		if($post_id) {
    		$data['post'] = $this->post_model->getPost($post_id, $user_id);
			$data['comments'] = $this->post_model->getPostComments($post_id, $user_id);
    		$data['photos'] = $this->post_model->get('post_photos', array('post_id' => $post_id));
		}
		
		$this->yall->set('title', $this->session->userdata('system_title'))
			->partial('main_content', 'post/detail', $data)
			->render('layouts/admin');
	}

    public function create($user_id) {
       	$data = array();
       	
       	if($user_id == "") {
       		$this->session->set_userdata("msg_error", "You need to select an user to create post.");
       		redirect('/user/', 'refresh');
       	}
    	
    	if($this->input->post("cmd") == "create") {
    		$errors = array();
    		
	    	$post = array(
	    		'user_id' => $user_id,
	    		'content' => $this->input->post('content'),
	    		'clothing_id' => $this->input->post('clothing_id'),
	    		'has_fitting_report' => $this->input->post('has_fitting_report'),
	    		'brand_name' => $this->input->post('brand_name'),
	    		'cloth_model' => $this->input->post('cloth_model'),
	    		'sizing' => $this->input->post('sizing'),
	    		'overall_rating' => $this->input->post('overall_rating'),
	    		'is_recommended' => $this->input->post('is_recommended')
	    	);
	    	
	    	$rating = array(
	    		'length_rating' => $this->input->post('length_rating'),
	    		'toe_rating' => $this->input->post('toe_rating'),
	    		'heel_rating' => $this->input->post('heel_rating'),
	    		'width_rating' => $this->input->post('width_rating'),
	    		'comfort_rating' => $this->input->post('comfort_rating'),
	    		'quality_rating' => $this->input->post('quality_rating'),
	    		'style_rating' => $this->input->post('style_rating'),
	    		'ease_rating' => $this->input->post('ease_rating'),
	    		'durability_rating' => $this->input->post('durability_rating'),
	    		'occasion_rating' => $this->input->post('occasion_rating')
	    	);
	    	
	    	$user = $this->user_model->getUser($user_id);
	    	
	    	if(empty($user)) {
	    		$errors[] = translate('User is empty');
	    	}
	    	elseif(isset($_FILES['photo_url']) && $this->input->post('upload_photo') == "1") {
	    		$upload = $this->config->item('upload');
	    		$upload_folder = 'post/'. time(). '/';
	    		$upload['upload_path'] = $upload['upload_base']. $upload_folder;
	    		
	    		if(!is_dir($upload['upload_path'])) {
	    			mkdir(rtrim($upload['upload_path'], '/'), 0777, true);
	    		}
	    		
	    		$this->load->library('upload', $upload);
	    		
	    		if($this->upload->do_upload('photo_url')) {
	    			$upload_data = $this->upload->data();
	    			$post['photo_url'] = $upload['upload_url']. $upload_folder. $upload_data['file_name'];
	    		}
	    		else {
	    			$errors[] = $this->upload->display_errors('', '');
	    		}
	    	}
	    	
	    	if(!empty($errors)) {
	    		$data['post'] = $post;
	    		array_merge($data['post'], $rating);
	    		$data['errors'] = $errors;
	    	}
	    	else {
	    		$post['created'] = date("Y-m-d H:i:s");
	    			 
	    		$post_id = $this->post_model->add('posts', $post);
	    			
	    		if($post_id) {
	    			$fitting_report = array(
	    				'Toe' => $rating['toe_rating'],
	    				'Heel' => $rating['heel_rating'],
	    				'Width' => $rating['width_rating'],
	    				'Length' => $rating['length_rating']
	    			);
	    			unset($rating['toe_rating']);
	    			unset($rating['heel_rating']);
	    			unset($rating['width_rating']);
	    			unset($rating['length_rating']);
	    			$rating['fitting_report'] = json_encode($fitting_report);
	    			
	    			$rating['post_id'] = $post_id;
	    			$this->post_model->add('ratings', $rating);
	    			
	    			/*
	    			 * Send Push to mention
	    			*/
	    			$usernames = array();
	    			 
	    			if($post['content'] != '') {
	    				$poster_name = $user['first_name'] == "" ? $user['user_name']: $user['first_name'];
	    				$message = $this->getMessage('mention', $poster_name);
	    				$custom_data = array('post_id' => $post_id, 'user_id' => $user_id, 'content' => $post['content']);
	    				 
	    				$pos = strpos($post['content'], '@');
	    				$matches = array('"'=>0,'\''=>0,'>'=>0, ' '=>0, "\n"=>0, "\r"=>0, ","=>0, "@"=>0);
	    				while($pos !== false) {
	    					$pos_end = strpos_needle_array($post['content'], $matches, $pos + 1);
	    					if($pos_end !== false && $pos_end > $pos + 1) {
	    						$username = substr($post['content'], $pos + 1, $pos_end - $pos - 1);
	    						if($username != "")
	    							$usernames[] = $username;
	    					}
	    					$pos = strpos($post['content'], '@', $pos + 1);
	    				}
	    				
	    				foreach($usernames as $username) {
	    					$user2 = $this->user_model->getUserByName($username);
	    					
	    					if(!empty($user2)) {
	    						apns_push($user2['user_id'], APNS_MODE_DEVELOPMENT, $message, $custom_data);
	    						$this->settings_model->logMessage($user_id, $user2['user_id'], $post_id, 'mention', '', $post['content']);
	    					}
	    				}
	    			}
	    				
	    			$this->session->set_userdata("msg_success", "You have created a post successfully.");
	    		}
	    		else {
	    			$errors[] = "It has been failed to add post";
	    		}
	    	}
	    }
	    
	    $data['user'] = $this->user_model->getUser($user_id);
	    $data['spec'] = $this->user_model->get('specs', array('user_id' => $user_id), 1);
	    $data['clothings'] = $this->post_model->getClothings();
	    $data['brands'] = $this->post_model->getBrands();
    	
    	$this->yall->set('title', $this->session->userdata('system_title'))
	    	->partial('main_content', 'post/create', $data)
	    	->render('layouts/admin');
    }
    
    public function getMessage($type, $user_name) {
    	$messages = array(
    			"like" => "{USER_NAME} liked your post.",
    			"reshare" => "{USER_NAME} re-shared your post.",
    			"comment" => "{USER_NAME} commented on your post.",
    			"mention" => "{USER_NAME} mentioned you.",
    			"comment_like" => "{USER_NAME} liked your comment."
    	);
    	 
    	if(!isset($messages[$type])) {
    		return "";
    	}
    	else {
    		return str_replace("{USER_NAME}", $user_name, $messages[$type]);
    	}
    }
}

/* End of file post.php */
/* Location: ./application/controllers/post.php */