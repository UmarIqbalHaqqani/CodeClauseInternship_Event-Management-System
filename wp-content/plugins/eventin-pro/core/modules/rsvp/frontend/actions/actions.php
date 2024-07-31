<?php

namespace Etn_Pro\Core\Modules\Rsvp\Frontend\Actions;

defined( 'ABSPATH' ) || die();

class Actions {

	use \Etn_Pro\Traits\Singleton;

	public function init() {
		// call ajax submit
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			$callback = ['etn_rsvp_insert'];

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
	public function etn_rsvp_insert() {
		$post_arr = filter_input_array( INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS );
		if ( ! empty( $post_arr['fields'] ) ) {
			$fields_arr = $post_arr['fields'];
			$fields = json_decode(html_entity_decode($fields_arr));

			if ( !empty( $fields->attendee_email ) ) {
				$emails = $fields->attendee_email;

				// get existing post
				$args1 = [
					'post_status'  		=> 'publish',
					'post_type'    		=> 'etn_rsvp',
					'posts_per_page' 	=> -1,
					'meta_query' => [
						'relation' => 'AND',
						[
							'key'     => 'attendee_email',
							'value'   => $fields->attendee_email,
            				'compare' => 'IN'
						],
						[
							'key'     => 'event_id',
							'value'   => $fields->event_id
						]
					],
				];

				$all_rsvp = get_posts( $args1 );

				if(!empty($all_rsvp)){

					foreach( $all_rsvp as $single_rsvp ){
						update_post_meta( $single_rsvp->ID, 'number_of_attendee', get_post_meta( $single_rsvp->ID, 'number_of_attendee', true ) );
						update_post_meta( $single_rsvp->ID, 'etn_rsvp_value', $fields->etn_rsvp_value );
						update_post_meta( $single_rsvp->ID, 'attendee_email', $emails[0] );
						update_post_meta( $single_rsvp->ID, 'rsvp_not_going_reason', $fields->rsvp_not_going_reason );
					}

				} else {

					// save parent post
					$args = [
						'post_title'   => $fields->attendee_name[0],
						'post_content' => '',
						'post_status'  => 'publish',
						'post_author'  => '1',
						'post_parent'  => 0,
						'post_type'    => 'etn_rsvp'
					];
		
					$parent_id = wp_insert_post( $args );

					if ( $parent_id ) {
						// insert meta
						$meta_s = array( 'number_of_attendee', 'etn_rsvp_value', 'event_id' , 'attendee_email' );
						
						if($fields->etn_rsvp_value == 'not_going'){
							array_push($meta_s, 'rsvp_not_going_reason');
						}

						foreach ( $meta_s as $meta ) {
							if( !empty( $fields->$meta ) ){
								$meta_data = $meta == "attendee_email" ?  $emails[0]  : $fields->$meta;
								add_post_meta( $parent_id, $meta , $meta_data );
							}
						}
					}
					// save child post
					for($i = 1; $i< count($emails) ; $i++ ) {
						$args = [
							'post_title'   => $fields->attendee_name[$i],
							'post_content' => '',
							'post_status'  => 'publish',
							'post_author'  => '1',
							'post_parent'  => $parent_id,
							'post_type'    => 'etn_rsvp',
						];
			
						$rsvp_form_id = wp_insert_post( $args );
						if ( is_wp_error( $rsvp_form_id ) ) {
							wp_send_json_error( [
								'status_code' => 400,
								'message'     => $rsvp_form_id->get_error_message(),
								'data'        => [],
							] );
						}else{
							// insert meta
							$meta_s = array( 'event_id','attendee_email' );
							foreach ( $meta_s as $meta ) {
								$meta_value = $meta == "event_id" ? $fields->$meta : $fields->$meta[$i];
								add_post_meta( $rsvp_form_id, $meta , $meta_value  );
							}
							// send rsvp email
							$this->etn_rsvp_email_data( $fields );
						}
					}
				}

				$response = array(
					'status_code' => 200,
					'message'     => [esc_html__( 'RSVP form submit successfully', 'eventin-pro' )],
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

	/**
	 * mail submit when rsvp report created by from submission 
	 */
	public function etn_rsvp_email_data($fields) {
	
		$settings = \Etn\Core\Settings\Settings::instance()->get_settings_option();
		
		$email_body['email_message'] = [];
		$mail_to                     = array_unique( $fields->attendee_email );;
		$form_data                   = [];
		$email_body                  = [];
		$email_body['event_id']      = $fields->event_id;
		$admin_email                 = get_option( 'admin_email' );
		$from_name                   = get_option( 'blogname' );

		$rsvp_auto_confirm_send_email = isset( $settings['rsvp_auto_confirm_send_email'] ) ? 'checked' : '';
		$rsvp_auto_confirm = isset( $settings['rsvp_auto_confirm'] ) ? 'checked' : '';

		if( $rsvp_auto_confirm_send_email == 'checked' && $rsvp_auto_confirm == 'checked' ) {
			$email_body['email_message'] = $settings['rsvp_auto_confirm_send_email_body'];
			$rsvp_status = 'approved';
		}
		else {
			$email_body['email_message'] = '';
			$rsvp_status = 'pending';
		}

		if ( ! empty( $mail_to ) ) {
				$form_data = [
					'email_body' => $email_body,
					'mail_to'    => $mail_to,
					'mail_from'  => $admin_email,
					'type'       => $rsvp_status,
					'subject'    => esc_html__( 'We received your RSVP request', 'eventin-pro' )
				];

			return  new \Etn_Pro\Core\Modules\Rsvp\Notifications\Rsvp_Response($form_data);
		} 

	}
	
}