<?php
/**
 * Authentication for integrations
 *
 * @package Eventin
 */

namespace Etn_Pro\Core\Modules\Integrations\Google_Meet\Auth;

use Etn\Traits\Singleton;
use Etn_Pro\Core\Modules\Integrations\Google_Meet\Service\Calendar;
use Etn_Pro\Core\Modules\Integrations\Google_Meet\Auth\Client;

/**
 * Auth Class
 *
 * @since 1.0.0
 */
class Auth {
    use Singleton;

    /**
     * Initialize
     *
     * @return  void
     */
    public function init() {
        add_action( 'template_redirect', [ $this, 'authenticate' ] );
        add_action( 'init', [$this, 'register'] );
    }

    /**
     * Authenticate integration
     *
     * @return  void
     */
    public function authenticate() {
        $query_var = get_query_var( 'etn-google-meet', false );
        $user_id   = get_current_user_id();

        $code = isset( $_GET['code'] ) ? sanitize_text_field( $_GET['code'] ) : '';

        if ( ! $query_var ) {
            return;
        }

        $this->google_auth( $code );

        wp_redirect( admin_url( 'admin.php?page=etn-event-settings') );
        exit;
    }

    /**
     * Authentication for google
     *
     * @param   string  $code
     *
     * @return  void
     */
    public function google_auth( $code = '' ) {

        // Prepare auth request data
        $settings      = \Etn\Core\Settings\Settings::instance()->get_settings_option();
        $client_id     = isset( $settings['google_meet_client_id'] ) ? $settings['google_meet_client_id'] : '';
        $client_secret = isset( $settings['google_meet_client_secret_key'] ) ? $settings['google_meet_client_secret_key'] : '';

        $redirect_uri = site_url( 'eventin-integration/google-auth');

        $client = new Client();
        $client->add_scope( Calendar::scope() );
        $client->set_auth_config(
            [
                'client_id'      => $client_id,
                'client_secrete' => $client_secret,
            ]
        );

        $client->set_redirect_uri( $redirect_uri );


        // Fetch access token with auth
        $data = $client->fetch_access_token_with_auth_code( $code );
        $data['code'] = $code;
        $data['expires_in'] = $data['expires_in'] + time();

        // Save access token.
        update_option( 'etn_google_auth', $data );
    }

/**
     * Register all custom endpoints
     *
     * @return  void
     */
    public function register() {
        $endpoints = $this->get_endpoints();

        foreach ( $endpoints as $endpoint ) {
            add_rewrite_endpoint( $endpoint, EP_ALL );
        }

        // Flush rewrite rules after register all custom endpoints.
        flush_rewrite_rules( true );
    }

    /**
     * Get all endpoints
     *
     * @return  array
     */
    public function get_endpoints() {
        /**
         * All endpoints that have to be register
         */
        return [
            'etn-google-meet',
        ];
    }
}
