<?php

namespace Etn_Pro\Core\Metaboxs;

defined('ABSPATH') || exit;

use Etn\Core\Metaboxs\Event_manager_metabox;

use Etn_Pro\Utils\Helper;

class Event_meta extends Event_manager_metabox {

	use \Etn\Traits\Singleton;

	public $event_fields = [];
	public $cpt_id = 'etn';

	/**
	 * Call all hooks
	 */
	public function init() {
		add_filter("etn_event_fields", [$this, "update_event_meta"]);
		add_filter("etn/metaboxs/etn_metaboxs", [$this, "register_meta_boxes"]);
		add_filter("etn/banner_fields/etn_metaboxs", [$this, "update_banner_meta"]);
		add_filter("etn_event_fields", [$this, "locations_meta_box"]);
		add_filter("etn_event_meta_fields_after_default", [$this, "update_event_general_meta"]);
	}

	/**
	 * Add metaboxes
	 */
	public function register_meta_boxes($existing_boxes) {

		unset($existing_boxes['etn_report']);

		return $existing_boxes;
	}

	/**
	 * Add event speaker metaboxes
	 */
	public function update_event_general_meta($metabox_fields) {
		$metabox_fields['etn_event_speaker'] = [
			'label'    => esc_html__('Event Speakers', 'eventin-pro'),
			'desc'     => esc_html__('Select the category which will be used as speaker.', 'eventin-pro'),
			'type'     => 'select_single',
			'options'  => Helper::get_orgs(),
			'priority' => 1,
			'required' => true,
			'attr'     => ['class' => 'etn-label-item etn-label-top ', 'tab' => 'general_settings'],
			'warning'       => esc_html__('Create Speaker', 'eventin-pro'),
			'warning_url'   => admin_url( 'edit.php?post_type=etn-speaker' )
		];
	
		return $metabox_fields;
	}

