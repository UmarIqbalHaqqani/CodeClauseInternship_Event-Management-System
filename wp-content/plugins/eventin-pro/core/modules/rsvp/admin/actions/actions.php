<?php

namespace Etn_Pro\Core\Modules\Rsvp\Admin\Actions;

defined( 'ABSPATH' ) || die();

class Actions {

	use \Etn_Pro\Traits\Singleton;

	public function init() {
		// call ajax submit
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			$callback = ['send_invitation_email'];

			if ( ! empty( $callback ) ) {
				foreach ( $callback as $key => $value ) {
					add_action( 'wp_ajax_' . $value, [$this, $value] );
					add_action( 'wp_ajax_nopriv_' . $value, [$this, $value] );
				}
			}
		}
	}

	/**
	 * Ajax submit
	 */
	public function send_invitation_email() {

		$post_arr = filter_input_array( INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS );
		if ( ! empty( $post_arr['fields'] ) ) {
			$fields_arr = $post_arr['fields'];
			$fields     = json_decode( html_entity_decode( $fields_arr ) );

			if ( ! empty( $fields->event_lists_invitations ) ) {

				$this->invitation_email_data( $fields );

				$response = array(
					'status_code' => 200,
					'message'     => [esc_html__( 'RSVP invitation email sent successfully', 'eventin-pro' )],
					'data'        => []
				);

				wp_send_json_success( $response );
			}

			$response = array(
				'status_code' => 400,
				'message'     => [esc_html__( 'Something is wrong', 'eventin-pro' )],
				'data'        => []
			);

			wp_send_json_error( $response );

			wp_die();
		}

	}

	private function invitation_email_data( $fields ) {
		$mail_to                     = [];
		$form_data                   = [];
		$email_body                  = [];
		$email_body['email_message'] = $fields->invitation_email_message;
		$email_body['event_id']      = $fields->event_lists_invitations;
		$admin_email                 = get_option( 'admin_email' );
		$from_name                   = get_option( 'blogname' );

		if ( ! empty( $fields->rsvp_invitation_info_name ) ) {
			$attendee_names = explode( PHP_EOL, $fields->rsvp_invitation_info_name );
			foreach ( $attendee_names as $invitation ) {
				if ( empty( $invitation ) ) {
					continue;
				}

				$invite = explode( ',', $invitation );
				$name  = ! empty( $invite[0] ) ? $invite[0] : '';
			}
		}

		if ( ! empty( $fields->rsvp_invitation_info_email ) ) {
			$attendee_emails = explode( PHP_EOL, $fields->rsvp_invitation_info_email );
			foreach ( $attendee_emails as $invitation ) {
				if ( empty( $invitation ) ) {
					continue;
				}

				$invite = explode( ',', $invitation );
				$email  = ! empty( $invite[0] ) ? $invite[0] : '';

				$mail_to[] = $email;
			}
		}

		$mail_to[] = $admin_email;

		if ( ! empty( $mail_to ) ) {
			$form_data = [
				'email_body' => $email_body,
				'mail_to'    => $mail_to,
				'mail_from'  => $admin_email,
				'from_name'  => $from_name,
				'type'       => 'invitations',
				'subject'    => esc_html__( 'You are invited to fill up the RSVP form', 'eventin-pro' ),
			];

			new \Etn_Pro\Core\Modules\Rsvp\Notifications\Invitations( $form_data );
			
		} 


	}
}