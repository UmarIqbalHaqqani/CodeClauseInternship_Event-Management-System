<?php
/**
 * Google Calendar Class
 *
 * @package Eventin
 */
namespace Etn_Pro\Core\Modules\Integrations\Google_Meet\Service;

use Etn_Pro\Core\Modules\Integrations\Google_Meet\Auth\Client;
/**
 * Class Calendar
 */
class Calendar {
    const EVENTIN_TIMEZONE_URI   = 'https://www.googleapis.com/calendar/v3/users/me/settings/timezone';
    const EVENTIN_CALENDAR_EVENT = 'https://www.googleapis.com/calendar/v3/calendars/primary/events/';

    /**
     * Create event
     *
     * @param   array  $args  Event data
     *
     * @return JSON | WP_Error
     */
    public function create_event( $args = [] ) {
        $defaults = [
            'summary'      => '',
            'description'  => '',
            'location'     => '',
            'start'        => '',
            'end'          => '',
            'attendees'    => [],
            'google_meet'  => true,
            'access_token' => $this->get_access_token(),
        ];

        $args = wp_parse_args( $args, $defaults );
        $data = $this->prepare_request_data( $args );

        $query_params = build_query( [
            'conferenceDataVersion' => '1',
            'sendUpdates'           => 'all',
        ] );

        $response = wp_remote_post( self::EVENTIN_CALENDAR_EVENT . '?' . $query_params, $data );

        if ( ! is_wp_error( $response ) ) {
            $data = json_decode( wp_remote_retrieve_body( $response ), true );

            return $data;
        }

        return $response;
    }

    /**
     * Update calender event
     *
     * @param   array  $args
     *
     * @return array
     */
    public function update_event( $event_id, $args ) {
        $defaults = [
            'summary'      => '',
            'description'  => '',
            'location'     => '',
            'start'        => '',
            'end'          => '',
            'attendees'    => [],
            'google_meet'  => true,
            'access_token' => '',
            'method'       => 'PUT',
        ];

        $args         = wp_parse_args( $args, $defaults );
        $query_params = build_query( [
            'conferenceDataVersion' => '1',
            'sendUpdates'           => 'all',
        ] );

        $data           = $this->prepare_request_data( $args );
        $data['method'] = 'PUT';

        $response = wp_remote_post( self::EVENTIN_CALENDAR_EVENT . $event_id . '?' . $query_params, $data );

        if ( ! is_wp_error( $response ) ) {
            $data = json_decode( wp_remote_retrieve_body( $response ), true );

            return $data;
        }

        return $response;
    }

    /**
     * Get timzeson
     *
     * @return  string | WP_Error
     */
    public function get_timezone( $access_token ) {
        $data = [
            'headers' => [
                'Authorization' => 'Bearer ' . $access_token,
            ],
        ];

        $response = wp_remote_get( self::EVENTIN_TIMEZONE_URI, $data );

        if ( ! is_wp_error( $response ) ) {
            $data = json_decode( wp_remote_retrieve_body( $response ), true );
            return !empty( $data['value'] ) ? $data['value'] : "";
        }

        return $response;
    }

    /**
     * Delete google calendar event
     *
     * @param   string  $event_id
     *
     * @return array
     */
    public function delete_event( $event_id, $access_token ) {
        $query_params = build_query( [
            'conferenceDataVersion' => '1',
            'sendUpdates'           => 'all',
        ] );

        $response = wp_remote_post( self::EVENTIN_CALENDAR_EVENT . $event_id . '?' . $query_params, [
            'headers' => [
                'Authorization' => 'Bearer ' . $access_token,
                'Content-Type'  => 'application/json; charset=utf-8',
            ],
            'method'  => 'DELETE',
        ] );

        if ( ! is_wp_error( $response ) ) {
            $data = json_decode( wp_remote_retrieve_body( $response ), true );

            return $data;
        }

        return $response;
    }