	/**
	 * Add extra field to event form
	 *
	 */
	public function update_event_meta($metabox_fields) {

		$metabox_fields["fluent_crm"] = [
			'label'        => esc_html__('Integrate fluent CRM', 'eventin-pro'),
			'desc'         => esc_html__('Enable Fluent CRM integration with this event.', 'eventin-pro'),
			'type'         => 'checkbox',
			'left_choice'  => 'yes',
			'right_choice' => 'no',
			'attr'         => ['class' => 'etn-label-item etn-enable-fluent-crm', 'tab' => 'crm'],
			'conditional'  => true,
			'condition-id' => 'fluent_crm_webhook',
		];

		$metabox_fields["fluent_crm_webhook"] = [
			'label'         => esc_html__('Fluent Webhook', 'eventin-pro'),
			'desc'          => esc_html__('Enter fluent web hook here to integrate fluent CRM with this event.', 'eventin-pro'),
			'type'          => 'text',
			'default'       => '',
			'value'         => '',
			'priority'      => 1,
			'placeholder'   => esc_html__('Enter URL', 'eventin-pro'),
			'required'      => true,
			'attr'          => ['class' => 'etn-label-item conditional-item', 'tab' => 'crm'],
			'tooltip_title' => '',
			'tooltip_desc'  => ''
		];

		if(!empty(\Etn_Pro\Utils\Helper::get_option("etn_groundhogg_api"))) {
			$metabox_fields["groundhogg_tags"] = [
				'label'       => esc_html__('Groundhogg Tags', 'eventin-pro'),
				'desc'        => esc_html__('Enter groundhogg tags(seperate by comma for multiple)', 'eventin-pro'),
				'type'        => 'text',
				'default'     => '',
				'value'       => '',
				'priority'    => 1,
				'placeholder' => 'tag1,tag2,tag3',
				'required'    => false,
				'attr'        => ['class' => 'etn-label-item', 'tab' => 'crm'],
			];
		}

		if(!empty(\Etn_Pro\Utils\Helper::get_option("attendee_registration"))) {

			$metabox_fields["attende_page_link"] = [
				'label'         => esc_html__('Attendee Page URL', 'eventin-pro'),
				'desc'          => esc_html__('Page link where the details of the attendees of this event is located.', 'eventin-pro'),
				'type'          => 'text',
				'default'       => '',
				'value'         => '',
				'priority'      => 1,
				'required'      => true,
				'placeholder'   => esc_html__('Enter Attendee Page URL', 'eventin-pro'),
				'attr'          => ['class' => 'etn-label-item', 'tab' => 'miscellaneous'],
				'tooltip_title' => '',
				'tooltip_desc'  => ''
			];
		}


		$metabox_fields['etn_event_logo'] = [
			'label'    => esc_html__('Event logo', 'eventin-pro'),
			'type'     => 'upload',
			'multiple' => true,
			'default'  => '',
			'value'    => '',
			'desc'     => esc_html__('Event logo will be shown on single page', "eventin-pro"),
			'priority' => 1,
			'required' => false,
			'attr'     => ['class' => ' banner etn-label-item', 'tab' => 'miscellaneous'],
		];

		$metabox_fields['etn_event_calendar_bg'] = [
			'label'         => esc_html__('Background Color For Calendar', 'eventin-pro'),
			'desc'          => esc_html__('This color will be used as the background on calendar module', "eventin-pro"),
			'type'          => 'text',
			'default-color' => '#FF55FF',
			'attr'          => ['class' => ' etn-label-item', 'tab' => 'miscellaneous'],
			'tooltip_title' => '',
			'tooltip_desc'  => ''
		];

		$metabox_fields['etn_event_calendar_text_color'] = [
			'label'         => esc_html__('Text Color For Calendar', 'eventin-pro'),
			'desc'          => esc_html__('This color will be used as the text color on calendar module', "eventin-pro"),
			'type'          => 'text',
			'default-color' => '#000000',
			'attr'          => ['class' => ' etn-label-item', 'tab' => 'miscellaneous'],
			'tooltip_title' => '',
			'tooltip_desc'  => ''
		];
		$metabox_fields['etn_event_certificate'] = [
			'label'    => esc_html__('Select Certificate Template', 'eventin-pro'),
			'desc'     => esc_html__('Select the page template which will be used as event certificate.', 'eventin-pro'),
			'type'     => 'select_single',
			'options'  => Helper::get_pages(),
			'priority' => 1,
			'required' => true,
			'attr'     => ['class' => 'etn-label-item etn-label-top ', 'tab' => 'miscellaneous'],
			'warning'       => esc_html__('Create Certificate Template', 'eventin-pro'),
			'warning_url'   => admin_url( 'post-new.php?post_type=page' )
		];
 
		$metabox_fields["event_external_link"] = [
			'label'         => esc_html__('Event External Link', 'eventin-pro'),
			'desc'          => esc_html__('An external link where the event details will redirect', 'eventin-pro'),
			'type'          => 'text',
			'default'       => '',
			'value'         => '',
			'priority'      => 1,
			'required'      => true,
			'placeholder'   => esc_html__('Enter External link', 'eventin-pro'),
			'attr'          => ['class' => 'etn-label-item', 'tab' => 'miscellaneous'],
			'tooltip_title' => '',
			'tooltip_desc'  => ''
		];

		
		$metabox_fields['etn_event_faq'] = [
			'label'            => esc_html__('Event FAQ\'s', 'eventin-pro'),
			'type'             => 'repeater',
			'default'          => '',
			'value'            => '',
			'walkthrough_desc' => '',
			'options'          => [
				'etn_faq_title'   => [
					'label'       => esc_html__('FAQ Title', 'eventin-pro'),
					'type'        => 'text',
					'default'     => '',
					'value'       => '',
					'desc'        => '',
					'priority'    => 1,
					'placeholder' => esc_html__('Title Here', 'eventin-pro'),
					'attr'        => ['class' => ''],
					'required'    => true,
				],
				'etn_faq_content' => [
					'label'       => esc_html__('FAQ Content', 'eventin-pro'),
					'type'        => 'textarea',
					'default'     => '',
					'value'       => '',
					'desc'        => '',
					'attr'        => [
						'class' => 'schedule',
						'row'   => 14,
						'col'   => 50,
					],
					'placeholder' => esc_html__('FAQ Content Here', 'eventin-pro'),
					'required'    => true,
				],
			],
			'desc'             => esc_html__('Add all frequently asked questions here', "eventin-pro"),
			'attr'             => ['class' => '', 'tab' => 'faq'],
			'priority'         => 1,
			'required'         => true,
		];

		
		return $metabox_fields;
	}

