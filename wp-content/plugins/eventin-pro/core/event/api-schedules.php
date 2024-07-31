<?php

namespace Etn_Pro\Core\Event;

use Exception;

defined( 'ABSPATH' ) || exit;

class Api_Schedules extends \Etn\Base\Api_Handler {

    /**
     * define prefix and parameter patten
     *
     * @return void
     */
    public function config() {
        $this->prefix = 'schedules';
        $this->param  = ''; // /(?P<id>\w+)/
    }

    /**
     * Get all schedules data through api
     *
     * @return array
     */
    public function get_all() {
        $status_code = 0;
        $messages    = $content = [];
        try {

            $schedules = get_posts( [
                'post_type'   => 'etn-schedule',
                'numberposts' => '-1',
                'post_status' => 'publish',
            ] );

            if ( is_wp_error( $schedules ) ) {
                return [
                    'status_code' => 409,
                    'messages'    => [esc_html__( 'Could not get schedules', 'eventin-pro' )],
                    'content'     => $schedules->get_error_message(),
                ];
            }

            return [
                'status_code' => 200,
                'messages'    => [
                    esc_html__( 'Success', 'eventin-pro' ),
                ],
                'content'     => $schedules,
            ];
        } catch ( \Exception $e ) {
            return [
                'status_code' => 404,
                'messages'    => [esc_html__( 'Something went wrong! Try again!', 'eventin-pro' )],
                'content'     => $e->getMessage(),
            ];
        }

    }

}

new Api_Schedules();