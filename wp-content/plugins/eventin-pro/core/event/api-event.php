<?php

namespace Etn_Pro\Core\Event;

use Etn\Utils\Helper;
use Etn_Pro\Utils\Helper as ProUtilsHelper;
use Etn_Pro\Core\Modules\Integrations\Google_Meet\Google_Meet;
use WP_Error;

defined('ABSPATH') || exit;

class Api_Event extends \Etn\Base\Api_Handler
{

    /**
     * define prefix and parameter patten
     *
     * @return void
     */
    public function config()
    {
        $this->prefix = 'events';
        $this->param = ''; // /(?P<id>\w+)/
    }

    /**
     * save settings data through api
     *
     * @return array
     */
    public function post_create()
    {
        $status_code = 0;
        $messages = [];
        $content = [];
        $request = $this->request;

        $request_array = json_decode($request->get_body(), true);

        $required_fields = [
            'post_author'  => '',
            'post_title'   => '',
            'post_content' => '',
        ];

        $required_fields_validation = $this->check_required_fields_exists($required_fields, $request_array);

        if (empty($required_fields_validation['validation_code'])) {
            // pass input field for checking empty value
            $inputs_field = [
                ['name' => 'post_author', 'required' => true, 'type' => 'number'],
                ['name' => 'post_title', 'required' => true, 'type' => 'text'],
                ['name' => 'post_content', 'required' => true, 'type' => 'richeditor'],
            ];

            $validation = Helper::input_field_validation($request_array, $inputs_field);

            if (!empty($validation['status_code']) && $validation['status_code'] == true) {

                $input_data = $validation['data'];
                $post_title = $input_data['post_title'];
                $post_content = $input_data['post_content'];
                $post_author = $input_data['post_author'];

                // insert event CPT
                $event_id = wp_insert_post([
                    'post_title'   => $post_title,
                    'post_content' => $post_content,
                    'post_author'  => $post_author,
                    'post_type'    => 'etn',
                    'post_status'  => 'publish',
                    'menu_order'   => !empty($request_array['_etn_buddy_group_id']) ? $request_array['_etn_buddy_group_id'] : 0,
                ]);

                if (!empty($event_id) && !is_wp_error($event_id)) {
                    $default_meta_fields = [
                        '_stock'         => 0,
                        '_price'         => 0,
                        '_sale_price'    => 0,
                        '_regular_price' => 0,
                    ];

                    foreach ($default_meta_fields as $field_name => $field_value) {
                        update_post_meta($event_id, $field_name, $field_value);
                    }

                    wp_set_post_terms($event_id, $request_array['etn_tags'], 'etn_tags');
                    wp_set_post_terms($event_id, $request_array['etn_category'], 'etn_category');

                    $this->update_event_meta_fields($event_id, $request_array);

                    $status_code = 1;
                    $messages[] = esc_html__('Event created successfully', 'eventin-pro');

                } else {
                    return [
                        'status_code' => 409,
                        'messages'    => [esc_html__('Could not create event', 'eventin-pro')],
                        'content'     => $event_id->get_error_message(),
                    ];
                }

            } else {
                $status_code = $validation['status_code'];
                $messages = $validation['messages'];
            }

        } else {

            $messages[] = $required_fields_validation['msg'];
        }

        // Sent event object as json response.
        if (!is_wp_error($event_id)) {
            $content['event'] = $this->prepare_item($event_id);
        }

        // Create recurrence.
        if ( ! empty( $request_array['recurring_enabled'] )  && 'yes' == $request_array['recurring_enabled'] ) {
            $post = get_post( $event_id );
            $update = true;
            \Etn\Core\Recurring_Event\Hooks::instance()->create_recurrences( $event_id, $post, $update );
        }

        // Google meet support for evet.
        $addons_options         = get_option( 'etn_addons_options' );
        $is_enable_google_meet  = ! empty( $addons_options['google_meet'] ) && 'on' === $addons_options['google_meet'];
        $event_googlet_meet     = get_post_meta( $event_id, 'etn_google_meet', true );

        if ( $is_enable_google_meet && 'yes' === $event_googlet_meet ) {
            $google_meet = new Google_Meet();
            $google_meet->etn_create_google_meet_meeting( $event_id );
        }
        

        return [
            'status_code' => $status_code,
            'messages'    => $messages,
            'content'     => $content,
        ];
    }

