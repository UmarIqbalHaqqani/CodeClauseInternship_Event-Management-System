<?php

namespace Etn_Pro\Core\Modules\Integrations\Buddyboss\Frontend;

defined( 'ABSPATH' ) || die();

/**
 * BuddyBoss functionality
 */
class Feeds {

	use \Etn\Traits\Singleton;

	public function init(){
		add_action('save_post_etn', array($this, 'on_add_events'), 10, 3);
		add_action('etn_assign_event_to_group', array($this, 'on_assign_event_to_group'), 10, 3);
		add_action('bp_register_activity_actions', array($this, 'register_activity_actions'));
	}

	public function on_add_events($post_ID, $post, $update){

		$etn_buddy_boss_group  = \Etn_Pro\Core\Modules\Integrations\Buddyboss\Buddyboss::instance()->check_buddy_group_enabled();

		if ( $etn_buddy_boss_group == false) {
			return;
		}

		if(empty($post_ID)){
			return;
		}

		$group_id = $post->menu_order;

		\Etn_Pro\Core\Modules\Integrations\Buddyboss\Helper::instance()->update_event_group_id( $post->ID, $group_id );

		// wp_publish_post( $post_ID );

		// Check activity component active or not.
		if (!bp_is_active('activity')) {
			return;
		}

		$event_activity = get_post_meta($post_ID, 'etn_notification_activity_id', true);
		if ($event_activity != null) {
			return;
		}

		$user_id = get_current_user_id();
		$group = null;
		// Get meeting group.
		if ( function_exists('groups_get_group') ) {
			$group = groups_get_group($group_id);
		}

		// Check group exists.
		if (empty($group->id)) {
			return;
		}

		$type = 'etn_event_create';

		/* translators: %1$s - user link, %2$s - group link. */
		$action = sprintf(__('%1$s scheduled a Eventin Event in the group %2$s', 'eventin-pro'), bp_core_get_userlink($user_id), '<a href="' . bp_get_group_permalink($group) . '">' . esc_html($group->name) . '</a>');

		$activity_id = groups_record_activity(
			array(
				'user_id' => $user_id,
				'action' => $action,
				'content' => '',
				'type' => $type,
				'item_id' => $group_id,
				'secondary_item_id' => $post_ID,
			)
		);

		if ($activity_id) {

			// save activity id in meeting.
			if (!empty($event_activity)) {

				update_post_meta($post_ID, 'etn_notification_activity_id', $activity_id);

				// setup activity meta for notification activity.
				bp_activity_update_meta($activity_id, 'etn_notification_activity_id', true);
			} else {
				update_post_meta($post_ID, 'etn_notification_activity_id', $activity_id);
			}

			// update activity meta.
			bp_activity_update_meta($activity_id, 'bp_event_id', $post_ID);

			groups_update_groupmeta($group_id, 'last_activity', bp_core_current_time());
		}
		// save group id
		update_post_meta($post_ID, '_etn_buddy_group_id', $group_id);
		$args = array(
			'ID'              => $post_ID,
			'menu_order'      => 0
		);
		wp_update_post( $args );

	}

