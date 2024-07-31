<?php
namespace EventinPro\Integrations\Google;

/**
 * Manage google credentials
 *
 * @package Eventin/Integrations/Google
 */

/**
 * Manage google credentials
 */
class GoogleCredential {
    /**
     * Store authentication url
     *
     * @var string
     */
    private static $auth_url = 'https://accounts.google.com/o/oauth2/v2/auth';

    /**
     * Store scope url
     *
     * @var string
     */
    private static $scope = 'https://www.googleapis.com/auth/calendar';

    /**
     * Get client id
     *
     * @return  string
     */
    public static function get_client_id() {
        return etn_get_option( 'google_meet_client_id' );
    }

    /**
     * Get client secret
     *
     * @return  string
     */
    public static function get_client_secret() {
        return etn_get_option( 'google_meet_client_secret_key' );
    }

    /**
     * Get auth url
     *
     * @return  string
     */
    public static function get_auth_url(): string {
        $args = [
            'client_id'     => self::get_client_id(),
            'redirect_uri'  => self::get_redirect_uri(),
            'scope'         => self::$scope,
            'response_type' => 'code',
            'access_type'   => 'offline',
        ];

        return add_query_arg( $args, self::$auth_url );
    }

    /**
     * Get auth redirect uri
     *
     * @return  string
     */
    public static function get_redirect_uri(): string {
        return site_url( 'eventin-integration/google-auth' );
    }
}