    /**
     * update event data through api
     *
     * @return array
     */
    public function post_update()
    {
        $status_code = 0;
        $messages = $content = [];
        $request = $this->request;

        $request_array = json_decode($request->get_body(), true);

        $required_fields = [
            'post_author'  => '',
            'post_title'   => '',
            'post_content' => '',
            'event_id'     => '',
        ];

        $required_fields_validation = $this->check_required_fields_exists($required_fields, $request_array);

        if (empty($required_fields_validation['validation_code'])) {
            // pass input field for checking empty value
            $inputs_field = [
                ['name' => 'post_author', 'required' => true, 'type' => 'number'],
                ['name' => 'post_title', 'required' => true, 'type' => 'text'],
                ['name' => 'post_content', 'required' => true, 'type' => 'richeditor'],
                ['name' => 'event_id', 'required' => true, 'type' => 'number'],
            ];

            $validation = Helper::input_field_validation($request_array, $inputs_field);

            if (!empty($validation['status_code']) && $validation['status_code'] == true) {
                $input_data = $validation['data'];
                $post_title = $input_data['post_title'];
                $post_content = $input_data['post_content'];
                $post_author = $input_data['post_author'];
                $event_id = $input_data['event_id'];

                if (!empty($event_id)) {

                    $event_id = wp_update_post([
                        'ID'           => $event_id,
                        'post_title'   => $post_title,
                        'post_content' => $post_content,
                        'post_author'  => $post_author,
                    ]);

                    if (!is_wp_error($event_id)) {
                        wp_set_post_terms($event_id, $request_array['etn_tags'], 'etn_tags');
                        wp_set_post_terms($event_id, $request_array['etn_category'], 'etn_category');
                        $this->update_event_meta_fields($event_id, $request_array);
                        $status_code = 1;
                        $messages[] = esc_html__('Event updated successfully', 'eventin-pro');
                    } else {
                        return [
                            'status_code' => 409,
                            'messages'    => [esc_html__('Could not update event', 'eventin-pro')],
                            'content'     => $event_id->get_error_message(),
                        ];
                    }

                }

            } else {

                $status_code = $validation['status_code'];

                $messages = $validation['messages'];

            }

        } else {

            $messages[] = $required_fields_validation['msg'];

        }

        if (!is_wp_error($event_id)) {
            $content['event'] = $this->prepare_item($event_id);
        }

        return [

            'status_code' => $status_code,

            'messages' => $messages,

            'content' => $content,
        ];

    }

    /**
     * validate required fields are exist in request
     *
     * @param array $required_fields
     * @param array $request_array
     * @return array
     */
    public function check_required_fields_exists($required_fields = [], $request_array = [])
    {
        $validation_code = 0;
        $msg = '';

        $has_required_fields = (0 === count(array_diff_key($required_fields, $request_array)));

        if (!$has_required_fields) {
            $validation_code = 400;
            $msg = esc_html__('Required fields are missing', 'eventin-pro');
        }

        return [
            'validation_code' => $validation_code,
            'msg'             => $msg,
        ];
    }

