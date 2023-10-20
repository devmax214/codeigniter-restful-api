<?php
/*
 * Stripe Helper Class
 */

require_once(BASEPATH. '../application/libraries/stripe-lib/Stripe.php');

class Helper_Stripe {
	
	public static function initialize() {
		Stripe::setApiKey(STRIPE_API_KEY);
	}
	
	public static function createRecipient($user) {

		try {
			Stripe::setApiKey(STRIPE_API_KEY);
				
			$recipient = Stripe_Recipient::create(array( 
				"name" => $user['first_name']. ' '. $user['last_name'], 
				"type" => "individual",
				"bank_account" => array( "country" => "US", "routing_number" => $user['bank_account'], "account_number" => $user['account_number']),
				"description" => "Teacher",
				"email" => $user['email_address']
			));
				
			return $recipient;
		}
		catch (Exception $e) {
			return array('error' => $e->getMessage());
		}
	}
	
	public static function createCustomer($token, $email = null) {
	
		try {
			Stripe::setApiKey(STRIPE_API_KEY);
				
			$customer = Stripe_Customer::create(array(
				"card" => $token,
				"email" => $email,
				"description" => "Portagram Customer"
			));
				
			return $customer;
		}
		catch (Exception $e) {
			return array('error' => $e->getMessage());
		}
	
	}
	
	public static function createTransfer($user, $amount, $currency='usd') {
	
		try {
			Stripe::setApiKey(STRIPE_API_KEY);
	
			$recipient_id = '';
			
			if(is_array($user)) {
				$recipient = Stripe_Recipient::create(array(
					"name" => $user['first_name']. ' '. $user['last_name'],
					"type" => "individual",
					"bank_account" => array( "country" => "US", "routing_number" => $user['bank_account'], "account_number" => $user['account_number']),
					"description" => "Teacher",
					"email" => $user['email_address']
				));
				$recipient_id = $recipient->id;
			}
			else {
				$recipient_id = $user;
			}
			return Stripe_Transfer::create(array(
				"amount" => $amount,
				"currency" => $currency,
				"recipient" => $recipient_id,
				"description" => "Transfer for phurchasing class"
			));
		}
		catch(Stripe_CardError $e) {
			return array('error' => 'Stripe_CardError: '. $e->getMessage());
		}
		catch (Stripe_InvalidRequestError $e) {
			return array('error' => 'Stripe_InvalidRequestError: '. $e->getMessage());
		}
		catch (Stripe_AuthenticationError $e) {
			return array('error' => 'Stripe_AuthenticationError: '. $e->getMessage());
		}
		catch (Stripe_ApiConnectionError $e) {
			return array('error' => 'Stripe_ApiConnectionError: '. $e->getMessage());
		}
		catch (Stripe_Error $e) {
			return array('error' => 'Stripe_Error: '. $e->getMessage());
		}
		catch (Exception $e) {
			return array('error' => 'Exception: '. $e->getMessage());
		}
	}
	
	public static function createCharge($customer_id, $amount, $currency='usd') {
	
		try {
			Stripe::setApiKey(STRIPE_API_KEY);
				
			return Stripe_Charge::create(array(
				"amount" => $amount,
				"currency" => $currency,
				"customer" => $customer_id,
				"description" => "Portagram Charge"
			));
		}
		catch(Stripe_CardError $e) {
			return array('error' => 'Stripe_CardError: '. $e->getMessage());
		}
		catch (Stripe_InvalidRequestError $e) {
			return array('error' => 'Stripe_InvalidRequestError: '. $e->getMessage());
		}
		catch (Stripe_AuthenticationError $e) {
			return array('error' => 'Stripe_AuthenticationError: '. $e->getMessage());
		}
		catch (Stripe_ApiConnectionError $e) {
			return array('error' => 'Stripe_ApiConnectionError: '. $e->getMessage());
		}
		catch (Stripe_Error $e) {
			return array('error' => 'Stripe_Error: '. $e->getMessage());
		}
		catch (Exception $e) {
			return array('error' => 'Exception: '. $e->getMessage());
		}
	
	}
	
	public static function retrieveTransfer($transfer_id) {
		try {
			Stripe::setApiKey(STRIPE_API_KEY);
				
			return Stripe_Transfer::retrieve($transfer_id);
		}
		catch (Exception $e) {
			return array('error' => $e->getMessage());
		}
	}
	
	public static function cancelTransfer($transfer) {
		try {
			Stripe::setApiKey(STRIPE_API_KEY);
	
			return $transfer->cancel();
		}
		catch(Stripe_CardError $e) {
			return array('error' => 'Stripe_CardError: '. $e->getMessage());
		}
		catch (Stripe_InvalidRequestError $e) {
			return array('error' => 'Stripe_InvalidRequestError: '. $e->getMessage());
		}
		catch (Stripe_AuthenticationError $e) {
			return array('error' => 'Stripe_AuthenticationError: '. $e->getMessage());
		}
		catch (Stripe_ApiConnectionError $e) {
			return array('error' => 'Stripe_ApiConnectionError: '. $e->getMessage());
		}
		catch (Stripe_Error $e) {
			return array('error' => 'Stripe_Error: '. $e->getMessage());
		}
		catch (Exception $e) {
			return array('error' => 'Exception: '. $e->getMessage());
		}
	}
	
	public static function retrieveCharge($charge_id) {
		try {
			Stripe::setApiKey(STRIPE_API_KEY);
	
			return Stripe_Charge::retrieve($charge_id);
		}
		catch (Exception $e) {
			return array('error' => $e->getMessage());
		}
	}
	
	public static function refundCharge($charge) {
		try {
			Stripe::setApiKey(STRIPE_API_KEY);
	
			return $charge->refunds->create();
		}
		catch(Stripe_CardError $e) {
			return array('error' => 'Stripe_CardError: '. $e->getMessage());
		}
		catch (Stripe_InvalidRequestError $e) {
			return array('error' => 'Stripe_InvalidRequestError: '. $e->getMessage());
		}
		catch (Stripe_AuthenticationError $e) {
			return array('error' => 'Stripe_AuthenticationError: '. $e->getMessage());
		}
		catch (Stripe_ApiConnectionError $e) {
			return array('error' => 'Stripe_ApiConnectionError: '. $e->getMessage());
		}
		catch (Stripe_Error $e) {
			return array('error' => 'Stripe_Error: '. $e->getMessage());
		}
		catch (Exception $e) {
			return array('error' => 'Exception: '. $e->getMessage());
		}
	}
	
}
