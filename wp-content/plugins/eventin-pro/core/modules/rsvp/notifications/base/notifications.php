<?php

namespace Etn_Pro\Core\Modules\Rsvp\Notifications\Base;

defined( 'ABSPATH' ) || die();

/**
 * Sending email structure
 */
Abstract class Notifications {

	public $mail_type  = "";
	private $mail_from = ""; // sender email
	private $mail_to   = "";
	private $subject   = "";
	public $email_body = "";
	public $from_name  = "";

	public function config( $args ) {
		$this->mail_from  = ! empty( $args['mail_from'] ) ? $args['mail_from'] : "";
		$this->mail_to    = ! empty( $args['mail_to'] ) ? $args['mail_to'] : "";
		$this->subject    = ! empty( $args['subject'] ) ? $args['subject'] : "";
		$this->from_name  = ! empty( $args['from_name'] ) ? $args['from_name'] : "";
		$this->email_body = ! empty( $args['email_body'] ) ? $args['email_body'] : "";
	}

	/**
	 * Verified/Pending/Confirm
	 */
	public function notification_type( $type ) {
		return $this->mail_type = $type;
	}

	/**
	 * Notification template
	 */
	public function notification_template() {
		return $this->email_body;
	}

	/**
	 * Throw email
	 */
	public function send_email() {

		$response = array( 'success' => false, 'message' => array( esc_html__( 'Something is wrong', 'eventin-pro' ) ), $data = array() );

		$body      = wpautop( html_entity_decode( $this->email_body ) );
		$from_name = html_entity_decode( $this->from_name );
		$headers   = array( 'Content-Type: text/html; charset=UTF-8', 'From: ' . $from_name . ' <' . $this->mail_from . '>' );
		// send email
		if ( is_array( $this->mail_to ) ) {
			foreach ( $this->mail_to as $mail ) {
				$transient_key = 'etn_email_sent_' . md5( $mail );
				if ( get_transient( $transient_key ) ) {

					continue;
				} else {
					//hold email sending for 5sec, if it goes from same email
					set_transient( $transient_key, true, 5 );
					$result = wp_mail( $mail, $this->subject, $body, $headers );
				}
				
			}
		} else {
			$result = wp_mail( $this->mail_to, $this->subject, $body, $headers );
		}

		if ( true == $result ) {
			$response = array( 'success' => true, 'message' => array( esc_html__( 'Email has been sent', 'eventin-pro' ) ), $data = array() );
		} else {
			$response = array( 'success' => false, 'message' => array( esc_html__( 'Failed to send email', 'eventin-pro' ) ), $data = array() );
		}

		return $response;
	}

}