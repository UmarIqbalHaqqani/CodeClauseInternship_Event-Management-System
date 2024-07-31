<?php
/**
 * Attendee Api Class
 *
 * @package Eventin\Attendee
 */
namespace Eventin\Attendee\Api;

use Etn\Core\Attendee\Attendee_Model;
use WP_Error;
use WP_Query;
use WP_REST_Controller;
use WP_REST_Server;

/**
 * Attendee Controller Class
 */
class AttendeeController extends WP_REST_Controller {
    /**
     * Constructor for AttendeeController
     *
     * @return void
     */
    public function __construct() {
        $this->namespace = 'eventin/v2';
        $this->rest_base = 'attendees';
    }

    /**
     * Check if a given request has access to get items.
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|boolean
     */
    public function register_routes() {
        register_rest_route( $this->namespace, $this->rest_base, [
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [$this, 'get_items'],
                'permission_callback' => [$this, 'get_item_permissions_check'],
            ],
            [
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => [$this, 'create_item'],
                'permission_callback' => [$this, 'create_item_permissions_check'],
            ],
            [
                'methods'             => WP_REST_Server::DELETABLE,
                'callback'            => [$this, 'delete_items'],
                'permission_callback' => [$this, 'delete_item_permissions_check'],
            ],
        ] );

        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/(?P<id>[\d]+)',
            array(
                'args'   => array(
                    'id' => array(
                        'description' => __( 'Unique identifier for the post.', 'eventin' ),
                        'type'        => 'integer',
                    ),
                ),
                array(
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => array( $this, 'get_item' ),
                    'permission_callback' => array( $this, 'get_item_permissions_check' ),
                    'args'                => $this->get_collection_params(),
                ),
                array(
                    'methods'             => WP_REST_Server::EDITABLE,
                    'callback'            => array( $this, 'update_item' ),
                    'permission_callback' => array( $this, 'update_item_permissions_check' ),
                    'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ),
                ),
                [
                    'methods'             => WP_REST_Server::DELETABLE,
                    'callback'            => [$this, 'delete_item'],
                    'permission_callback' => [$this, 'delete_item_permissions_check'],
                ],
                // 'allow_batch' => $this->allow_batch,
                'schema' => array( $this, 'get_item_schema' ),
            ),
        );
    }

    /**
     * Get a collection of items.
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get_items( $request ) {

        $per_page = ! empty( $request['per_page'] ) ? intval( $request['per_page'] ) : 20;
        $paged    = ! empty( $request['paged'] ) ? intval( $request['paged'] ) : 1;
        $type     = ! empty( $request['type'] ) ? sanitize_text_field( $request['type'] ) : '';

        $args = [
            'post_type'      => 'etn-attendee',
            'post_status'    => 'publish',
            'posts_per_page' => $per_page,
            'paged'          => $paged,
        ];
        $attendees = [];

        $post_query   = new WP_Query();
        $query_result = $post_query->query( $args );
        $total_posts  = $post_query->found_posts;

        foreach ( $query_result as $post ) {
            $attendee  = new Attendee_Model( $post->ID );
            $post_data = $this->prepare_item_for_response( $attendee, $request );

            $attendees[] = $this->prepare_response_for_collection( $post_data );
        }

        $response = rest_ensure_response( $attendees );

        $response->header( 'X-WP-Total', $total_posts );

        return $response;
    }

    /**
     * Get one item from the collection.
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get_item( $request ) {
        $id       = intval( $request['id'] );
        $attendee = new Attendee_Model( $id );

        $item = $this->prepare_item_for_response( $attendee, $request );

        $response = rest_ensure_response( $item );

        return $response;
    }

    /**
     * Check if a given request has access to get items.
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|boolean
     */
    public function get_item_permissions_check( $request ) {
        return current_user_can( 'manage_options' );
    }

    /**
     * Creates a single event.
     *
     * @since 4.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function create_item( $request ) {
        $prepared_item = $this->prepare_item_for_database( $request );

        if ( is_wp_error( $prepared_item ) ) {
            return $prepared_item;
        }

        $attendee = new Attendee_Model();
        $created  = $attendee->create( $prepared_item );

        if ( ! $created ) {
            return new WP_Error(
                'attendee_create_error',
                __( 'Attendee can not be created.', 'eventin' ),
                array( 'status' => 500 )
            );
        }

        $item = $this->prepare_item_for_response( $attendee, $request );

        do_action( 'eventin_attendee_created', $attendee, $request );

        $response = rest_ensure_response( $item );
        $response->set_status( 201 );

        return $response;
    }

    /**
     * Checks if a given request has access to create a event.
     *
     * @since 4.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return true|WP_Error True if the request has access to create items, WP_Error object otherwise.
     */
    public function create_item_permissions_check( $request ) {
        return current_user_can( 'manage_options' );
    }

    /**
     * Updates a single event.
     *
     * @since 4.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function update_item( $request ) {
        $prepared_item = $this->prepare_item_for_database( $request );

        if ( is_wp_error( $prepared_item ) ) {
            return $prepared_item;
        }

        $attendee = new Attendee_Model( $request['id'] );
        $updated  = $attendee->update( $prepared_item );

        if ( ! $updated ) {
            return new WP_Error(
                'attendee_update_error',
                __( 'Attendee can not be updated.', 'eventin' ),
                array( 'status' => 500 )
            );
        }

        $item = $this->prepare_item_for_response( $attendee, $request );

        do_action( 'eventin_attendee_updated', $attendee, $request );

        $response = rest_ensure_response( $item );

        return $response;
    }

    /**
     * Checks if a given request has access to update a event.
     *
     * @since 4.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return true|WP_Error True if the request has access to update the item, WP_Error object otherwise.
     */
    public function update_item_permissions_check( $request ) {
        return current_user_can( 'manage_options' );
    }

    /**
     * Delete one item from the collection.
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function delete_item( $request ) {
        $id = intval( $request['id'] );

        $attendee = new Attendee_Model( $id );
        $previous = $this->prepare_item_for_response( $attendee, $request );
        $deleted  = $attendee->delete();
        $response = new \WP_REST_Response();

        $response->set_data(
            array(
                'deleted'  => true,
                'previous' => $previous,
            )
        );

        if ( ! $deleted ) {
            return new WP_Error(
                'rest_cannot_delete',
                __( 'The attendee cannot be deleted.', 'eventin' ),
                array( 'status' => 500 )
            );
        }

        do_action( 'eventin_attendee_deleted', $id );

        return $response;
    }

    /**
     * Delete multiple items from the collection.
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function delete_items( $request ) {
        $ids = ! empty( $request['ids'] ) ? $request['ids'] : [];

        if ( ! $ids ) {
            return new WP_Error(
                'rest_cannot_delete',
                __( 'Attendee ids can not be empty.', 'eventin' ),
                array( 'status' => 400 )
            );
        }
        $count = 0;

        foreach ( $ids as $id ) {
            $attendee = new Attendee_Model( $id );

            if ( $attendee->delete() ) {
                $count++;
            }
        }

        if ( $count == 0 ) {
            return new WP_Error(
                'rest_cannot_delete',
                __( 'Attendee cannot be deleted.', 'eventin' ),
                array( 'status' => 500 )
            );
        }

        $message = sprintf( __( '%d Attendee are deleted of %d', 'eventin' ), $count, count( $ids ) );

        return rest_ensure_response( $message );
    }

    /**
     * Prepare the item for create or update operation.
     *
     * @param WP_REST_Request $request Request object.
     * @return WP_Error|object $prepared_item
     */
    public function delete_item_permissions_check( $request ) {
        return current_user_can( 'manage_options' );
    }

    /**
     * Prepare the item for the REST response.
     *
     * @param mixed           $item WordPress representation of the item.
     * @param WP_REST_Request $request Request object.
     * @return WP_REST_Response $response
     */
    public function prepare_item_for_response( $item, $request ) {
        $id = $item->id;

        $response = [
            'id'             => $id,
            'name'           => get_post_meta( $id, 'etn_name', true ),
            'email'          => get_post_meta( $id, 'etn_email', true ),
            'phone'          => get_post_meta( $id, 'etn_phone', true ),
            'event_id'       => get_post_meta( $id, 'etn_event_id', true ),
            'event_name'     => get_the_title( get_post_meta( $id, 'etn_event_id', true ) ),
            'ticket_id'      => get_post_meta( $id, 'etn_unique_ticket_id', true ),
            'ticket_name'    => get_post_meta( $id, 'ticket_name', true ),
            'ticket_status'  => get_post_meta( $id, 'etn_attendeee_ticket_status', true ),
            'ticket_price'   => get_post_meta( $id, 'etn_ticket_price', true ),
            'payment_status' => get_post_meta( $id, 'etn_status', true ),
        ];

        return $response;
    }

    /**
     * Prepare the item for create or update operation.
     *
     * @param WP_REST_Request $request Request object.
     * @return WP_Error|object $prepared_item
     */
    protected function prepare_item_for_database( $request ) {
        $prepared_item = [];
        $input_data    = json_decode( $request->get_body(), true );

        if ( ! empty( $input_data['name'] ) ) {
            $prepared_item['etn_name'] = $input_data['name'];
        }

        if ( ! empty( $input_data['email'] ) ) {
            $prepared_item['etn_email'] = $input_data['email'];
        }

        if ( ! empty( $input_data['phone'] ) ) {
            $prepared_item['etn_phone'] = $input_data['phone'];
        }

        if ( ! empty( $input_data['event_id'] ) ) {
            $prepared_item['etn_event_id'] = $input_data['event_id'];
        }

        if ( ! empty( $input_data['ticket_id'] ) ) {
            $prepared_item['etn_unique_ticket_id'] = $input_data['ticket_id'];
        }

        if ( ! empty( $input_data['ticket_name'] ) ) {
            $prepared_item['ticket_name'] = $input_data['ticket_name'];
        }

        if ( ! empty( $input_data['status'] ) ) {
            $prepared_item['etn_attendeee_ticket_status'] = $input_data['status'];
        }

        if ( ! empty( $input_data['ticket_price'] ) ) {
            $prepared_item['etn_ticket_price'] = $input_data['ticket_price'];
        }

        if ( ! empty( $input_data['payment_status'] ) ) {
            $prepared_item['etn_status'] = $input_data['payment_status'];
        }

        return $prepared_item;
    }
}
