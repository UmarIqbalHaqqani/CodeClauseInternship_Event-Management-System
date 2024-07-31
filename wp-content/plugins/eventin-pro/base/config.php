<?php

namespace Etn_Pro\Base;

/**
 * This file is responsible for active and de-active modules
 */
class Config {

	use \Etn\Traits\Singleton;

	/**
	 * Fire main function to active modules
	 */
	public function init() {
		// active modules
		$this->active_modules();
		$this->back_version_compatibility();
	}

	/**
	 * Check version compatibility
	 */
	public function back_version_compatibility() {
		if ( ! class_exists( "\Etn\Core\Addons\Helper" ) ) {
			if ( class_exists( 'Woocommerce' ) && class_exists( 'WeDevs_Dokan' ) ) {
				//Initialize event multi-vendor module.
				\Etn_Pro\Core\Modules\Multivendor\Multivendor::instance()->init();
			}
		}
	}

	/**
	 * Active features & integrations
	 */
	public function active_modules() {
		if ( class_exists( "\Etn\Core\Addons\Helper" ) ) {
			$modules_arr = array( 'buddyboss', 'dokan', 'rsvp', 'google_meet', 'facebook_events' );

			foreach ( $modules_arr as $key => $value ) {
				$module_check = \Etn\Core\Addons\Helper::instance()->check_active_module( $value );

				if ( 'rsvp' == $value && $module_check ) {
					//Initialize event rsvp module.
					\Etn_Pro\Core\Modules\Rsvp\Rsvp::instance()->init();
				}
				if ( 'buddyboss' == $value && $module_check ) {
					// BuddyBoss integration.
					\Etn_Pro\Core\Modules\Integrations\Buddyboss\Buddyboss::instance()->init();
				}
				if ( 'dokan' == $value && $module_check && class_exists( 'WeDevs_Dokan' ) ) {
					//Initialize event multi-vendor module.
					\Etn_Pro\Core\Modules\Multivendor\Multivendor::instance()->init();
				}
				if ( 'google_meet' == $value && $module_check ) {
					// Google meet auth init  ()
					// TODO: Must activated this as addon
					\Etn_Pro\Core\Modules\Integrations\Google_Meet\Google_Meet::instance()->init();
				}

				if ( 'facebook_events' == $value && $module_check ) {
					// Facebook events
					// TODO: Must activated this as addon
					\EtnFBAddon\Core\Modules\Integrations\Facebook_Events\Facebook_Events::instance()->init();
				}

			}
		}
	}

}