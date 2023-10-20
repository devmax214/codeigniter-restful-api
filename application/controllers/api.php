<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * API Controller
 * Created by: arangde
 * Date: 10/15/14
 * 
 */
class Api extends CI_Controller {

	protected $logged;
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('settings_model');
		$this->load->model('user_model');
		$this->load->model('post_model');
		
		$this->session->set_userdata($this->settings_model->getSettings('system'));
		
		$logged = $this->session->userdata('loggedin');
		if (empty($logged)) {
			$this->logged = 0;
		}
		else {
			$this->logged = 1;
			
			saveLastActivity();
		}
	}
	
	/**
	 * API validate_name
	 * url /api/validate_name
	 */
	public function validate_name() {
		$data = array(
			'user_name' => $this->input->post('user_name')
		);
		
		$user = $this->user_model->getUserByName($data['user_name']);
		
		if(empty($user)) {
			$data['success'] = 'This name can be used';
		}
		else {
			$data['error'] = 'Sorry, this nickname is taken.';
		}
		
		echo json_encode($data);
	}	
	
	/**
	 * API login
	 * url /api/login
	 */
	public function login() {
		$data = array(
			'email_address' => $this->input->post('email_address'),
			'password' => $this->input->post('password')
		);
		if($data['email_address'] != '') {
			$auth_result = $this->user_model->authenticate($data['email_address'], $data['password']);
		}
		 
		if(is_array($auth_result) && !empty($auth_result)) {
			$this->session->set_userdata('user_id', $auth_result['user_id']);
			$this->session->set_userdata('email_address', $auth_result['email_address']);
			$this->session->set_userdata('role', $auth_result['role']);
	
			$this->session->set_userdata('loggedin', 1);
			$this->logged = 1;
			
			$user_change = array();
			
			$device_token = $this->input->post('device_token');
			
			if($device_token != '')
				$user_change['device_token'] = $device_token;
			
			if($auth_result['is_first'])
				$user_change['is_first'] = 0;
			
			if(!empty($user_change))
				$this->user_model->update('users', $user_change, array('user_id' => $auth_result['user_id']));
			
			$auth_result['spec'] = $this->user_model->get('specs', array('user_id' => $auth_result['user_id']), 1);
			
			echo json_encode($auth_result);
		} 
		else {
			if($auth_result == AUTH_FAIL) {
				$data['error'] = translate('You have entered an invalid password.');
			}
			elseif($auth_result == AUTH_NO_FOUND_NAME) {
				$data['error'] = translate('You have entered an invalid user name.');
			}
			elseif($auth_result == AUTH_NO_FOUND) {
				$data['error'] = translate('You have entered an invalid email.');
			}
			elseif($auth_result == AUTH_NOTACTIVE) {
				$data['error'] = translate('You are not activate status, check your activation please.');
			}
	
			$this->session->unset_userdata('user_id');
			$this->session->unset_userdata('email_address');
			$this->session->unset_userdata('role');
	
			$this->session->unset_userdata('loggedin');
			$this->logged = 0;
			
			echo json_encode($data);
		}
		 
		exit();
	}
	
	/**
	 * API login_name
	 * url /api/login_name
	 */
	public function login_name() {
		$data = array(
			'user_name' => $this->input->post('user_name'),
		);
		
		$user = $this->user_model->getUserByName($data['user_name']);
			
		if(empty($user)) {
			$data['error'] = translate('You have entered an invalid user name.');
			
			echo json_encode($data);
		}
		else {
			$device_token = $this->input->post('device_token');
			
			if($device_token != '')
				$user_change['device_token'] = $device_token;
			
			if($user['is_first'])
				$user_change['is_first'] = 0;
			
			if(!empty($user_change))
				$this->user_model->update('users', $user_change, array('user_id' => $user['user_id']));
			
			$user['spec'] = $this->user_model->get('specs', array('user_id' => $user['user_id']), 1);
			
			echo json_encode($user);
		}
			
		exit();
	}

	/**
	 * API logout
	 * url api/logout
	 */
	public function logout() {
		$this->session->unset_userdata('loggedin');
		$this->session->unset_userdata('user_id');
		$this->session->unset_userdata('email_address');
		$this->session->unset_userdata('role');
		 
		$this->session->sess_destroy();
		 
		exit();
	}
	
	/**
	 * API signp
	 * url /api/signup
	 */
	public function signup() {
		$data = array(
			'email_address' => $this->input->post('email_address'),
			'password' => $this->input->post('password'),
			'user_name' => $this->input->post('user_name'),
			'first_name' => $this->input->post('first_name'),
			'last_name' => $this->input->post('last_name'),
			'gender' => $this->input->post('gender'),
			'device_token' => $this->input->post('device_token'),
			'is_first' => 1
		);
		 
		if($this->user_model->checkDuplicationEmail($data['email_address'])) {
			$data['error'] = translate('Your email has already been registered.');
		}
		elseif($this->user_model->checkDuplicationName($data['user_name'])) {
			$data['error'] = translate('Your user name has already been registered.');
		}
		else {
			if(isset($_FILES['photo_url'])) {
				$upload = $this->config->item('upload');
				$upload_folder = 'user/'. time(). '/';
				$upload['upload_path'] = $upload['upload_base']. $upload_folder;
			
				if(!is_dir($upload['upload_path'])) {
					mkdir(rtrim($upload['upload_path'], '/'), 0777, true);
				}
			
				$this->load->library('upload', $upload);
			
				if ($this->upload->do_upload('photo_url')) {
					$upload_data = $this->upload->data();
					$data['photo_url'] = $upload['upload_url']. $upload_folder. $upload_data['file_name'];
				}
				else {
    				$data['error'] = $this->upload->display_errors('', '');
    			}
			}
			
			if(!isset($data['error'])) {
				$user_id = $this->user_model->addUser($data, true);
				 
				if($user_id) {
					$user = $this->user_model->getUser($user_id);
					
					$this->user_model->add('specs', array('user_id' => $user_id));
					
					$this->session->set_userdata('user_id', $user['user_id']);
					$this->session->set_userdata('email_address', $user['email_address']);
					$this->session->set_userdata('role', $user['role']);
					
					$this->session->set_userdata('loggedin', 1);
					$this->logged = 1;
					
					$user['success'] = translate('Thanks for registering!');
					
					$this->sendRegisterMail($user);
					
					echo json_encode($user);
					exit();
				}
				else {
					$data['error'] = translate('It has been failed to add user');
				}
			}
		}
	
		echo json_encode($data);
		exit();
	}
	
	/**
	 * API newsignp
	 * url /api/newsignup
	 */
	public function newsignup() {
		$data = array(
			'user_name' => $this->input->post('user_name'),
			'gender' => $this->input->post('gender'),
			'device_token' => $this->input->post('device_token'),
			'is_first' => 1
		);
			
		if($this->user_model->checkDuplicationName($data['user_name'])) {
			$data['error'] = translate('Your user name has already been registered.');
		}
		else {
			$user_id = $this->user_model->addUser($data, true);
					
			if($user_id) {
				$user = $this->user_model->getUser($user_id);
						
				$this->user_model->add('specs', array('user_id' => $user_id, 'height' => $this->input->post('height'), 'weight' => $this->input->post('weight')));
						
				$this->session->set_userdata('user_id', $user['user_id']);
				$this->session->set_userdata('role', $user['role']);
						
				$this->session->set_userdata('loggedin', 1);
				$this->logged = 1;
						
				$user['spec'] = $this->user_model->get('specs', array('user_id' => $user_id), 1);
				
				$user['success'] = translate('Thanks for registering!');
						
				echo json_encode($user);
				exit();
			}
		}
	
		echo json_encode($data);
		exit();
	}
	
	/**
	 * API fbconnect
	 * url /api/fblogin
	 */
    public function fblogin() {
    	$data = array(
    		'fb_id' => $this->input->post('fb_id'),
    		'user_name' => $this->input->post('user_name'),
			'first_name' => $this->input->post('first_name'),
    		'last_name' => $this->input->post('last_name'),
    		'gender' => $this->input->post('gender'),
    		'photo_url' => $this->input->post('photo_url'),
    		'device_token' => $this->input->post('device_token')
    	);
    	
    	if($this->input->post('email_address') != "") {
    		$data['email_address'] = $this->input->post('email_address');
    	}
    	    		
    	$user = $this->user_model->get('users', array('fb_id' => $data['fb_id']), 1);
    	
    	if(empty($user)) {
    		$data['error'] = translate('You have not registered yet.');
    		/*
    		//signup
	    	if(isset($data['email_address'])) {
	    		if($this->user_model->checkDuplicationEmail($data['email_address'])) {
					$data['error'] = translate('Your email has already been registered.');
				}
    		}
    		
    		if(!isset($data['error'])) {
    			$data['is_first'] = 1;
		    		
		    	$user_id = $this->user_model->addUser($data, true);
	    		
	    		if($user_id) {
	    			$user = $this->user_model->getUser($user_id);
	    			
	    			$this->user_model->add('specs', array('user_id' => $user_id));
	    			
	    			$user['success'] = translate('You have been registered successfully from Facebook.');
	    			
	    			$this->session->set_userdata('user_id', $user['user_id']);
	    			$this->session->set_userdata('email_address', $user['email_address']);
	    			$this->session->set_userdata('role', $user['role']);
	    			 
	    			$this->session->set_userdata('loggedin', 1);
	    			$this->logged = 1;
	    			
	    			$this->sendRegisterMail($user);
	    			
	    			echo json_encode($user);
	    			exit();
	    		}
	    		else {
	    			$data['error'] = translate('It has been failed to add user');
	    		}
    		}
    		*/
    	}
    	else {
    		//login
    		$user['success'] = translate('Facebook login successed!.');
    			
    		$this->session->set_userdata('user_id', $user['user_id']);
    		$this->session->set_userdata('email_address', $user['email_address']);
    		$this->session->set_userdata('role', $user['role']);
    			 
    		$this->session->set_userdata('loggedin', 1);
    		$this->logged = 1;
    			
    		$user_change = array();
    				
    		if($data['device_token'] != '') {
    			$user_change['device_token'] = $data['device_token'];
    			$user['device_token'] = $data['device_token'];
    		}
    			
    		if($data['photo_url'] != '') {
    			$user_change['photo_url'] = $data['photo_url'];
    		}
    				
    		if($user['is_first']) {
    			$user['is_first'] = 0;
    		}
    		
    		if(!empty($user_change))
    			$this->user_model->update('users', $user_change, array('user_id' => $user['user_id']));
    				
    		$user['spec'] = $this->user_model->get('specs', array('user_id' => $user['user_id']), 1);
    		
    		echo json_encode($user);
    		exit();
    	}
    	
		$this->session->unset_userdata('user_id');
		$this->session->unset_userdata('email_address');
		$this->session->unset_userdata('role');
		
		$this->session->unset_userdata('loggedin');
		$this->logged = 0;
		 
		echo json_encode($data);
		exit();
    }
    
    /**
     * API twconnect
     * url /api/twlogin
     */
    public function twlogin() {
    	$data = array(
    		'tw_id' => $this->input->post('tw_id'),
    		/*'email_address' => $this->input->post('email_address'),*/
    		'user_name' => $this->input->post('user_name'),
			'first_name' => $this->input->post('first_name'),
    		'last_name' => $this->input->post('last_name'),
    		'photo_url' => $this->input->post('photo_url'),
    		'device_token' => $this->input->post('device_token')
    	);
    
    	$user = $this->user_model->get('users', array('tw_id' => $data['tw_id']), 1);
    	 
    	if(empty($user)) {
    		$data['error'] = translate('You have not registered yet.');
    		/*
    		//signup
	    	$data['is_first'] = 1;
	    
	    	$user_id = $this->user_model->addUser($data, true);
	    		 
	    	if($user_id) {
    			$user = $this->user_model->getUser($user_id);
    			
    			$this->user_model->add('specs', array('user_id' => $user_id));
    			 
    			$user['success'] = translate('You have been registered successfully from Twitter.');
    			 
    			$this->session->set_userdata('user_id', $user['user_id']);
    			$this->session->set_userdata('role', $user['role']);
    
    			$this->session->set_userdata('loggedin', 1);
    			$this->logged = 1;
    			 
    			echo json_encode($user);
    			exit();
    		}
    		else {
	   			$data['error'] = translate('It has been failed to add user');
	   		}
	   		*/
    	}
    	else {
    		//login
    		$user['success'] = translate('Twitter login successed!.');
    			 
    		$this->session->set_userdata('user_id', $user['user_id']);
    		$this->session->set_userdata('role', $user['role']);
    
    		$this->session->set_userdata('loggedin', 1);
    		$this->logged = 1;
    			 
    		$user_change = array();
    
    		if($data['device_token'] != '') {
    			$user_change['device_token'] = $data['device_token'];
    			$user['device_token'] = $data['device_token'];
    		}
    		
    		if($data['photo_url'] != '') {
    			$user_change['photo_url'] = $data['photo_url'];
    		}
    		
    		if($user['is_first'])
    			$user['is_first'] = 0;
    
    		if(!empty($user_change))
    			$this->user_model->update('users', $user_change, array('user_id' => $user['user_id']));
    
    		$user['spec'] = $this->user_model->get('specs', array('user_id' => $user['user_id']), 1);
    			 
    		echo json_encode($user);
    		exit();
    	}
    	 
    	$this->session->unset_userdata('user_id');
    	$this->session->unset_userdata('role');
    
    	$this->session->unset_userdata('loggedin');
    	$this->logged = 0;
    		
    	echo json_encode($data);
    	exit();
    }
    
    /*
     * Send welcome emails
     */
    public function sendRegisterMail($user) {
    	 
    	$subject = translate("Welcome to ". $this->session->userdata("system_title"). "!");
    
    	$data = array(
    		"subject" => $subject,
    		"user" => $user
    		//"register_url" => base_url("index/activate/". $user['active_code'])
    	);
    	$data['content'] = $this->load->view('email/email_register_view', $data, true);
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
    
    /**
     * API Forgot password
     * url /api/forgot
     */

    public function forgot() {
    	$data = array(
    		'forgot_email' => $this->input->post('forgot_email'),
    	);
    
    	$user = $this->user_model->getUserByEmail($data['forgot_email']);
    	if(empty($user)) {
    		$data['error'] = translate('Your email has not been registered.');
    	}
    	else {
    		$active_code = sha1($this->config->item('encryption_key'). $data['forgot_email']. time());
    
    		$this->user_model->update('users', array('active_code' => $active_code), array('user_id' => $user['user_id']));
    
    		$subject = translate("Reset Password in ". $this->session->userdata("system_title"));
    
    		$email_data = array(
    			"subject" => $subject,
    			"user" => $user,
    			"reset_url" => base_url("index/reset/". $active_code)
    		);
    		$email_data['content'] = $this->load->view('email/email_reset_password', $email_data, true);
    		$msg = $this->load->view('email/email_template_view', $email_data, true);
    
    		$config_email = $this->config->item('email');
    		$this->load->library('email', $config_email);
    
    		$this->email->from($this->session->userdata("report_email"), $this->session->userdata("system_title"));
    		$this->email->to($data['forgot_email']);
    		$this->email->subject($subject);
    		$this->email->message($msg);
    		$this->email->send();
    		 
    		//echo $this->email->print_debugger();
    
    		$data['success'] = translate('Reset password request email has been sent! Please check your email.');
    	}
    
    	echo json_encode($data);
    	exit();
    }
    
    /**
     * API update user info
     * url /api/update_user_info
     */
    public function update_user_info() {
    	$data = array(
    		'user_id' => $this->input->post('user_id'),
    		'user_name' => $this->input->post('user_name'),
    		'first_name' => $this->input->post('first_name'),
    		'last_name' => $this->input->post('last_name'),
    		'gender' => $this->input->post('gender'),
    		'status' => $this->input->post('status'),
    		'location' => $this->input->post('location')
    	);
    		
    	$user = $this->user_model->getUser($data['user_id']);
    	
    	if(empty($user)) {
    		$data['error'] = translate('Empty User.');
    	}
    	else {
    		if(isset($_FILES['photo_url'])) {
    			$upload = $this->config->item('upload');
    			$upload_folder = 'user/'. time(). '/';
    			$upload['upload_path'] = $upload['upload_base']. $upload_folder;
    				
    			if(!is_dir($upload['upload_path'])) {
    				mkdir(rtrim($upload['upload_path'], '/'), 0777, true);
    			}
    				
    			$this->load->library('upload', $upload);
    				
    			if ($this->upload->do_upload('photo_url')) {
    				$upload_data = $this->upload->data();
    				$data['photo_url'] = $upload['upload_url']. $upload_folder. $upload_data['file_name'];
    			}
    			else {
    				$data['error'] = $this->upload->display_errors('', '');
    			}
    		}
    		
    		if(!isset($data['error'])) {
    			$this->user_model->updateUser($data, $data['user_id']);
    			
    			$user = $this->user_model->getUser($data['user_id']);
    			$user['spec'] = $this->user_model->get('specs', array('user_id' => $data['user_id']), 1);
    		
    			$data = $user;
    			$data['success'] = translate('It has been successed to update user info.');
    		}
    	}
    
    	echo json_encode($data);
    	exit();
    }
    
    /**
     * API update user mail
     * url /api/update_user_mail
     */
    public function update_user_mail() {
    	$user_id = $this->input->post('user_id');
    	
    	$data = array();
    	    	
    	if($this->input->post('email_address') != "") {
    		$data['email_address'] = $this->input->post('email_address');
    		if($this->user_model->checkDuplicationEmail($data['email_address'], $user_id)) {
    			$data['error'] = translate('Sorry, email has already been registered.');
    		}
    	}
    	if($this->input->post('password') != "") {
    		$data['password'] = sha1($this->config->item('encryption_key'). $this->input->post('password'));
    	}
    	if($this->input->post('fb_id') != "") {
    		$data['fb_id'] = $this->input->post('fb_id');
    		$fb_user = $this->user_model->get('users', array('fb_id' => $data['fb_id']), 1);
    		if(!empty($fb_user) /* && $fb_user['user_id'] == $user_id*/ ) {
    			$data['error'] = translate('Sorry, facebook ID has already been registerged.');
    		}
    	}
    	if($this->input->post('tw_id') != "") {
    		$data['tw_id'] = $this->input->post('tw_id');
    		$tw_user = $this->user_model->get('users', array('tw_id' => $data['tw_id']), 1);
    		if(!empty($tw_user) /* && $tw_user['user_id'] == $user_id */ ) {
    			$data['error'] = translate('Sorry, twitter ID has already been registerged.');
    		}
    	}
    	if($this->input->post('first_name') != "") {
    		$data['first_name'] = $this->input->post('first_name');
    	}
    	if($this->input->post('photo_url') != "") {
    		$data['photo_url'] = $this->input->post('photo_url');
    	}
    	
    	if(empty($data)) {
    		$data['error'] = translate('Empty data to change.');
    	}
    	
    	$user = $this->user_model->getUser($user_id);
    	 
    	if(empty($user)) {
    		$data['error'] = translate('Empty User.');
    	}
    	
    	if(empty($data['error'])) {
    		$this->user_model->updateUser($data, $user_id);
    		
    		$user = $this->user_model->getUser($user_id);
    		$user['spec'] = $this->user_model->get('specs', array('user_id' => $user_id), 1);
    
    		if(isset($data['email_address'])) {
    			$this->sendRegisterMail($user);
    		}
    		
    		$data = $user;
    		$data['success'] = translate('It has been successed to update user mail.');
    	}
    
    	echo json_encode($data);
    	exit();
    }
    
    /**
     * API update user spec
     * url /api/update_user_spec
     */
    public function update_user_spec() {
    	$data = array(
    		'user_id' => $this->input->post('user_id'),
    		'unit' => $this->input->post('unit'),
    		'height' => $this->input->post('height'),
    		'weight' => $this->input->post('weight'),
    		'chest' => $this->input->post('chest'),
    		'waist' => $this->input->post('waist'),
    		'hip' => $this->input->post('hip'),
    		//'cup_size' => $this->input->post('cup_size'),
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
    
    	$user = $this->user_model->getUser($data['user_id']);
    	 
    	if(empty($user)) {
    		$data['error'] = translate('Empty User.');
    	}
    	else {
    		$spec = $this->user_model->get('specs', array('user_id' => $data['user_id']), 1);
    		
    		if(empty($spec)) {
    			$this->user_model->add('specs', $data);
    		}
    		else {
    			unset($data['user_id']);
    			$this->user_model->update('specs', $data, array('user_id' => $user['user_id']));
    		}

    		$data = $user;
    		$data['spec'] = $this->user_model->get('specs', array('user_id' => $user['user_id']), 1);
    		$data['success'] = translate('It has been successed to update user spec.');
    		
    	}
    
    	echo json_encode($data);
    	exit();
    }
    
    /**
     * API get brand list
     * url /api/get_brands
     */
    public function get_brands() {
    	$brands = $this->post_model->getBrands();
    	 
    	echo json_encode($brands);
    	exit();
    }
    
    /**
     * API get users names
     * url /api/get_user_names
     */
    public function get_user_names() {
    	$data = array(
    		'user_id' => $this->input->post('user_id')
    	);
    	
    	$users = $this->user_model->getUserNames($data['user_id']);
    
    	echo json_encode($users);
    	exit();
    }
    
    /**
     * API get clothings list
     * url /api/get_clothings
     */
    public function get_clothings() {
    	$data = array(
    		'user_id' => $this->input->post('user_id')
    	);
    	 
    	$user = $this->user_model->getUser($data['user_id']);
    	if(empty($user)) {
    		$data['error'] = "Empty User";
    		
    		echo json_encode($data);
    		exit();
    	}
    	
    	$clothings = $this->post_model->getClothings();
    
    	echo json_encode($clothings);
    	exit();
    }
    
    /**
     * API add post
     * url /api/add_post
     */
    public function add_post() {
    	$data = array(
    		'user_id' => $this->input->post('user_id'),
    		'content' => $this->input->post('content'),
    		'clothing_id' => $this->input->post('clothing_id'),
    		'has_fitting_report' => $this->input->post('has_fitting_report'),
    		'brand_name' => $this->input->post('brand_name'),
    		'cloth_model' => $this->input->post('cloth_model'),
    		'sizing' => $this->input->post('sizing'),
    		'overall_rating' => $this->input->post('overall_rating'),
    		'is_recommended' => $this->input->post('is_recommended'),
    		'photo_width' => $this->input->post('photo_width'),
    		'photo_height' => $this->input->post('photo_height')
    	);
    	
    	$rating = array(
    		/*
    		'fit_rating' => $this->input->post('fit_rating'),
    		'length_rating' => $this->input->post('length_rating'),
    		'waist_rating' => $this->input->post('waist_rating'),
    		'rear_rating' => $this->input->post('rear_rating'),
    		'rise_rating' => $this->input->post('rise_rating'),
    		'leg_rating' => $this->input->post('leg_rating'),
    		*/
    		'fitting_report' => $this->input->post('fitting_report'),
    		'comfort_rating' => $this->input->post('comfort_rating'),
    		'quality_rating' => $this->input->post('quality_rating'),
    		'style_rating' => $this->input->post('style_rating'),
    		'ease_rating' => $this->input->post('ease_rating'),
    		'durability_rating' => $this->input->post('durability_rating'),
    		'occasion_rating' => $this->input->post('occasion_rating')
    	);
    	
    	$photo_urls = $this->input->post('photo_urls');
    	$photo_urls = $photo_urls != "" ? explode(',', $photo_urls): '';
    	
    	$user = $this->user_model->getUser($data['user_id']);
    	
    	if(empty($user)) {
    		$data['error'] = translate('User is empty');
    	}
    	elseif($photo_urls != '' && count($photo_urls) > 0) {
    		$data['photo_url'] = $photo_urls[0];
    		$data['created'] = date("Y-m-d H:i:s");
    			 
    		$post_id = $this->post_model->add('posts', $data);
    			
    		if($post_id) {
    			$rating['post_id'] = $post_id;
    			$this->post_model->add('ratings', $rating);
    			
    			$photos_count = 0;
    			if(count($photo_urls) > 1) {
    				$photos_count = 1;
    				foreach($photo_urls as $i=>$photo_url) {
    					if($i==0)
    						continue;
    					
    					if(trim($photo_url) == '')
    						continue;
    					
    					$this->post_model->add('post_photos', array(
    							'post_id' => $post_id,
    							'photo_url' => $photo_url
    						)
    					);
    					
    					$photos_count++;
    				}
    			}
    			$data['post_photos'] = $post_photos;
    			
    			/*
    			 * add brand
    			 */
    			/*
    			$brand = $this->settings_model->get('brands', 'brand_name LIKE "'. $data['brand_name']. '"', 1);
    			if(empty($brand)) {
    				$this->settings_model->add('brands', array('brand_name' => $data['brand_name']));
    			}
    			*/
    				
    			/*
    			 * add post viewable 
    			 */
    			/*
    			$this->post_model->addPostView($data['user_id'], $post_id);
    				
    			$friends = $this->post_model->get('follows', array("friend_id" => $data['user_id']));
    			foreach($friends as $friend) {
    				$this->post_model->addPostView($friend['user_id'], $post_id);
    			}
    			*/
    			
    			/*
    			 * Send Push to mention
    			*/
    			$usernames = array();
    			 
    			if($data['content'] != '') {
    				$poster_name = $user['first_name'] == "" ? $user['user_name']: $user['first_name'];
    				$message = $this->getMessage('mention', $poster_name);
    				$custom_data = array('post_id' => $post_id, 'user_id' => $data['user_id'], 'content' => $data['content']);
    				 
    				$pos = strpos($data['content'], '@');
    				$matches = array('"'=>0,'\''=>0,'>'=>0, ' '=>0, "\n"=>0, "\r"=>0, ","=>0, "@"=>0);
    				while($pos !== false) {
    					$pos_end = strpos_needle_array($data['content'], $matches, $pos + 1);
    					if($pos_end !== false && $pos_end > $pos + 1) {
    						$username = substr($data['content'], $pos + 1, $pos_end - $pos - 1);
    						if($username != "")
    							$usernames[] = $username;
    					}
    					$pos = strpos($data['content'], '@', $pos + 1);
    				}
    			
    				foreach($usernames as $username) {
    					$user2 = $this->user_model->getUserByName($username);
    					if(!empty($user2)) {
    						apns_push($user2['user_id'], APNS_MODE_DEVELOPMENT, $message, $custom_data);
    						$this->settings_model->logMessage($data['user_id'], $user2['user_id'], $post_id, 'mention', '', $data['content']);
    					}
    				}
    			}
    				
    			$data['rating'] = $rating;
    		}
    		else {
    			$data['error'] = "It has been failed to add post";
    		}
    	}
    	else {
    		$data['error'] = "Photos are empty";
    	}
    	
    	echo json_encode($data);
    	exit();
    }
    
    /**
     * API delete post
     * url /api/delete_post
     */
    public function delete_post() {
    	$data = array(
    		'post_id' => $this->input->post('post_id')
    	);
    
    	$post = $this->post_model->get('posts', array('post_id' => $data['post_id']), 1);
    	
    	if(empty($post)) {
    		$data['error'] = "Empty post";
    	}
    	else {
    		$this->post_model->delete('post_photos', array('post_id' => $data['post_id']));
    		$this->post_model->delete('likes', array('post_id' => $data['post_id']));
    		$this->post_model->delete('ratings', array('post_id' => $data['post_id']));
    		$this->post_model->delete('comments', array('post_id' => $data['post_id']));
    		$this->post_model->delete('shares', array('post_id' => $data['post_id']));
    		//$this->post_model->delete('post_views', array('post_id' => $data['post_id']));
    		$this->post_model->delete('notifications', array('post_id' => $data['post_id']));
    		$this->post_model->delete('posts', array('post_id' => $data['post_id']));
    		
    		$data['success'] = "The post has been removed successfully.";
    	}
    	
    	echo json_encode($data);
    	exit();
    }
    
    /**
     * API get new posts
     * url /api/get_new_posts
     */
    public function get_new_posts() {
    	$data = array(
    		'user_id' => $this->input->post('user_id')
    	);
    
    	$user = $this->user_model->getUser($data['user_id']);
    	
    	if(empty($user)) {
    		$data['error'] = "Empty user";
    	}
    	else {
    		$where = '';
    		if($user['last_read_time'] != '') {
    			//$where = 'posts.created >= "'. $user['last_read_time']. '"';
    		}
    		
    		$data['posts'] = array();
    		
    		$follows = $this->user_model->get('follows', array('user_id' => $data['user_id']));
    		if(empty($follows)) {
    			$suggested_users = $this->user_model->getSimilarUsers($data['user_id'], 'simple', $user['gender'], RECENT_ROWS_LIMIT, 0);
    			foreach($suggested_users as $suggested_user) {
    				$where = "";
    				$posts = $this->post_model->getUserPosts($user['user_id'], $suggested_user['user_id'], $where);
    				
    				$data['posts'] = array_merge($data['posts'], $posts);
    				if(count($data['posts']) >= RECENT_ROWS_LIMIT) {
    					break;
    				}
    			}
    			
    		}
    		else {
    			$data['posts'] = $this->post_model->getNewPosts($user['user_id'], $user['gender'], true, $where, RECENT_ROWS_LIMIT);
    		}
    		
    		$data['read_time'] = $user['last_read_time'];
    		$this->user_model->update('users', array('last_read_time' => date('Y-m-d H:i:s')), array('user_id' => $data['user_id']));
    	}
    
    	echo json_encode($data);
    	exit();
    }
    
    /**
     * API get post details
     * url /api/get_post_detail
     */
    public function get_post_detail() {
    	$data = array(
    		'post_id' => $this->input->post('post_id'),
    		'user_id' => $this->input->post('user_id')
    	);
    	 
    	$post = $this->post_model->getPost($data['post_id'], $data['user_id']);
    	$user = $this->user_model->getUser($data['user_id']);
    	 
    	if(empty($post)) {
    		$data['error'] = translate('Post is empty');
    	}
    	else {
    		$post['comments'] = $this->post_model->getPostComments($data['post_id'], $data['user_id']);
    		$post['specs'] = $this->post_model->get('specs', array('user_id' => $post['user_id']), 1);
    		$post['photos'] = $this->post_model->get('post_photos', array('post_id' => $post['post_id']));
    		
//     		if($user['user_id'] == $post['user_id']) {
//     			$post['similar_percent'] = 100;
//     		}
//     		else {
//     			$post['similar_percent'] = $this->user_model->getUserSimilar($user['user_id'], $post['user_id'], $post['clothing_id']);
//     		}
    		
    		
    		echo json_encode($post);
    		exit();
    	}
    	 
    	echo json_encode($data);
    	exit();
    }
    
    /**
     * API post like
     * url /api/post_like
     */
    public function post_like() {
    	$data = array(
    		'user_id' => $this->input->post('user_id'),
    		'post_id' => $this->input->post('post_id')
    	);
    	
    	$post = $this->post_model->get('posts', array('post_id' => $data['post_id']), 1);
    	$user = $this->user_model->getUser($data['user_id']);
    	$like = $this->post_model->get('likes', $data, 1);
    	 
    	if(empty($post)) {
    		$data['error'] = translate('Post is empty');
    	}
    	elseif(empty($user)) {
    		$data['error'] = translate('User is empty');
    	}
    	/*
    	elseif($post['user_id'] == $data['user_id']) {
    		$data['error'] = translate("You can't like to your own post");
    	}
    	*/
    	elseif(!empty($like)) {
    		$data['error'] = translate('You have already added the post like');
    	}
    	else {
    		$data['created'] = date('Y-m-d H:i:s');
	    		
	    	$like_id = $this->post_model->add('likes', $data);

	    	if($like_id) {
	    		$data['post'] = $this->post_model->getPost($data['post_id'], $data['user_id']);
	    		$data['success'] = translate('It has been successed to like post');
	    		
	    		if($post['user_id'] != $data['user_id']) {
		    		/*
		    		 * Send push to poster
		    		 */
		    		$user_name = $user['first_name'] == "" ? $user['user_name']: $user['first_name'];
		    		$message = $this->getMessage('like', $user_name);
		    		$custom_data = array('post_id' => $data['post_id'], 'user_id' => $data['user_id']);
		    		 
		    		apns_push($post['user_id'], APNS_MODE_DEVELOPMENT, $message, $custom_data);
		    		 
		    		$this->settings_model->logMessage($data['user_id'], $post['user_id'], $post['post_id'], 'like', '', json_encode($custom_data));
	    		}
	    	}
	    	else
	    		$data['error'] = translate('It has been failed to like post');
	    }
    	 
    	echo json_encode($data);
    	exit();
    }
    
    /**
     * API post dislike
     * url /api/post_dislike
     */
    public function post_dislike() {
    	$data = array(
    		'user_id' => $this->input->post('user_id'),
    		'post_id' => $this->input->post('post_id')
    	);
    	 
    	$post = $this->post_model->get('posts', array('post_id' => $data['post_id']), 1);
    	$user = $this->user_model->getUser($data['user_id']);
    	$like = $this->post_model->get('likes', $data, 1);
    
    	if(empty($post)) {
    		$data['error'] = translate('Post is empty');
    	}
    	elseif(empty($user)) {
    		$data['error'] = translate('User is empty');
    	}
    	elseif(empty($like)) {
    		$data['error'] = translate('You have not added the post like');
    	}
    	else {
    		$this->post_model->delete('likes', $data);
    
    		$data['post'] = $this->post_model->getPost($data['post_id'], $data['user_id']);
    		$data['success'] = translate('It has been successed to dislike post');
    	   
    		/*
    		 * Send push to poster
    		 */
    		/*
    		$poster = $this->user_model->getUser($post['user_id']);
    	   
    		$message = $user['first_name']. ' '. $user['last_name']. " disliked your post.";
    		$custom_data = array('post_id' => $data['post_id'], 'user_id' => $data['user_id']);
    
    		if($poster['device_token'] != '') {
    			apns_push(array($poster['device_token']), false, $message, $custom_data);
    		}
    
    		$this->settings_model->logMessage($data['user_id'], $post['user_id'], $post['post_id'], 'dislike', $message, json_encode($custom_data));
    		*/
    	}
    
    	echo json_encode($data);
    	exit();
    }

    /**
     * API post share
     * url /api/post_share
     */
    public function post_share() {
    	$data = array(
    		'user_id' => $this->input->post('user_id'),
    		'post_id' => $this->input->post('post_id')
    	);
    	 
    	$post = $this->post_model->get('posts', array('post_id' => $data['post_id']), 1);
    	$user = $this->user_model->getUser($data['user_id']);
    	$share = $this->post_model->get('shares', $data, 1);
    
    	if(empty($post)) {
    		$data['error'] = translate('Post is empty');
    	}
    	elseif(empty($user)) {
    		$data['error'] = translate('User is empty');
    	}
    	elseif($post['user_id'] == $data['user_id']) {
    		$data['error'] = translate("You can't share your own post");
    	}
    	elseif(!empty($share)) {
    		$data['error'] = translate('You have already shared the post');
    	}
    	else {
    		$data['created'] = date('Y-m-d H:i:s');
    		 
    		$share_id = $this->post_model->add('shares', $data);
    
    		if($share_id) {
    			/*
    			 * update post updated
    			 */
    			/*
    			$this->post_model->update('posts', 
    				array('last_sharer_id' => $data['user_id'], 'updated' => date('Y-m-d H:i:s')), 
    				array('post_id' => $data['post_id'])
    			);
    			*/
    			
    			/*
    			 * add post viewable
    			*/
    			/*
    			$friends = $this->post_model->get('follows', array("friend_id" => $data['user_id']));
    			foreach($friends as $friend) {
    				$this->post_model->addPostView($friend['user_id'], $data['post_id']);
    			}
    			*/
    			
    			$data['post'] = $this->post_model->getPost($data['post_id'], $data['user_id']);
    			$data['success'] = translate('It has been successed to share post');
    			
    			/*
    			 * Send push to poster
    			*/
    			$user_name = $user['first_name'] == "" ? $user['user_name']: $user['first_name'];
    			$message = $this->getMessage('reshare', $user_name);
    			$custom_data = array('post_id' => $data['post_id'], 'user_id' => $data['user_id']);
    			
    			apns_push($post['user_id'], APNS_MODE_DEVELOPMENT, $message, $custom_data);
    			
    			$this->settings_model->logMessage($data['user_id'], $post['user_id'], $post['post_id'], 'reshare', '', json_encode($custom_data));
    		}
    		else
    			$data['error'] = translate('It has been failed to share post');
    	}
    
    	echo json_encode($data);
    	exit();
    }
    

    /**
     * API post comment
     * url /api/post_comment
     */
    public function post_comment() {
    	$data = array(
    		'user_id' => $this->input->post('user_id'),
    		'post_id' => $this->input->post('post_id')
    	);
    	 
    	$post = $this->post_model->get('posts', array('post_id' => $data['post_id']), 1);
    	$user = $this->user_model->getUser($data['user_id']);
    	//$comment = $this->post_model->get('comments', $data, 1);
    
    	if(empty($post)) {
    		$data['error'] = translate('Post is empty');
    	}
    	elseif(empty($user)) {
    		$data['error'] = translate('User is empty');
    	}
//     	elseif($post['user_id'] == $data['user_id']) {
//     		$data['error'] = translate("You can't comment to your own post");
//     	}
    	/*
    	elseif(!empty($comment)) {
    		$data['error'] = translate('You have already added comment to the post');
    	}
    	*/
    	else {
    		$data['created'] = date('Y-m-d H:i:s');
    		$data['comment'] = $this->input->post('comment');
    		
    		$comment_id = $this->post_model->add('comments', $data);
    
    		if($comment_id) {
    			$user_name = $user['first_name'] == "" ? $user['user_name']: $user['first_name'];
    			
    			if($post['user_id'] != $data['user_id']) {
	    			/*
		    		 * Send push to poster
		    		 */
		    		$message = $this->getMessage('comment', $user_name);
		    		$custom_data = array('post_id' => $data['post_id'], 'user_id' => $data['user_id'], 'comment' => $data['comment']);
		    		
		    		apns_push($post['user_id'], APNS_MODE_DEVELOPMENT, $message, $custom_data);
		    		
		    		$this->settings_model->logMessage($data['user_id'], $post['user_id'], $post['post_id'], 'comment', '', $data['comment']);
    			}

    			/*
		    	 * Send Push to mention
		    	 */
    			$usernames = array();
	    		
	    		if($data['comment'] != '') {
	    			$message = $this->getMessage('mention', $user_name);
	    			$custom_data = array('post_id' => $data['post_id'], 'user_id' => $data['user_id'], 'comment' => $data['comment']);
		    			 
	    			$pos = strpos($data['comment'], '@');
	    			$matches = array('"'=>0,'\''=>0,'>'=>0, ' '=>0, "\n"=>0, "\r"=>0, ","=>0, "@"=>0);
	    			while($pos !== false) {
	    				$pos_end = strpos_needle_array($data['comment'], $matches, $pos + 1);
	    				if($pos_end !== false && $pos_end > $pos + 1) {
	    					$username = substr($data['comment'], $pos + 1, $pos_end - $pos - 1);
	    					if($username != "")
	    						$usernames[] = $username;
	    				}
	    				$pos = strpos($data['comment'], '@', $pos + 1);
	    			}
		    			
		    		foreach($usernames as $username) {
		    			$user2 = $this->user_model->getUserByName($username);
		    			if(!empty($user2) && $user2['user_id'] != $data['user_id']) {
		    				apns_push($user2['user_id'], APNS_MODE_DEVELOPMENT, $message, $custom_data);
		    				
		    				$this->settings_model->logMessage($data['user_id'], $user2['user_id'], $post['post_id'], 'mention', '', $data['comment']);
		    			}
		    		}
	    		}
	    		
    			$post = $this->post_model->getPost($data['post_id'], $data['user_id']);
    			$post['comments'] = $this->post_model->getPostComments($data['post_id'], $data['user_id']);
    			$post['specs'] = $this->post_model->get('specs', array('user_id' => $post['user_id']), 1);
    			
//     			if($user['user_id'] == $post['user_id']) {
//     				$post['similar_percent'] = 100;
//     			}
//     			else {
//     				$post['similar_percent'] = $this->user_model->getUserSimilar($user['user_id'], $post['user_id'], $post['clothing_id']);
//     			}
    			
    			$data['post'] = $post;
    			$data['success'] = translate('It has been successed to comment post');
    		}
    		else
    			$data['error'] = translate('It has been failed to comment post');
    	}
    
    	echo json_encode($data);
    	exit();
    }

    /**
     * API comment like
     * url /api/comment_like
     */
    public function comment_like() {
    	$data = array(
    		'user_id' => $this->input->post('user_id'),
    		'comment_id' => $this->input->post('comment_id')
    	);
    	 
    	$comment = $this->post_model->get('comments', array('comment_id' => $data['comment_id']), 1);
    	$user = $this->user_model->getUser($data['user_id']);
    	$comment_like = $this->post_model->get('comment_likes', $data, 1);
    
    	if(empty($comment)) {
    		$data['error'] = translate('Comment is empty');
    	}
    	elseif(empty($user)) {
    		$data['error'] = translate('User is empty');
    	}
    	/*
    	elseif($comment['user_id'] == $data['user_id']) {
    		$data['error'] = translate("You can't like your own comment");
    	}
    	*/
    	elseif(!empty($comment_like)) {
    		$data['error'] = translate('You have already added the comment like');
    	}
    	else {
    		$data['created'] = date('Y-m-d H:i:s');
    		 
    		$like_id = $this->post_model->add('comment_likes', $data);
    
    		if($like_id) {
    			$data['success'] = translate('It has been successed to like comment');
    			
    			if($comment['user_id'] != $data['user_id']) {
	    			/*
	    			 * Send push to poster
	    			*/
	    			$poster = $this->user_model->getUser($comment['user_id']);
	    			
	    			$user_name = $user['first_name'] == "" ? $user['user_name']: $user['first_name'];
	    			$message = $this->getMessage('comment_like', $user_name);
	    			$custom_data = array('comment_id' => $data['comment_id'], 'user_id' => $data['user_id']);
	    			
	    			apns_push($comment['user_id'], APNS_MODE_DEVELOPMENT, $message, $custom_data);
	    			
	    			$this->settings_model->logMessage($data['user_id'], $comment['user_id'], $comment['post_id'], 'comment_like', '', json_encode($custom_data));
    			}
    		}
    		else
    			$data['error'] = translate('It has been failed to like comment');
    	}
    
    	echo json_encode($data);
    	exit();
    }
    
    /**
     * API comment dislike
     * url /api/comment_dislike
     */
    public function comment_dislike() {
    	$data = array(
    		'user_id' => $this->input->post('user_id'),
    		'comment_id' => $this->input->post('comment_id')
    	);
    
    	$comment = $this->post_model->get('comments', array('comment_id' => $data['comment_id']), 1);
    	$user = $this->user_model->getUser($data['user_id']);
    	$comment_like = $this->post_model->get('comment_likes', $data, 1);
    
    	if(empty($comment)) {
    		$data['error'] = translate('Comment is empty');
    	}
    	elseif(empty($user)) {
    		$data['error'] = translate('User is empty');
    	}
    	elseif(empty($comment_like)) {
    		$data['error'] = translate('You have not added the comment like');
    	}
    	else {
    		$this->post_model->delete('comment_likes', $data);
    
    		$data['success'] = translate('It has been successed to dislike comment');
    			 
    		/*
    		 * Send push to poster
    		 */
    		/*
    		$poster = $this->user_model->getUser($comment['user_id']);
    			 
    		$message = $user['first_name']. ' '. $user['last_name']. " disliked your comment.";
    		$custom_data = array('comment_id' => $data['comment_id'], 'user_id' => $data['user_id']);
    			 
    		if($poster['device_token'] != '') {
    			apns_push(array($poster['device_token']), false, $message, $custom_data);
    		}
    			 
    		$this->settings_model->logMessage($data['user_id'], $comment['user_id'], $comment['post_id'], 'comment_dislike', $message, json_encode($custom_data));
    		*/
    	}
    
    	echo json_encode($data);
    	exit();
    }
    
    /**
     * API comment delete
     * url /api/delete_comment
     */
    public function delete_comment() {
    	$data = array(
    		'user_id' => $this->input->post('user_id'),
    		'comment_id' => $this->input->post('comment_id')
    	);
    
    	$comment = $this->post_model->get('comments', array('comment_id' => $data['comment_id']), 1);
    	$user = $this->user_model->getUser($data['user_id']);
    	
    	if(empty($comment)) {
    		$data['error'] = translate('Comment is empty');
    	}
    	elseif(empty($user)) {
    		$data['error'] = translate('User is empty');
    	}
    	else {
    		$this->post_model->delete('comment_likes', $data);
    		$this->post_model->delete('comments', array('comment_id' => $data['comment_id']));
    		
    		$post = $this->post_model->getPost($comment['post_id'], $data['user_id']);
    		$post['comments'] = $this->post_model->getPostComments($post['post_id'], $data['user_id']);
    		$post['specs'] = $this->post_model->get('specs', array('user_id' => $post['user_id']), 1);
    		 
    		$data['post'] = $post;
    		$data['success'] = translate('It has been successed to delete comment');
    	}
    
    	echo json_encode($data);
    	exit();
    }
    
    /**
     * API get user posts
     * url /api/get_user_posts
     */
    public function get_user_posts() {
    	$data = array(
    		'user_id' => $this->input->post('user_id'),
    		'poster_id' => $this->input->post('poster_id')
    	);
    
    	$user = $this->user_model->getUser($data['user_id']);
    	$poster = $this->user_model->getUser($data['poster_id']);
    	 
    	if(empty($user)) {
    		$data['error'] = "Empty user";
    	}
    	elseif(empty($poster)) {
    		$data['error'] = "Empty poster";
    	}
    	else {
    		$data['posts'] = array();
    		$posts = $this->post_model->getUserPosts($data['user_id'], $data['poster_id']);
    		
//     		foreach($posts as $i => $post) {
//     			if($user['user_id'] == $post['user_id']) {
//     				$post['similar_percent']  =100;
//     			}
//     			else {
//     				$post['similar_percent'] = $this->user_model->getUserSimilar($user['user_id'], $post['user_id'], $post['clothing_id']);
//     			}
//     			$data['posts'][$i] = $post;
//     		}

    		$data['posts'] = $posts;
    	}
    
    	echo json_encode($data);
    	exit();
    }
    
    /**
     * API follow friend
     * url /api/follow_friend
     */
    public function follow_friend() {
    	$data = array(
    		'user_id' => $this->input->post('user_id'),
    		'friend_id' => $this->input->post('friend_id')
    	);
    
    	if($data['user_id'] == $data['friend_id']) {
    		$data['error'] = translate("It's same user ids");
    	}
    	else {
	    	$user = $this->user_model->getUser($data['user_id']);
	    	$friend = $this->user_model->getUser($data['friend_id']);
	    	$follow = $this->user_model->get('follows', $data, 1);
	    
	    	if(empty($user)) {
	    		$data['error'] = translate('User is empty');
	    	}
	    	elseif(empty($friend)) {
	    		$data['error'] = translate('Friend is empty');
	    	}
	    	elseif(!empty($follow)) {
	    		$data['error'] = translate('You have already followed the friend');
	    	}
    		//elseif($user['gender'] != $friend['gender']) {
	    	//	$data['error'] = translate('You are not same gender of the friend');
	    	//}
	    	else {
	    		$data['created'] = date('Y-m-d H:i:s');
	    		 
	    		$follow_id = $this->post_model->add('follows', $data);
	    
	    		if($follow_id) {
	    			/*
	    			 * add post viewable
	    			*/
	    			/*
	    			$posts = $this->post_model->get('posts', array("user_id" => $data['friend_id']));
	    			foreach($posts as $post) {
	    				$this->post_model->addPostView($data['user_id'], $post['post_id']);
	    			}
	    			*/
	    			
	    			$data['success'] = translate('It has been successed to follow friend');
	    		}
	    		else
	    			$data['error'] = translate('It has been failed to follow friend');
	    	}
    	}
    	
    	echo json_encode($data);
    	exit();
    }
    
    /**
     * API un follow friend
     * url /api/unfollow
     */
    public function unfollow() {
    	$data = array(
    		'user_id' => $this->input->post('user_id'),
    		'friend_id' => $this->input->post('friend_id')
    	);
    
        $user = $this->user_model->getUser($data['user_id']);
	    $friend = $this->user_model->getUser($data['friend_id']);
	    $follow = $this->user_model->get('follows', $data, 1);
	    
	    if(empty($user)) {
	      $data['error'] = translate('User is empty');
	    }
	    elseif(empty($follow)) {
	      $data['error'] = translate('You have not followed the friend');
	    }
	    else {
	      $this->post_model->delete('follows', array('follow_id' => $follow['follow_id']));
	       
	      $data['success'] = translate('It has been successed to unfollow friend');
	    }
	     
    	echo json_encode($data);
    	exit();
    }
    
    /**
     * API get user info
     * url /api/get_user_info
     */
    public function get_user_info() {
    	$data = array(
    		'user_id' => $this->input->post('user_id'),
    		'friend_id' => $this->input->post('friend_id')
    	);
    
    	$user = $this->user_model->getUser($data['friend_id'], true);
    
    	if(empty($user)) {
    		$data['error'] = "Empty user";
    		
    		echo json_encode($data);
    	}
    	else {
    		$follow = $this->user_model->get('follows', $data, 1);
    		if(empty($follow))
    			$user['is_following'] = 0;
    		else
    			$user['is_following'] = 1;
    		
    		$user['followings'] = $this->user_model->getFollows($data['friend_id'], $data['user_id']);
    		$user['followers'] = $this->user_model->getFollows($data['friend_id'], $data['user_id'], false);
    		$user['spec'] = $this->user_model->get('specs', array('user_id' => $data['friend_id']), 1);
    		
    		
    		echo json_encode($user);
    	}
    	
    	exit();
    }
    
    /**
     * API get user followings
     * url /api/get_user_followings
     */
    public function get_user_followings() {
    	$data = array(
    		'user_id' => $this->input->post('user_id'),
    		'my_id' => $this->input->post('my_id')
    	);
    
    	$user = $this->user_model->getUser($data['user_id']);
    
    	if(empty($user)) {
    		$data['error'] = "Empty user";
    	}
    	else {
    		$data['followings'] = $this->user_model->getFollows($data['user_id'], $data['my_id']);
    	}
    
    	echo json_encode($data);
    	exit();
    }
    
    /**
     * API get user followers
     * url /api/get_user_followers
     */
    public function get_user_followers() {
    	$data = array(
    		'user_id' => $this->input->post('user_id'),
    		'my_id' => $this->input->post('my_id')
    	);
    
    	$user = $this->user_model->getUser($data['user_id']);
    
    	if(empty($user)) {
    		$data['error'] = "Empty user";
    	}
    	else {
    		$data['followers'] = $this->user_model->getFollows($data['user_id'], $data['my_id'], false);
    	}
    
    	echo json_encode($data);
    	exit();
    }
    
    /**
     * API get user likes
     * url /api/get_user_likes
     */
    public function get_user_likes() {
    	$data = array(
    		'user_id' => $this->input->post('user_id')
    	);
    
    	$user = $this->user_model->getUser($data['user_id']);
    
    	if(empty($user)) {
    		$data['error'] = "Empty user";
    	}
    	else {
    		$data['posts'] = array();
    		$posts = $this->post_model->getUserLikePosts($data['user_id']);
    		
//     		foreach($posts as $i => $post) {
//     			$post['similar_percent'] = $this->user_model->getUserSimilar($user['user_id'], $post['user_id'], $post['clothing_id']);
//     			$data['posts'][$i] = $post;
//     		}

    		$data['posts'] = $posts;
    	}
    
    	echo json_encode($data);
    	exit();
    }
    
    /**
     * API get user counts of post, following, follower, like
     * url /api/get_user_counts
     */
    public function get_user_counts() {
    	$data = array(
    		'user_id' => $this->input->post('user_id')
    	);
    
    	$user = $this->user_model->getUser($data['user_id']);
    
    	if(empty($user)) {
    		$data['error'] = "Empty user";
    
    		echo json_encode($data);
    	}
    	else {
    		$user['followings'] = count($this->user_model->getFollows($data['user_id'], $data['user_id']));
    		$user['followers'] = count($this->user_model->getFollows($data['user_id'], $data['user_id'], false));
    		$user['posts'] = count($this->post_model->get('posts', array('user_id' => $data['user_id'])));
    		$user['likes'] = count($this->post_model->get('likes', array('user_id' => $data['user_id'])));
    
    		echo json_encode($user);
    	}
    	 
    	exit();
    }
    
    /**
     * API get similar users
     * url /api/get_similar_users
     */
    public function get_similar_users() {
    	$data = array(
    		'user_id' => $this->input->post('user_id'),
    		'bsi_type' => $this->input->post('bsi_type')
    	);
    	
    	$user = $this->user_model->getUser($data['user_id']);
    	
    	if(empty($user)) {
    		$data['error'] = "Empty user";
    	}
    	else {
//     		global $ClothingTypeSpecs;
//     		$bsi_type = '';
    		
//     		if($data['clothing_id'] == 0 || $data['clothing_id'] == "") {
//     			$bsi_type = '';
//     		}
//     		else {
//     			$clothing = $this->user_model->get('clothings', array('clothing_id' => $data['clothing_id']), 1);
    			
//     			//remove cup_size for male
//     			//if($user['gender'] == 'male' && isset($spec_keys['cup_size']))
//     			//	unset($spec_keys['cup_size']);
    				 
//     			$bsi_type = $clothing['bsi_type'];
//     		}
    		
//     		if($bsi_type == '') {
//     			$data['error'] = "Empty specs";
//     		}
//     		else {
    			$data['users'] = $this->user_model->getSimilarUsers($data['user_id'], $data['bsi_type'], $user['gender'], RECENT_ROWS_LIMIT, SIMILAR_PERCENT_LIMIT);
//     		}
    	}
    	
    	echo json_encode($data);
    	exit();
    }
    
    /**
     * API get posts count by brand
     * url /api/count_by_brand
     */
    public function count_by_brand() {
    	$data = array(
    		'user_id' => $this->input->post('user_id')
    	);
    	
    	$user = $this->user_model->getUser($data['user_id']);
    	
    	if(empty($user)) {
    		$data['error'] = "Empty user";
    	}
    	else {
	    	$data['result'] = $this->post_model->countByBrand();
	    }
	    	
    	echo json_encode($data);
	    exit();
    }
    
    /**
     * API get posts count by search brand
     * url /api/count_by_brand_name
     */
    public function count_by_brand_name() {
    	$data = array(
    		'user_id' => $this->input->post('user_id'),
    		'brand_name' => $this->input->post('brand_name')
    	);
    	 
    	$user = $this->user_model->getUser($data['user_id']);
    	 
    	if(empty($user)) {
    		$data['error'] = "Empty user";
    	}
    	else {
    		$data['result'] = $this->post_model->countBySearchBrand($data['brand_name']);
    	}
    
    	echo json_encode($data);
    	exit();
    }
    
    /**
     * API get posts count by clothing
     * url /api/count_by_clothing
     */
    public function count_by_clothing() {
    	$data = array(
    		'user_id' => $this->input->post('user_id')
    	);
    	
    	$user = $this->user_model->getUser($data['user_id']);
    	
    	if(empty($user)) {
    		$data['error'] = "Empty user";
    	}
    	else {
	    	$data['result'] = $this->post_model->countByClothing();
	    }
	    	
    	echo json_encode($data);
	    exit();
    }
    
    /**
     * API search posts by brand
     * url /api/search_by_brand
     */
    public function search_by_brand() {
    	$data = array(
    		'user_id' => $this->input->post('user_id'),
    		'brand_name' => $this->input->post('brand_name')
    	);
    	 
    	$user = $this->user_model->getUser($data['user_id']);
    	 
    	if(empty($user)) {
    		$data['error'] = "Empty user";
    	}
    	else {
    		$where = '';
    		
    		$data['posts'] = array();
    		$posts = $this->post_model->getPostsByBrandName($data['user_id'], $data['brand_name'], $where);

    		$data['posts'] = $posts;
    	}
    
    	echo json_encode($data);
    	exit();
    }
    
    /**
     * API search posts by clothing
     * url /api/search_by_clothing
     */
    public function search_by_clothing() {
    	$data = array(
    		'user_id' => $this->input->post('user_id'),
    		'clothing_id' => $this->input->post('clothing_id')
    	);
    
    	$user = $this->user_model->getUser($data['user_id']);
    
    	if(empty($user)) {
    		$data['error'] = "Empty user";
    	}
    	else {
    		$where = 'posts.clothing_id = '. $data['clothing_id'];
    
    		$data['posts'] = array();
    		$posts = $this->post_model->getPosts($data['user_id'], $where);
    
//     		foreach($posts as $i => $post) {
//     			if($user['user_id'] == $post['user_id']) {
//     				$post['similar_percent']  =100;
//     			}
//     			else {
//     				$post['similar_percent'] = $this->user_model->getUserSimilar($user['user_id'], $post['user_id'], $post['clothing_id']);
//     			}
//     			$data['posts'][$i] = $post;
//     		}
    		
    		$data['posts'] = $posts;
    	}
    
    	echo json_encode($data);
    	exit();
    }
    
    /**
     * API search recent users
     * url /api/search_popular
     */
    public function search_popular() {
    	$data = array(
    		'user_id' => $this->input->post('user_id')
    	);
    
    	$user = $this->user_model->getUser($data['user_id']);
    
    	if(empty($user)) {
    		$data['error'] = "Empty user";
    	}
    	else {
    		$data['users'] = array();
    		$users = $this->user_model->getRecentUsers($data['user_id'], '');
    
//     		foreach($users as $i => $user2) {
    			
//     			if($user['user_id'] == $post['user_id']) {
//     				$post['similar_percent']  =100;
//     			}
//     			else {
//     				$post['similar_percent'] = $this->user_model->getUserSimilar($user['user_id'], $post['user_id'], $post['clothing_id']);
//     			}
    			
//     			$data['users'][$i] = $user2;
//     		}

    		$data['users'] = $users;
    	}
    
    	echo json_encode($data);
    	exit();
    }
    
	/**
     * API get notifications
     * url /api/get_notifications
     */
    public function get_notifications() {
    	$data = array(
    		'user_id' => $this->input->post('user_id'),
    		'start' => $this->input->post('start')
    	);
    
    	$user = $this->user_model->getUser($data['user_id']);
    	
    	if(empty($user)) {
    		$data['error'] = "Empty user";
    	}
    	else {
    		$read_time = date('Y-m-d H:i:s');
    		$where = '';
    		$notifications = $this->settings_model->getNotifications($data['user_id'], $where, RECENT_ROWS_LIMIT, intval($data['start']));
    		
    		$data['notifications'] = array();
    		$ids = array();
    		foreach($notifications as $notification) {
    			$user_name = $notification['first_name'] == "" ? $notification['user_name']: $notification['first_name'];
    			$notification['message'] = $this->getMessage($notification['type'], $user_name);;
    			$data['notifications'][] = $notification;
    			$ids[] = $notification['notification_id'];
    		}
    		
    		if(count($ids) > 0) {
    			$this->settings_model->updateIn($ids);
    		}
    		
    		$data['read_time'] = $user['notification_read_time'];
    		$this->user_model->update('users', array('notification_read_time' => $read_time), array('user_id' => $data['user_id']));
    	}
    
    	echo json_encode($data);
    	exit();
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
    
    /**
     * API flag post
     * url /api/flag_post
     */
    public function flag_post() {
    	$data = array(
    		'user_id' => $this->input->post('user_id'),
    		'post_id' => $this->input->post('post_id')
    	);
    	
    	$post = $this->post_model->get('posts', array('post_id' => $data['post_id']), 1);
    	$user = $this->user_model->getUser($data['user_id']);
    	$flag = $this->post_model->get('flags', $data, 1);
    	 
    	if(empty($post)) {
    		$data['error'] = translate('Post is empty');
    	}
    	elseif(empty($user)) {
    		$data['error'] = translate('User is empty');
    	}
    	elseif(!empty($flag)) {
    		$data['error'] = translate('You have already flagged on this post');
    	}
    	else {
    		$data['created'] = date('Y-m-d H:i:s');
	    		
	    	$this->post_model->add('flags', $data);
	    	$data['success'] = translate('Thank you, our team will take a look at this post within 24 hours');
	    }
    	 
    	echo json_encode($data);
    	exit();
    }
}

/* End of file api.php */
/* Location: ./application/controllers/api.php */