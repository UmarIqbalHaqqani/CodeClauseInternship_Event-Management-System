<?php
/**
 * Admin Assets Class
 * 
 * @package Eventin
 */
namespace Etn\Base\Enqueue;

/**
 * Admin Scripts and Styles class
 */
class AdminAssets implements AssetsInterface {

    /**
     * Register scripts
     *
     * @return  array
     */
    public static function get_scripts() {
        $scripts = [
            'jquery-ui'     => [
                'src'       => \Wpeventin::plugin_url( 'assets/js/etn-ui.min.js' ),
                'deps'      => ['jquery'],
                'in_footer' => false,
            ],
            'etn-packages' => [
                'src'       => \Wpeventin::plugin_url( 'build/js/packages.js' ),
                'in_footer' => false,
            ],
            'etn'     => [
                'src'       => \Wpeventin::plugin_url( 'assets/js/event-manager-admin.js' ),
                'deps'      => ['jquery'],
                'in_footer' => false,
            ],
            'select2'     => [
                'src'       => \Wpeventin::plugin_url( 'assets/lib/js/select2.min.js' ),
                'deps'      => ['jquery'],
                'in_footer' => false,
            ],
            'jquery-repeater'     => [
                'src'       => \Wpeventin::plugin_url( 'assets/lib/js/jquery.repeater.min.js' ),
                'deps'      => ['jquery'],
                'in_footer' => true,
            ],
            'flatpickr'     => [
                'src'       => \Wpeventin::plugin_url( 'assets/lib/js/flatpickr.js' ),
                'deps'      => ['jquery'],
                'in_footer' => true,
            ],
            'etn-app-index'     => [
                'src'       => \Wpeventin::plugin_url( 'build/js/index-calendar.js' ),
                'deps'      => [ 'jquery' ],
                'in_footer' => true,
            ],
            'etn-onboard-index' => [
                'src'       => \Wpeventin::plugin_url( 'build/js/index-onboard.js' ),
                'deps'      => [ 'jquery' ],
                'in_footer' => true,
            ],
            'etn-ai' => [
                'src'       => \Wpeventin::plugin_url( 'build/js/index-ai-script.js' ),
                'deps'      => [ 'jquery' ],
                'in_footer' => true,
            ],
            'etn-version-four' => [
                'src'       => \Wpeventin::plugin_url( 'build/js/etn-version-four.js' ),
                'deps'      => ['etn-packages', 'wp-format-library'],
                'in_footer' => true,
            ],
        ];

        return apply_filters( 'etn_admin_register_scripts', $scripts );
    }

    /**
     * Get styles
     *
     * @return  array
     */
    public static function get_styles() {
        $styles = [
            'select2'    => [
                'src' => \Wpeventin::plugin_url( 'assets/lib/css/select2.min.css'),
            ],
            'etn-icon'    => [
                'src' => \Wpeventin::plugin_url( 'assets/css/etn-icon.css' ),
            ],
            'etn-ui'    => [
                'src' => \Wpeventin::plugin_url( 'assets/css/etn-ui.css' ),
            ],
            'jquery-ui'    => [
                'src' => \Wpeventin::plugin_url( 'assets/lib/css/jquery-ui.css' ),
            ],
            'flatpickr-min'    => [
                'src' => \Wpeventin::plugin_url( 'assets/lib/css/flatpickr.min.css' ),
            ],
            'event-manager-admin'    => [
                'src' => \Wpeventin::plugin_url( 'build/css/event-manager-admin.css' ),
            ],
            'etn-onboard-index'    => [
                'src' => \Wpeventin::plugin_url( 'build/css/index-onboard.css' ),
            ],
            'etn-ai'    => [
                'src' => \Wpeventin::plugin_url( 'build/css/index-ai-style.css' ),
            ],
            'etn-version-four'    => [
                'src' => \Wpeventin::plugin_url( 'build/css/etn-version-four.css' ),
                'deps' => ['wp-edit-blocks']
            ],
        ];

        return apply_filters( 'etn_admin_register_styles', $styles );
    }
}