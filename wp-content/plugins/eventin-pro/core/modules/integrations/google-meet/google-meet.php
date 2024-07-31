<?php

namespace Etn_Pro\Core\Modules\Integrations\Google_Meet;
use Etn_Pro\Core\Modules\Integrations\Google_Meet\Service\Calendar;

defined( 'ABSPATH' ) || die();

class Google_Meet {
    use \Etn\Traits\Singleton;

    public function init() {
        \Etn_Pro\Core\Modules\Integrations\Google_Meet\Auth\Auth::instance()->init();

		if ( is_admin() ) {		
			\Etn_Pro\Core\Modules\Integrations\Google_Meet\Admin\Admin::instance()->init();
		} else {
			\Etn_Pro\Core\Modules\Integrations\Google_Meet\Frontend\Frontend::instance()->init();
		}
    }
	
	public function etn_create_google_meet_meeting( $post_id ) {

		$settings = \Etn\Core\Settings\Settings::instance()->get_settings_option();
		$google_meet_client_id = isset( $settings['google_meet_client_id'] ) ? $settings['google_meet_client_id'] : '';
		$google_meet_client_secret_key = isset( $settings['google_meet_client_secret_key'] ) ? $settings['google_meet_client_secret_key'] : '';

		if( empty( $google_meet_client_id ) || empty( $google_meet_client_secret_key ) ) {
			return;
		}

		$calendar = new Calendar();
		$event_title 				= !empty( get_the_title( $post_id ) ) ? get_the_title( $post_id ) : '';
		$event_start_date 			= get_post_meta($post_id, 'etn_start_date', true );
		$event_end_date 			= get_post_meta($post_id, 'etn_end_date', true );
		$event_start_time 			= get_post_meta($post_id, 'etn_start_time', true );
		$event_end_time 			= get_post_meta($post_id, 'etn_end_time', true );
		$event_short_description 	= get_post_meta($post_id, 'etn_google_meet_short_description', true );
		$google_meet_link = get_post_meta( $post_id, 'etn_google_meet_link', true );

		$event_data = [
			'summary' => $event_title,
			'description'   => $event_short_description,
			'start' => [
				'date'  => $event_start_date,
				'time'  => $event_start_time
			],
			'end'   => [
				'date'  => $event_end_date,
				'time'  => $event_end_time
			],
		] ;

		if( !empty($google_meet_link )){
			// This is for update post
			$response	 = $calendar->update_event( $post_id, $event_data );
		} else {
			// This is a newly created post
			$response	 = $calendar->create_event( $event_data );
		}
		update_post_meta( $post_id, 'google_calendar_event_data', $response );

		$google_meet_url   = ( ! empty( $response['hangoutLink'] ) ) ? $response['hangoutLink'] : '';

		if ( $google_meet_url ) {
			update_post_meta( $post_id, 'etn_google_meet_link', $google_meet_url );
		}
	}
}