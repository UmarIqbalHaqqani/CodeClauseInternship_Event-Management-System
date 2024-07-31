<?php
/**
 * License activator class
 *
 * @package ThemeLicense
 */
namespace Theme\License;

/**
 * License Activator Class
 */
class License_Activator {
    /**
     * Store store url
     *
     * @var string
     */
    private $store_url = THEME_LICENSE_STROE;

    /**
     * Store product id
     *
     * @var integer
     */
    private $product_id = THEME_LICENSE_PRODUCT;

    /**
     * Activate envato license key
     *
     * @param   array  $args  Envato Licence Details
     *
     * @return  void
     */
    public function activate_envato( $args ) {
        $envato_activate_url = $this->store_url . '/wp-json/nuclear/v1/license/envato';
        $defaults            = [
            'name'        => '',
            'email'       => '',
            'license_key' => '',
            'item_id'     => THEME_LICENSE_PRODUCT,
            'url'         => home_url(),
            'token_name'  => 'envato_for_edd_theme_token'
        ];

        $args = wp_parse_args( $args, $defaults );

        $response = wp_remote_post( $envato_activate_url, [
            'body' => json_encode( $args ),
        ] );

        $data = json_decode( wp_remote_retrieve_body( $response ), true );

        return $data;
    }

    /**
     * Activate Edd License
     *
     * @param   array  $args  Edd License Details
     *
     * @return  void
     */
    public function activate_license( $args ) {
        $data = $this->update_license( $args, 'activate_license' );

        // Update License Key.
        theme_update_user( [
            'name'          => $args['name'],
            'email'         => $args['email'],
            'license_key'   => $args['license_key'],
        ] );

        // Store license details.
        $this->store_license_details( $data );

        return $data;
    }

    /**
     * Deactivate license
     *
     * @param  array  $args License details
     *
     * @return  array
     */
    public function deactivate_license( $args ) {
        // Deactive edd store license.
        $data = $this->update_license( $args, 'deactivate_license' );
        
        // Delete user details.
        theme_delete_user_details();

        // Delete license details.
        theme_delete_license_details();

        return $data;
    }

    /**
     * Update license
     *
     * @param   array  $args    User details
     * @param   string  $action  Edd license action
     *
     * @return  array
     */
    public function update_license( $args, $action ) {

        $defaults = [
            'name'        => '',
            'email'       => '',
            'license_key' => '',
        ];

        // Prepare data.
        $args = wp_parse_args( $args, $defaults );
        $url  = site_url();

        $api_url = $this->store_url . "?edd_action={$action}&item_id={$this->product_id}&license={$args['license_key']}&url={$url}";

        // Send remote request.
        $response = wp_remote_get( $api_url );
        $data     = json_decode( wp_remote_retrieve_body( $response ), true );
        
        // Return response.
        if ( ! empty( $data['error'] ) && 'missing' == $data['error'] ) {
            return $this->activate_envato( $args );
        }

        return $data;
    }

    /**
     * Store license details
     *
     * @param array
     *
     * @return  void
     */
    public function store_license_details( $data ) {
        $key = '_theme_license';

        // Update license data.
        update_option( $key, $data );
    }
}
