<?php
/**
 * Event Exporter Class
 *
 * @package Eventin
 */
namespace Etn\Core\Event;

use Etn\Base\Exporter\Exporter_Factory;
use Etn\Base\Exporter\Post_Exporter_Interface;

/**
 * Class Event Exporter
 *
 * Export Event Data
 */
class Event_Exporter implements Post_Exporter_Interface {
    /**
     * Store file name
     *
     * @var string
     */
    private $file_name = 'event-data';

    /**
     * Store event data
     *
     * @var array
     */
    private $data;

    /**
     * Export event data
     *
     * @return void
     */
    public function export( $data, $format ) {
        $this->data = $data;

        $rows      = $this->prepare_data();
        $columns   = $this->get_columns();
        $file_name = $this->file_name;

        $exporter = Exporter_Factory::get_exporter( $format );

        $exporter->export( $rows, $columns, $file_name );
    }

    /**
     * Prepare data to export
     *
     * @return  array
     */
    private function prepare_data() {
        $ids           = $this->data;
        $exported_data = [];

        foreach ( $ids as $id ) {
            $schedule_data = [
                'id'                      => $id,
                'title'                   => get_the_title( $id ),
                'schedule_type'           => get_post_meta( $id, 'etn_select_speaker_schedule_type', true ),
                'schedules'               => get_post_meta( $id, 'etn_event_schedule', true ),
                'organizer'               => get_post_meta( $id, 'etn_event_organizer', true ),
                'speaker'                 => get_post_meta( $id, 'etn_event_speaker', true ),
                'timezone'                => get_post_meta( $id, 'event_timezone', true ),
                'start_date'              => get_post_meta( $id, 'etn_start_date', true ),
                'end_date'                => get_post_meta( $id, 'etn_end_date', true ),
                'start_time'              => get_post_meta( $id, 'etn_start_time', true ),
                'end_time'                => get_post_meta( $id, 'etn_end_time', true ),
                'ticket_availability'     => get_post_meta( $id, 'etn_ticket_availability', true ),
                'event_logo'              => get_post_meta( $id, 'etn_event_logo', true ),
                'calendar_bg'             => get_post_meta( $id, 'etn_event_calendar_bg', true ),
                'calendar_text_color'     => get_post_meta( $id, 'etn_event_calendar_text_color', true ),
                'registration_deadline'   => get_post_meta( $id, 'etn_registration_deadline', true ),
                'attende_page_link'       => get_post_meta( $id, 'attende_page_link', true ),
                'zoom_event'              => get_post_meta( $id, 'etn_zoom_event', true ),
                'zoom_id'                 => get_post_meta( $id, 'etn_zoom_id', true ),
                'total_ticket'            => get_post_meta( $id, 'etn_total_avaiilable_tickets', true ),
                'sold_tickets'            => get_post_meta( $id, 'etn_total_sold_tickets', true ),
                'ticket_variations'       => get_post_meta( $id, 'etn_ticket_variations', true ),
                'event_socials'           => get_post_meta( $id, 'etn_event_socials', true ),
                'google_meet'             => get_post_meta( $id, 'etn_google_meet', true ),
                'google_meet_link'        => get_post_meta( $id, 'etn_google_meet_link', true ),
                'google_meet_description' => get_post_meta( $id, 'etn_google_meet_short_description', true ),
                'fluent_crm'              => get_post_meta( $id, 'fluent_crm', true ),
                'fluent_crm_webhook'      => get_post_meta( $id, 'fluent_crm_webhook', true ),
                'faq'                     => get_post_meta( $id, 'etn_event_faq', true ),
                'extra_fields'            => get_post_meta( $id, 'attendee_extra_fields', true ),
            ];

            $location_type = get_post_meta( $id, 'etn_event_location_type', true );
            $location      = get_post_meta( $id, 'etn_event_location', true );

            if ( 'new_location' == $location_type ) {
                $location = get_post_meta( $id, 'etn_event_location_list', true );
            }

            $schedule_data['location_type'] = $location_type;
            $schedule_data['location']      = $location;

            array_push( $exported_data, $schedule_data );
        }

        return $exported_data;
    }

    /**
     * Get columns
     *
     * @return  array
     */
    private function get_columns() {
        return [
            'id'                      => __( 'ID', 'eventin' ),
            'title'                   => __( 'Title', 'eventin' ),
            'schedule_type'           => __( 'Schedule Type', 'eventin' ),
            'schedules'               => __( 'Schedules', 'eventin' ),
            'organizer'               => __( 'Organizer', 'eventin' ),
            'speaker'                 => __( 'Speaker', 'eventin' ),
            'timezone'                => __( 'Timezone', 'eventin' ),
            'start_date'              => __( 'Start Date', 'eventin' ),
            'end_date'                => __( 'End Date', 'eventin' ),
            'start_time'              => __( 'Start Time', 'eventin' ),
            'end_time'                => __( 'End Time', 'eventin' ),
            'location_type'           => __( 'Location Type', 'eventin' ),
            'location'                => __( 'Location', 'eventin' ),
            'ticket_availability'     => __( 'Ticket Availability', 'eventin' ),
            'event_logo'              => __( 'Logo', 'eventin' ),
            'calendar_bg'             => __( 'Calendar Background', 'eventin' ),
            'calendar_text_color'     => __( 'Calendar Text Color', 'eventin' ),
            'registration_deadline'   => __( 'Registration Deadline', 'eventin' ),
            'attende_page_link'       => __( 'Attendee Page Link', 'eventin' ),
            'zoom_event'              => __( 'Zoom Event', 'eventin' ),
            'zoom_id'                 => __( 'Zoom Event ID', 'eventin' ),
            'total_ticket'            => __( 'Total Ticket', 'eventin' ),
            'sold_tickets'            => __( 'Sold Ticket', 'eventin' ),
            'ticket_variations'       => __( 'Ticket Variations', 'eventin' ),
            'event_socials'           => __( 'Event Socials', 'eventin' ),
            'google_meet'             => __( 'Google Meet', 'eventin' ),
            'google_meet_link'        => __( 'Google Meet Link', 'eventin' ),
            'google_meet_description' => __( 'Google Meet Description', 'eventin' ),
            'fluent_crm'              => __( 'Fluent CRM', 'eventin' ),
            'fluent_crm_webhook'      => __( 'Fluent CRM Webhook', 'eventin' ),
            'faq'                     => __( 'FAQ', 'eventin' ),
            'extra_fields'            => __( 'Extra Fields', 'eventin' ),
        ];
    }
}
