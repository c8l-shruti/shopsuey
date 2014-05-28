<?php

namespace Fuel\Tasks;

class Testbraintree
{
	public static function run($args = null)
	{
	    \Package::load('braintree');

	    $credit_card = array(
            'number' => '4111111111111111',
            'cvv' => '111',
            'expiration_date' => '11/15',
	    );
	    $amount = 100.00;
	    $billing_address = array(
    		'address' => 'J. Celman 951',
    		'city' => 'Rio Cuarto',
    		'state' => 'Cordoba',
            'zip_code' => '5800',
	    );
	     
	    try {
	    	$transaction = \Braintree\Api::direct_sale($amount, $credit_card, TRUE);
	        echo "Transaction id => {$transaction->id}\n";
	    } catch (\Braintree\Exception $e) {
	        echo "Direct transaction error\n";
	        print_r($e->getErrorsObject());
	    }

	    try {
	    	$customer = \Braintree\Api::create_customer($credit_card, $billing_address);
	    } catch (\Braintree\Exception $e) {
	        print_r($e->getErrorsObject());
	    	die("Error on customer creation\n");
	    }
	    
        echo "\nCustomer created\n";
	    print_r($customer);
	    
	    $add_ons = array(
            'update' => array(
                array(
                    'id' => 'one_time_setup',
                    'amount' => 100.00,
                ),
                array(
            		'id' => 'monthly_fee',
            		'quantity' => 5,
                ),
            ),
        );
	    
	    $trial_info = array(
            'enable' => TRUE,
            'duration' => 5,
            'unit' => 'day',
        );
// 	    $trial_info = array(
//             'enable' => FALSE,
//         );
	    
	    try {
	    	$subscription = \Braintree\Api::subscribe_customer($customer->credit_card_token, NULL, NULL, $trial_info, $add_ons);
	    } catch (\Braintree\Exception $e) {
	    	print_r($e->getErrorsObject());
	    	die("Error on customer subscription\n");
	    }
	     
	    echo "\nCustomer subcribed\n";
	    print_r($subscription);

	    try {
	    	$subscription = \Braintree\Api::get_subscription($subscription->subscription_id);
	    } catch (\Braintree\Exception $e) {
	        print_r($e->getErrorsObject());
	    	die("Error while fetching subscription\n");
	    }
	    
	    echo "\nSubscription info\n";
	    print_r($subscription);
	     
	    // Try with $2000 / $3000 to generate different errors
	    $vault_charge_amount = 100.00;
	    
	    try {
	    	$transaction = \Braintree\Api::charge_customer($customer->credit_card_token, $vault_charge_amount);
	    } catch (\Braintree\Exception $e) {
	        print_r($e->getErrorsObject());
    	    die("\nTransaction for user on vault error\n");
	    }

	    echo "\nCustomer stored on vault charged\n";
	    print_r($transaction);
	}
}
