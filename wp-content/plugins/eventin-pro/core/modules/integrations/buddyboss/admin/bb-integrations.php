<?php

namespace Etn_Pro\Core\Modules\Integrations\Buddyboss\Admin;

defined( 'ABSPATH' ) || die();

/**
 * BuddyBoss admin functionality
 */
class Bb_Integrations  extends \BP_Integration {
	/**
	 * BP_MEC_Integration constructor.
	 */
	public function __construct() {
		$this->start(
			'etn',
			__('Eventin', 'eventin-pro'),
			'etn',
			array(
				'required_plugin' => array(),
			)
		);
	}

	/**
	 * Load functions
	 */
	public function setup_admin_integration_tab(){
		// call admin
		require_once \Wpeventin_Pro::core_dir() .'modules/integrations/buddyboss/admin/admin.php';

		$base_url = '';
		if (function_exists('bb_platform_pro')) {
			$base_url = trailingslashit(bb_platform_pro()->integration_dir) . $this->id;
		}

		new \Etn_Pro\Core\Modules\Integrations\Buddyboss\Admin\Admin_Tab(
			"bp-{$this->id}",
			$this->name,
			array(
				'root_path' => $base_url,
				'root_url' => $base_url,
				'required_plugin' => $this->required_plugin,
			)
		);
	}
}