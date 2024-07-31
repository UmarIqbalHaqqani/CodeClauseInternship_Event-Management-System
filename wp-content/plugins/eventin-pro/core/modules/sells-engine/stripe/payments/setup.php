<?php

namespace Etn_Pro\Core\Modules\Sells_Engine\Stripe\Payments;

use Exception;

defined('ABSPATH') || exit;

class Setup {

	private static $instance;

	private $_sandbox = false;

	private $secret_key = null;

	private $test_secret_key = null;

	private $live_keys = null;

	public $return_config = [];

	public function init(array $config) {

		if(isset($config['_sandbox'])) {
			$this->_sandbox = $config['_sandbox'];
		}

		if(isset($config['stripe_secret_key_test'])) {
			$this->test_secret_key = $config['stripe_secret_key_test'];
		}

		if(isset($config['stripe_secret_key'])) {
			$this->secret_key = $config['stripe_secret_key'];
		}

		$this->live_keys = ($this->_sandbox) ? $this->test_secret_key : $this->secret_key;

		$this->return_config['_sandbox'] = $this->_sandbox;
		$this->return_config['test_secret_key'] = $this->test_secret_key;
		$this->return_config['secret_key'] = $this->secret_key;
		$this->return_config['live_keys'] = $this->live_keys;

		return $this;
	}

	public function stripe_verify($config) {

		require_once __DIR__ . '/stripe-php/init.php';

		$token      = isset($config['token']) ? $config['token'] : '';
		$amount_cent = isset($config['amount']) ? $config['amount'] : 0;
		$currency   = isset($config['currency']) ? $config['currency'] : 'USD';

		try {
			\Stripe\Stripe::setApiKey($this->live_keys);

			try {
				$charge = \Stripe\Charge::create(array('amount' => $amount_cent, 'currency' => $currency, 'source' => $token));
				$payment_data = array(
					'livemode'             => $charge['livemode'],
					'amount'               => $charge['amount'],
					'currency'             => $charge['currency'],
					'paid'                 => $charge['paid'],
					'status'               => $charge['status'],
					'receipt_email'        => $charge['receipt_email'],
					'receipt_number'       => $charge['receipt_number'],
					'refunded'             => $charge['refunded'],
					'amount_refunded'      => $charge['amount_refunded'],
					'application_fee'      => $charge['application_fee'],
					'balance_transaction'  => $charge['balance_transaction'],
					'captured'             => $charge['captured'],
					'created'              => $charge['created'],
					'customer'             => $charge['customer'],
					'description'          => $charge['description'],
					'destination'          => $charge['destination'],
					'dispute'              => $charge['dispute'],
					'failure_code'         => $charge['failure_code'],
					'failure_message'      => $charge['failure_message'],
					'fraud_details'        => $charge['fraud_details'],
					'invoice'              => $charge['invoice'],
					'order'                => $charge['order'],
					'shipping'             => $charge['shipping'],
					'source_transfer'      => $charge['source_transfer'],
					'statement_descriptor' => $charge['statement_descriptor'],
				);
				return ['status' => true, 'get' => $payment_data];
			}catch(\Stripe\Exception\CardException $e) {
				error_log("A payment error occurred: {$e->getError()->message}");
			} catch (\Stripe\Exception\InvalidRequestException $e) {
				error_log("An invalid request occurred.");
			} catch (Exception $e) {
			// payment has declined
				return ['status' => false, 'get' => [] , 'message' => $e->getMessage(), 'payment_status' => 'Declined'];
			}

		} catch(Exception $e) {

			return ['status' => false, 'get' => $e->getMessage()];
		}

		return ['status' => false, 'get' => ''];
	}

	public function _load_script() {
		// script load
		add_action('wp_enqueue_scripts', [$this, '_script']);
	}

	public function _script() {
		// stripe script
		wp_register_script('stripe-checkout', 'https://js.stripe.com/v3/', array('jquery'), '1.0.0', false);

		wp_enqueue_script('stripe-checkout');
	}

	public function _return_config() {
		return $this->return_config;
	}

	public static function instance() {
		if(!self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}