<?php
/**
 * Manage admin hooks
 *
 * @package EventinPro/Admin
 */
namespace EventinPro\Admin;

use Eventin\Integrations\Google\GoogleMeet;
use EventinPro\Event\Api\EventController;

/**
 * Admin Hooks Class
 */
class Hooks {
    /**
     * Initialize
     *
     * @return  void
     */
    public function __construct() {
        add_filter( 'etn_admin_register_scripts', [$this, 'add_script_dependency'] );
        add_filter( 'etn_admin_register_styles', [$this, 'add_style_dependency'] );
        add_filter( 'eventin_online_meeting_platforms', [$this, 'add_google_meet'] );
        add_filter( 'eventin_settings', [$this, 'added_google_connection'] );
        add_filter( 'eventin_api_controllers', [ $this, 'add_api_controllers' ] );
    }

    /**
     * Add script dependency
     *
     * @return  array
     */
    public function add_script_dependency( $scripts ) {
        $version_4_script = ! empty( $scripts['etn-version-four'] ) ? $scripts['etn-version-four'] : [];

        if ( ! $version_4_script ) {
            return $scripts;
        }

        $pro_dependency              = ['etn-script-pro'];
        $version_4_script['deps']    = array_merge( $version_4_script['deps'], $pro_dependency );
        $scripts['etn-version-four'] = $version_4_script;

        return $scripts;
    }

    /**
     * Add style dependency
     *
     * @return  array
     */
    public function add_style_dependency( $styles ) {
        $version_4_style = ! empty( $styles['etn-version-four'] ) ? $styles['etn-version-four'] : [];

        if ( ! $version_4_style ) {
            return $styles;
        }

        $pro_dependency             = ['etn-style-pro'];
        $version_4_style['deps']    = array_merge( $version_4_style['deps'], $pro_dependency );
        $styles['etn-version-four'] = $version_4_style;

        return $styles;
    }

    /**
     * Added google meet platform for online event management
     *
     * @param   array  $platforms
     *
     * @return  array
     */
    public function add_google_meet( $platforms ) {
        $platforms['google_meet'] = GoogleMeet::class;

        return $platforms;
    }

    /**
     * Added google meet connection settings
     *
     * @param   array  $settings  Setting
     *
     * @return  array $settings
     */
    public function added_google_connection( $settings ) {
        $settings['google_meet_connected'] = GoogleMeet::is_connected();

        return $settings;
    }

    /**
     * Add api controllers
     *
     * @param   array  $controllers
     *
     * @return  array
     */
    public function add_api_controllers( $controllers ) {
        $new_controllers = [
            EventController::class,
        ];

        return array_merge( $controllers, $new_controllers );
    }
}