	public function on_assign_event_to_group($event_id, $group_id, $assign_action) {

		$is_admin = is_admin();
		if ( !$is_admin) {
			return;
		}
		$etn_buddy_boss_group  = \Etn_Pro\Core\Modules\Integrations\Buddyboss\Buddyboss::instance()->check_buddy_group_enabled();

		if ( $etn_buddy_boss_group == false) {
			return;
		}

		if(empty($event_id)){
			return;
		}

		$user_id = get_current_user_id();

		// Get meeting group.
		$group = null;
		if ( function_exists('groups_get_group') ) {
			$group = groups_get_group($group_id);
		}

		// Check group exists.
		if (empty($group->id)) {
			return;
		}

		if ($group_id == null || $group_id <= 0) {
			return;
		}

		$post = get_post($event_id);
		if (!$post) {
			return;
		}

		// // Event.
		$event_permalink = get_permalink($event_id);
		$event_title = $post->post_title;
		$event_link = '<a href="' . esc_url($event_permalink) . '">' . esc_html($event_title) . '</a>';

		switch( $assign_action ){

			case 'add':
				$type = 'etn_added_event_to_group';
				/* %1$s - user link, %2$s - group link. */
				$action = sprintf(
					__('%1$s added event "%2$s" to the group %3$s', 'eventin-pro'),
					bp_core_get_userlink($user_id),
					$event_link,
					'<a href="' . bp_get_group_permalink($group) . '">' . esc_html($group->name) . '</a>'
				);
				break;
			case 'delete':

				$type = 'etn_removed_event_to_group';
				$action = sprintf(
					__('%1$s removed event "%2$s" from the group %3$s', 'eventin-pro'),
					bp_core_get_userlink($user_id),
					$event_link,
					'<a href="' . bp_get_group_permalink($group) . '">' . esc_html($group->name) . '</a>'
				);

				break;

			default:
				break;

		}

		$activity_id = groups_record_activity(
			array(
				'user_id' => $user_id,
				'action' => $action,
				'content' => '',
				'type' => $type,
				'item_id' => $group_id,
				'secondary_item_id' => $event_id,
			)
		);

		if ($activity_id) {

			// save activity id in meeting.
			if (!empty($event_activity)) {

				update_post_meta($event_id, 'etn_notification_activity_id', $activity_id);

				// setup activity meta for notification activity.
				bp_activity_update_meta($activity_id, 'etn_notification_activity_id', true);
			} else {

				update_post_meta($event_id, 'etn_notification_activity_id', $activity_id);
			}

			// update activity meta.
			bp_activity_update_meta($activity_id, 'bp_event_id', $event_id);

			groups_update_groupmeta($group_id, 'last_activity', bp_core_current_time());
		}

	}

	/**
	 * Register our activity actions with BuddyBoss
	 */
	public function register_activity_actions() {

		$bp = buddypress();
		if(!$bp){
			return;
		}

		if(!isset($bp->groups)){
			return;
		}
		if(!isset($bp->groups->id)){
			return;
		}

		// Group activity stream items.
		bp_activity_set_action(
			buddypress()->groups->id,
			'etn_event_create',
			esc_html__('New Eventin Event', 'eventin-pro'),
			array(
				$this,
				'event_activity_action_callback',
			)
		);

		bp_activity_set_action(
			buddypress()->groups->id,
			'etn_added_event_to_group',
			esc_html__('Added event to group', 'eventin-pro'),
			array(
				$this,
				'assign_event_to_group_activity_action_callback',
			)
		);

		bp_activity_set_action(
			buddypress()->groups->id,
			'etn_removed_event_to_group',
			esc_html__('Removed event to group', 'eventin-pro'),
			array(
				$this,
				'assign_event_to_group_activity_action_callback',
			)
		);

	}

	/**
	 * Eventin Event activity action.
	 */
	public function assign_event_to_group_activity_action_callback($action, $activity) {
		$etn_group_is_setup = \Etn_Pro\Core\Modules\Integrations\Buddyboss\Frontend\Frontend::instance()->etn_is_group_setup($activity->item_id);

		if (buddypress()->groups->id === $activity->component && !$etn_group_is_setup ) {
			return $action;
		}

		return $action;
	}

	/**
	 * Event activity action.
	 */
	public function event_activity_action_callback($action, $activity) {
		$etn_group_is_setup = \Etn_Pro\Core\Modules\Integrations\Buddyboss\Frontend\Frontend::instance()->etn_is_group_setup($activity->item_id);

		if (buddypress()->groups->id === $activity->component && !$etn_group_is_setup ) {
			return $action;
		}

		$user_id = $activity->user_id;
		$group_id = $activity->item_id;
		$event_id = $activity->secondary_item_id;

		// User link.
		$user_link = bp_core_get_userlink($user_id);
		$post = get_post($event_id);
		if (!$post) {
			return $action;
		}

		// Event.
		$event_permalink = get_permalink($event_id);
		$event_title = $post->post_title;
		$event_link = '<a href="' . esc_url($event_permalink) . '">' . esc_html($event_title) . '</a>';

		$group = groups_get_group($group_id);
		$group_link = bp_get_group_link($group);

		$activity_action = sprintf(
			esc_html__('%1$s scheduled a Eventin Event %2$s in the group %3$s', 'eventin-pro'),
			$user_link,
			$event_link,
			$group_link
		);

		return $activity_action;
	}

}