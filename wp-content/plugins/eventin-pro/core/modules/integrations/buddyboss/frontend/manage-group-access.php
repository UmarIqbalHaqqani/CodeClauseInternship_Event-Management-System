<?php

namespace Etn_Pro\Core\Modules\Integrations\Buddyboss\Frontend;
use \Etn_Pro\Core\Modules\Integrations\Buddyboss\Buddyboss as BuddyBOss;

defined( 'ABSPATH' ) || die();

class ManageGroupAccess{

	use \Etn\Traits\Singleton;

	/**
	* Group wise role access , who can create event in this group
	*/
	function bp_etn_is_user_can_event_change($group_id = null) {
		$user_id = get_current_user_id();
		// get manager who will create new event
		$manager = $this->bp_etn_group_get_manager($group_id);
		if ($this->bp_etn_groups_can_user_manage($user_id, $group_id, $manager) === true) {
			return true;
		}

		return false;
	}

	/**
	 * Get the etn manager of a group.
	 */
	public function bp_etn_group_get_manager($group_id = false) {
		global $groups_template;

		if (!$group_id) {
			$bp = buddypress();

			if (isset($bp->groups->current_group->id)) {
				// Default to the current group first.
				$group_id = $bp->groups->current_group->id;
			} elseif (isset($groups_template->group->id)) {
				// Then see if we're in the loop.
				$group_id = $groups_template->group->id;
			} else {
				return false;
			}
		}

		$manager = groups_get_groupmeta($group_id, 'bp-group-etn-role', true);

		// default manager set
		if (!$manager) {
			$manager = apply_filters('bp_etn_group_manager_fallback', 'admins');
		}

		return apply_filters('bp_etn_group_get_manager', $manager, $group_id);
	}

	/**
 * Check whether a user is allowed to manage group.
 */
	public function bp_etn_groups_can_user_manage($user_id, $group_id, $manager = null) {
		$is_allowed = false;

		if (!is_user_logged_in()) {
			return false;
		}

		// Site admins always have access.
		if (bp_current_user_can('bp_moderate')) {
			return true;
		}

		if (!groups_is_user_member($user_id, $group_id)) {
			return false;
		}

		if ($manager === null && strlen($manager) <= 0) {
			$manager = $this->bp_etn_group_get_manager($group_id);
		}

		$is_admin = groups_is_user_admin($user_id, $group_id);
		$is_mod = groups_is_user_mod($user_id, $group_id);

		if ('members' === $manager) {
			$is_allowed = true;
		} elseif ('mods' === $manager && ($is_mod || $is_admin)) {
			$is_allowed = true;
		} elseif ('admins' === $manager && $is_admin) {
			$is_allowed = true;
		}

		return apply_filters('bp_etn_groups_can_user_manage_event', $is_allowed);
	}

	public static function current_user_can_submit_event( $user_id = 0 ){

		if( !$user_id ){

			$user_id = get_current_user_id();
		}

		$user_data = get_userdata( $user_id );

		if( !is_a( $user_data, 'WP_User' ) ){
			return false;
		}

		$user_roles = is_array( $user_data->roles ) ? $user_data->roles : array();
		$roles = BuddyBOss::bp_etn_get_settings( 'event_submission_roles', false);

		if( false === $roles ){

			$roles = array( "administrator" );
		}

		$roles = is_array( $roles ) ? $roles : array();

		$intersect = array_intersect( $user_roles, $roles );
		if( count( $intersect ) > 0 ){
			return true;
		}
		return false;
	}



}