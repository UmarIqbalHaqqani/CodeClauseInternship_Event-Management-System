<?php
/**
 * Event Payload
 *
 * @package Eventin
 */
namespace Etn_Pro\Core\Webhook\Payloads;

/**
 * Event Payload Class
 */
class EventPayload implements PayloadInterface {
    /**
     * Get payload
     *
     * @param   mixed  $args
     *
     * @return  array
     */
    public function get_data( $event_id ) {
        /**
         * Event meta data
         */
        $event              = get_post( $event_id );
        $sold_tickets       = get_post_meta( $event_id, 'etn_total_sold_tickets', true );
        $avaiilable_tickets = get_post_meta( $event_id, 'etn_total_avaiilable_tickets', true );
        $start_date         = get_post_meta( $event_id, 'etn_start_date', true );
        $location           = get_post_meta( $event_id, 'etn_event_location', true );
        $permalink          = get_permalink( $event_id );
        $event_image        = wp_get_attachment_url( get_post_thumbnail_id( $event_id ) );

        /**
         * Get event data and prepare for response
         */
        return [
            'id'            => $event_id,
            'title'         => $event->post_title,
            'date'          => date_i18n( get_option( 'date_format' ), strtotime( $start_date ) ),
            'location'      => $location,
            'image'         => $event_image,
            'permalink'     => $permalink,
            'availbe_seats' => intval( $avaiilable_tickets ),
            'booked_seats'  => intval( $sold_tickets ),
        ];
    }
}