	/**
	 * Add extra field to banner form
	 *
	 */
	public function update_banner_meta($metabox_fields) {
		$metabox_fields['etn_banner'] = [
			'label'        => esc_html__('Display Banner', 'eventin-pro'),
			'desc'         => esc_html__('Place banner to event page. Banner will be displayed in Event template 2 and template 3.', 'eventin-pro'),
			'type'         => 'checkbox',
			'left_choice'  => 'Show',
			'right_choice' => 'Hide',
			'attr'         => ['class' => 'etn-label-item etn-label-banner', 'tab' => 'banner'],
		];

		$metabox_fields['banner_bg_type']  = [
			'label'        => esc_html__('Background type', 'eventin-pro'),
			'desc'         => esc_html__('Choose background type text or image', 'eventin-pro'),
			'type'         => 'checkbox',
			'left_choice'  => 'Color',
			'right_choice' => 'Image',
			'attr'         => ['class' => 'etn-label-item banner_bg_type', 'tab' => 'banner'],
		];
		$metabox_fields['banner_bg_color'] = [
			'label'         => esc_html__('Background color', 'eventin-pro'),
			'desc'          => esc_html__('Choose background color of banner', 'eventin-pro'),
			'type'          => 'text',
			'default-color' => '#FF55FF',
			'attr'          => ['class' => 'etn-label-item banner_bg_color', 'tab' => 'banner'],
		];
		$metabox_fields['banner_bg_image'] = [
			'label' => esc_html__('Background image', 'eventin-pro'),
			'desc'  => esc_html__('Choose background image of banner', 'eventin-pro'),
			'type'  => 'upload',
			'attr'  => ['class' => 'etn-label-item', 'tab' => 'banner'],
		];

		return $metabox_fields;
	}

	/**
	 * Add location fields in single event venue/location tab
	 *
	 */
	public function locations_meta_box($metabox_fields) {

		if(get_post_meta(get_the_ID(), "etn_event_location_type", true) == 'new_location') {
			$class  = 'etn-existing-items-hide';
			$class2 = 'etn-existing-items-show';
		} else {
			$class  = '';
			$class2 = '';
		}

		$metabox_fields['etn_event_location_type'] = [
			'label'       => esc_html__('Location Type', 'eventin-pro'),
			'desc'        => esc_html__('Select locations type', 'eventin-pro'),
			'placeholder' => esc_html__('Select locations type', 'eventin-pro'),
			'type'        => 'select_single',
			'options'     => [
				'existing_location' => esc_html__('Enter Full Address', 'eventin-pro'),
				'new_location'      => esc_html__('Existing Locations', 'eventin-pro')
			],
			'priority'    => 1,
			'required'    => true,
			'attr'        => ['class' => 'etn-label-item etn-label-top', 'tab' => 'locations']
		];

		$metabox_fields['etn_event_location'] = [
			'label'         => esc_html__('Event Location', 'eventin-pro'),
			'desc'          => esc_html__('Place event location', 'eventin-pro'),
			'placeholder'   => esc_html__('Place event location', 'eventin-pro'),
			'type'          => 'text',
			'priority'      => 1,
			'required'      => true,
			'attr'          => ['class' => 'etn-label-item etn-existing-items ' . $class, 'tab' => 'locations'],
			'tooltip_title' => '',
			'tooltip_desc'  => ''
		];

		$metabox_fields['etn_event_location_list'] = [
			'label'       => esc_html__('Event Location', 'eventin-pro'),
			'desc'        => esc_html__('Select locations', 'eventin-pro'),
			'placeholder' => esc_html__('Select locations', 'eventin-pro'),
			'type'        => 'select2',
			'options'     => Helper::get_location_data('', 'yes'),
			'priority'    => 1,
			'required'    => true,
			'attr'        => ['class' => 'etn-label-item etn-new-items ' . $class2, 'tab' => 'locations'],
			'warning'     => esc_html__('Create New Locations', 'eventin-pro'),
			'warning_url' => admin_url('edit-tags.php?taxonomy=etn_location')
		];

		return $metabox_fields;
	}

}
