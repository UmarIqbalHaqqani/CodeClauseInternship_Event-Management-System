<?php
/**
 * Payload Class
 * 
 * @package Eventin
 */
namespace Etn_Pro\Core\Webhook\Payloads;

/**
 * Class Payload
 */
class PayloadFactory {
    /**
     * Get payload method
     *
     * @return  Object
     */
    public static function get_payload( $type ) {
        switch ( $type ) {
            case 'etn':
                return new EventPayload();
            case 'etn-speaker':
                return new SpeakerPayload();
            case 'etn-zoom-meeting':
                return new ZoomMeetingPayload();
            case 'etn-attendee':
                return new AttendeePayload();
            case 'etn-schedule':
                return new SchedulePayload();
            case 'shop_order':
            case 'etn-stripe-order':
                return new OrderPayload();
        }
    }
}
