<?php

namespace Etn_Pro\Core\Modules\Rsvp\Notifications;
use Etn_Pro\Core\Modules\Rsvp\Notifications\Base\Notifications as Base_Notifications;

defined( 'ABSPATH' ) || die();

/**
 * Send RSVP Response Email
 */
class Rsvp_Response extends Base_Notifications {

	/**
	 * Receive Data
	 */
	public function __construct( $args = array() ) {
		// send email from/to/subject
		$this->config( $args );
		// set notification type
		$type = !empty( $args['type'] ) ? $args['type'] : "";
		$this->notification_type( $type );
		// set notification template
		$this->notification_template();
		// send email
		$this->process_email();
	}

	/**
	 * Make decision to send email
	 */
	public function process_email() {
		return $this->send_email();
	}

	/**
	 * Get notification type
	 */
	public function notification_type( $type ) {
		return $this->mail_type = $type;
	}

	/**
	 * Get notification template
	 */
	public function notification_template() {
		$email_body = $this->email_body;
		$template = "";
		$post   = get_post( $email_body['event_id'] );

		if ( "approved" == $this->mail_type ) {

			if($email_body['email_message'] != ''){
				$template .= $email_body['email_message'];
			} else {
				$template .= 'Dear Attendee,

				As a valued Attendee, you are hereby informed that we received your RSVP for the '.$post->post_title.'.
				
				Waiting to join you at the event.
				
				<a href="'.get_permalink($post->ID).'">'. esc_html__('Confirm your attendance here', 'eventin-pro') .'</a>
								
				Kind regards,'
				. esc_html__( ' from ', 'eventin-pro' ) . $this->from_name;
			}
			
			$template .= '<br/><a href="'.get_permalink($post->ID).'">'.$post->post_title.'</a>';

		} 
		else if( "pending" == $this->mail_type ){
			$template .= esc_html__('We have received your request. Very soon will inform the update  for the event','eventin-pro').$post->post_title;
		}
		 else {
			$template = "reject";
		}
		return $this->email_body = $template;
	}
}