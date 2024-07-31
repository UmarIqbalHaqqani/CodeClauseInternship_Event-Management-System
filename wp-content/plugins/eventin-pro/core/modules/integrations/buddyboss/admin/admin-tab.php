<?php

namespace Etn_Pro\Core\Modules\Integrations\Buddyboss\Admin;
use \Etn_Pro\Core\Modules\Integrations\Buddyboss\Buddyboss as BuddyBOss;

defined( 'ABSPATH' ) || die();

/**
 * Admin Tab
 */
class Admin_Tab extends \BP_Admin_Integration_tab{

	use \Etn\Traits\Singleton;

	/**
	 * Current section.
	 *
	 * @var $current_section
	 */
	protected $current_section;

	/**
	 * Initialize
	 */
	public function initialize() {
		$this->tab_order = 50;
		$this->current_section = 'bp_etn-integration';
	}

	/**
	 * Load the settings html
	 */
	public function form_html() {
		parent::form_html();
	}


	/**
	 * Eventin Integration is active?
	 */
	public function is_active() {
		return true;
	}


	/**
	 * Register setting fields for etn integration.
	 */
	public function register_fields() {

		$sections = $this->get_settings_sections();
		foreach ((array) $sections as $section_id => $section) {

			// Only add section and fields if section has fields.
			$fields = $this->get_settings_fields_for_section($section_id);

			if (empty($fields)) {
				continue;
			}

			$section_title = !empty($section['title']) ? $section['title'] : '';
			$section_callback = !empty($section['callback']) ? $section['callback'] : false;
			$tutorial_callback = !empty($section['tutorial_callback']) ? $section['tutorial_callback'] : false;

			// Add the section.
			$this->add_section($section_id, $section_title, $section_callback, $tutorial_callback);

			// Loop through fields for this section.
			foreach ((array) $fields as $field_id => $field) {

				$field['args'] = isset($field['args']) ? $field['args'] : array();

				if (!empty($field['callback']) && !empty($field['title'])) {
					$sanitize_callback = isset($field['sanitize_callback']) ? $field['sanitize_callback'] : array();
					$this->add_field($field_id, $field['title'], $field['callback'], $sanitize_callback, $field['args']);
				}
			}
		}
	}

	/**
	 * Settings section.
	 */
	public function get_settings_sections() {
		if (version_compare(BP_PLATFORM_VERSION, '1.5.7.3', '<=')) {
			$settings = array(
				'bp_etn_settings_section' => array(
					'page' => 'etn',
					'title' => __('Eventin Settings', 'eventin-pro'),
				),
			);
		} else {
			$settings = array(
				'bp_etn_settings_section' => array(
					'page' => 'etn',
					'title' => __('Eventin Settings', 'eventin-pro'),
					'tutorial_callback' => [$this,'bp_etn_settings_tutorial'],
				),
			);
		}

		return $settings;
	}

	/**
	* Call back function
	*/
	public function bp_etn_settings_tutorial() {
		return '';
	}

	/**
	* Get settings field
	*/
	public function get_settings_fields_for_section($section_id = ''){
		//if section is empty return.
		if (empty($section_id)) {
			return false;
		}

		$fields = $this->get_settings_fields();

		$fields = isset($fields[$section_id]) ? $fields[$section_id] : false;

		return $fields;
	}

	/**
	* Get settings field
	*/
	public function get_settings_fields(){
		$fields = array();
		$etn_buddy_boss_module = \Etn_Pro\Core\Modules\Integrations\Buddyboss\Buddyboss::instance()->check_buddy_boss_enabled();
		if ( $etn_buddy_boss_module ) {
			$fields['bp_etn_settings_section']['bb_etn_event_submission_roles'] = array(
				'title' => __('Event submission roles', 'eventin-pro'),
				'callback' => array($this, 'bp_etn_settings_callback_event_submission_roles'),
				'sanitize_callback' => 'wp_slash',
				'args' => array(),
			);
			$fields['bp_etn_settings_section']['bb_etn_event_tab_label'] = array(
				'title' => __('Event Tab Label', 'eventin-pro'),
				'callback' => array($this, 'bp_etn_settings_callback_event_tab_label'),
				'sanitize_callback' => 'wp_slash',
				'args' => array(),
			);
		}

		return $fields;
		
	}

	/**
	* Add settings option
	*/

	public function bp_etn_settings_callback_event_submission_roles() {

		$selected_roles = BuddyBOss::bp_etn_get_settings( 'event_submission_roles', array() );
		$selected_roles = !empty( $selected_roles ) && is_array( $selected_roles ) ? $selected_roles : array('administrator');

		global $wp_roles;
		$roles = $wp_roles->get_names();

		?>

		<select id="bb_etn_event_submission_roles" class="select2" name="bb_etn_event_submission_roles[]" multiple="multiple">
			<?php foreach($roles as $role_key => $role_name): ?>
				<option value="<?php echo esc_attr($role_key); ?>" <?php echo (is_array($selected_roles) and in_array(trim($role_key), $selected_roles)) ? 'selected="selected"' : ''; ?>><?php echo $role_name; ?></option>
			<?php endforeach; ?>
		</select>
		<p><?php esc_html_e( 'Select roles that have access to submission events from individual user profile', 'eventin-pro' ); ?></p>
		<?php
	}

	public function bp_etn_settings_callback_event_tab_label() {

		$tab_label = BuddyBOss::bp_etn_get_settings( 'event_tab_label', '' );
		$tab_label = !empty( $tab_label ) ? $tab_label : esc_html__('Events', 'eventin-pro');

		?>
		<input type="text" name="bb_etn_event_tab_label" id="bb_etn_event_tab_label" class="regular-text code" placeholder="<?php echo esc_attr__('Events', 'eventin-pro'); ?>" value="<?php echo esc_attr($tab_label); ?>" />
		<p><?php esc_html_e( 'This label will be shown in the front end menu tab of profile and group', 'eventin-pro' ); ?></p>
		<?php
	}


}