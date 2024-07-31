<?php

namespace Etn_Pro\Core\Modules\Integrations\Buddyboss;
use \Etn_Pro\Core\Modules\Integrations\Buddyboss\Buddyboss as BuddyBoss;
defined( 'ABSPATH' ) || die();

class Helper {

	use \Etn\Traits\Singleton;

    /**
     * Update event group
     *
     * @param int $event_id
     * @param int[] $groups
     * @param bool $run_hook
     *
     * @return void
     */
    public static function update_event_group_id( $event_id, $group_id, $run_hook = true  ){
		$saved_group_id = get_post_meta($event_id, "etn_bp_group_{$group_id}", true);
		update_post_meta($event_id, "etn_bp_group_{$group_id}", $group_id);

		if( $run_hook ){
			if( $saved_group_id ){
				do_action('etn_assign_event_to_group', $event_id, $group_id, 'update' );
			} else{
				do_action('etn_assign_event_to_group', $event_id, $group_id, 'add' );
			}
		}
	}

	/**
	* Get Group wise event list
	*/
	public function group_event_list( $limit = "-1" ){
		$group_id = null;

		if( BuddyBoss::instance()->check_buddy_boss_enabled() &&  bp_is_group() ) {
			$group_id = bp_get_current_group_id();
		}

		$args = array(
			'post_type' => 'etn',
			'numberposts' => $limit,
			'post_status' => 'publish',
		);

		if ($group_id !== null && $group_id > 0) {
			$args['meta_query'] = array(
				'filter_bp_group' => array(
					array(
						'key' => "etn_bp_group_{$group_id}",
						'value' => $group_id,
						'compare' => '=',
					),
				)
			);
		}
		else {
			$args['author'] =  bp_displayed_user_id();
		}
		
		
		$all = get_posts($args);

		return $all;
	}

}