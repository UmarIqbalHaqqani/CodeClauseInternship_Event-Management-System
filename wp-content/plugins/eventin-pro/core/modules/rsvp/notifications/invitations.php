<?php

namespace Etn_Pro\Core\Modules\Rsvp\Notifications;
use Etn_Pro\Core\Modules\Rsvp\Notifications\Base\Notifications as Base_Notifications;

defined( 'ABSPATH' ) || die();

/**
 * Send Invitation
 */
class Invitations extends Base_Notifications {

	/**
	 * Receive Data 
	 */
	public function __construct( $args = array() ) {
		// send email from/to/subject
		$this->config( $args );
		// set notification type
		$this->notification_type( $args['type'] );
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
		
		$template = '';
		if ( "invitations" == $this->mail_type ) {
			$post   = get_post( $email_body['event_id'] );
			if($email_body['email_message'] != ''){
				$template .= $email_body['email_message'];
			} else {
				$template .= 'Dear [name],

				As a valued [customer/member], you are hereby cordially invited to join us at the '.$post->post_title.'. It will take place at [location] on [date].
				
				In order to reserve your spot, all you need to do is RSVP by clicking on the button below:
				
				<a href="'.get_permalink($post->ID).'">'. esc_html__('Confirm your attendance here', 'eventin-pro') .'</a>
				
				We hope to see you there!
				
				Kind regards,'
				. esc_html__( ' from ', 'eventin-pro' ) . $this->from_name;
			}
			
			$template .= '<br/><a href="'.get_permalink($post->ID).'">'.$post->post_title.'</a>';
		}
		
		return $this->email_body = $template;
	}
}