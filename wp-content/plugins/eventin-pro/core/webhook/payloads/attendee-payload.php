<?php
/**
 * Event Payload
 *
 * @package Eventin
 */
namespace Etn_Pro\Core\Webhook\Payloads;

use \Etn_Pro\Utils\Helper;

/**
 * Attendee Payload Class
 */
class AttendeePayload implements PayloadInterface {
    /**
     * Get payload
     *
     * @param   mixed  $args
     *
     * @return  array
     */
    public function get_data( $id ) {
        $attendee = [
            'id'             => $id,
            'name'           => get_post_meta( $id, 'etn_name', true ),
            'event_id'       => get_post_meta( $id, 'etn_event_id', true ),
            'event_name'     => get_the_title( get_post_meta( $id, 'etn_event_id', true ) ),
            'ticket_id'      => get_post_meta( $id, 'etn_unique_ticket_id', true ),
            'ticket_name'    => get_post_meta( $id, 'ticket_name', true ),
            'ticket_status'  => get_post_meta( $id, 'etn_attendeee_ticket_status', true ),
            'payment_status' => get_post_meta( $id, 'etn_status', true ),
        ];

        if ( ! empty( Helper::get_option( 'reg_require_email' ) ) ) {
            $attendee['email'] = get_post_meta( $id, 'etn_email', true );
        }

        if ( ! empty( Helper::get_option( 'reg_require_phone' ) ) ) {
            $attendee['phone'] = get_post_meta( $id, 'etn_phone', true );
        }

        return $attendee;
    }
}
