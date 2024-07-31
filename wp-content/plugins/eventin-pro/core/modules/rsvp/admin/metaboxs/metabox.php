<?php

namespace Etn_Pro\Core\Modules\Rsvp\Admin\Metaboxs;

use Etn\Core\Metaboxs\Event_manager_metabox;

defined( 'ABSPATH' ) || die();

/**
 * Single page meta box for rsvp
 */
class Metabox extends Event_manager_metabox {

	use \Etn_Pro\Traits\Singleton;

	public $report_box_id = 'etn-rsvp';
	public $event_fields  = [];
	public $cpt_id        = 'etn';
	public $text_domain   = 'eventin-pro';

	public function init() {
		add_filter( "etn/metaboxs/etn_metaboxs", [$this, "register_meta_boxes"] );
		add_filter( "etn/metabox/tab", [$this, "register_meta_boxes_tab"] );
	}

	public function register_meta_boxes( $existing_boxes ) {
		$existing_boxes['etn_rsvp_settings'] = [
			'label'        => esc_html__( 'RSVP Settings', 'eventin-pro' ),
			'instance'     => $this,
			'callback'     => 'display_callback',
			'cpt_id'       => 'etn',
			'display_type' => 'tab',
		];

		return $existing_boxes;
	}

	public function etn_meta_fields() {
		// Tab
		$tab_items = $this->get_tab_pane();
		// Tab item wise meta
		$event_rsvp_fields                    = [];
		// global
		$event_rsvp_fields['etn_enable_rsvp_form'] = [
			'label'        => esc_html__( 'Enable RSVP?', 'eventin-pro' ),
			'desc'         => esc_html__( 'Do you want to enable RSVP for this event?', "eventin-pro" ),
			'type'         => 'checkbox',
			'left_choice'  => 'no',
			'right_choice' => 'yes',
			'attr'         => ['class' => 'etn-label-item', 'tab' => 'rsvp-general-Settings'],
		];
		// global
		$event_rsvp_fields['etn_disable_purchase_form'] = [
			'label'        => esc_html__( 'Disable Purchase Form?', 'eventin-pro' ),
			'desc'         => esc_html__( 'Disable selling for this event?', "eventin-pro" ),
			'type'         => 'checkbox',
			'left_choice'  => 'no',
			'right_choice' => 'yes',
			'attr'         => ['class' => 'etn-label-item', 'tab' => 'rsvp-general-Settings'],
		];
		// stock
		$event_rsvp_fields['etn_rsvp_limit'] = [
			'label'        => esc_html__( 'Limit RSVP attendee capacity.', 'eventin-pro' ),
			'desc'         => esc_html__( 'If you want to maintain the limit for attendee capacity, turn on the switcher.', "eventin-pro" ),
			'type'         => 'checkbox',
			'left_choice'  => 'yes',
			'right_choice' => 'no',
			'attr'         => ['class' => 'etn-label-item', 'tab' => 'etn-rsvp-stock'],
			'data'         => ['limit_info' => ''],
			'conditional'  => true,
			'condition-id' => 'etn_rsvp_limit_amount',
		];

		$event_rsvp_fields['etn_rsvp_limit_amount'] = [
			'label' => esc_html__( 'RSVP capacity attendee limit', 'eventin-pro' ),
			'desc'  => esc_html__( 'Total attendee for this RSVP', "eventin-pro" ),
			'type'  => 'number',
			'attr'  => ['class' => 'etn-label-item etn_rsvp_limit_amount conditional-item', 'tab' => 'etn-rsvp-stock'],
		];
		$event_rsvp_fields['etn_rsvp_attendee_form_limit'] = [
			'label'        => esc_html__( 'Maximum attendee registration for each response', 'eventin-pro' ),
			'desc'         => esc_html__( 'Total attendee registration for a single response', "eventin-pro" ),
			'type'         => 'number',
			'min'          => 1,
			'attr'         => ['class' => 'etn-label-item etn_rsvp_attendee_form_limit', 'tab' => 'etn-rsvp-stock'],
		];
		$event_rsvp_fields['etn_rsvp_miminum_attendee_to_start'] = [
			'label'        => esc_html__( 'Minimum attendee to start event', 'eventin-pro' ),
			'desc'         => esc_html__( 'Minimum attendee to start a event', "eventin-pro" ),
			'type'         => 'number',
			'min'          => 0,
			'value'		   => '',
			'default'      => '',
			'placeholder'  => esc_html__( '0', 'eventin-pro' ),
			'attr'         => ['class' => 'etn-label-item etn_rsvp_attendee_form_limit', 'tab' => 'etn-rsvp-stock'],
		];
		// form
		$event_rsvp_fields['etn_rsvp_form_type'] = [
			'label'         => esc_html__( 'RSVP Form Type', 'eventin-pro' ),
			'type'          => 'multi_checkbox',
			'desc'          => esc_html__( 'How many form will be shown in form', 'eventin-pro' ),
			'inputs'        => [esc_html__( 'Going', 'eventin-pro' ), esc_html__( 'Not Going', 'eventin-pro' ), esc_html__( 'Maybe', 'eventin-pro' )],
			'input_checked' => array('going'),
			'attr'          => ['class' => 'etn-label-item', 'tab' => 'etn-rsvp-forms'],
		];

		$event_rsvp_fields['etn_show_rsvp_attendee'] = [
			'label'        => esc_html__( 'Display Attendee list', 'eventin-pro' ),
			'desc'         => esc_html__( 'Do you want to display going attendee list?', "eventin-pro" ),
			'type'         => 'checkbox',
			'left_choice'  => 'no',
			'right_choice' => 'yes',
			'attr'         => ['class' => 'etn-label-item', 'tab' => 'etn-rsvp-forms'],
			'conditional'  => true,
			'condition-id' => 'etn_attendee_list_limit',
		];

		$event_rsvp_fields['etn_attendee_list_limit'] = [
			'label'        => esc_html__( 'Attendee List Limit', 'eventin-pro' ),
			'desc'         => esc_html__( 'Number of attendee you want to show in the single event page. Empty or "-1" will show all the entries.', "eventin-pro" ),
			'type'         => 'number',
			'min'          => 1,
			'attr'         => ['class' => 'etn-label-item etn_attendee_list_limit', 'tab' => 'etn-rsvp-forms'],
		];

		$event_rsvp_fields['etn_rsvp_attendee_link'] = [
			'type'         => 'markup',
			'text'         => \Etn_Pro\Core\Modules\Rsvp\Admin\Admin::instance()->get_rsvp_summary_markup( get_the_ID() ),
			'attr'         => ['class' => 'etn-label-item', 'tab' => 'rsvp-attendee-list'],
		];


		$this->event_fields = $event_rsvp_fields;

		return ['fields' => $this->event_fields, 'tab_items' => $tab_items, 'display' => 'tab'];

	}

	public function get_tab_pane() {
		$tab_items = [
			[
				'name' => esc_html__( 'General Settings', 'eventin-pro' ),
				'id'   => 'rsvp-general-Settings',
				'icon' => '<svg width="14" height="13" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"></path></svg>',
			],
			[
				'name' => esc_html__( 'Forms', 'eventin-pro' ),
				'id'   => 'etn-rsvp-forms',
				'icon' => '<svg width="14" height="13" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"></path></svg>',
			],
			[
				'name' => esc_html__( 'Stock', 'eventin-pro' ),
				'id'   => 'etn-rsvp-stock',
				'icon' => '<svg width="14" height="13" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"></path></svg>',
			],
			[
				'name' => esc_html__( 'Attendee List', 'eventin-pro' ),
				'id'   => 'rsvp-attendee-list',
				'icon' => '<svg width="14" height="13" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"></path></svg>',
			],
		];

		return $tab_items;
	}

}
