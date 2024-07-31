<?php

namespace Etn_Pro\Core\Modules\Integrations\Buddyboss\Frontend;
use \Etn_Pro\Core\Modules\Integrations\Buddyboss\Buddyboss as BuddyBoss;

defined( 'ABSPATH' ) || die();

/**
 * BuddyBoss functionality
 */
class Frontend {

	use \Etn\Traits\Singleton;

	/**
	 * Fire hooks.
	 */
	public function init() {
		$etn_buddy_boss_module = \Etn_Pro\Core\Modules\Integrations\Buddyboss\Buddyboss::instance()->check_buddy_boss_enabled();
		$etn_buddy_boss_group  = \Etn_Pro\Core\Modules\Integrations\Buddyboss\Buddyboss::instance()->check_buddy_group_enabled();

		if(  $etn_buddy_boss_module && is_user_logged_in() ){
			add_action('bp_setup_nav', array($this, 'add_tab_nav_item'));
		}

		if( $etn_buddy_boss_group ){
			add_action('bp_setup_nav', array($this, 'add_tab_nav_in_group'), 100 );
			add_filter('bp_nouveau_customizer_group_nav_items', array( $this, 'add_customizer_group_nav_items' ),10,2);
		}


		// Load etn admin page.
		add_action('bp_screens', array($this, 'etn_admin_page'));
		// add user menu
		add_action( 'init', array( __CLASS__ , 'add_user_menu' ), 99 );

		add_action( 'wp_enqueue_scripts', array( $this , 'js_css_public' ) );

		add_action('wp_ajax_nopriv_etn_bp_event', array($this, 'bp_event_content'));
		add_action('wp_ajax_etn_bp_event', array($this, 'bp_event_content'));

		add_action('wp_ajax_etn_bp_event_list', array($this, 'event_list'));

	}