    /**
     * update event specific meta data
     *
     * @param [type] $event_id
     * @return void
     */
    public function update_event_meta_fields($event_id = null, $request_array = [])
    {

        $update_fields = [
            'etn_event_organizer',
            'etn_event_speaker',
            'etn_event_schedule',
            'etn_event_socials',
            'etn_event_logo',
            '_thumbnail_id',
            'event_etzone',
            'etn_start_time',
            'etn_end_time',
            'etn_start_date',
            'etn_end_date',
            'etn_registration_deadline',
            'etn_ticket_availability',
            'etn_event_location_type',
            'etn_location',
            'banner_bg_image',
            'event_external_link',
            'etn_google_meet',
            'etn_google_meet_link',
            'etn_google_meet_short_description',
            'etn_select_speaker_schedule_type',
            'recurring_enabled',
            'etn_event_recurrence',
            'etn_event_faq',
            'etn_event_certificate',
            'event_type'
        ];

        foreach ($update_fields as $index => $field_name) {
            $field_value = isset($request_array[$field_name]) ? $request_array[$field_name] : '';
            update_post_meta($event_id, $field_name, $field_value);
        }

        $field_name = 'etn_event_location';

        if (isset($request_array[$field_name]) && !empty($request_array[$field_name])) {
            update_post_meta($event_id, $field_name, $request_array[$field_name]);
        }

        if (isset($request_array['etn_location']) && !empty($request_array['etn_location'])) {
            wp_set_post_terms($event_id, $request_array['etn_location'], 'etn_location');
        }

        $field_name = 'banner_bg_image';
        if (isset($request_array[$field_name]) && !empty($request_array[$field_name])) {
            update_post_meta($event_id, $field_name, $request_array[$field_name]);
            update_post_meta($event_id, 'etn_banner', 'on');
            update_post_meta($event_id, 'banner_bg_type', 'no');
        }

        $field_name = 'etn_is_virtual';
        if (!empty($request_array[$field_name])) {
            update_post_meta($event_id, '_virtual', $request_array[$field_name]);
            update_post_meta($event_id, 'virtual', $request_array[$field_name]);
        }

        $field_name = 'etn_ticket_variations';
        if (isset($request_array[$field_name])) {
            $ticket_variations_info = Helper::get_ticket_variations_info($event_id, $request_array[$field_name]);
            $ticket_variations = $ticket_variations_info['ticket_variations'];
            $etn_total_created_tickets = $ticket_variations_info['etn_total_created_tickets'];

            $saved_ticket_variations = get_post_meta($event_id, 'etn_ticket_variations', true);

            foreach ($ticket_variations as $info_key => &$info_value) {
                $info_value['etn_sold_tickets'] = isset($saved_ticket_variations[$info_key]['etn_sold_tickets']) ? $saved_ticket_variations[$info_key]['etn_sold_tickets'] : 0;
            }

            update_post_meta($event_id, $field_name, $ticket_variations);
            update_post_meta($event_id, 'etn_total_avaiilable_tickets', $etn_total_created_tickets);
        }

        if ( isset( $request_array['etn_event_speaker'] ) ) {
            $speaker_group_slug = $request_array['etn_event_speaker'];
            $speakers = $this->prepare_speaker_organizer( $speaker_group_slug );
            update_post_meta( $event_id, 'etn_event_speaker', $speakers );
            update_post_meta( $event_id, 'speaker_type', 'group' );
            update_post_meta( $event_id, 'speaker_slug', $speaker_group_slug );
            

            $speaker_term = get_term_by( 'slug', $speaker_group_slug, 'etn_speaker_category');

            if ( $speaker_term ) {
                update_post_meta( $event_id, 'speaker_group', [$speaker_term->term_id] );
            }
        }

        if ( isset( $request_array['etn_event_organizer'] ) ) {
            $organizer_slug = $request_array['etn_event_organizer'];
            $organizer = $this->prepare_speaker_organizer(  $organizer_slug );
            update_post_meta( $event_id, 'etn_event_organizer', $organizer );
            update_post_meta( $event_id, 'organizer_type', 'group' );
            update_post_meta( $event_id, 'organizer_slug', $organizer_slug );

            $organizer_term = get_term_by( 'slug', $organizer_slug, 'etn_speaker_category');

            if ( $organizer_term ) {
                update_post_meta( $event_id, 'organizer_group', [$organizer_term->term_id] );
            }
        }

        if ( $request_array['event_external_link'] ) {
            update_post_meta( $event_id, 'external_link', $request_array['event_external_link'] );
        }

        if ( isset( $request_array['etn_event_certificate'] ) ) {
            update_post_meta( $event_id, 'certificate_template', $request_array['etn_event_certificate'] );
        }

        if ( isset( $request_array['etn_is_virtual'] ) ) {
            update_post_meta( $event_id, 'virtual', $request_array['etn_is_virtual'] );
        }

        if ( isset( $request_array['etn_event_location_type'] ) ) {
            update_post_meta( $event_id, 'event_type', $request_array['etn_event_location_type'] );
        }

        if ( isset( $request_array['event_etzone'] ) ) {  
            update_post_meta( $event_id, 'event_timezone', $request_array['event_etzone'] );
            update_post_meta( $event_id, 'event_etzone', $request_array['event_etzone'] );
        }

    }

    public function get_timezone()
    {
        $status_code = 0;
        $messages = $content = [];
        $request = $this->request;

        $timezone_markup = wp_timezone_choice('', get_user_locale());

        if (!empty($timezone_markup)) {
            $status_code = 1;
            $messages[] = esc_html__('Timezone retrieved successfully', 'eventin-pro');
            $content['timezone_markup'] = $timezone_markup;
        }

        return [
            'status_code' => $status_code,
            'messages'    => $messages,
            'content'     => $content,
        ];
    }

