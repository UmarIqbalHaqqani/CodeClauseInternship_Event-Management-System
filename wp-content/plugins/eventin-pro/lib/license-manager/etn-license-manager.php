<?php
/**
 * Etn License Manager
 *
 * @package Etn License
 */
namespace Etn\License\Missile;

/**
 * Class Etn License Manager
 */
class Etn_License_Manager {
    /**
     * Initialize
     *
     * @return void
     */
    public function run( $store_url, $product_id ) {
        $this->includes();
        $this->define_constants( $store_url, $product_id );
        // $this->init_hooks();

    }

    /**
     * Get instance
     *
     * @return  Object
     */
    public static function instance() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new Etn_License_Manager();
        }

        return $instance;
    }

    /**
     * Init hooks
     *
     * @return  void
     */
    public function init_hooks() {
        // add_action( 'admin_init', [$this, 'updater'] );
        
    }

    /**
     * Includes require file
     *
     * @return  void
     */
    public function includes() {
        require_once dirname( __FILE__ ) . '/includes/admin.php';
        require_once dirname( __FILE__ ) . '/includes/license-activator.php';
        require_once dirname( __FILE__ ) . '/includes/license-functions.php';
        require_once dirname( __FILE__ ) . '/updater/edd-theme-updater.php';
    }

    /**
     * Define constants
     *
     * @param   string  $store_url   EDD store url
     * @param   integer $product_id  EDD Product ID
     *
     * @return  void
     */
    public function define_constants( $store_url, $product_id ) {
        define( 'ETN_LICENSE_STROE', $store_url );
        define( 'ETN_LICENSE_PRODUCT', $product_id );
    }

    public function updater() {

        if ( ! etn_is_valid_license() ) {
            return;
        }

        $theme = wp_get_theme();

        // Config.
        $config = array(
            'item_name'      => $theme->get('Name'),
            'author'         => $theme->get('Author'),
            'version'        => $theme->get('Version'),
            'license'        => trim( etn_get_license_key() ),
            'remote_api_url' => ETN_LICENSE_STROE,
            'item_id'        => ETN_LICENSE_PRODUCT,
        );
        
        // Strings.
        $strings = array(
            'theme-license'             => __( 'Theme License', 'eventin-pro' ),
            'enter-key'                 => __( 'Enter your theme license key.', 'eventin-pro' ),
            'license-key'               => __( 'License Key', 'eventin-pro' ),
            'license-action'            => __( 'License Action', 'eventin-pro' ),
            'deactivate-license'        => __( 'Deactivate License', 'eventin-pro' ),
            'activate-license'          => __( 'Activate License', 'eventin-pro' ),
            'status-unknown'            => __( 'License status is unknown.', 'eventin-pro' ),
            'renew'                     => __( 'Renew?', 'eventin-pro' ),
            'unlimited'                 => __( 'unlimited', 'eventin-pro' ),
            'license-key-is-active'     => __( 'License key is active.', 'eventin-pro' ),
            'expires%s'                 => __( 'Expires %s.', 'eventin-pro' ),
            '%1$s/%2$-sites'            => __( 'You have %1$s / %2$s sites activated.', 'eventin-pro' ),
            'license-key-expired-%s'    => __( 'License key expired %s.', 'eventin-pro' ),
            'license-key-expired'       => __( 'License key has expired.', 'eventin-pro' ),
            'license-keys-do-not-match' => __( 'License keys do not match.', 'eventin-pro' ),
            'license-is-inactive'       => __( 'License is inactive.', 'eventin-pro' ),
            'license-key-is-disabled'   => __( 'License key is disabled.', 'eventin-pro' ),
            'site-is-inactive'          => __( 'Site is inactive.', 'eventin-pro' ),
            'license-status-unknown'    => __( 'License status is unknown.', 'eventin-pro' ),
            'update-notice'             => __( "Updating this theme will lose any customizations you have made. 'Cancel' to stop, 'OK' to update.", 'eventin-pro' ),
            'update-available'          => __( '<strong>%1$s %2$s</strong> is available. <a href="%3$s" class="thickbox" title="%4s">Check out what\'s new</a> or <a href="%5$s"%6$s>update now</a>.', 'eventin-pro' ),
        );

        new EDD_Theme_Updater( $config, $strings );
    }
}