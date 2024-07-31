<?php
/**
 * Event Model Class
 *
 * @package Eventin
 */
namespace Etn\Core\Event;

use Etn\Base\Post_Model;

/**
 * Event Model
 */
class Event_Model extends Post_Model {
    /**
     * Store post type
     *
     * @var string
     */
    protected $post_type = 'etn';

    /**
     * Store event data
     *
     * @var array
     */
    protected $data = [
        'etn_select_speaker_schedule_type'  => '',
        'etn_event_organizer'               => '',
        'etn_event_speaker'                 => '',
        'event_timezone'                    => '',
        'etn_start_date'                    => '',
        'etn_end_date'                      => '',
        'etn_start_time'                    => '',
        'etn_end_time'                      => '',
        'etn_ticket_availability'           => '',
        'etn_ticket_variations'             => '',
        'etn_registration_deadline'         => '',
        'etn_zoom_id'                       => '',
        'etn_zoom_event'                    => '',
        'etn_total_avaiilable_tickets'      => '',
        'etn_google_meet'                   => '',
        'etn_google_meet_short_description' => '',
        'fluent_crm'                        => '',
        'etn_event_location_type'           => '',
        'etn_event_location'                => '',
        'etn_event_socials'                 => [],
        'etn_event_schedule'                => [],
        'etn_event_faq'                     => [],
        'recurring_enabled'                 => '',
        'etn_event_recurrence'              => [],
        'etn_google_meet'                   => '',
        'rsvp_settings'                     => '',
        'seat_plan'                         => '',
        'seat_plan_settings'                => '',
        'certificate_template'              => '',
        'ticket_template'                   => '',
        'external_link'                     => '',
        'speaker_type'                      => '',
        'organizer_type'                    => '',
        'speaker_group'                     => '',
        'organizer_group'                   => '',
        'etn_event_logo'                    => '',
        'etn_event_calendar_bg'             => '',
        'etn_event_calendar_text_color'     => '',
        'fluent_crm_webhook'                => '',
        'attende_page_link'                 => '',
        'event_banner'                      => '',
        'event_layout'                      => '',
        'attendee_extra_fields'             => '',
        'event_type'                        => '',
        'location'                          => '',
        'meeting_link'                      => '',
        'is_clone'                          => false,
        'certificate_preference'            => '',
        'virtual'                           => '',
        'event_logo_id'                      => '',
        'event_banner_id'                   => '',         
    ];

    /**
     * Get total tickets
     *
     * @return  integer
     */
    public function get_total_ticket() {
        $ticket_variations = $this->etn_ticket_variations;
        $total_ticket      = 0;

        if ( is_array( $ticket_variations ) ) {
            foreach ( $ticket_variations as $ticket ) {
                if ( ! empty( $ticket['etn_avaiilable_tickets'] ) ) {
                    $total_ticket += $ticket['etn_avaiilable_tickets'];
                }
            }
        }

        return $total_ticket;
    }
}
