<?php

namespace Etn_Pro\Core\Event;

use Exception;
use WP_Error;

defined( 'ABSPATH' ) || exit;

class Api_Location_Taxonomy extends \Etn\Base\Api_Handler {

    /**
     * define prefix and parameter patten
     *
     * @return void
     */
    public function config() {
        $this->prefix = 'location-taxonomy';
        $this->param  = ''; // /(?P<id>\w+)/
    }

    /**
     * Get event locations data through api
     *
     * @return array
     */
    public function get_locations() {
        $status_code = 0;
        $messages    = $content    = [];
        $request     = $this->request;
        $group_id = ! empty( $request['group_id'] ) ? intval( $request['group_id'] ) : 0;

        try {

            $args = array(
                'taxonomy'   => 'etn_location',
                'hide_empty' => false,
            );

            if($group_id){
                $args['meta_query'] = [
					[
						'key'     => 'group_id',
						'value'   => $group_id,
						'compare' => '=',
					]
				];
            }

            $terms = get_terms($args);

            if ( is_wp_error( $terms ) ) {
                return [
                    'status_code' => 409,
                    'messages'    => [esc_html__( 'Could not get locations', 'eventin-pro' )],
                    'content'     => $terms->get_error_message(),
                ];
            }
            $items = [];

            // Prepare response.
            foreach ( $terms as $term ) {
                $items[] = $this->prepare_term( $term->term_id );
            }

            return rest_ensure_response( $items );

        } catch ( \Exception $e ) {
            return [
                'status_code' => 404,
                'messages'    => [esc_html__( 'Something went wrong! Try again!', 'eventin-pro' )],
                'content'     => $e->getMessage(),
            ];
        }
    }

    /**
     * Get single location
     *
     * @return  array | WP_Error
     */
    public function get_location() {
        $request = $this->request;

        $id = ! empty( $request['id'] ) ? intval( $request['id'] ) : 0;

        if ( ! term_exists( $id ) ) {
            return new WP_Error( 'term_not_found', esc_html__( 'Term not found.', 'eventin-pro' ) );
        }

        return rest_ensure_response( $this->prepare_term( $id ) );
    }

    /**
     * Create term
     *
     * @return  JSON | WP_Error
     */
    public function post_locations() {
        return $this->save_location();
    }

    /**
     * Update term
     *
     * @return  JSON | WP_Error
     */
    public function put_locations() {
        return $this->save_location();
    }

    /**
     * Delete locations
     *
     * @return JSON | WP_Error
     */
    public function delete_locations() {
        $request = $this->request;
        $data    = json_decode( $request->get_body(), true );
        $ids     = ! empty( $data['ids'] ) ? $data['ids'] : [];

        // Delete terms.
        foreach ( $ids as $id ) {
            wp_delete_term( $id, 'etn_location' );
        }

        $data = [
            'status_code' => 200,
            'message'     => esc_html__( 'Successfully deleted', 'eventin-pro' ),
        ];

        return rest_ensure_response( $data );
    }

    /**
     * Save location
     *
     * @return JSON | WP_Error
     */
    public function save_location() {
        $request = $this->request;

        $data = json_decode( $request->get_body(), true );

        $id          = ! empty( $data['id'] ) ? sanitize_text_field( $data['id'] ) : 0;
        $parent      = ! empty( $data['parent'] ) ? sanitize_text_field( $data['parent'] ) : '';
        $name        = ! empty( $data['name'] ) ? sanitize_text_field( $data['name'] ) : '';
        $email       = ! empty( $data['email'] ) ? sanitize_text_field( $data['email'] ) : '';
        $description = ! empty( $data['description'] ) ? sanitize_text_field( $data['description'] ) : '';
        $address     = ! empty( $data['address'] ) ? sanitize_text_field( $data['address'] ) : '';
        $latitude    = ! empty( $data['latitude'] ) ? sanitize_text_field( $data['latitude'] ) : '';
        $longitude   = ! empty( $data['longitude'] ) ? sanitize_text_field( $data['longitude'] ) : '';
        $group_id    = ! empty( $data['group_id'] ) ? intval( $data['group_id'] ) : 0;

        if ( ! $name ) {
            return new WP_Error( 'name_error', esc_html__( 'Location name can\'t be empty', 'eventin-pro' ), 'eventin-pro' );
        }

        if ( $id ) {
            $term = wp_update_term( $id, 'etn_location', [
                'name'         => $name,
                'description' => $description,
                'parent'      => $parent,
            ] );
        } else {
            $term = wp_insert_term( $name, 'etn_location', [
                'description' => $description,
                'parent'      => $parent,
            ] );
        }

        if ( is_wp_error( $term ) ) {
            return [
                'status_code' => 409,
                'message'     => $term->get_error_message(),
            ];
        }

        // Update term meta.
        $term_meta = [
            'latitude'       => $latitude,
            'longitude'      => $longitude,
            'location_email' => $email,
            'address'        => $address,
            'group_id'       => $group_id
        ];

        foreach ( $term_meta as $key => $value ) {
            update_term_meta( $term['term_id'], $key, $value );
        }

        // Send json response.
        return rest_ensure_response( $this->prepare_term( $term['term_id'] ) );
    }

    /**
     * Prepare term for response
     *
     * @param   integer  $term_id  Term ID
     *
     * @return  array Term Details
     */
    public function prepare_term( $term_id ) {
        $term = get_term( $term_id );

        return [
            'id'               => $term->term_id,
            'name'             => $term->name,
            'parent'           => $term->parent,
            'count'            => $term->count,
            'slug'             => $term->slug,
            'taxonomy'         => $term->taxonomy,
            'term_taxonomy_id' => $term->term_taxonomy_id,
            'description'      => $term->description,
            'term_link'        => get_term_link( $term_id ),
            'location_email'   => get_term_meta( $term_id, 'location_email', true ),
            'address'          => get_term_meta( $term_id, 'address', true ),
            'latitude'         => get_term_meta( $term_id, 'latitude', true ),
            'longitude'        => get_term_meta( $term_id, 'longitude', true ),
        ];
    }

}

new Api_Location_Taxonomy();