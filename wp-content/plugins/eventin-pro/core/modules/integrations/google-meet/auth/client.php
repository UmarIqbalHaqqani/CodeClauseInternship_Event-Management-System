<?php
/**
 * Google Calendar Auth Class
 *
 * @package Eventin
 */
namespace Etn_Pro\Core\Modules\Integrations\Google_Meet\Auth;

/**
 * Class Auth
 */
class Client {
    const EVENTIN_REVOKE_URI = 'https://oauth2.googleapis.com/revoke';
    const EVENTIN_TOKEN_URI  = 'https://oauth2.googleapis.com/token';
    const EVENTIN_AUTH_URL   = 'https://accounts.google.com/o/oauth2/v2/auth';
    const API_BASE_PATH       = 'https://www.googleapis.com';

    /**
     * Store google app client id
     *
     * @var string
     */
    private $client_id;

    /**
     * Store google app secrete
     *
     * @var string
     */
    private $client_secrete;

    /**
     * Store google app auth scope
     *
     * @var string
     */
    private $auth_scope;

    /**
     * Store google auth redirect uri
     *
     * @var string
     */
    private $redirect_uri;

    /**
     * Get app client id
     *
     * @return  string
     */
    public function get_client_id() {
        return $this->client_id;
    }

    /**
     * Get app secrete
     *
     * @return  string
     */
    public function get_client_secrete() {
        return $this->client_secrete;
    }

    /**
     * Get app auth scope
     *
     * @return  string
     */
    public function get_auth_scope() {
        return $this->auth_scope;
    }

    /**
     * Get auth redirect uri
     *
     * @return  string
     */
    public function get_redirect_uri() {
        return $this->redirect_uri;
    }

    /**
     * Get auth url
     *
     * @return  string
     */
    public function get_auth_url() {
        $auth_url = add_query_arg(
            [
				'client_id'     => $this->client_id,
				'scope'         => urlencode_deep( $this->auth_scope ),
				'redirect_uri'  => $this->redirect_uri,
				'response_type' => 'code',
				'access_type'   => 'offline',

			], self::EVENTIN_AUTH_URL
        );

        return $auth_url;
    }

    /**
     * Set google authentication configuration
     *
     * @return  void
     */
    public function set_auth_config( $args = [] ) {
        $defaults = [
            'client_id'      => '',
            'client_secrete' => '',
        ];

        $args = wp_parse_args( $args, $defaults );

        $this->client_id      = $args['client_id'];
        $this->client_secrete = $args['client_secrete'];
    }

    /**
     * Set authentication scope
     *
     * @return void
     */
    public function add_scope( $scope ) {
        $this->auth_scope = $scope;
    }

    /**
     * Set redirect uri
     *
     * @param   string  $uri  Redirect uri
     *
     * @return void
     */
    public function set_redirect_uri( $uri ) {
        $this->redirect_uri = $uri;
    }

    /**
     * Fetch access token
     *
     * @param   string  $code
     *
     * @return void
     */
    public function fetch_access_token_with_auth_code( $code ) {
        if ( strlen( $code ) === 0 ) {
            throw new \InvalidArgumentException( 'Invalid code' );
        }

        $args = [
            'client_id'     => $this->client_id,
            'client_secret' => $this->client_secrete,
            'code'          => $code,
            'redirect_uri'  => $this->redirect_uri,
            'grant_type'    => 'authorization_code',
        ];

        $response = wp_remote_post( self::EVENTIN_TOKEN_URI, [ 'body' => $args ] );

        if ( ! is_wp_error( $response ) ) {
            $data = json_decode( wp_remote_retrieve_body( $response ), true );

            return $data;
        }

        return $response;
    }

    /**
     * Fetch access token by using refresh token
     *
     * @return  void
     */
    public function fetch_access_token_with_refresh_token( $refresh_token ) {
        $settings           = \Etn\Core\Settings\Settings::instance()->get_settings_option();
        $client_id          = isset( $settings['google_meet_client_id'] ) ? $settings['google_meet_client_id'] : '';
        $client_secret      = isset( $settings['google_meet_client_secret_key'] ) ? $settings['google_meet_client_secret_key'] : '';
        $redirect_uri       = site_url( 'eventin-integration/google-auth');
        $args = [
            'client_id'     => $client_id,
            'client_secret' => $client_secret,
            'refresh_token' => $refresh_token,
            'redirect_uri'  => $redirect_uri,
            'grant_type'    => 'refresh_token',
        ];

        $response = wp_remote_post( self::EVENTIN_TOKEN_URI, [ 'body' => $args ] );

        if ( ! is_wp_error( $response ) ) {
            $data = json_decode( wp_remote_retrieve_body( $response ), true );

            return $data;
        }

        return $response;
    }

    /**
     * Revoke authorization
     *
     * @param   string  $token
     *
     * @return bool
     */
    public function revoke( $token ) {
        $response = wp_remote_post(
            self::EVENTIN_REVOKE_URI, [
                'headers' => [
                    'content-type' => 'application/x-www-form-urlencoded',
                ],
                'body'    => build_query(
                    [
                        'token' => $token,
                    ]
                ),
            ]
        );

        $status_code = wp_remote_retrieve_response_code( $response );

        return $status_code === 200;
    }
}
