<?php
/**
 * Class stripe payment
 *
 * @package Timetics
 */
namespace Etn_Pro\Core\Modules\Sells_Engine\Stripe;

/**
 * Class StripePayment
 */
class StripePayment {
    /**
     * Store stripe paymentintent api url
     *
     * @var string
     */
    private $payment_intent_url = 'https://api.stripe.com/v1/payment_intents';

    /**
     * Create stripe paymentintent
     *
     * @param   array  $args  Stripe payment details
     *
     * @return  array
     */
    public function create_payment( $args = [] ) {
        $defaults = [
            'amount'                 => '',
            'currency'               => '',
            'payment_method_types[]' => 'card',
        ];

        $settings = \Etn\Core\Settings\Settings::instance()->get_settings_option();
        $stripe_secret_key = (isset($settings['stripe_live_secret_key']) ? $settings['stripe_live_secret_key'] : '');

        $args   = wp_parse_args( $args, $defaults );
        $url    = $this->payment_intent_url;
        $secret = $stripe_secret_key;

        $params = [
            'headers' => [
                'Authorization' => 'Bearer ' . $secret,
                'Content-Type'  => 'application/x-www-form-urlencoded;charset=UTF-8',
            ],
            'body'    => build_query( $args ),
        ];

        $response = wp_remote_post( $url, $params );

        if ( ! is_wp_error( $response ) ) {
            return json_decode( wp_remote_retrieve_body( $response ), true );
        }

        return $response;
    }

    /**
     * Create fund
     *
     * @return
     */
    public function create_refund( $payment_intent_id ) {
        $api_url = 'https://api.stripe.com/v1/refunds';
        $settings = \Etn\Core\Settings\Settings::instance()->get_settings_option();
        $stripe_secret_key = (isset($settings['stripe_live_secret_key']) ? $settings['stripe_live_secret_key'] : '');

        $args = [
            'payment_intent' => $payment_intent_id,
        ];

        $params = [
            'headers' => [
                'Authorization' => 'Bearer ' . $stripe_secret_key,
                'Content-Type'  => 'application/x-www-form-urlencoded;charset=UTF-8',
            ],
            'body'    => build_query( $args ),
        ];

        $response = wp_remote_post( $api_url, $params );
        
        return 200 == wp_remote_retrieve_response_code( $response );
    }
}
