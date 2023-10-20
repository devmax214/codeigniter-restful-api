<?php
/**
 * APNS push
*/
	$tokens=array('5afd6eaebb639c7d53f421027ef5b50a547c6310cb486bbe382776b6bac9d2d3');
	$development=false;
	$message='Thanks';
	$custom_data=array('cmd'=>'offer');
	$badge=1;
	$sound='default';

	$payload = array();
	$payload['aps'] = array('alert' => $message, 'badge' => intval($badge), 'content-available' => 1, 'sound' => $sound);

	$payload['custom'] = $custom_data;
	$payload = json_encode($payload);

	$apns_url = NULL;
	$apns_cert = NULL;
	$apns_port = 2195;

	if($development)
	{
		$apns_url = 'gateway.sandbox.push.apple.com';
		$apns_cert = 'push_dev.pem';
	}
	else
	{
		$apns_url = 'gateway.push.apple.com';
		$apns_cert = 'push_dist.pem';
	}

	$stream_context = stream_context_create();
	stream_context_set_option($stream_context, 'ssl', 'local_cert', $apns_cert);
	//stream_context_set_option($stream_context, 'ssl', 'passphrase', $pass);

	$apns = stream_socket_client('ssl://' . $apns_url . ':' . $apns_port, $error, $error_string, 300, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $stream_context);

	if($error) {
		print("\nAPN: Maybe some errors: $error: $error_string");
		//print("\nAPN: Maybe some errors: $error: $error_string");
	}

	if (!$apns) {
			
		if ($error) {
			print("\nAPN Failed". 'ssl://' . $apns_url . ':' . $apns_port. " to connect: $error $error_string");
			//print("\nAPN Failed". 'ssl://' . $apns_url . ':' . $apns_port. " to connect: $error $error_string");
		}
		else {
			print("\nAPN Failed to connect: Something wrong with context");
			//print("\nAPN Failed to connect: Something wrong with context");
		}
	}
	else {
		print("\nAPN: Opening connection to: {ssl://" . $apns_url . ":" . $apns_port. "}");
		//print("\nAPN: Opening connection to: {ssl://" . $apns_url . ":" . $apns_port. "}");

		//  You will need to put your device tokens into the $device_tokens array yourself
		if(!empty($tokens)){
			foreach($tokens as $device_token)
			{
				//$apns_message = chr(0) . chr(0) . chr(32) . pack('H*', str_replace(' ', '', $device_token)) . chr(0) . chr(strlen($payload)) . $payload;
				$apns_message = chr(1)           // command
						 . pack("N", time())       // identifier
						 . pack("N", time() + 30000) // expiry
						 . pack('n', 32)        // token length
						 . pack('H*', str_replace(' ', '', $device_token))   // device token
						 . pack('n', strlen($payload))  // payload length
						 . $payload;
				$result = fwrite($apns, $apns_message, strlen($apns_message));

				if($result) {
					print("\nAPN: Push OK - ". $payload);
				}
				else {
					print("\nAPN: Push Failed - ". $payload);
				}
			}
		}
		//@socket_close($apns);
		@fclose($apns);
	}
	
	print("\n");
	
	exit();