<?php

/**
 * Wrapper class for the Braintree PHP Client Library
 *
 */
namespace Braintree;

class Api {

    const ERROR_INCOMPLETE_CREDIT_CARD_INFO = 1;
    const ERROR_TRANSACTION_VALIDATION = 2;
    const ERROR_TRANSACTION_PROCESSOR_DECLINED = 3;
    const ERROR_TRANSACTION_GATEWAY_REJECTION = 4;
    const ERROR_TRANSACTION_UNKNOW = 5;
    const ERROR_INCOMPLETE_BILLING_ADDRESS = 6;
    const ERROR_SUBSCRIPTION_GET = 7;
    const ERROR_CUSTOMER_GET = 8;
    
    const SUBSCRIPTION_ACTIVE = \Braintree_Subscription::ACTIVE;
    const SUBSCRIPTION_CANCELED = \Braintree_Subscription::CANCELED;
    const SUBSCRIPTION_EXPIRED = \Braintree_Subscription::EXPIRED;
    const SUBSCRIPTION_PAST_DUE = \Braintree_Subscription::PAST_DUE;
    const SUBSCRIPTION_PENDING = \Braintree_Subscription::PENDING;
    
    private static $_transaction_statuses = array(
        \Braintree_Transaction::AUTHORIZATION_EXPIRED    => 'Authorization Expired',
        \Braintree_Transaction::AUTHORIZING              => 'Authorizing',
        \Braintree_Transaction::AUTHORIZED               => 'Authorized',
        \Braintree_Transaction::GATEWAY_REJECTED         => 'Gateway Rejected',
        \Braintree_Transaction::FAILED                   => 'Failed',
        \Braintree_Transaction::PROCESSOR_DECLINED       => 'Processor Declined',
        \Braintree_Transaction::SETTLED                  => 'Settled',
        \Braintree_Transaction::SETTLING                 => 'Settling',
        \Braintree_Transaction::SUBMITTED_FOR_SETTLEMENT => 'Submitted For Settlement',
        \Braintree_Transaction::VOIDED                   => 'Voided',
    );

    private static $_transaction_types = array(
        \Braintree_Transaction::SALE   => 'Sale',
        \Braintree_Transaction::CREDIT => 'Credit',
    );

	private static $_environment;
	private static $_merchant_id;
	private static $_public_key;
	private static $_private_key;
	private static $_client_side_encryption_key;
	private static $_default_subscription_id;

	final private function __construct() {}

	private static function _init()
	{
		\Config::load('braintree', true);

		static::$_environment = \Config::get('braintree.environment', '');
		static::$_merchant_id = \Config::get('braintree.merchant_id', '');
		static::$_public_key = \Config::get('braintree.public_key', '');
		static::$_private_key = \Config::get('braintree.private_key', '');
		static::$_client_side_encryption_key = \Config::get('braintree.client_side_encryption_key', '');
		static::$_default_subscription_id = \Config::get('braintree.default_subscription_id', '');
		
		\Braintree_Configuration::environment(static::$_environment);
		\Braintree_Configuration::merchantId(static::$_merchant_id);
		\Braintree_Configuration::publicKey(static::$_public_key);
		\Braintree_Configuration::privateKey(static::$_private_key);
	}

	/**
	 * Creates a transaction to charge an amount to a user without storing anything on Braintree's vault.
	 * It won't work if the merchant is configured to apply additional checks to billing address.
	 * 
	 * @param unknown $amount
	 * @param unknown $credit_card_info
	 * @param string $settle
	 * @throws Exception
	 */
	public static function direct_sale($amount, $credit_card_info, $settle = FALSE) {

	    static::_init();
	    
	    if (! static::_check_credit_card_info($credit_card_info)) {
	        throw new Exception("Missing information for credit card", static::ERROR_INCOMPLETE_CREDIT_CARD_INFO);
	    }

	    $sale_info = array(
            'amount' => $amount,
            'creditCard' => array(
                'number'         => $credit_card_info['number'],
                'cvv'            => $credit_card_info['cvv'],
                'expirationDate' => $credit_card_info['expiration_date'],
            ),
            'options' => array(
                'submitForSettlement' => $settle,
            ),
        );

	    $result = \Braintree_Transaction::sale($sale_info);

	    static::_check_transaction_result($result);
	    
	    $transaction = new \stdClass();
	    $transaction->transaction_id = $result->transaction->id;
	    
	    return $transaction;
	}