    public function get_related_info()
    {
        $status_code           = 0;
        $messages              = $content = [];
        $request               = $this->request;
        $taxonomy_obj          = new \Etn_Pro\Core\Event\Api_Event_Taxonomy();
        $categories_info       = $taxonomy_obj->get_categories();
        $tags_info             = $taxonomy_obj->get_tags()['content'];
        $google_auth           = get_option( 'etn_google_auth' );
        $addons_options        = get_option( 'etn_addons_options' );
        $speaker_cat_info      = (new \Etn_Pro\Core\Event\Api_Speaker_Taxonomy())->get_categories()['content'];
        $timezone_info         = $this->get_timezone()['content']['timezone_markup'];
        $current_site_timezone = wp_timezone_string();
        $locations             = (new \Etn_Pro\Core\Event\Api_Location_Taxonomy())->get_locations();
        $user_id               = ! empty( $request['user_id'] ) ? intval( $request['user_id'] ) : 0;

        $content['categories']          =!empty($categories_info) ? $categories_info->data : [];
        $content['tags']                = $tags_info;
        $content['speaker_categories']  = $speaker_cat_info;
        $content['schedules']           = ProUtilsHelper::get_schedule_by_user_id( $user_id );
        $content['timezone_markup']     = $timezone_info;
        $content['site_timezone']       = $current_site_timezone;
        $content['locations']           = !empty( $locations) ? $locations->data : [];
        $content['google_meet_connection'] = ! empty( $google_auth['access_token'] );
        $content['google_meet_addon'] = ! empty( $addons_options['google_meet'] ) && 'on' === $addons_options['google_meet'];
        $content['certificate_templates'] = $this->get_certificate_pages();

        $status_code = 1;
        $messages[] = esc_html__('All info retireved', 'eventin-pro');

        return [
            'status_code' => $status_code,
            'messages'    => $messages,
            'content'     => $content,
        ];
    }

    /**
     * Prepare item for response
     *
     * @param object $event [$event description]
     *
     * @return  array          [response array with data]
     */
    public function prepare_item($event_id)
    {
        /**
         * Event meta data
         */
        $event = get_post($event_id);
        $sold_tickets = get_post_meta($event_id, 'etn_total_sold_tickets', true);
        $available_tickets = get_post_meta($event_id, 'etn_total_avaiilable_tickets', true);
        $start_date = get_post_meta($event_id, 'etn_start_date', true);
        $location = get_post_meta($event_id, 'etn_event_location', true);
        $virtual_product = get_post_meta($event_id, '_virtual', true);
        $event_external_link = get_post_meta($event->ID, 'event_external_link', true);
        $etn_google_meet = get_post_meta($event->ID, 'etn_google_meet', true);
        $etn_google_meet_link = get_post_meta($event->ID, 'etn_google_meet_link', true);
        $etn_google_description = get_post_meta($event->ID, 'etn_google_meet_short_description', true);
        $etn_select_speaker_schedule_type = get_post_meta($event->ID, 'etn_select_speaker_schedule_type', true);
        $etn_event_certificate = get_post_meta($event->ID, 'etn_event_certificate', true);
        $permalink = get_permalink($event_id);
        $event_image = wp_get_attachment_url(get_post_thumbnail_id($event_id));

        $event_type			   = get_post_meta( $event->ID, 'event_type', true );

		$address 			   = 'offline' === $event_type && isset( $location['address'] ) ? $location['address'] : '';

		$event_banner 		  = get_post_meta( $event->ID, 'event_banner', true );

		$event_image		  = $event_banner ?: $event_image;
		$virtual 			  = get_post_meta( $event->ID, '_virtual', true ) ?: get_post_meta( $event->ID, 'virtual', true ); 

        $speaker   = get_post_meta( $event_id, 'etn_event_speaker', true );
        $organizer = get_post_meta( $event_id, 'etn_event_organizer', true );

        if ( $speaker && is_array( $speaker ) ) {
            $speaker_rterms = get_the_terms( $speaker[0], 'etn_speaker_category' );
            if ( ! empty( $speaker_rterms ) ){
                // get the first term
                $speaker_rterms    = array_shift( $speaker_rterms );
                $speaker = $speaker_rterms->slug;
            }
        }

        if ( $organizer && is_array( $organizer ) ) {
            $organizer_terms = get_the_terms( $speaker[0], 'etn_speaker_category' );
            if ( ! empty( $organizer_terms ) ){
                // get the first term
                $organizer_terms = array_shift( $organizer_terms );
                $organizer = $organizer_terms->slug;
            }
        }

        /**
         * Get event data and prepare for response
         */
        return [
            'id'                                => $event_id,
            'title'                             => $event->post_title,
            'date'                              => date_i18n(get_option('date_format'), strtotime($start_date)),
            'location'                          => $address,
            'image'                             => $event_image,
            'permalink'                         => $permalink,
            'availbe_seats'                     => intval($available_tickets),
            'booked_seats'                      => intval($sold_tickets),
            'etn_is_virtual'                    => $virtual_product,
            'event_external_link'               => $event_external_link,
            'etn_google_meet'                   => $etn_google_meet,
            'etn_google_meet_link'              => $etn_google_meet_link,
            'etn_google_meet_short_description' => $etn_google_description,
            'etn_select_speaker_schedule_type'  => $etn_select_speaker_schedule_type,
            'etn_event_certificate'             => $etn_event_certificate,
            'type'                              => $this->get_event_type( $event->ID ),
			'event_type'			            => $event_type,
			'virtual'				            => $virtual,
            'etn_event_organizer'               => $speaker,
            'etn_event_speaker'                 => $organizer,
        ];
    }

