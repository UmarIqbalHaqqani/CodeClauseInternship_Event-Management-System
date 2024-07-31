<?php

namespace Etn_Pro\Core\Event;

use Exception;
use WP_Error;

defined( 'ABSPATH' ) || exit;

class Api_Event_Taxonomy extends \Etn\Base\Api_Handler {

    /**
     * define prefix and parameter patten
     *
     * @return void
     */
    public function config() {
        $this->prefix = 'event-taxonomy';
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
        $group_id = ! empty( $request['group_id'] ) ? intval( $request['group_id'] ) : 0;

        try {

            $args = array(
                'taxonomy'   => 'etn_category',
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
                    'messages'    => [esc_html__( 'Could not get categories', 'eventin-pro' )],
                    'content'     => $terms->get_error_message(),
                ];
            }

            $items = [];

            foreach ( $terms as $term ) {
                $items[] = $this->prepare_category( $term->term_id );
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
     * Get event category
     *
     * @return  JSON | WP_Error
     */
    public function get_category() {
        $request = $this->request;

        $id = ! empty( $request['id'] ) ? intval( $request['id'] ) : 0;

        if ( ! term_exists( $id, 'etn_category' ) ) {
            return new WP_Error( 'term_error', esc_html__( 'Term not found', 'eventin-pro' ) );
        }

        return rest_ensure_response( $this->prepare_category( $id ) );
    }

    /**
     * Get event tags data through api
     *
     * @return array
     */
    public function get_tags() {
        $status_code = 0;
        $messages    = $content    = [];
        $request     = $this->request;
        $group_id = ! empty( $request['group_id'] ) ? intval( $request['group_id'] ) : 0;

        try {

            $args = array(
                'taxonomy'   => 'etn_tags',
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
                    'messages'    => [esc_html__( 'Could not get tags', 'eventin-pro' )],
                    'content'     => $terms->get_error_message(),
                ];
            }

            return [
                'status_code' => 200,
                'messages'    => [
                    esc_html__( 'Success', 'eventin-pro' ),
                ],
                'content'     => $terms,
            ];

        } catch ( \Exception $e ) {
            return [
                'status_code' => 404,
                'messages'    => [esc_html__( 'Something went wrong! Try again!', 'eventin-pro' )],
                'content'     => $e->getMessage(),
            ];
        }
    }

    /**
     * Create event category
     *
     * @return  JSON | WP_Error
     */
    public function post_categories() {
        return $this->save_category();
    }

    /**
     * Update event category
     *
     * @return  JSON | WP_Error
     */
    public function put_categories() {
        return $this->save_category();
    }

    /**
     * Delete event categories
     *
     * @return  JSON
     */
    public function delete_categories() {
        $request = $this->request;
        $data    = json_decode( $request->get_body(), true );
        $ids     = ! empty( $data['ids'] ) ? $data['ids'] : [];

        // Delete terms.
        foreach ( $ids as $id ) {
            wp_delete_term( $id, 'etn_category' );
        }

        $data = [
            'status_code' => 200,
            'message'     => esc_html__( 'Successfully deleted', 'eventin-pro' ),
        ];

        return rest_ensure_response( $data );
    }

    /**
     * Save event category
     *
     * @return  JSON | WP_Error
     */
    public function save_category() {
        $request = $this->request;

        $data = json_decode( $request->get_body(), true );

        $id          = ! empty( $data['id'] ) ? sanitize_text_field( $data['id'] ) : 0;
        $parent      = ! empty( $data['parent'] ) ? intval( $data['parent'] ) : 0;
        $name        = ! empty( $data['name'] ) ? sanitize_text_field( $data['name'] ) : '';
        $description = ! empty( $data['description'] ) ? sanitize_text_field( $data['description'] ) : '';
        $group_id    = ! empty( $data['group_id'] ) ? intval( $data['group_id'] ) : 0;

        if ( ! $name ) {
            return new WP_Error( 'name_error', esc_html__( 'Category name can\'t  be empty', 'eventin-pro' ) );
        }

        if ( $id ) {
            $term = wp_update_term( $id, 'etn_category', [
                'name'        => $name,
                'parent'      => $parent,
                'description' => $description,
            ] );
        } else {
            $term = wp_insert_term( $name, 'etn_category', [
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
            'group_id'       => $group_id
        ];

        foreach ( $term_meta as $key => $value ) {
            update_term_meta( $term['term_id'], $key, $value );
        }

        return rest_ensure_response( $this->prepare_category( $term['term_id'] ) );
    }

    /**
     * Prepare event category
     *
     * @param   integer  $term_id
     *
     * @return  [type]            [return description]
     */
    public function prepare_category( $term_id ) {
        $term = get_term( $term_id, 'etn_category' );

        return [
            'id'          => $term_id,
            'count'       => $term->count,
            'description' => $term->description,
            'link'        => get_term_link( $term_id ),
            'name'        => $term->name,
            'slug'        => $term->slug,
            'taxonomy'    => 'etn_category',
            'parent'      => $term->parent,
        ];
    }

}

new Api_Event_Taxonomy();