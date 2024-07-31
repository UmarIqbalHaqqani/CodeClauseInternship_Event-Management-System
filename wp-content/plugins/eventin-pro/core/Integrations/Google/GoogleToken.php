<?php
namespace EventinPro\Integrations\Google;

/**
 * Manage google access token
 *
 * @package Eventin\Integrations\Google
 */
class GoogleToken {
    /**
     * Store token url
     *
     * @var [type]
     */
    private static $token_url = 'https://oauth2.googleapis.com/token';

    /**
     * Get google access token
     *
     * @return  string
     */
    public static function get(): string {
        $token_data = etn_get_option( 'google_token' );

        $access_token      = ! empty( $token_data['access_token'] ) ? $token_data['access_token'] : '';
        $token_expire_time = ! empty( $token_data['expire_time'] ) ? $token_data['expire_time'] : 0;
        $refresh_token     = ! empty( $token_data['refresh_token'] ) ? $token_data['refresh_token'] : '';

        if ( $access_token && $token_expire_time > time() ) {
            return $access_token;
        }

        $token_data = self::get_remote( $refresh_token, 'refresh_token' );

        if ( ! $token_data ) {
            return false;
        }

        if ( ! empty( $token_data['access_token'] ) ) {
            return $token_data['access_token'];
        }

        return false;
    }

    /**
     * Update google access token
     *
     * @return  void
     */
    public static function get_remote( $code, $grant_type = 'authorization_code' ) {
        
        $client_id     = GoogleCredential::get_client_id();
        $client_secret = GoogleCredential::get_client_secret();
        $redirect_uri  = GoogleCredential::get_redirect_uri();

        $args = [
            'client_id'     => $client_id,
            'client_secret' => $client_secret,
            'redirect_uri'  => $redirect_uri,
            'grant_type'    => $grant_type,
        ];

        if ( 'refresh_token' === $grant_type ) {
            $args['refresh_token'] = $code;
        } else {
            $args['code'] = $code;
        }

        $response = wp_remote_post( self::$token_url, ['body' => $args] );
        $status_code = wp_remote_retrieve_response_code( $response );

        if ( 200 != $status_code ) {
            return false;
        }

        $data = json_decode( wp_remote_retrieve_body( $response ), true );

        $data['expire_time'] = time() + $data['expires_in'];

        etn_update_option( 'google_token', $data );

        return $data;
    }
}
