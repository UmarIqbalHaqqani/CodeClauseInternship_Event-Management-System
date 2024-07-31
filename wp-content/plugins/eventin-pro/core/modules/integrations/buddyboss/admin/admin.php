<?php

namespace Etn_Pro\Core\Modules\Integrations\Buddyboss\Admin;
use Etn_Pro\Core\Modules\Integrations\Buddyboss\Buddyboss as BuddyBoss;

defined( 'ABSPATH' ) || die();

/**
 * BuddyBoss admin functionality
 */
class Admin{

	use \Etn\Traits\Singleton;

/**
 * Load functions
 */
	public function init(){
		// load bb admin
		$module_enable = BuddyBoss::instance()->check_buddy_boss_enabled();
		if($module_enable){
			new \Etn_Pro\Core\Modules\Integrations\Buddyboss\Admin\Bb_Integrations();
		}
	}

}