    /**
     * Assign event on a certain group
     *
     * @return  JSON | WP_Error
     */
    public function post_assign_group()
    {
        $request = $this->request;

        $data = json_decode($request->get_body(), true);

        $event_id = !empty($data['event_id']) ? intval($data['event_id']) : 0;
        $group_id = !empty($data['group_id']) ? intval($data['group_id']) : 0;

        if (!$event_id) {
            return new WP_Error('event_id_error', esc_html__('Event id can\'t be empty', 'eventin-pro'));
        }

        if (!$group_id) {
            return new WP_Error('event_id_error', esc_html__('Event id can\'t be empty', 'eventin-pro'));
        }

        $assigned_group = update_post_meta($event_id, 'etn_bp_group_' . $group_id, $group_id);

        if (!$assigned_group) {
            return rest_ensure_response([
                'success'     => false,
                'status_code' => 400,
                'message'     => esc_html__('Something went wrong, please try again', 'eventin-pro')
            ]);
        }

        return rest_ensure_response([
            'success'     => true,
            'status_code' => 200,
            'message'     => esc_html__('Successfully assigned event', 'eventin-pro'),
        ]);
    }

    /**
     * Get certificate pages
     *
     * @return  array
     */
    private function get_certificate_pages() {
        $args = array(
            'post_type' => 'page',//it is a Page right?
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => '_wp_page_template',
                    'value' => 'template-pdf-certificate.php', // template name as stored in the dB
                )
            )
        );

        $pages = get_posts( $args );

        $items = [];

        if ( $pages ) {
            foreach( $pages as $page ) {
                $items[$page->ID] = $page->post_title;
            }
        }

        return $items;
    }

    /**
     * Get speaker by term slug
     *
     * @return  array
     */
    protected function prepare_speaker_organizer( $slug ) {
        $args = array(
            'numberposts'   => -1,
            'post_type'     => 'etn-speaker',
            'post_status'   => 'any',
            'fields'        => 'ids',
            
            'tax_query' => array(
                'relation' => 'AND',
                [
                    'taxonomy' => 'etn_speaker_category',
                    'field'    => 'slug',
                    'terms'    => $slug
                ]
            )
        );

        $speakers = get_posts( $args );

        return $speakers;
    }

    /**
	 * Get event type
	 *
	 * @param   integer  $post_id
	 *
	 * @return  string
	 */
	private function get_event_type( $post_id ) {
		$is_recurring = get_post_meta( $post_id, 'recurring_enabled', true );

		if ( 'yes' == $is_recurring ) {
			return 'recurring_parent';
		} else if ( has_post_parent( $post_id ) ) {
			return 'recurring_child';
		} else {
			return 'simple';
		}
	}
}

new Api_Event();