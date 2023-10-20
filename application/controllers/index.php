<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Index Controller
 * Created by: arangde
 * Date: 07/25/2014
 * 
 */
class Index extends CI_Controller {

	protected $logged;
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('settings_model');
		$this->session->set_userdata($this->settings_model->getSettings('system'));
		
		$this->load->model('user_model');
		
		$logged = $this->session->userdata('loggedin');
		if (empty($logged)) {
			$this->logged = 0;
		}
		else {
			$this->logged = 1;
		}
		
		//$this->load->library('yall');
	}

    public function index() {
    	$data = array();
    	
    	if($this->input->post('login_email')) {
    		$data = $this->login();
    	}
    	else if($this->input->post('logout')) {
    		$this->logout();
    	}
    	
    	if($this->session->userdata("msg_success") != "") {
    		$data['msg_success'] = $this->session->userdata("msg_success");
    		$this->session->unset_userdata("msg_success");
    	}
    	if($this->session->userdata("msg_error") != "") {
    		$data['msg_error'] = $this->session->userdata("msg_error");
    		$this->session->unset_userdata("msg_error");
    	}
    		
    	$data['logged'] = $this->logged;
    	
    	if($this->logged) {
	    	redirect('/admin', 'refresh');
    	}
    	else {
    		$data['title'] = $this->session->userdata('system_title');
    		$this->yall->set('title', $this->session->userdata('system_title'))
	    		->partial('main_content', 'index/login', $data)
	    		->render('layouts/admin');
    	}
    }

    public function login() {
    	$data = array(
    		'login_email' => $this->input->post('login_email'),
    		'login_pass' => $this->input->post('login_pass')
    	);
    	
    	$auth_result = $this->user_model->authenticate($data['login_email'], $data['login_pass']);
    	
    	if(is_array($auth_result) && !empty($auth_result)) {
    		if($auth_result['role'] != USER_ROLE_ADMIN) {
    			$data['msg_error'] = translate('You have permission to administrator.');
    		}
    		else {
	    		$this->session->set_userdata('user_id', $auth_result['user_id']);
	    		$this->session->set_userdata('first_name', $auth_result['first_name']);
	    		$this->session->set_userdata('user_email', $auth_result['email_address']);
	    		$this->session->set_userdata('user_role', $auth_result['role']);
	    		$this->session->set_userdata('loggedin', 1);
	    		$this->logged = 1;
	    		
	    		redirect('/admin', 'refresh');
    		}
    	} 
    	else {
    		if($auth_result == AUTH_FAIL) {
    			$data['msg_error'] = translate('Your password is invalid.');
    		}
    		elseif($auth_result == AUTH_NO_FOUND) {
    			$data['msg_error'] = translate('Your email is invalid.');
    		}
    		elseif($auth_result == AUTH_NOTACTIVE) {
    			$data['msg_error'] = translate('You are not activate status, check your activation please.');
    		}
    	}
    	
    	$this->session->unset_userdata('user_id');
    	$this->session->unset_userdata('first_name');
    	$this->session->unset_userdata('user_email');
    	$this->session->unset_userdata('user_role');
    	$this->session->unset_userdata('loggedin');
    	$this->logged = 0;
    	
    	return $data;
    }
    
    public function logout() {
    	$this->session->unset_userdata('loggedin');
    	$this->session->unset_userdata('user_id');
    	$this->session->unset_userdata('first_name');
    	$this->session->unset_userdata('user_email');
    	$this->session->unset_userdata('user_role');
    	$this->session->sess_destroy();
    	
    	redirect('/index', 'refresh');
    }
    
    public function forgot() {
    	$data = array(
    		'forgot_email' => $this->input->post('forgot_email'),
    	);
    	 
    	$user = $this->user_model->getUserByEmail($data['forgot_email']);
    	if(empty($user)) {
    		$data['forgot_error'] = translate('Your email has not been registered.');
    	}
    	else {
    		$new_password = generateRandomString(8);
    
    		$this->user_model->update('users', array('password' => sha1($this->config->item('encryption_key'). $new_password)), array('id' => $user['id']));
    
    		$subject = translate("Reset Password");
    
    		$data = array(
    				"subject" => $subject,
    				"login_url" => base_url("/index"),
    				"new_password" => $new_password
    		);
    		$data['content'] = $this->load->view('email/email_confirm_reset_view.php', $data, true);
    		$msg = $this->load->view('email/email_template_view', $data, true);
    
    		$config_email = $this->config->item('email');
    		$this->load->library('email', $config_email);
    
    		$this->email->from($this->session->userdata("report_email"), $this->session->userdata("system_title"));
    		$this->email->to($user['email_address']);
    		$this->email->cc($this->session->userdata("report_email"));
    		$this->email->subject($subject);
    		$this->email->message($msg);
    		$this->email->send();
    		 
    		//echo $this->email->print_debugger();
    
    		$data['forgot_success'] = translate('You have sent password forgotten email successfully! Please check your email.');
    	}
    
    	echo json_encode($data);
    	exit();
    }
    
    public function activate($active_code) {
    	if($active_code == '')
    		redirect('/index/', 'refresh');
    	 
    	$user = $this->user_model->activateUser($active_code);
    	 
    	if(!$user) {
    		redirect('/index/index/invalid', 'refresh');
    	}
    	else {
    		$this->session->set_userdata('user_id', $user['user_id']);
    		$this->session->set_userdata('email_address', $user['email_address']);
    		$this->session->set_userdata('role', $user['role']);
    
    		$this->session->set_userdata('loggedin', 1);
    
    		redirect('/index/index/activte_success', 'refresh');
    	}
    }
    
    public function sendRegisterMail($user) {
    	 
    	$subject = translate("Welcome to ". $this->session->userdata("system_title"));
    
    	$data = array(
    			"subject" => $subject,
    			"register_url" => base_url("index/activate/". $user['active_code'])
    	);
    	$data['content'] = $this->load->view('email/email_register_view', $data, true);
    	$msg = $this->load->view('email/email_template_view', $data, true);
    
    	$config_email = $this->config->item('email');
    	$this->load->library('email', $config_email);
    
    	$this->email->from($this->session->userdata("report_email"), $this->session->userdata("system_title"));
    	$this->email->to($user['email_address']);
    	$this->email->cc($this->session->userdata("report_email"));
    	$this->email->subject($subject);
    	$this->email->message($msg);
    	$this->email->send();
    	 
    	//echo $this->email->print_debugger();
    	 
    }
    
    public function reset($active_code = '') {
    	$data = array('active_code' => $active_code);
    	 
    	if($active_code == '') {
    		$data['error'] = "Invalid token. please check the url again.";
    	}
    	else {
    		$cmd = $this->input->post('cmd');
    
    		if($cmd =='reset') {
    			$user = $this->user_model->getUserByActiveCode($active_code);
    		  
    			if(empty($user)) {
    				$data['error'] = "Invalid user to be changed. please check the url again.";
    			}
    			else {
    				$data['password'] = $this->input->post('password');
    				$password = sha1($this->config->item('encryption_key'). $data['password']);
    
    				$this->user_model->update('users', array('password' => $password, 'active_code' => ''), array('user_id' => $user['user_id']));
    
    				$subject = translate("Password changed in ". $this->session->userdata("system_title"));
    
    				$email_data = array(
    					"subject" => $subject,
    					"user" => $user
    				);
    				$email_data['content'] = $this->load->view('email/email_password_changed', $email_data, true);
    				$msg = $this->load->view('email/email_template_view', $email_data, true);
    
    				$config_email = $this->config->item('email');
    				$this->load->library('email', $config_email);
    
    				$this->email->from($this->session->userdata("report_email"), $this->session->userdata("system_title"));
    				$this->email->to($user['email_address']);
    				$this->email->subject($subject);
    				$this->email->message($msg);
    				$this->email->send();
    
    				$data['success'] = "You have changed password successfully!";
    			}
    		}
    	}
    	 
    	$this->yall->set('title', $this->session->userdata('system_title'))
	    	->set('data', $data)
	    	->render('index/reset');
    }
}

/* End of file index.php */
/* Location: ./application/controllers/index.php */
