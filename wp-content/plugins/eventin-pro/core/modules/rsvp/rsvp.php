<?php

namespace Etn_Pro\Core\Modules\Rsvp;

defined( 'ABSPATH' ) || die();
class Rsvp {

	use \Etn_Pro\Traits\Singleton;

	public function init() {
		if ( is_admin() ) {		
			\Etn_Pro\Core\Modules\Rsvp\Admin\Admin::instance()->init();
		} else {
			\Etn_Pro\Core\Modules\Rsvp\Frontend\Frontend::instance()->init();
		}
		// rsvp ajax actions
		\Etn_Pro\Core\Modules\Rsvp\Frontend\Actions\Actions::instance()->init();

		// rsvp invitation ajax actions
		\Etn_Pro\Core\Modules\Rsvp\Admin\Actions\Actions::instance()->init();

	}

}
