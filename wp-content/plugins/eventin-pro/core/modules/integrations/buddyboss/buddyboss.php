<?php

namespace Etn_Pro\Core\Modules\Integrations\Buddyboss;

use \Etn_Pro\Utils\Helper;

defined( 'ABSPATH' ) || die();

/**
 * BuddyBoss functionality
 */
class Buddyboss {

	use \Etn\Traits\Singleton;

	/**
	 * Fire hooks.
	 */
	public function init() {
		$etn_buddy_boss_module  = $this->check_buddy_boss_enabled();

		if ( ! $this->check_buddy_boss_platform_enabled() || ! $etn_buddy_boss_module  ) {
		// if buddyboss platform isn't active return.
			return true;
		}

		if ( is_admin() ) {
			//  Load admin
			\Etn_Pro\Core\Modules\Integrations\Buddyboss\Admin\Admin::instance()->init();
		}
		// Load Frontend
		\Etn_Pro\Core\Modules\Integrations\Buddyboss\Frontend\Frontend::instance()->init();
		// load feeds
		\Etn_Pro\Core\Modules\Integrations\Buddyboss\Frontend\Feeds::instance()->init();

		// Include api file.
		include_once \Wpeventin_Pro::core_dir() . 'modules/integrations/buddyboss/api.php';
	}

	/**
	* Get Eventin settings from buddyboss
	*/
	public static function bp_etn_get_settings( $key, $default = '' ) {

		return bp_get_option( "bb_etn_$key", $default );
	}

	/**
	 * Check if buddy boss plugin active
	 */
	public function check_buddy_boss_platform_enabled(){
		return is_plugin_active('buddyboss-platform/bp-loader.php');
	}

	/**
	 * Check if buddy boss module is enabled for eventin
	 */
	public function check_buddy_boss_enabled(){
		return  \Etn\Core\Addons\Helper::instance()->check_active_module('buddyboss');
	}

	/**
	 * Check if buddy boss is enabled
	 */
	public function check_buddy_group_enabled(){
		$etn_buddy_boss_module  = $this->check_buddy_boss_enabled();

		if( $etn_buddy_boss_module ){
			return true;
		}

		return false;
	}

}
