<?php
/**
 * Google Meet Integration
 *
 * @package Eventin
 */
namespace Eventin\Integrations\Google;

use EventinPro\Integrations\GoogleClient;
use EventinPro\Integrations\Google\GoogleToken;
use Eventin\Interfaces\MeetingPlatformInterface;

/**
 * Google Meet class
 */
class GoogleMeet implements MeetingPlatformInterface {
    /**
     * Store meeting data
     *
     * @var array
     */
    protected static $meeting_data;

    /**
     * Check google meet is connected or not
     *
     * @return  bool
     */
    public static function is_connected(): bool {
        if ( GoogleToken::get() ) {
            return true;
        }

        return false;
    }

    /**
     * Get zoom meeting link
     *
     * @return string
     */
    public static function create_link( $args = [] ): string {
        $defaults = [
            'title'      => '',
            'start_date' => '',
            'end_date'   => '',
            'start_time' => '',
            'end_time'   => '',
            'timezone'   => 'Asia/Dhaka',
        ];

        $args = wp_parse_args( $args, $defaults );

        return self::create_meeting( $args )->get_join_url();
    }

    /**
     * Get meeting join url
     *
     * @return  string
     */
    public static function get_join_url() {
        return self::$meeting_data['hangoutLink'];
    }

    /**
     * Get meeting data
     *
     * @return  array
     */
    public static function get_meeting_data() {
        return self::$meeting_data;
    }

    /**
     * Create zoom meeting
     *
     * @param   array  $args  Meeting args
     *
     * @return  Object
     */
    private static function create_meeting( $args ) {
        $access_token = GoogleToken::get();
        $google       = new GoogleClient( $access_token );

        self::$meeting_data = $google->calender->create_event( $args );

        return new self;
    }
}
