<?php

namespace Etn_Pro\Core\Modules\Rsvp\Frontend;

defined( 'ABSPATH' ) || die();

class Frontend {

	use \Etn_Pro\Traits\Singleton;

	public function init() {
		// enqueue scripts
		$this->enqueue_scripts();
		// include rsvp form
		add_action( 'etn_after_single_event_details_rsvp_form', array( $this, 'after_single_event_rsvp_form' ) );
	}

	/**
	 * Enqueue scripts.
	 */
	public function enqueue_scripts() {
		add_action( 'wp_enqueue_scripts', array( $this, 'js_css_public' ) );
	}

	/**
	 *  Frontend scripts.
	 */
	public function js_css_public() {
		// Main script of rsvp script and js
		wp_enqueue_script( 'etn-rsvp-public', ETN_PRO_CORE . 'modules/rsvp/assets/js/etn-rsvp.js', ['jquery'], \Wpeventin_Pro::version(), false );
		$form_data             = array();
		$form_data['ajax_url'] = admin_url( 'admin-ajax.php' );
		$form_data['attendee_title'] = esc_html__( 'Attendee', 'eventin-pro' );

		wp_localize_script( 'etn-rsvp-public', 'localized_rsvp_data', $form_data );
	}

	/**
	 * RSVP form include
	 */
	public function after_single_event_rsvp_form() {
		$settings = \Etn\Core\Settings\Settings::instance()->get_settings_option();
		
		$rsvp_auto_confirm                          = isset( $settings['rsvp_auto_confirm'] ) ? 'checked' : '';
		$rsvp_auto_confirm_send_email               = isset( $settings['rsvp_auto_confirm_send_email'] ) ? 'checked' : '';
		$rsvp_display_form_only_for_logged_in_users = isset( $settings['rsvp_display_form_only_for_logged_in_users'] ) ? 'checked' : '';

		$rsvp_min_attendees 						= isset( $settings['rsvp_min_attendees'] ) ? $settings['rsvp_min_attendees'] : 0;

	

		if ( file_exists( ETN_PRO_DIR . '/core/modules/rsvp/frontend/views/forms/rsvp-form.php' ) ) {
			if(($rsvp_display_form_only_for_logged_in_users == 'checked')){
				if(is_user_logged_in()) {
					include ETN_PRO_DIR . '/core/modules/rsvp/frontend/views/forms/rsvp-form.php';
				}
			}
			else {
				include ETN_PRO_DIR . '/core/modules/rsvp/frontend/views/forms/rsvp-form.php';
			}
		}
	}

}
