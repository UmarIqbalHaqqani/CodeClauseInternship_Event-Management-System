<?php

namespace Etn_Pro\Core\Modules\Integrations\Buddyboss;

use Etn\Utils\Helper;
use WP_Error;

defined( 'ABSPATH' ) || exit;

class Api extends \Etn\Base\Api_Handler {

    /**
     * define prefix and parameter patten
     *
     * @return void
     */
    public function config() {
        $this->prefix = 'buddyboss';
        $this->param  = ''; // /(?P<id>\w+)/
    }
    
    /**
     * Assign event on a certain group
     *
     * @return  JSON | WP_Error
     */
    public function post_assign_group() {
        $request = $this->request;

        $data = json_decode( $request->get_body(), true );

        $event_id = ! empty( $data['event_id'] ) ? intval( $data['event_id'] ) : 0;
        $group_id = ! empty( $data['group_id'] ) ? intval( $data['group_id'] ) : 0;

        if ( ! $event_id ) {
            return new WP_Error( 'event_id_error', esc_html__( 'Event id can\'t be empty', 'eventin-pro' ) );
        }

        if ( ! $group_id ) {
            return new WP_Error( 'event_id_error', esc_html__( 'Event id can\'t be empty', 'eventin-pro' ) );
        }

        $assigned_group = update_post_meta( $event_id, 'etn_bp_group_' . $group_id, $group_id );

        if ( ! $assigned_group ) {
            return rest_ensure_response([
                'success'     => false,
                'status_code' => 400,
                'message'     => esc_html__( 'Something went wrong, please try again', 'eventin-pro' )
            ]);
        }

        return rest_ensure_response( [
            'success'       =>  true,
            'status_code'   => 200,
            'message'       => esc_html__( 'Successfully assigned event', 'eventin-pro' ),
        ] );
    }

}

new Api();