	/**
	 * Creates a customer on Braintree's vault and stores the info of a credit card for him.
	 * This credit card info can be used at a later time to charge the user or the subscribe him to a plan
	 * without having to check the credit card again
	 * 
	 * @param unknown $credit_card_info
	 * @param unknown $billing_address
	 * @throws Exception
	 * @return \stdClass
	 */
	public static function create_customer($credit_card_info, $billing_address) {
	    static::_init();
	     
	    if (! static::_check_credit_card_info($credit_card_info)) {
	    	throw new Exception("Missing information for credit card", static::ERROR_INCOMPLETE_CREDIT_CARD_INFO);
	    }

	    if (! static::_check_billing_address($billing_address)) {
	    	throw new Exception("Missing information for billing address", static::ERROR_INCOMPLETE_BILLING_ADDRESS);
	    }

	    $customer_info = array(
            'creditCard' => array(
                'number'         => $credit_card_info['number'],
                'cvv'            => $credit_card_info['cvv'],
                'expirationDate' => $credit_card_info['expiration_date'],
                'billingAddress' => array(
                    'streetAddress' => $billing_address['address'],
            		'locality'      => $billing_address['city'],
            		'region'        => $billing_address['state'],
            		'postalCode'    => $billing_address['zip_code'],
                ),
                'options' => array(
            		'verifyCard' => TRUE
                )
            ),
        );
	    
	    $result = \Braintree_Customer::create($customer_info);

	    static::_check_customer_creation_result($result);

	    $customer = new \stdClass();
	    $customer->customer_id = $result->customer->id;
	    $customer->credit_card_token = $result->customer->creditCards[0]->token;
	    
	    return $customer;
	}

	/**
	 * Updates a customer on Braintree's vault.
	 *
	 * @param unknown $credit_card_info
	 * @param unknown $billing_address
	 * @throws Exception
	 * @return \stdClass
	 */
	public static function update_customer($customer_id, $credit_card_token, $credit_card_info, $billing_address) {
		static::_init();

		if (! static::_check_credit_card_info($credit_card_info)) {
			throw new Exception("Missing information for credit card", static::ERROR_INCOMPLETE_CREDIT_CARD_INFO);
		}

		if (! static::_check_billing_address($billing_address)) {
			throw new Exception("Missing information for billing address", static::ERROR_INCOMPLETE_BILLING_ADDRESS);
		}

		$customer_info = array(
			'creditCard' => array(
				'number'         => $credit_card_info['number'],
				'cvv'            => $credit_card_info['cvv'],
				'expirationDate' => $credit_card_info['expiration_date'],
		        'options' => array(
	        		'updateExistingToken' => $credit_card_token,
		        ),
				'billingAddress' => array(
					'streetAddress' => $billing_address['address'],
					'locality'      => $billing_address['city'],
					'region'        => $billing_address['state'],
					'postalCode'    => $billing_address['zip_code'],
			        'options' => array(
                        'updateExisting' => true,
			        )
				),
				'options' => array(
					'verifyCard' => TRUE,
				)
			),
		);

		$result = \Braintree_Customer::update($customer_id, $customer_info);

		static::_check_customer_creation_result($result);

		$customer = new \stdClass();
		$customer->customer_id = $customer_id;
		$customer->credit_card_token = $result->customer->creditCards[0]->token;

		return $customer;
	}
	
