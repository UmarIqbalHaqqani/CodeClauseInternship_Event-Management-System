<?php
namespace Etn_Pro\Utils\Updater;

defined( 'ABSPATH' ) || exit;

class Init {

    use \Etn_Pro\Traits\Singleton;

    public function init() {

        $license_key = get_option( '_etn_license_user' );
        $license_key = isset( $license_key['license_key'] ) ? $license_key['license_key'] : '';

        $plugin_dir_and_filename = ETN_PRO_DIR . 'eventin-pro.php';

        $active_plugins = get_option( 'active_plugins' );

        foreach ( $active_plugins as $active_plugin ) {

            if ( false !== strpos( $active_plugin, 'eventin-pro.php' ) ) {
                $plugin_dir_and_filename = $active_plugin;
                break;
            }

        }

        if ( ! isset( $plugin_dir_and_filename ) || empty( $plugin_dir_and_filename ) ) {
            throw ( 'Plugin not found! Check the name of your plugin file in the if check above' );
        }

        new Edd_Warper(
            \Etn_Pro\Bootstrap::instance()->store_url(),
            $plugin_dir_and_filename,
            array(
                'version' => \Wpeventin_Pro::version(),
                'license' => $license_key,
                'item_id' => \Etn_Pro\Bootstrap::instance()->product_id(),
                'author'  => \Etn_Pro\Bootstrap::instance()->author_name(),
                'url'     => site_url(),
            )
        );
    }

}