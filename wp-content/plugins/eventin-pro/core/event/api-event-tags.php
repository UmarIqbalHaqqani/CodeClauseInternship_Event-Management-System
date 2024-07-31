<?php
/**
 * Events Tags
 *
 * @package EventinPro
 */
namespace Etn_Pro\Core\Event;

use WP_Error;

defined( 'ABSPATH' ) || exit;

class Api_Event_Tags extends \Etn\Base\Api_Handler {

    /**
     * define prefix and parameter patten
     *
     * @return void
     */
    public function config() {
        $this->prefix = 'event-tag';
        $this->param  = ''; // /(?P<id>\w+)/
    }

    /**
     * Get tags
     *
     * @return  JSON
     */
    public function get_tags() {
        $request = $this->request;
        $group_id = ! empty( $request['group_id'] ) ? intval( $request['group_id'] ) : 0;

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
                'messages'    => $terms->get_error_message(),
            ];
        }

        $items = [];

        foreach ( $terms as $term ) {
            $items[] = $this->prepare_tag( $term->term_id );
        }

        return rest_ensure_response( $items );
    }

    /**
     * Get single tag by ID
     *
     * @return  JSON | WP_Error
     */
    public function get_tag() {
        $request = $this->request;

        $id = ! empty( $request['id'] ) ? intval( $request['id'] ) : 0;

        if ( ! term_exists( $id, 'etn_tags' ) ) {
            return new WP_Error( 'not_found_tag', esc_html__( 'No tag found', 'eventin-pro' ) );
        }

        return rest_ensure_response( $this->prepare_tag( $id ) );
    }

    /**
     * Create tag
     *
     * @return JSON | WP_Error
     */
    public function post_tags() {
        return $this->save_tag();
    }

    /**
     * Update tag
     *
     * @return  JSON | WP_Error
     */
    public function put_tags() {
        return $this->save_tag();
    }

    /**
     * Delete tag
     *
     * @return  JSON
     */
    public function delete_tags() {
        $request = $this->request;
        $data    = json_decode( $request->get_body(), true );

        $ids = ! empty( $data['ids'] ) ? $data['ids'] : [];

        foreach ( $ids as $id ) {
            wp_delete_term( $id, 'etn_tags' );
        }

        return [
            'status_code' => 200,
            'message'     => esc_html__( 'Successfully deleted tag', 'eventin-pro' ),
        ];
    }

    /**
     * Save tag
     *
     * @return JSON | WP_Error
     */
    public function save_tag() {
        $request = $this->request;

        $tag = json_decode( $request->get_body(), true );

        $id          = ! empty( $tag['id'] ) ? intval( $tag['id'] ) : 0;
        $parent      = ! empty( $tag['parent'] ) ? intval( $tag['parent'] ) : 0;
        $name        = ! empty( $tag['name'] ) ? sanitize_text_field( $tag['name'] ) : '';
        $description = ! empty( $tag['description'] ) ? sanitize_text_field( $tag['description'] ) : '';
        $group_id    = ! empty( $data['group_id'] ) ? intval( $data['group_id'] ) : 0;

        if ( ! $name ) {
            return new WP_Error( 'name_error', esc_html__( 'Name can\'t be empty', 'eventin-pro' ) );
        }

        if ( $id ) {
            $term = wp_update_term( $id, 'etn_tags', [
                'name'        => $name,
                'description' => $description,
                'parent'      => $parent,
            ] );
        } else {
            $term = wp_insert_term( $name, 'etn_tags', [
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

        return rest_ensure_response( $this->prepare_tag( $term['term_id'] ) );
    }

    /**
     * Prepare tag for response
     *
     * @param   integer  $tag_id  [$tag_id description]
     *
     * @return  JSON | WP_Error
     */
    public function prepare_tag( $tag_id ) {
        $term = get_term( $tag_id, 'etn_tags' );

        return [
            'id'          => $tag_id,
            'term_id'     => $tag_id,
            'name'        => $term->name,
            'slug'        => $term->slug,
            'parent'      => $term->parent,
            'description' => $term->description,
            'count'       => $term->count,
            'taxonomy'    => 'etn_tags',
            'link'        => get_term_link( $tag_id, 'etn_tags' ),
        ];
    }
}

new Api_Event_Tags();