	/**
	 * Subscribes a user to a given plan. Various parameters can be passed to override the default config of the plan
	 * 
	 * @param unknown $credit_card_token
	 * @param string $plan_id
	 * @param string $price
	 * @param string $trial_info
	 * @param string $add_ons
	 * @return unknown
	 */
	public static function subscribe_customer($credit_card_token, $plan_id = NULL, $price = NULL, $trial_info = NULL, $add_ons = NULL, $discounts = NULL) {

	    $plan_id = is_null($plan_id) ? static::$_default_subscription_id : $plan_id;
	    
	    $subscription_info = array(
            'paymentMethodToken' => $credit_card_token,
            'planId' => $plan_id,
	    );

	    if (!is_null($price)) {
	        $subscription_info['price'] = $price;
	    }

	    if (!is_null($trial_info)) {
	    	$subscription_info['trialPeriod'] = $trial_info['enable'];
	    	if (isset($trial_info['duration'])) {
	    	    $subscription_info['trialDuration'] = $trial_info['duration'];
	    	}
	    	if (isset($trial_info['unit'])) {
    	    	$subscription_info['trialDurationUnit'] = $trial_info['unit'];
	    	}
	    }

	    if (!is_null($add_ons)) {
	        $processed_add_ons = array();

	        if (isset($add_ons['add'])) {
	            $processed_add_ons['add'] = static::_process_add_ons($add_ons['add'], TRUE);
	        }
	        if (isset($add_ons['update'])) {
	        	$processed_add_ons['update'] = static::_process_add_ons($add_ons['update'], FALSE);
	        }
	        if (isset($add_ons['remove'])) {
	        	$processed_add_ons['remove'] = static::_process_remove_add_ons($add_ons['remove']);
	        }
	         
	        $subscription_info['addOns'] = $processed_add_ons;
	    }

	    if (!is_null($discounts)) {
	    	$processed_discounts = array();
	    
	    	if (isset($discounts['add'])) {
	    		$processed_discounts['add'] = static::_process_add_ons($discounts['add'], TRUE);
	    	}
	    	if (isset($discounts['update'])) {
	    		$processed_discounts['update'] = static::_process_add_ons($discounts['update'], FALSE);
	    	}
	    	if (isset($discounts['remove'])) {
	    		$processed_discounts['remove'] = static::_process_remove_add_ons($discounts['remove']);
	    	}
	    
	    	$subscription_info['discounts'] = $processed_discounts;
	    }

	    $result = \Braintree_Subscription::create($subscription_info);
	    
	    static::_check_transaction_result($result);

	    $subscription = new \stdClass();
	    $subscription->subscription_id = $result->subscription->id;
	     
	    return $subscription;
	}

	/**
	 * Creates a transaction to charge an amount to a customer stored on Braintree's vault.
	 * Notice that no checks for card validity are performed because the credit card is already stored on the vault 
	 *
	 * @param unknown $credit_card_token
	 * @param unknown $amount
	 * @throws Exception
	 */
	public static function charge_customer($credit_card_token, $amount) {
	
		static::_init();
		 
		$sale_info = array(
	        'paymentMethodToken' => $credit_card_token,
			'amount' => $amount,
		);
	
		$result = \Braintree_Transaction::sale($sale_info);

		static::_check_transaction_result($result);
		 
		$transaction = new \stdClass();
		$transaction->transaction_id = $result->transaction->id;
		
		return $transaction;
	}
	
	/**
	 * Searches for a given subscription
	 * 
	 * @param unknown $subscription_id
	 */
	public static function get_subscription($subscription_id) {
	    static::_init();

	    try {
	        $result = \Braintree_Subscription::find($subscription_id);
	    } catch (\Braintree_Exception_NotFound $e) {
        	$errors = new \stdClass();
        	$errors->messages = array("The subscription could not be found");
    		$exception = new Exception("Error on Braintree subscription retrieval", static::ERROR_SUBSCRIPTION_GET);
    		$exception->setErrorsObject($errors);
    		throw $exception;
	    }

	    $subscription = new \stdClass();
	    $subscription->info = $result;
	     
	    return $subscription;
	}

	/**
	 * Searches for a given customer
	 *
	 * @param unknown $customer_id
	 */
	public static function get_customer($customer_id) {
		static::_init();
	
		try {
			$result = \Braintree_Customer::find($customer_id);
		} catch (\Braintree_Exception_NotFound $e) {
			$errors = new \stdClass();
			$errors->messages = array("The customer could not be found");
			$exception = new Exception("Error on Braintree customer retrieval", static::ERROR_CUSTOMER_GET);
			$exception->setErrorsObject($errors);
			throw $exception;
		}
	
		$customer = new \stdClass();
		$customer->info = $result;
	
		return $customer;
	}
	
	public static function get_transaction_statuses() {
	    return static::$_transaction_statuses;
	}

	public static function get_transaction_types() {
		return static::$_transaction_types;
	}
	
	private static function _check_credit_card_info($credit_card_info) {
	    return is_array($credit_card_info) &&
	        isset($credit_card_info['number']) &&
	        isset($credit_card_info['cvv']) &&
	        isset($credit_card_info['expiration_date']);
	}

