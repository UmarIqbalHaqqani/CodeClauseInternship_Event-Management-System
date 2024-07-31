<?php
/**
 * Webhooks hooks.
 *
 * @package EventinPro
 */
namespace Etn_Pro\Core\Webhook;

use \Etn_Pro\Traits\Singleton;

/**
 * Class Hokks
 */
class Hooks {
    use Singleton;
    /**
     * Initialize Hooks class.
     *
     * @return  void
     */
    public function init() {
        // Initialize eventin resource hooks.
        $this->publish_resource();

        add_action( 'init', [$this, 'load_webhooks'] );
        add_action( 'etn_webhook_process_delivery', [$this, 'webhook_process_delivery'], 10, 2 );
        add_action( 'shutdown', [ $this, 'webhook_execute_queue' ] );

        // Resource create, delete, update hooks for webhook.
        add_action( 'publish_etn', [ $this, 'create_rescource' ], 10, 2 );
        add_action( 'wp_trash_post', [ $this, 'delete_resource' ] );
        add_action( 'untrashed_post', [ $this, 'restore_resource' ] );
        add_action( 'post_updated', [ $this, 'updated_post' ], 10, 2 );
    }

    /**
     * Process the webhook at the end of the request.
     *
     * @return  void
     */
    public function webhook_execute_queue() {
        global $etn_queued_webhooks;

        if ( empty( $etn_queued_webhooks ) ) {
            return;
        }

        foreach( $etn_queued_webhooks as $data ) {
            // Deliver imediately.
            $data['webhook']->deliver( $data['arg'] );
        }
    }

    /**
     * Load webhooks.
     *
     * @return  void
     */
    public function load_webhooks() {
        $webhooks = $this->get_webhooks_ids();

        if ( $webhooks ) {
            foreach ( $webhooks as $webhook_id ) {
                $webhook = new Webhook( $webhook_id );

                $webhook->enqueue();
            }
        }
    }

    /**
     * Webhook process delivery
     *
     * @param   Webhook  $webhook  Webhook
     * @param   array  $arg Delivery argument
     *
     * @return  array
     */
    public function webhook_process_delivery( $webhook, $arg ) {
        global $etn_queued_webhooks;

        if ( ! isset( $etn_queued_webhooks ) ) {
            $etn_queued_webhooks = [];
        }

        $etn_queued_webhooks[] = [
            'webhook' => $webhook,
            'arg'     => $arg,
        ];
    }

    // Utility functions.

    /**
     * Get webhooks ids.
     *
     * @return  array
     */
    public function get_webhooks_ids() {
        $webhooks = get_posts( [
            'post_type'   => 'etn-webhook',
            'post_status' => 'publish',
            'numberposts' => -1,
            'fields'      => 'ids',
        ] );

        return $webhooks;
    }

    /**
     * Create publish resource hook.
     *
     * @return void
     */
    public function publish_resource() {
        $post_types = [
            'etn',
            'etn-speaker',
            'etn-zoom-meeting'
        ];

        foreach( $post_types as $post_type ) {
            add_action( 'publish_' . $post_type, [ $this, 'create_rescource' ], 10, 2 );
        }
    }

    /**
     * Create resource hook.
     *
     * @param   integer  $post_id  Post ID
     * @param   Object  $post     Post
     * @param   bool  $updated  The is create or exiting post is updated
     *
     * @return  void 
     */
    public function create_rescource( $post_id, $post ) {
        /**
        * Fired when eventin events, speakers, schedules, zoom meetings are created.
        */
        do_action( 'eventin_create_' . $post->post_type, $post_id );
    }

    /**
     * Updating resource.
     *
     * @param   integer  $post_id  POST ID
     * @param   WP_Post  $post     Post Object
     *
     * @return  void
     */
    public function updated_post( $post_id, $post ) {
        /**
         * Fired when eventin events, speakers, schedules, zoom meetings are updated.
         */
        do_action( 'eventin_update_' . $post->post_type, $post_id );
    }

    /**
     * Delete post hook
     *
     * @param   integer  $post_id  Post ID
     *
     * @return  void
     */
    public function delete_resource( $post_id ) {
        /**
         * Get post type by post id.
         *
         * @var string
         */
        $post_type = get_post_type( $post_id );

        /**
         * Fired when eventin events, speakers, schedules, zoom meetings are deleted.
         */
        do_action( 'eventin_delete_'. $post_type, $post_id );
    }

    /**
     * Restore post
     *
     * @param   integer  $post_id  Post ID
     *
     * @return  void
     */
    public function restore_resource( $post_id ) {
        /**
         * Get post type by post id
         *
         * @var string
         */
        $post_type = get_post_type( $post_id );

        /**
         * Fired when eventin events, speakers, schedules, zoom meetings are restored.
         */
        do_action('eventin_restore_' . $post_type, $post_id );
    }
}