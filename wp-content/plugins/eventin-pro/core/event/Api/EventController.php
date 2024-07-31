<?php
/**
 * Event Controller
 * 
 * @package Eventin Pro
 */
namespace EventinPro\Event\Api;

use Eventin\Event\Api\EventController as ApiEventController;
use WP_REST_Server;
use Etn_Pro\Utils\Helper;
use WP_Error;

/**
 * EventController Class
 */
class EventController extends ApiEventController {
    /**
     * Check if a given request has access to get items.
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|boolean
     */
    public function register_routes() {
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/(?P<id>[\d]+)' . '/send_certificate',
            array(
                'args' => array(
                    'id' => array(
                        'description' => __( 'Unique identifier for the post.', 'eventin-pro' ),
                        'type'        => 'integer',
                    ),
                ),
                array(
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => array( $this, 'send_certificate' ),
                    'permission_callback' => array( $this, 'get_item_permissions_check' ),
                    'args'                => $this->get_collection_params(),
                ),

                // 'allow_batch' => $this->allow_batch,
                'schema' => array( $this, 'get_item_schema' ),
            ),
        );

        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/certificate_templates',
            array(
                'args' => array(
                    'id' => array(
                        'description' => __( 'Unique identifier for the post.', 'eventin-pro' ),
                        'type'        => 'integer',
                    ),
                ),
                array(
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => array( $this, 'get_certificate_templates' ),
                    'permission_callback' => array( $this, 'get_item_permissions_check' ),
                    'args'                => $this->get_collection_params(),
                ),

                // 'allow_batch' => $this->allow_batch,
                'schema' => array( $this, 'get_item_schema' ),
            ),
        );
    }

    /**
     * Send certificate
     *
     * @param   WP_Rest_Request  $request
     *
     * @return  json
     */
    public function send_certificate( $request ) {
        $event_id = intval( $request['id'] );
        $response = Helper::send_certificate_email( $event_id );

        if ( ! $response ) {
            return $response;
        }

        return rest_ensure_response( $response );
    }

    /**
     * Get all certificate pages usages as certificate templates
     *
     * @param   WP_Rest_Request  $request
     *
     * @return  array
     */
    public function get_certificate_templates( $request ) {
        $args = array(
            'post_type' => 'page',//it is a Page right?
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => '_wp_page_template',
                    'value' => 'template-pdf-certificate.php', // template name as stored in the dB
                )
            )
        );

        $pages = get_posts( $args );

        $items = [];

        if ( $pages ) {
            
            foreach( $pages as $page ) {
                $item = [
                    'id'    => $page->ID,
                    'title' => $page->post_title
                ];
                $items[] = $item;
            }
        }

        return rest_ensure_response( $items );
    }
}