    /**
     * Get timezone offset
     *
     * @param   string  $timezone
     *
     * @return string
     */
    public function get_timezone_offset( $timezone ) {

		if( empty( $timezone ) ) {
			return false;
		}

        $current       = timezone_open( $timezone );
		if ( $current == false ) {
			return false;
		}
		
        $utc_time      = new \DateTime( 'now', new \DateTimeZone( 'UTC' ) );
        $offset_insecs = timezone_offset_get( $current, $utc_time );
        $hours_and_sec = gmdate( 'H:i', abs( $offset_insecs ) );

        return stripos( $offset_insecs, '-' ) === false ? "+{$hours_and_sec}" : "-{$hours_and_sec}";
    }

    /**
     * Prepare time for calendar event
     *
     * @param   array  $data
     *
     * @return array
     */
    private function prepare_time( $data, $access_token ) {
        $start_date      = isset( $data['start']['date'] ) ? $data['start']['date'] : gmdate( 'Y-m-d' );
        $start_time      = isset( $data['start']['time'] ) ? $data['start']['time'] : gmdate( 'H:i:s' );
        $end_date        = isset( $data['end']['date'] ) ? $data['end']['date'] : gmdate( 'Y-m-d' );
        $end_time        = isset( $data['end']['time'] ) ? $data['end']['time'] : gmdate( 'H:i:s' );
        $timezone        = $this->get_timezone( $access_token );
        $timezone_offset = $this->get_timezone_offset( $timezone );

        $start_date_time = $start_date . 'T' . $this->convertTo24HourFormat( $start_time ) . $timezone_offset;
        $end_date_time   = $end_date . 'T' . $this->convertTo24HourFormat( $end_time ) . $timezone_offset;

        $date_time = [
            'start' => [
                'dateTime' => $start_date_time,
                'timeZone' => $timezone,
            ],
            'end'   => [
                'dateTime' => $end_date_time,
                'timeZone' => $timezone,
            ],
        ];

        return $date_time;
    }

    /**
     * Convet 12 hours format to 24 hours format
     *
     * @param   string  $time
     *
     * @return string
     */
    public function convertTo24HourFormat( $time ) {
        $hours   = intval( substr( $time, 0, 2 ) );
        $minutes = intval( substr( $time, 3, 2 ) );
        $seconds = intval( substr( $time, 6, 2 ) );
        $ampm    = substr( $time, 9 );

        if ( strtolower( $ampm ) == 'pm' && $hours < 12 ) {
            $hours += 12;
        }

        if ( strtolower( $ampm ) == "am" && $hours == 12 ) {
            $hours = 0;
        }

        return sprintf( "%02d:%02d:%02d", $hours, $minutes, $seconds );
    }

    /**
     * Prepare event create requested data
     *
     * @param   array  $args
     *
     * @return array
     */
    private function prepare_request_data( $args = [] ) {
        $access_token = $args['access_token'];
        $date         = $this->prepare_time(
            [
                'start' => $args['start'],
                'end'   => $args['end'],
            ],
            $access_token
        );

        $args['start'] = [$date['start']];
        $args['end']   = [$date['end']];

        if ( $args['google_meet'] ) {
            $args['conferenceData'] = [
                'createRequest' => [
                    'requestId'             => 'sample123',
                    'conferenceSolutionKey' => ['type' => 'hangoutsMeet'],
                ],
            ];
        }

        unset( $args['access_token'] );
        $data = [
            'headers' => [
                'Authorization' => 'Bearer ' . $access_token,
                'Content-Type'  => 'application/json; charset=utf-8',
            ],
            'body'    => json_encode( $args ),
        ];

        return $data;
    }

    /**
     * Get google calendar auth scope
     *
     * @return  string
     */
    public static function scope() {
        return 'https://www.googleapis.com/auth/calendar';
    }

    /**
     * Get access token.
     */
    public function get_access_token() {
        $data   = get_option( 'etn_google_auth' );
        $client = new Client();

        // if (  ! empty($data) && ! array_key_exists( 'refresh_token', $data ) ) {
        //     return '';
        // }

        if ( ( $data['expires_in'] - 30 ) < time() ) {
            $refresh_token = $data['refresh_token'];
            $data = $client->fetch_access_token_with_refresh_token( $refresh_token );
            $data['expires_in'] = $data['expires_in'] + time();

            update_option( 'etn_google_auth', $data );
        }

        return $data['access_token'];
    }
}