	/**
	* Check if is current user profile
	*/
	public function get_current_user_profile(){
		return  get_current_user_id() === bp_displayed_user_id() ? true : false;
	}
	/**
	* Enqueue js
	*/
	public function js_css_public() {
		$assing_group_url = get_site_url().'/wp-json/eventin/v1/buddyboss/assign_group';
		wp_enqueue_media();
		wp_enqueue_style('etn-frontend-submission');
		wp_enqueue_script('etn-frontend-submission');
		wp_enqueue_script( 'etn-buddyboss-public', ETN_PRO_CORE . 'modules/integrations/buddyboss/assets/js/etn-buddyboss.js', ['jquery'], \Wpeventin_Pro::version(), false );
		wp_localize_script('etn-buddyboss-public', 'etn_buddyboss_ajax', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'is_current_user_profile' => $this->get_current_user_profile(),
			'assing_group_url'	=> $assing_group_url
		));
	}

	/**
	* Check if theme active
	*/
	public static function add_user_menu(){
		if( defined('THEME_HOOK_PREFIX') ){
			add_action( THEME_HOOK_PREFIX . 'after_bb_groups_menu', array( __CLASS__ , 'add_menu_in_bb_theme') );
		}
	}

	/**
	* Add menu item in dropdown
	*/
	public static function add_menu_in_bb_theme() {
		if( !is_user_logged_in() ){
			return;
		}
		$user_domain = bp_loggedin_user_domain();
		$events_link = trailingslashit( $user_domain . 'etn-main' );
		?>
		<li id="wp-admin-bar-my-account-forums" class="menupop parent etn-bb-menu-item">
			<a class="ab-item" aria-haspopup="true" href="<?php echo esc_url( $events_link ); ?>">
			
				<svg width="16" height="20" viewBox="0 0 52 82" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M51.1216 39.6216L35.7666 55.0058L25.8676 64.9256L21.0175 60.0653L16.0542 55.0916C14.8149 53.8497 13.8571 52.4269 13.187 50.906C10.9241 45.802 11.8804 39.6002 16.0542 35.4222C18.6735 32.7943 22.1604 31.3469 25.866 31.3469C27.8458 31.3469 29.7583 31.7578 31.5117 32.5428L25.8125 38.254C21.1276 42.9487 21.1276 50.5595 25.8125 55.2541L39.2948 41.7436L48.4441 32.575C47.315 30.5481 45.8875 28.6408 44.1678 26.9175C42.9714 25.7186 41.6785 24.653 40.3076 23.7285C38.539 22.531 36.6387 21.5697 34.6451 20.8507C32.2614 22.8362 29.2014 24.032 25.8676 24.032C22.5322 24.032 19.4707 22.8346 17.0869 20.8491C13.5496 22.1247 10.306 24.1762 7.56732 26.9191C-1.31888 35.8239 -2.37916 49.6442 4.388 59.7219C5.30446 61.088 6.36475 62.3866 7.56732 63.5932L12.5306 68.5669L25.866 81.9334L44.1678 63.5932C50.6473 57.1001 52.9683 47.9883 51.1216 39.6216Z" fill="url(#paint0_linear_1052_2417)"/>
					<path d="M25.8675 20.17C31.3338 20.17 35.765 15.7295 35.765 10.2518C35.765 4.77406 31.3338 0.333496 25.8675 0.333496C20.4013 0.333496 15.97 4.77406 15.97 10.2518C15.97 15.7295 20.4013 20.17 25.8675 20.17Z" fill="url(#paint1_linear_1052_2417)"/>
					<defs>
					<linearGradient id="paint0_linear_1052_2417" x1="7.13198" y1="76.8784" x2="43.004" y2="16.5888" gradientUnits="userSpaceOnUse">
					<stop offset="0.1788" stop-color="#702CE7"/>
					<stop offset="0.8196" stop-color="#FF4A97"/>
					</linearGradient>
					<linearGradient id="paint1_linear_1052_2417" x1="15.264" y1="23.7835" x2="36.6345" y2="-3.37408" gradientUnits="userSpaceOnUse">
					<stop offset="0.326" stop-color="#702CE7"/>
					<stop offset="0.8707" stop-color="#FF4A97"/>
					</linearGradient>
					</defs>
				</svg>

				<span><?php esc_html_e( 'Eventin Events', 'eventin-pro' ); ?></span>
			</a>
		</li>
		<?php
	}

	/**
	* Create tab for Admin User
	*/
	public function add_tab_nav_item(){
		$current_user_profile = $this->get_current_user_profile();
		if ( !$current_user_profile ) {
			return true;
		}

		$tab_label = BuddyBoss::bp_etn_get_settings( 'event_tab_label', '' );
		$tab_label = !empty( $tab_label ) ? $tab_label : esc_html__('Events', 'eventin-pro');

		bp_core_new_nav_item(array(
			'name' => $tab_label,
			'slug' => 'etn-main',
			'position' => 99,
			'screen_function' => array($this, 'etn_main_template'),
			'default_subnav_slug' => 'etn-main-created',
		));

		bp_core_new_subnav_item(
			array(
				'name' => esc_html__('Event List', 'eventin-pro'),
				'slug' => 'etn-main-created',
				'show_for_displayed_user' => false,
				'parent_url' => bp_loggedin_user_domain() . '/etn-main/',
				'parent_slug' => 'etn-main',
				'position' => 10,
				'screen_function' => array($this, 'etn_created_content'),
			)
		);

	}

	/**
	* Base Tab content
	*/
	public function etn_main_template(){
		add_action('bp_template_content', array($this, 'etn_main_content'));
		bp_core_load_template(apply_filters('bp_core_template_plugin', 'members/single/plugins'));
	}
	/**
	* Sub Tab content
	*/
	public function etn_created_content() {
		add_action('bp_template_content', array($this, 'etn_created_base_content'));
		bp_core_load_template(apply_filters('bp_core_template_plugin', 'members/single/plugins'));
	}

	/**
	* Create content area
	*/
	public function etn_created_base_content($area = 'profile', $group_id = null, $template = 'create-new-events', $create_mode = false) {

		global $wp;
		if ($create_mode == false && strpos($wp->request, 'create-event') !== false) {
			$create_mode = true;
		}

		$data = new \stdClass;
		$data->created = true;
		$data->booked = false;
		$data->events = [];
		$data->create_mode = $create_mode;

		// load the template
		if( file_exists(\Wpeventin_Pro::core_dir() . 'modules/integrations/buddyboss/frontend/views/'.$template.'.php')){
			include \Wpeventin_Pro::core_dir() . 'modules/integrations/buddyboss/frontend/views/'.$template.'.php';
		}
	}
	/**
	* Base content wrapper call back
	*/
	public function etn_main_content(){
		return;
	}
	/**
	* Get all meta data
	*/
	public function get_all_events_data(){

	}

	/**
	 * Create manage tab item
	 */
	public function add_tab_nav_in_group(){
		// return if no group.
		if (!bp_is_group()) {
			return;
		}

		$current_group = groups_get_current_group();
		$group_link    = bp_get_group_permalink($current_group);
		$sub_nav       = array();
		
		// if current group has etn enable then return.
		$tab_label = BuddyBoss::bp_etn_get_settings( 'event_tab_label', '' );
		$tab_label = !empty( $tab_label ) ? $tab_label : esc_html__('Events', 'eventin-pro');

		// save edit screen options.
		if (bp_is_groups_component() && bp_is_current_action('admin') && bp_is_action_variable('etn', 0)) {
			$this->edit_screen_save($current_group->id);

			// Load etn admin page.
			add_action('bp_screens', array($this, 'etn_admin_page'));
		}

		// etn-groups-li
		if ($this->etn_is_group_setup($current_group->id)) {
			$sub_nav[] = array(
				'name'            => $tab_label,
				'slug'            => 'etn-group',
				'parent_url'      => $group_link,
				'parent_slug'     => $current_group->slug,
				'screen_function' => array($this, 'etn_group_main_page'),
				'item_css_id'     => 'etn',
				'position'        => 100,
				'user_has_access' => $current_group->user_has_access,
				'no_access_url'   => $group_link,
			);

			$sub_nav[] = array(
				'name'            => esc_html__('Create Event', 'eventin-pro'),
				'slug'            => 'create-event',
				'parent_url'      => $group_link,
				'parent_slug'     => 'etn-group',
				'screen_function' => array($this, 'etn_add_content'),
				'user_has_access' => $current_group->user_has_access,
				'no_access_url'   => $group_link,

			);

		}

		// If the user is a group admin, then show the group admin nav item under manage tab
		if (bp_is_item_admin()) {
			$admin_link = trailingslashit($group_link . 'admin');
			$sub_nav[] = array(
				'name'              => $tab_label,
				'slug'              => 'etn',
				'position'          => 100,
				'parent_url'        => $admin_link,
				'parent_slug'       => $current_group->slug . '_manage',
				'screen_function'   => 'groups_screen_group_admin',
				'user_has_access'   => bp_is_item_admin(),
				'show_in_admin_bar' => true,
			);
		}

		foreach ($sub_nav as $nav) {
			bp_core_new_subnav_item($nav, 'groups');
		}

	}

	public function etn_admin_page(){
		if (!bp_is_groups_component()) {
			return false;
		}

		if ('etn' !== bp_get_group_current_admin_tab()) {
			return false;
		}

		if (!bp_is_item_admin() && !bp_current_user_can('bp_moderate')) {
			return false;
		}
		add_action('groups_custom_edit_steps', array($this, 'edit_screen'));
		bp_core_load_template(apply_filters('bp_core_template_plugin', 'groups/single/home'));
	}

	public function edit_screen($group = false){
		$group_id = empty($group->id) ? bp_get_new_group_id() : $group->id;

		if (empty($group->id)) {
			$group_id = bp_get_new_group_id();
		}

		if (empty($group_id)) {
			$group_id = bp_get_group_id();
		}

		if (empty($group_id)) {
			$group_id = $group->id;
		}

		$enable_tab = groups_get_groupmeta($group_id, 'bp-group-etn-tab-enable', true);
		$checked = '';

		if($enable_tab == '1'){
			$checked = ' checked=checked';
		}

		?>
		<div class="bb-group-etn-settings-container">
			
			<div id="bp-group-etn-settings-additional" class="group-settings-selections">
				<fieldset class="radio group-media">
					<legend><?php echo esc_html__('Enable Eventin Events in Group', 'eventin-pro');?></legend>
					<p class="group-setting-label" tabindex="0"><?php echo esc_html__('If you want to enable event tab in this group', 'eventin-pro');?></p>

					<div class="field-group">
						<p class="checkbox bp-checkbox-wrap bp-group-option-enable">
						<input type="checkbox" name="bp-group-etn-tab-enable" id="bp-group-etn-tab-enable" class="bs-styled-checkbox" value="1"<?php echo esc_attr($checked);?> />
						<label for="bp-group-etn-tab-enable"><?php echo esc_html__('Yes, I want this group to have an event tab.', 'eventin-pro');?></label></p>
					</div>
				</fieldset>
			</div>

			<div id="bp-group-etn-settings-additional" class="group-settings-selections">

				<fieldset class="radio group-media">
					<legend><?php echo esc_html__('Group Permissions', 'eventin-pro');?></legend>
					<p class="group-setting-label" tabindex="0"><?php echo esc_html__('Which members of this group are allowed to create, edit and delete Eventin Events?', 'eventin-pro');?></p>

					<div class="bp-radio-wrap">
						<input type="radio" name="bp-group-etn-role" id="group-etn-role-members" class="bs-styled-radio" value="members"<?php $this->etn_group_show_manager_setting('members', $group);?> />
						<label for="group-etn-role-members"><?php echo esc_html__('All group members', 'eventin-pro');?></label>
					</div>

					<div class="bp-radio-wrap">
						<input type="radio" name="bp-group-etn-role" id="group-etn-role-mods" class="bs-styled-radio" value="mods"<?php $this->etn_group_show_manager_setting('mods', $group);?> />
						<label for="group-etn-role-mods"><?php echo esc_html__('Organizers and Moderators', 'eventin-pro');?></label>
					</div>

					<div class="bp-radio-wrap">
						<input type="radio" name="bp-group-etn-role" id="group-etn-role-admins" class="bs-styled-radio" value="admins"<?php $this->etn_group_show_manager_setting('admins', $group);?> />
						<label for="group-etn-role-admins"><?php echo esc_html__('Organizers', 'eventin-pro');?></label>
					</div>
				</fieldset>
			</div>

			<div class="bp-etn-group-button-wrap">
				<button type="submit" class="bb-save-settings">
					<?php echo esc_html__('Save Changes', 'eventin-pro');?>
				</button>
			</div>
			<?php wp_nonce_field('groups_edit_save_etn');?>
		</div>
		<?php
	}

	/**
	* Group customizer
	*/
	public function add_customizer_group_nav_items($nav_items, $group){
		$nav_items[ 'etn' ] = array(
			'name' => esc_html__( 'Eventin Events', 'eventin-pro'),
			'slug' => 'etn-group',
			'position' => 10,
			'parent_slug' => $nav_items['root']['slug'],
		);

		return $nav_items;
	}

	/**
	* Creating Admin group base content
	*/
	public function etn_group_main_page() {

		add_action('bp_template_content', array($this, 'etn_group_page_content'));
		bp_core_load_template(apply_filters('bp_core_template_plugin', 'groups/single/home'));
	}

	/**
	* Creating Admin group base content Call back
	*/
	public function etn_group_page_content() {

		$current_group = groups_get_current_group();

		$this->etn_created_base_content('group', $current_group->id);
	}

	public function edit_screen_save($group_id) {

		// Bail if not a POST action.
		if (!bp_is_post_request()) {
			return;
		}

		$nonce = filter_input(INPUT_POST, '_wpnonce', FILTER_SANITIZE_SPECIAL_CHARS);

		// Admin Nonce check.
		if (is_admin()) {
			check_admin_referer('groups_edit_save_etn', 'etn_group_admin_ui');

			// Theme-side Nonce check.
		} elseif (empty($nonce) || (!empty($nonce) && !wp_verify_nonce($nonce, 'groups_edit_save_etn'))) {
			return;
		}

		$manager = filter_input(INPUT_POST, 'bp-group-etn-role', FILTER_SANITIZE_SPECIAL_CHARS);
		$manager = !empty($manager) ? $manager : $this->etn_group_get_manager($group_id);
		groups_update_groupmeta($group_id, 'bp-group-etn-role', $manager);
		$tab_enable = filter_input(INPUT_POST, 'bp-group-etn-tab-enable', FILTER_SANITIZE_SPECIAL_CHARS);
		groups_update_groupmeta($group_id, 'bp-group-etn-tab-enable', $tab_enable);

		/**
		 * Add action that fire before user redirect
		 *
		 * @Since 1.0.0
		 *
		 * @param int $group_id Current group id
		 */
		do_action('bp_group_admin_after_edit_screen_save', $group_id);

	}

	public function etn_group_show_manager_setting($setting, $group = false) {
		$group_id = isset($group->id) ? $group->id : $group;
	
		$status = $this->etn_group_get_manager($group_id);
	
		if ($setting === $status) {
			echo ' checked="checked"';
		}
	}

	public function etn_group_get_manager($group_id = false) {
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
		// Backward compatibility. When '$manager' is not set, fall back to a default value.
		if (!$manager) {
			$manager = apply_filters('etn_group_manager_fallback', 'admins');
		}

		/**
		 * Filters the album status of a group.
		 *
		 * @since 1.0.0
		 *
		 * @param string $manager Membership level needed to manage albums.
		 * @param int    $group_id      ID of the group whose manager is being checked.
		 */
		return apply_filters('etn_group_get_manager', $manager, $group_id);
	}

	public function etn_is_group_setup($group_id) {

		$enable_tab = groups_get_groupmeta($group_id, 'bp-group-etn-tab-enable', true);

		if (!bp_is_active('groups')) {
			return false;
		}

		if (!$enable_tab) {
			return false;
		}

		$user_id = bp_loggedin_user_id();
		if (!$user_id) {
			return false;
		}

		if (groups_is_user_mod($user_id, $group_id)) {
			return true;
		}

		if(groups_is_user_creator($user_id,$group_id)){
			return true;
		}

		return true;
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
		$roles = self::bp_etn_get_settings( 'event_submission_roles', false);
		if( false === $roles ){

			return true;
		}

		$roles = is_array( $roles ) ? $roles : array();

		$intersect = array_intersect( $user_roles, $roles );
		if( count( $intersect ) > 0 ){
			return true;
		}

		return false;
	}

	/**
	* Ajax action to get single event details
	*/
	public function bp_event_content() {
		$post_arr = filter_input_array( INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS );
		$event_id = isset($post_arr['event_id']) ? $post_arr['event_id'] : null;

		if (!$event_id) {
			return wp_send_json_error(array('status' => false, 'error' => 'bad request'));
		}

		ob_start();
		$event_details = get_post( $event_id );
		include \Wpeventin_Pro::core_dir() . 'modules/integrations/buddyboss/frontend/views/single-event-details.php';
		$data = ob_get_clean();

		return wp_send_json_success($data);
	}

	public function single_event_content( $event_id, $book_id ){

		$event = get_post( $event_id );

		$event->id2 = $book_id ? $book_id : $event_id;

		return $event;

	}

	/**
	* Fetch event list on ajax search
	*/
	public function event_list() {
		$get_arr = filter_input_array( INPUT_GET, FILTER_SANITIZE_SPECIAL_CHARS );
		$category = isset($get_arr['category']) ? $get_arr['category'] : null;
		$query = isset($get_arr['query']) ? $get_arr['query'] : null;
		$group_id = null;

		if (bp_is_group()) {
			$group_id = bp_get_current_group_id();
		}

		$args = array(
			'post_type' => 'etn',
			'numberposts' => -1,
			'post_status' => 'publish',

		);

		if ($category !== null && strlen($category) > 0) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'etn_category',
					'field' => 'term_id',
					'terms' => array($category),
				));
		}

		if ($query !== null && strlen($query) > 0) {
			$args['s'] = "{$query}";
		}

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
		} else {
			$is_current_user_profile = isset( $get_arr['is_current_user_profile'] )  ? true : false;
			$args['author'] = $is_current_user_profile ? bp_loggedin_user_id() : bp_displayed_user_id();
		}

		$all = get_posts($args);

		ob_start();
		foreach ($all as $k => $event) {
			include \Wpeventin_Pro::core_dir() . 'modules/integrations/buddyboss/frontend/views/loop-event-list.php';
		}
		$ret = ob_get_clean();

		return wp_send_json_success($ret);
	}

}
