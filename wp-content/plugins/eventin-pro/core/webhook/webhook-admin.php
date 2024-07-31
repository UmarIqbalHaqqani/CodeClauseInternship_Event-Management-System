<?php
/**
 * Webhook Admin
 *
 * @package EventinPro
 */
namespace Etn_Pro\Core\Webhook;

use Etn_Pro\Traits\Singleton;

/**
 * Class WebhookAdmin
 */
class Webhook_Admin {
    use Singleton;

    /**
     * Initalize for the Webhook_Admin
     *
     * @return void
     */
    public function init() {
        
        // Save new webhook.
        add_action( 'wp_ajax_etn-save-webhook', [$this, 'save_webhook'] );

        // Delete webhook.
        add_action( 'wp_ajax_etn-delete-webhook', [$this, 'delete_webhook'] );
    }


    /**
     * Save webhook item
     *
     * @return  void  Save webhook item using ajax
     */
    public function save_webhook() {
        $id           = isset( $_POST['id'] ) ? wp_unslash( intval( $_POST['id'] ) ) : 0;
        $name         = isset( $_POST['name'] ) ? wp_unslash( sanitize_text_field( $_POST['name'] ) ) : '';
        $status       = isset( $_POST['status'] ) ? wp_unslash( sanitize_text_field( $_POST['status'] ) ) : '';
        $topic        = isset( $_POST['topic'] ) ? wp_unslash( sanitize_text_field( $_POST['topic'] ) ) : '';
        $topic        = isset( $_POST['topic'] ) ? wp_unslash( sanitize_text_field( $_POST['topic'] ) ) : '';
        $delivery_url = isset( $_POST['delivery_url'] ) ? wp_unslash( sanitize_text_field( $_POST['delivery_url'] ) ) : '';
        $secrete      = isset( $_POST['secrete'] ) ? wp_unslash( sanitize_text_field( $_POST['secrete'] ) ) : '';
        $description  = isset( $_POST['description'] ) ? wp_unslash( sanitize_text_field( $_POST['description'] ) ) : '';

        $errors = [];

        if ( ! $name ) {
            $errors['name_error'] = __( 'Please enter webhook name', 'eventin-pro' );
        }

        if ( ! $status ) {
            $errors['status_error'] = __( 'Please enter webhook status', 'eventin-pro' );
        }

        if ( ! $delivery_url ) {
            $errors['delivery_url_error'] = __( 'Please enter delivery url', 'eventin-pro' );
        }

        // Send error message.
        if ( $errors ) {
            wp_send_json_error( $errors, 403 );
        }

        // Prepare args.
        $args = [
            'name'         => $name,
            'status'       => $status,
            'topic'        => $topic,
            'delivery_url' => $delivery_url,
            'description'  => $description,
            'secrete'       => $secrete,
        ];

        // Save webhook.
        $webhook = new Webhook();
        $webhook->set_props( $args );

        if ( $id ) {
            $webhook->set_id( $id );
        }

        $webhook->save();

        // Send JSON response.
        wp_send_json_success( [
            'id'           => $webhook->get_id(),
            'name'         => $webhook->get_name(),
            'topic'        => $webhook->get_topic(),
            'status'       => $webhook->get_status(),
            'delivery_url' => $webhook->get_delivery_url(),
            'secrete'      => $webhook->get_secrete(),
            'description'  => $webhook->get_description(),
        ] );
    }

    /**
     * Delete webhook
     *
     * @return  mixed
     */
    public function delete_webhook() {
        $post_id = isset( $_POST['post_id'] ) ? wp_unslash( intval( $_POST['post_id'] ) ) : 0;

        $webhook = new Webhook( $post_id );
        $delete = $webhook->delete();

        // Send JSON error if failed to delete.
        if ( is_wp_error( $delete ) ) {
            wp_send_json_error( [
                'message'   =>  __( 'Something went wrong, couldn\'t delete webhook', 'eventin-pro' )
            ], 500 );
        }

        // Send JSON success if successfully deleted.
        wp_send_json_success([
            'message'   =>  __( 'Successfully deleted webhook', 'eventin-pro' ),
        ], 200);
    }
}
