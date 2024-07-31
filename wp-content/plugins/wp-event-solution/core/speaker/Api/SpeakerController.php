<?php
/**
 * Speaker Api Class
 *
 * @package Eventin\Speaker
 */
namespace Eventin\Speaker\Api;

use Etn\Core\Speaker\Speaker_Model;
use Eventin\Speaker\CPT\Speaker;
use WP_Error;
use WP_Query;
use WP_REST_Controller;
use WP_REST_Server;

/**
 * Speaker Controller Class
 */
class SpeakerController extends WP_REST_Controller {
    /**
     * Constructor for SpeakerController
     *
     * @return void
     */
    public function __construct() {
        $this->namespace = 'eventin/v2';
        $this->rest_base = 'speakers';
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
                'permission_callback' => [$this, 'get_item_permissions_check'],
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
                'args' => array(
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
                    'args'                => $this->get_collection_params(),
                ),
                array(
                    'methods'             => WP_REST_Server::DELETABLE,
                    'callback'            => array( $this, 'delete_item' ),
                    'permission_callback' => array( $this, 'delete_item_permissions_check' ),
                    'args'                => $this->get_collection_params(),
                ),
            ),
        );
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
            'post_type'      => 'etn-speaker',
            'post_status'    => 'any',
            'posts_per_page' => $per_page,
            'paged'          => $paged,
        ];
        $events = [];

        $post_query   = new WP_Query();
        $query_result = $post_query->query( $args );
        $total_posts  = $post_query->found_posts;

        foreach ( $query_result as $post ) {
            $speaker   = new Speaker_Model( $post->ID );
            $post_data = $this->prepare_item_for_response( $speaker, $request );

            $events[] = $this->prepare_response_for_collection( $post_data );
        }

        $response = rest_ensure_response( $events );

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
        $id   = intval( $request['id'] );
        $post = get_post( $id );

        $item = $this->prepare_item_for_response( $post, $request );

        $response = rest_ensure_response( $item );

        return $response;
    }

    /**
     * Create one item from the collection.
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function create_item( $request ) {
        $data = $this->prepare_item_for_database( $request );

        if ( is_wp_error( $data ) ) {
            return $data;
        }

        $speaker = new Speaker_Model();

        $created = $speaker->create( $data );

        if ( ! $created ) {
            return new WP_Error( 'create_error', __( 'Speaker can not create. Please try again', 'eventin' ), ['status' => 409] );
        }

        wp_set_object_terms( $speaker->id, 'speaker', 'etn_speaker_category' );
        set_post_thumbnail( $speaker->id, $speaker->image_id );

        $item = $this->prepare_item_for_response( $speaker, $request );

        do_action( 'eventin_speaker_created', $speaker, $request );

        $response = rest_ensure_response( $item );
        $response->set_status( 201 );

        return $response;
    }

    /**
     * Check if a given request has access to create items.
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|boolean
     */
    public function create_item_permissions_check( $request ) {
        return current_user_can( 'manage_options' );
    }

    /**
     * Update one item from the collection.
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function update_item( $request ) {
        $data = $this->prepare_item_for_database( $request );

        if ( is_wp_error( $data ) ) {
            return $data;
        }

        $speaker = new Speaker_Model( $request['id'] );

        $updated = $speaker->update( $data );

        if ( ! $updated ) {
            return new WP_Error( 'update_error', __( 'Speaker can not updated. Please try again', 'eventin' ), ['status' => 409] );
        }

        wp_set_object_terms( $speaker->id, 'speaker', 'etn_speaker_category' );
        set_post_thumbnail( $speaker->id, $speaker->image_id );

        $item = $this->prepare_item_for_response( $speaker, $request );

        do_action( 'eventin_speaker_update', $speaker, $request );

        $response = rest_ensure_response( $item );

        return $response;
    }

    /**
     * Check if a given request has access to create items.
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|boolean
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

        $post = get_post( $id );

        if ( is_wp_error( $post ) ) {
            return $post;
        }

        $speaker = new Speaker_Model( $id );

        $previous = $this->prepare_item_for_response( $speaker, $request );
        $deleted  = $speaker->delete();
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
                __( 'The speaker cannot be deleted.', 'eventin' ),
                array( 'status' => 500 )
            );
        }

        do_action( 'eventin_speaker_deleted', $id );

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
                __( 'Speaker ids can not be empty.', 'eventin' ),
                array( 'status' => 400 )
            );
        }
        $count = 0;

        foreach ( $ids as $id ) {
            $event = new Speaker_Model( $id );

            if ( $event->delete() ) {
                $count++;
            }
        }

        if ( $count == 0 ) {
            return new WP_Error(
                'rest_cannot_delete',
                __( 'Speaker cannot be deleted.', 'eventin' ),
                array( 'status' => 500 )
            );
        }

        $message = sprintf( __( '%d speakers are deleted of %d', 'eventin' ), $count, count( $ids ) );

        return rest_ensure_response( $message );
    }

    /**
     * Delete one item from the collection.
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
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

        $term_obj_list = get_the_terms( $id, 'etn_speaker_category' );
        $terms_string  = wp_list_pluck( $term_obj_list, 'slug' );

        $speaker_data = [
            'id'           => $id,
            'name'         => get_post_meta( $id, 'etn_speaker_title', true ),
            'email'        => get_post_meta( $id, 'etn_speaker_website_email', true ),
            'designation'  => get_post_meta( $id, 'etn_speaker_designation', true ),
            'summary'      => get_post_meta( $id, 'etn_speaker_summery', true ),
            'social'       => get_post_meta( $id, 'etn_speaker_socials', true ),
            'company_logo' => get_post_meta( $id, 'etn_speaker_company_logo', true ),
            'company_url'  => get_post_meta( $id, 'etn_speaker_url', true ),
            'image'        => get_post_meta( $id, 'image', true ),
            'category'     => $terms_string,
        ];

        return $speaker_data;
    }

    /**
     * Prepare the item for create or update operation.
     *
     * @param WP_REST_Request $request Request object.
     * @return WP_Error|array $prepared_item
     */
    protected function prepare_item_for_database( $request ) {
        $prepared_data = [];
        $input_data    = json_decode( $request->get_body(), true );

        if ( ! empty( $input_data['id'] ) ) {
            $prepared_data['id'] = intval( $input_data['id'] );
        }

        if ( ! empty( $input_data['name'] ) ) {
            $prepared_data['etn_speaker_title'] = sanitize_text_field( $input_data['name'] );
        }

        if ( ! empty( $input_data['designation'] ) ) {
            $prepared_data['etn_speaker_designation'] = sanitize_text_field( $input_data['designation'] );
        }

        if ( ! empty( $input_data['email'] ) ) {
            $prepared_data['etn_speaker_website_email'] = sanitize_text_field( $input_data['email'] );
        }

        if ( ! empty( $input_data['summary'] ) ) {
            $prepared_data['etn_speaker_summery'] = sanitize_text_field( $input_data['summary'] );
        }

        if ( ! empty( $input_data['social'] ) ) {
            $prepared_data['etn_speaker_socials'] = sanitize_text_field( $input_data['social'] );
        }

        if ( ! empty( $input_data['company_logo'] ) ) {
            $prepared_data['etn_speaker_company_logo'] = sanitize_text_field( $input_data['company_logo'] );
        }

        if ( ! empty( $input_data['company_url'] ) ) {
            $prepared_data['etn_speaker_url'] = sanitize_text_field( $input_data['company_url'] );
        }

        if ( ! empty( $input_data['image'] ) ) {
            $prepared_data['image']    = sanitize_text_field( $input_data['image'] );
            $prepared_data['image_id'] = attachment_url_to_postid( $input_data['image'] );
        }

        $prepared_data['post_status'] = ! empty( $input_data['post_status'] ) ? $input_data['post_status'] : 'publish' ;

        return $prepared_data;
    }
}
