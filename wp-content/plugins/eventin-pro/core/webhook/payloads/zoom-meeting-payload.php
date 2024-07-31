<?php
/**
 * Zoom Meeting Payload
 *
 * @package Eventin
 */
namespace Etn_Pro\Core\Webhook\Payloads;

/**
 * Zoom Meeting Payload Class
 */
class ZoomMeetingPayload implements PayloadInterface {
    /**
     * Get payload
     *
     * @param   mixed  $args
     *
     * @return  array
     */
    public function get_data( $zoom_id ) {
        $zoom_join_url     = get_post_meta( $zoom_id, 'zoom_join_url', true );
        $zoom_meeting_id   = get_post_meta( $zoom_id, 'zoom_meeting_id', true );
        $zoom_topic        = get_post_meta( $zoom_id, 'zoom_topic', true );
        $zoom_start_time   = get_post_meta( $zoom_id, 'zoom_start_time', true );
        $zoom_timezone     = get_post_meta( $zoom_id, 'zoom_timezone', true );
        $zoom_meeting_host = get_post_meta( $zoom_id, 'zoom_meeting_host', true );

        return [
            'id'               => $zoom_id,
            'topic'            => $zoom_topic,
            'start_at'         => $zoom_start_time,
            'time_zone'        => $zoom_timezone,
            'meeting_id'       => $zoom_meeting_id,
            'meeting_host'     => $zoom_meeting_host,
            'meeting_join_url' => $zoom_join_url,
        ];
    }
}