	private static function _check_billing_address($billing_address) {
		return is_array($billing_address) &&
    		isset($billing_address['address']) &&
		    isset($billing_address['city']) &&
    		isset($billing_address['state']) &&
    		isset($billing_address['zip_code']);
	}
	
	private static function _check_transaction_result($result) {
	    $code = NULL;
	    $errors = new \stdClass();
	    $errors->messages = array();

	    if ($result->success) {
	    	return TRUE;
	    } elseif ($result->transaction) {
	        $transaction = $result->transaction;
	        switch ($transaction->status) {
	            case 'processor_declined':
                    $code = static::ERROR_TRANSACTION_PROCESSOR_DECLINED;
                    $errors->messages[] = "{$transaction->processorResponseText} [{$transaction->processorResponseCode}]";
                    break;
                case 'gateway_rejected':
                    $code = static::ERROR_TRANSACTION_GATEWAY_REJECTION;
                    $errors->messages[] = $transaction->gatewayRejectionReason;
                    break;
                default:
                    $code = static::ERROR_TRANSACTION_UNKNOW;
                    $errors->messages[] = 'Unknow transaction error';
                    break;
	        }
	    } else {
	        $code = static::ERROR_TRANSACTION_VALIDATION;
	    	foreach ($result->errors->deepAll() as $error) {
	    	    $errors->messages[] = $error->message;
	    	}
	    }
	    
	    $exception = new Exception("Error on Braintree transaction", $code);
	    $exception->setErrorsObject($errors);
	    throw $exception;
	}
	
	private static function _check_customer_creation_result($result) {
	    $code = NULL;
	    $errors = new \stdClass();
	    $errors->messages = array();

	    if (!$result->success) {
	        if (isset($result->creditCardVerification) && !empty($result->creditCardVerification)) {
    	        $verification = $result->creditCardVerification;
    	    	switch ($verification->status) {
    	    		case \Braintree_Transaction::PROCESSOR_DECLINED:
    	    			$code = static::ERROR_TRANSACTION_PROCESSOR_DECLINED;
    	    			$errors->messages[] = "{$verification->processorResponseText} [{$verification->processorResponseCode}]";
    	    			break;
    	    		case \Braintree_Transaction::GATEWAY_REJECTED:
    	    			$code = static::ERROR_TRANSACTION_GATEWAY_REJECTION;
    	    			$errors->messages[] = "{$result->message} ({$verification->gatewayRejectionReason})";
    	    			break;
    	    		default:
    	    			$code = static::ERROR_TRANSACTION_UNKNOW;
    	    			$errors->messages[] = $result->message;
    	    			break;
    	    	}
	        } else {
    	        $code = static::ERROR_TRANSACTION_VALIDATION;
    	    	foreach ($result->errors->deepAll() as $error) {
    	    	    $errors->messages[] = $error->message;
    	    	}
	        }
	    	$exception = new Exception("Error on Braintree customer creation", $code);
	    	$exception->setErrorsObject($errors);
	    	throw $exception;
	    }

	    return TRUE;
	}

	private static function _process_add_ons($add_ons, $addition = TRUE) {
	    $processed_add_ons = array();
	    
	    $add_on_key = $addition ? 'inheritedFromId' : 'existingId';
	    
	    foreach($add_ons as $add_on) {
	    	$processed_add_on = array(
    			$add_on_key => $add_on['id'],
	    	);
	    	if (isset($add_on['amount'])) {
	    		$processed_add_on['amount'] = $add_on['amount'];
	    	}
	    	if (isset($add_on['quantity'])) {
	    		$processed_add_on['quantity'] = $add_on['quantity'];
	    	}
	    	if (isset($add_on['number_of_billing_cycles'])) {
	    		$processed_add_on['numberOfBillingCycles'] = $add_on['number_of_billing_cycles'];
	    	} elseif (isset($add_on['never_expires'])) {
	    		$processed_add_on['neverExpires'] = $add_on['never_expires'];
	    	}
	    	$processed_add_ons[] = $processed_add_on;
	    }
	     
	    return $processed_add_ons;
	}
	
	private static function _process_remove_add_ons($add_ons) {
		$processed_add_ons = array();
		
		foreach($add_ons as $add_on) {
		    $processed_add_ons[] = $add_on['id'];
		}
		
		return $processed_add_ons;
	}
}
