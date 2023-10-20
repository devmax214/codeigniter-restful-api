<?php
/**
 * Get user token and badge
 */
function apns_push($user_id, $development=true, $message='', $custom_data=array()) {
	$CI = & get_instance();
	
	$CI->load->model('settings_model');
	$CI->load->model('user_model');
	
	$user = $CI->user_model->get('users', array('user_id' => $user_id), 1);
	if(!empty($user)) {
		$notifications = $CI->settings_model->get('notifications', array('to_id' => $user_id, 'read' => '0'));
		$badge = empty($notifications)? 1: count($notifications) + 1;
	
		if($user['device_token'] != '') {
			apns_send(array($user['device_token']), $development, $message, $custom_data, $badge);
			return true;
		}
	}	
	return false;
}

/**
 * APNS push
 */
function apns_send($tokens=array(),$development=true,$message='',$custom_data=array(), $badge=1,$sound='default'){

	$payload = array();
	$payload['aps'] = array('alert' => $message, 'badge' => intval($badge), 'content-available' => 1, 'sound' => $sound);

	$payload['custom'] = $custom_data;
	$payload = json_encode($payload);

	$apns_url = NULL;
	$apns_cert = NULL;
	$apns_port = 2195;

	$CI = & get_instance();
	$config = $CI->config->item('apns');
	
	if($development)
	{
		$apns_url = 'gateway.sandbox.push.apple.com';
		$apns_cert = $config['development_key'];
	}
	else
	{
		$apns_url = 'gateway.push.apple.com';
		$apns_cert = $config['distribute_key'];
	}
	
	$stream_context = stream_context_create();
	stream_context_set_option($stream_context, 'ssl', 'local_cert', $apns_cert);
	//stream_context_set_option($stream_context, 'ssl', 'passphrase', $pass);

	$apns = stream_socket_client('ssl://' . $apns_url . ':' . $apns_port, $error, $error_string, 300, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $stream_context);
	
	if($error) {
		log_message('debug', "APN: Maybe some errors: $error: $error_string");
		//print("APN: Maybe some errors: $error: $error_string");
	}
	
	if (!$apns) {
		 
		if ($error) {
			log_message('debug', "APN Failed". 'ssl://' . $apns_url . ':' . $apns_port. " to connect: $error $error_string");
			//print("APN Failed". 'ssl://' . $apns_url . ':' . $apns_port. " to connect: $error $error_string");
		}
		else {
			log_message('debug', "APN Failed to connect: Something wrong with context");
			//print("APN Failed to connect: Something wrong with context");
		}
	
		return false;
	}
	else {
		log_message('debug', "APN: Opening connection to: {ssl://" . $apns_url . ":" . $apns_port. "}");
		//print("APN: Opening connection to: {ssl://" . $apns_url . ":" . $apns_port. "}");
		
		//  You will need to put your device tokens into the $device_tokens array yourself
		if(!empty($tokens)){
			foreach($tokens as $device_token)
			{
				$apns_message = chr(0) . chr(0) . chr(32) . pack('H*', str_replace(' ', '', $device_token)) . chr(0) . chr(strlen($payload)) . $payload;
				$result = fwrite($apns, $apns_message, strlen($apns_message));
			}
		}
		//@socket_close($apns);
		@fclose($apns);
	}
}