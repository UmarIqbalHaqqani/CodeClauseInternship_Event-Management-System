<?php

namespace Etn_Pro\Core\Modules\Integrations\Google_Meet\Admin\Metaboxs;

defined( 'ABSPATH' ) || die();

/**
 * Single page meta box for google meet
 */
class Metabox {

	use \Etn_Pro\Traits\Singleton;

	public function init() {
		// add_filter( "etn_event_meta_fields_google_meet", [$this, "etn_google_meet_meta_fields"] );
	}

	/**
	 * Add extra field to event form
	 *
	 */
	public function etn_google_meet_meta_fields( $event_google_meet_fields ) {

		$meet_data 		   = get_post_meta( get_the_ID(), 'google_calendar_event_data', true );
		$google_meet_url   = ( !empty( $meet_data['hangoutLink'] ) ) ? $meet_data['hangoutLink'] : '';

		$event_google_meet_fields['etn_google_meet'] = [
			'label'        => esc_html__( 'Google Meet', 'eventin-pro' ),
			'desc'         => esc_html__( 'Enable if this event is a Google meet event', 'eventin-pro' ),
			'type'         => 'checkbox',
			'left_choice'  => 'Yes',
			'right_choice' => 'no',
			'attr'         => ['class' => 'etn-label-item etn-googlemeet-event', 'tab' => 'google_meet_settings'],
			'conditional'  => true,
			'condition-id' => 'etn_google_meet_link',
		];

		$event_google_meet_fields['etn_google_meet_link'] = [
			'label'         => esc_html__( 'Google Meet Link', 'eventin-pro' ),
			'type'          => 'text',
			'default'       => '',
			'desc'          => esc_html__( 'Link will be generated when you publish the event.', 'eventin-pro' ),
			'value'         => $google_meet_url,
			'priority'      => 1,
			'readonly'		=> true,
			'placeholder'   => esc_html__( 'Google Meet link here', 'eventin-pro' ),
			'required'      => false,
			'attr'          => [
				'class' 	=> 'etn-label-item conditional-item',
				'icon'  	=> '',
				'tab'   	=> 'google_meet_settings'
			],
			'tooltip_title' => '',
			'tooltip_desc'  => '',
		];

		$event_google_meet_fields['etn_google_meet_short_description'] = [
			'label'       => esc_html__( 'Google Meet Description', 'eventin-pro' ),
			'desc'        => esc_html__( 'Short description about the meeting.', 'eventin-pro' ),
			'default'     => '',
			'value'       => '',
			'type'        => 'textarea',
			'priority'    => 1,
			'placeholder' => esc_html__( 'A short description for Google Meet', 'eventin-pro' ),
			'attr'        => ['class' => 'etn-label-item conditional-item', 'tab' => 'google_meet_settings'],
		];

		return $event_google_meet_fields;
	}

}
