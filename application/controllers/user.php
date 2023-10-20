<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * User Controller
 * Created by: arangde
 * Date: 11/21/13
 * 
 */
class User extends CI_Controller {

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
			$where = '(users.user_name LIKE "%'. $search_text. '%" OR users.first_name LIKE "%'. $search_text. '%" OR users.email_address LIKE "%'. $search_text. '%")';
		}
		$users = $this->user_model->getUsers($where);
		 
		$data['users'] = $users;
		 
		$this->yall->set('title', $this->session->userdata('system_title'))
			->partial('main_content', 'user/index', $data)
			->render('layouts/admin');
	}
	
	public function resetPass($user_id) {
		$password = $this->input->post("password");
		if($this->user_model->update("users", array("password"=>sha1($this->config->item('encryption_key'). $password)), array("user_id"=>$user_id)))
			echo json_encode(array("success" => "It has been successed!"));
		else
			echo json_encode(array("error" => translate("It has been failed to reset password", "admin")));
		exit();
	}
	
	public function delete($user_id) {
		$this->user_model->delete("users", array("user_id"=>$user_id));
		$this->user_model->delete("follows", array("user_id"=>$user_id));
		$this->user_model->delete("follows", array("friend_id"=>$user_id));
		$this->user_model->delete("specs", array("user_id"=>$user_id));
		$this->post_model->delete("shares", array("user_id"=>$user_id));
		 
		redirect('/user/index');
	}
	
	public function detail($user_id) {
		$data = array();
		
		$session_user_id = $this->session->userdata('user_id');
		
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
		
		if($user_id) {
			$data['user'] = $this->user_model->getUser($user_id);
			$data['spec'] = $this->user_model->get('specs', array('user_id' => $user_id), 1);
			$data['posts'] = $this->post_model->getUserPosts($session_user_id, $user_id);
			
			$data['bsi_types'] = array('simple', 'lower', 'upper', 'full');
			$data['similar_users'] = array();
			foreach( $data['bsi_types'] as $bsi_type ) {
				$data['similar_users'][$bsi_type] = $this->user_model->getSimilarUsers($user_id, $bsi_type, $data['user']['gender'], RECENT_ROWS_LIMIT, SIMILAR_PERCENT_LIMIT);
			}
		}
		
		$this->yall->set('title', $this->session->userdata('system_title'))
			->partial('main_content', 'user/detail', $data)
			->render('layouts/admin');
	}

    public function create() {
       	$data = array();
    	
    	if($this->input->post("cmd") == "create") {
    		$errors = array();
    		
    		$user = array(
    			'user_name' => $this->input->post('user_name'),
    			'first_name' => $this->input->post('first_name'),
    			'email_address' => $this->input->post('email_address'),
    			'gender' => $this->input->post('gender'),
    			'status' => $this->input->post('status'),
    			'location' => $this->input->post('location'),
    			'password' => $this->input->post('password'),
    			'created' => date('Y-m-d H:i:s')
    		);
    		
    		$spec = array(
    			'unit' => $this->input->post('unit'),
    			'height' => $this->input->post('height'),
    			'weight' => $this->input->post('weight'),
    			'chest' => $this->input->post('chest'),
    			'waist' => $this->input->post('waist'),
    			'hip' => $this->input->post('hip'),
    			'foot' => $this->input->post('foot'),
    			'neck' => $this->input->post('neck'),
    			'shoulder' => $this->input->post('shoulder'),
    			'arm_length' => $this->input->post('arm_length'),
    			'upper_arm_size' => $this->input->post('upper_arm_size'),
    			'torso_height' => $this->input->post('torso_height'),
    			'belly' => $this->input->post('belly'),
    			'leg_length' => $this->input->post('leg_length'),
    			'thigh' => $this->input->post('thigh'),
    			'calf' => $this->input->post('calf'),
    			'last_changed' => date('Y-m-d H:i:s')
    		);
    		
    		if($this->user_model->checkDuplicationEmail($user['email_address'])) {
    			$errors[] = translate('Your email has already been registered.');
    		}
    		elseif($this->user_model->checkDuplicationName($user['user_name'])) {
    			$errors[] = translate('Your user name has already been registered.');
    		}
    		
    		if(isset($_FILES['photo_url']) && $this->input->post('upload_photo') == "1") {
    			$upload = $this->config->item('upload');
    			$upload_folder = 'user/'. time(). '/';
    			$upload['upload_path'] = $upload['upload_base']. $upload_folder;
    		
    			if(!is_dir($upload['upload_path'])) {
    				mkdir(rtrim($upload['upload_path'], '/'), 0777, true);
    			}
    		
    			$this->load->library('upload', $upload);
    		
    			if($this->upload->do_upload('photo_url')) {
    				$upload_data = $this->upload->data();
    				$user['photo_url'] = $upload['upload_url']. $upload_folder. $upload_data['file_name'];
    			}
    			else {
    				$errors[] = $this->upload->display_errors('', '');
    			}
    		}
    		
    		if(!empty($errors)) {
    			$data['user'] = $user;
    			$data['spec'] = $spec;
    			$data['errors'] = $errors;
    		}
    		else {
    			$user_id = $this->user_model->addUser($user, true);
    			
    			$spec['user_id'] = $user_id;
    			$this->user_model->add('specs', $spec);
    			
    			$this->session->set_userdata("msg_success", "You have created a user successfully.");
    			redirect('/user/detail/'. $user_id, 'refresh');
    		}
    	}
    	
    	$this->yall->set('title', $this->session->userdata('system_title'))
	    	->partial('main_content', 'user/create', $data)
	    	->render('layouts/admin');
    }
    
    public function sendDeleteEmail($user) {
    	
    	$subject = translate("Confirm to delete account!");
    
    	$data = array(
    		"subject" => $subject,
    		'first_name' => $user['first_name'],
    		'last_name' => $user['last_name'],
    		"delete_url" => base_url("user/delete_confirm/". $user['active_code'])
    	);
    	$data['content'] = $this->load->view('email/email_delete_view', $data, true);
    	$msg = $this->load->view('email/email_template_view', $data, true);
    
    	$config_email = $this->config->item('email');
    	$this->load->library('email', $config_email);
    
    	$this->email->from($this->session->userdata("report_email"), $this->session->userdata("system_title"));
    	$this->email->to($user['email_address']);
    	$this->email->subject($subject);
    	$this->email->message($msg);
    	$this->email->send();
    	 
    	//echo $this->email->print_debugger();
    }
}

/* End of file user.php */
/* Location: ./application/controllers/user.php */