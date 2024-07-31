<?php

namespace Etn_Pro\Core\Event;

use Exception;

defined( 'ABSPATH' ) || exit;

class Api_Speaker_Taxonomy extends \Etn\Base\Api_Handler {

    /**
     * define prefix and parameter patten
     *
     * @return void
     */
    public function config() {
        $this->prefix = 'speaker-taxonomy';
        $this->param  = ''; // /(?P<id>\w+)/
    }

    /**
     * Get event categories data through api
     *
     * @return array
     */
    public function get_categories() {
        $status_code = 0;
        $messages    = $content    = [];
        $request     = $this->request;
        
        try{

            $terms = get_terms( array(
                'taxonomy' => 'etn_speaker_category',
                'hide_empty' => false,
            ) );
    
            if ( is_wp_error( $terms ) ) {
                return [
                    'status_code' => 409,
                    'messages'    => [esc_html__( 'Could not get speaker categories', 'eventin-pro' )],
                    'content'     => $terms->get_error_message(),
                ];
            }
            
            return [
                'status_code' => 200,
                'messages'    => [
                    esc_html__( 'Success', 'eventin-pro' )
                ],
                'content'     => $terms,
            ];
        } catch( \Exception $e){
            return [
                'status_code' => 404,
                'messages'    => [esc_html__( 'Something went wrong! Try again!', 'eventin-pro' )],
                'content'     => $e->getMessage(),
            ];
        }
    }

}

new Api_Speaker_Taxonomy();