<?php

namespace Etn_Pro\Core\Attendee;

use Etn\Core\Attendee\Attendee_Exporter;
use \Etn\Utils\Helper;
use WP_REST_Response;

defined( 'ABSPATH' ) || exit;

class Api extends \Etn\Base\Api_Handler{

    /**
     * define prefix and parameter patten
     *
     * @return void
     */
    public function config() {
        $this->prefix = 'event-attendees';
        $this->param  = ''; // /(?P<id>\w+)/
    }

    /**
     * get attendee list
     * @API Link www.domain.com/wp-json/eventin/v1/attendees/export
     * @return array status_code, messages, content
     */
    public function get_export() {
        $data   = json_decode( $this->request->get_body(), true );
        $ids    = ! empty( $data['ids'] ) ? $data['ids'] : [];
        $format = ! empty( $data['format'] ) ? sanitize_text_field( $data['format'] ) : 'csv';

        $formats = [
            'csv',
            'json',
        ];

        if ( ! in_array( $format, $formats ) ) {
            $response = [
                'success'     => 0,
                'status_code' => 400,
                'message'     => __( 'Unsuported format', 'eventin-pro' ),
            ];

            return new WP_REST_Response( $response, 400 );
        }

        if ( empty( $ids ) ) {
            $response = [
                'success'     => 0,
                'status_code' => 400,
                'message'     => __( 'Attendee ids can\'t be empty', 'eventin-pro' ),
            ];

            return new WP_REST_Response( $response, 400 );
        }
        
        $attendee_exporter = new Attendee_Exporter();
        $attendee_exporter->export( $ids, $format );
    }
}

new Api();
