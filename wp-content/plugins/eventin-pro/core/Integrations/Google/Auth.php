<?php
/**
 * Authenticate for Google Services
 * 
 * @package EventinPro
 */
namespace EventinPro\Integrations\Google;

/**
 * Authentication for google
 */
class Auth {
    /**
     * Initialize the class
     *
     * @return  void
     */
    public function __construct() {
        add_action( 'eventin_integration_auth', [ $this, 'authenticate' ], 10, 2 ); 
    }

    /**
     * Update authentication for google service
     *
     * @param   string  $query_var  Google auth
     * @param   string  $code       Authentication code from google
     *
     * @return  void
     */
    public function authenticate( $query_var, $code ) {
        if ( 'google-auth' != $query_var ) {
            return;
        }

        GoogleToken::get_remote( $code );
    }
}
